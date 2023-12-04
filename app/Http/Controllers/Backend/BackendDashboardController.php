<?php namespace App\Http\Controllers\Backend;

use App\User;
use Carbon\Carbon;
use Config;
use DB;
use App\Transporter;
use App\Checklist;
use App\Question;

class BackendDashboardController extends BackendController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getIndex()
    {
        $user = User::find(\Auth::user()->id);

        $total_requests = 0;
        $pendingForAdminApproval = 0;
        $completedRequests = 0;
        $rejectedByAdminRequests = 0;
        $vehicleDispatched = 0;
        $vehicleReached = 0;
        $transporterSelection = 0;
        $transporterSubmission = 0;

        switch (\Auth::user()->role_id) {
            case Config::get('constants.ROLE_ID_SUPPLIER'):
                $tickets = DB::select('select ticket_id,max(status_id) as stat
                from status_ticket
                join (select id from tickets where user_id = ' . $user->id . ' and deleted_at IS NULL) as t
                 on t.id = ticket_id
                  group by ticket_id
                ');
                $total_requests = count($tickets);
                foreach ($tickets as $ticket) {
                    if ($ticket->stat == Config::get('constants.OPEN_BY_SUPPLIER')) {
                        $pendingForAdminApproval += 1;
                    }
                    if ($ticket->stat == Config::get('constants.VEHICLE_OFFLOADED_BY_SITE_TEAM')) {
                        $completedRequests += 1;
                    }
                    if ($ticket->stat == Config::get('constants.CANCEL_BY_ADMIN')) {
                        $rejectedByAdminRequests += 1;
                    }
                }
                break;
            case Config::get('constants.ROLE_ID_SUPER'):
            case Config::get('constants.ROLE_ID_VIEWER'):
            case Config::get('constants.ROLE_ID_ADMIN'):
                $tickets = DB::select('select ticket_id,max(status_id) as stat,max(s.created_at)
                from status_ticket as s
                join (select id from tickets where deleted_at IS NULL) as t
                 on t.id = ticket_id
                group by ticket_id');
                $total_requests = count($tickets);
                foreach ($tickets as $ticket) {
                    if ($ticket->stat == Config::get('constants.OPEN_BY_SUPPLIER')) {
                        $pendingForAdminApproval += 1;
                    }
                    if ($ticket->stat == Config::get('constants.VEHICLE_OFFLOADED_BY_SITE_TEAM')) {
                        $completedRequests += 1;
                    }
                    if ($ticket->stat == Config::get('constants.CANCEL_BY_ADMIN')) {
                        $rejectedByAdminRequests += 1;
                    }
                    if ($ticket->stat == Config::get('constants.VEHICLE_LOADED_BY_SUPPLIER')) {
                        $vehicleDispatched += 1;
                    }
                    if ($ticket->stat == Config::get('constants.VEHICLE_REACHED_AT_DESTINATION_BY_SITE_TEAM')) {
                        $vehicleReached += 1;
                    }
                    if ($ticket->stat == Config::get('constants.ACCEPT_BY_TRANSPORTER')) {
                        $transporterSelection += 1;
                    }
                    if ($ticket->stat == Config::get('constants.APPROVE_BY_ADMIN')) {
                        $transporterSubmission += 1;
                    }
                }
                break;
            case Config::get('constants.ROLE_ID_SITE_TEAM'):
                $tickets = DB::select('select * from tickets
                join (select * from site_user where user_id = ' . $user->id . ') as su
                on su.site_id = site_id_to
                join (select ticket_id,max(status_id) as stat from status_ticket GROUP by ticket_id) as st
                on st.ticket_id = id
                where deleted_at IS NULL
                group by id
                ');
                $total_requests = count($tickets);
                foreach ($tickets as $ticket) {
                    if ($ticket->stat == Config::get('constants.OPEN_BY_SUPPLIER')) {
                        $pendingForAdminApproval += 1;
                    }
                    if ($ticket->stat == Config::get('constants.VEHICLE_OFFLOADED_BY_SITE_TEAM')) {
                        $completedRequests += 1;
                    }
                    if ($ticket->stat == Config::get('constants.CANCEL_BY_ADMIN')) {
                        $rejectedByAdminRequests += 1;
                    }
                    if ($ticket->stat == Config::get('constants.VEHICLE_LOADED_BY_SUPPLIER')) {
                        $vehicleDispatched += 1;
                    }
                    if ($ticket->stat == Config::get('constants.VEHICLE_REACHED_AT_DESTINATION_BY_SITE_TEAM')) {
                        $vehicleReached += 1;
                    }
                    if ($ticket->stat == Config::get('constants.ACCEPT_BY_TRANSPORTER')) {
                        $transporterSelection += 1;
                    }
                    if ($ticket->stat == Config::get('constants.APPROVE_BY_ADMIN')) {
                        $transporterSubmission += 1;
                    }
                }
                break;
            case Config::get('constants.ROLE_ID_TRANSPORTER'):
                //$total_requests = count($user->transporterTickets());
                $total_requests = count(DB::select("SELECT DISTINCT(tt.ticket_id) FROM ticket_transporter AS tt
                JOIN transporter_user AS tu ON tt.transporter_id = tu.transporter_id
                JOIN tickets AS t ON tt.ticket_id = t.id
                JOIN status_ticket as st on tt.ticket_id = st.ticket_id
                WHERE tu.user_id = " . $user->id . " && t.deleted_at IS NULL && st.status_id > 2"));
                break;
            default:
                break;
        }

        $scorecard = BackendDashboardController::getScorecard($user);

        $total = [
            'requests' => $total_requests != null ? $total_requests : 0,
            'requests_pending_for_admin_approval' => $pendingForAdminApproval != null ? $pendingForAdminApproval : 0,
            'requests_rejected_by_admin' => $rejectedByAdminRequests != null ? $rejectedByAdminRequests : 0,
            'requests_completed' => $completedRequests != null ? $completedRequests : 0,
            'requests_vehicle_dispatched' => $vehicleDispatched != null ? $vehicleDispatched : 0,
            'requests_vehicle_reached' => $vehicleReached != null ? $vehicleReached : 0,
            'requests_transporter_selection' => $transporterSelection != null ? $transporterSelection : 0,
            'requests_transporter_submission' => $transporterSubmission != null ? $transporterSubmission : 0,
        ];

        $users = DB::select('SELECT * FROM (SELECT id as unique_id,
        CASE WHEN role_id = 2 OR role_id = 3 THEN name
        WHEN role_id = 4 THEN CONCAT(name,"(",(SELECT title FROM transporters t join transporter_user tu on tu.transporter_id = t.id where tu.user_id = unique_id),")")
        WHEN role_id = 6 THEN CONCAT(name,"(",(SELECT title FROM sites s join site_user su on su.site_id = s.id where su.user_id = unique_id),")") 
        END as name
        FROM users) u WHERE name is not NULL ORDER BY name ASC');
        $usernames = [];
        foreach ($users as $key => $value) {
            $t =[];
            foreach ($value as $k => $v) {
                array_push($t,$v);
            }
            $usernames[$t[0]] = $t[1];
        }
        
        return view(admin_view('dashboard'), compact('total', 'user', 'scorecard','usernames'));
    }

    public static function getScorecard(User $user, Carbon $to = null, Carbon $from = null)
    {
        $to = is_null($to) ? Carbon::now() : $to;
        $from = is_null($from) ?  Carbon::now()->subDays(Config::get('constants.REPORT_DURATION')) : $from;
        $scorecard = [];

        switch ($user->role_id) {
            case Config::get('constants.ROLE_ID_ADMIN'):
                $scorecard = BackendDashboardController::getScorecardForAdmin($user, $to, $from);
                break;
            case Config::get('constants.ROLE_ID_SUPPLIER'):
                $scorecard = BackendDashboardController::getScorecardForSupplier($user, $to, $from);
                break;
            case Config::get('constants.ROLE_ID_TRANSPORTER'):
               $scorecard = BackendDashboardController::getScorecardForTransporter($user, $to, $from);
                break;
            case Config::get('constants.ROLE_ID_SITE_TEAM'):
                $scorecard = BackendDashboardController::getScorecardForSiteUser($user, $to, $from);
                break;
            default:
                break;
        }

        return $scorecard;
    }

    private static function getScorecardForSupplier(User $user, Carbon $to, Carbon $from)
    {
        $tickets = DB::select('SELECT one.id,
                                        CASE WHEN TIMEDIFF(two.created_at,one.created_at) < "' . Config::get('constants.SUPPLIER_TAT') . '" THEN 1 ELSE 0 END as diff
                                        FROM (SELECT t.id, st.created_at, st.status_id FROM users u
                                        JOIN tickets t ON t.user_id = u.id
                                        JOIN status_ticket st ON st.ticket_id = t.id
                                        where u.id = ' . $user->id . ' AND
                                        (st.status_id = ' . Config::get('constants.VEHICLE_APPROVED_BY_SUPPLIER') . ') )one
                                        JOIN (SELECT t.id, st.created_at, st.status_id FROM users u
                                        JOIN tickets t ON t.user_id = u.id
                                        JOIN status_ticket st ON st.ticket_id = t.id
                                        WHERE u.id = ' . $user->id . ' AND
                                        t.created_at >= "' . $from . '" AND
                                        t.created_at <= "' . $to . '" AND
                                        (st.status_id = ' . Config::get('constants.VEHICLE_LOADED_BY_SUPPLIER') . '))two
                                        ON one.id = two.id');
        $onTime = 0;
        if (count($tickets)) {
            foreach ($tickets as $t) {
                $onTime += $t->diff;
            }
            $scorecard["Turn-Around Time"] = number_format($onTime * 100 / count($tickets), 2) . "%";
        } else {
            $scorecard["Turn-Around Time"] = 'nil';
        }
        return $scorecard;
    }

    private static function getScorecardForAdmin(User $user, Carbon $to, Carbon $from)
    {
        /*
         * ADMIN TURN AROUND TIME
         */
        $tickets = DB::select('SELECT one.id,
                                        CASE WHEN TIMEDIFF(two.created_at,one.created_at) < "' . Config::get('constants.ADMIN_TAT') . '" THEN 1 ELSE 0 END as diff
                                        FROM (SELECT t.id, st.created_at, st.status_id FROM tickets t
                                        JOIN status_ticket st ON st.ticket_id = t.id
                                        where (st.status_id = ' . Config::get('constants.OPEN_BY_SUPPLIER') . ') )one
                                        JOIN (SELECT t.id, st.created_at, st.status_id FROM tickets t
                                        JOIN status_ticket st ON st.ticket_id = t.id
                                        WHERE st.user_id = ' . $user->id . ' AND
                                        t.created_at >= "' . $from . '" AND
                                        t.created_at <= "' . $to . '" AND
                                        (st.status_id = ' . Config::get('constants.CANCEL_BY_ADMIN') . ' OR
                                        st.status_id = ' . Config::get('constants.APPROVE_BY_ADMIN') . '))two
                                        ON one.id = two.id');
        $onTime = 0;
        if (count($tickets)) {
            foreach ($tickets as $t) {
                $onTime += $t->diff;
            }
            $scorecard["Turn-Around Time"] = number_format($onTime * 100 / count($tickets), 2) . "%";
        } else {
            $scorecard["Turn-Around Time"] = 'nil';
        }

        /*
         * TRANSPORTER PUNCTUALITY
         */
        $tickets = DB::select('SELECT one.id,
                                        CASE WHEN TIMEDIFF(two.created_at,one.created_at) < "' . Config::get('constants.TRANSPORTER_PUNCTUALITY') . '" THEN 1 ELSE 0 END as diff
                                        FROM (SELECT t.id, st.created_at, st.status_id FROM tickets t
                                        JOIN status_ticket st ON st.ticket_id = t.id
                                        where (st.status_id = ' . Config::get('constants.VEHICLE_ARRIVED_BY_SUPPLIER') . ') )two
                                        JOIN (SELECT t.id, st.created_at, st.status_id FROM tickets t
                                        JOIN status_ticket st ON st.ticket_id = t.id
                                        WHERE st.user_id = ' . $user->id . ' AND
                                        t.created_at >= "' . $from . '" AND
                                        t.created_at <= "' . $to . '" AND
                                        (st.status_id = ' . Config::get('constants.APPROVE_BY_ADMIN') . '))one
                                        ON one.id = two.id');
        $onTime = 0;
        if (count($tickets)) {
            foreach ($tickets as $t) {
                $onTime += $t->diff;
            }
            $scorecard["Transporter Punctuality"] = number_format($onTime * 100 / count($tickets), 2) . "%";
        } else {
            $scorecard["Transporter Punctuality"] = 'nil';
        }

        /*
         * Safety Compliance
         */
        $tickets = DB::select('SELECT count(three.created_at) as rejected,
                                COUNT(*) as total
                                FROM (SELECT ticket_id as id FROM status_ticket 
                                where user_id = '.$user->id.' and
                                status_id = '.Config::get('constants.APPROVE_BY_ADMIN').') two
                                JOIN (SELECT t.id, st.created_at, st.status_id FROM tickets t
                                JOIN status_ticket st ON st.ticket_id = t.id
                                where (st.status_id = '.Config::get('constants.VEHICLE_ARRIVED_BY_SUPPLIER').')   AND
                                t.created_at >= "' . $from . '" AND
                                t.created_at <= "' . $to . '") one
                                ON one.id = two.id
                                LEFT JOIN (SELECT t.id, st.created_at, st.status_id FROM tickets t
                                JOIN status_ticket st ON st.ticket_id = t.id
                                where (st.status_id = '.Config::get('constants.CANCELLED_BY_SUPPLIER').')) three
                                ON one.id = three.id');

        if ($tickets[0]->total) {
            $safe = $tickets[0]->total - $tickets[0]->rejected;
            $scorecard["Safety Compliance"] = number_format($safe * 100 / $tickets[0]->total, 2) . "%";
        } else {
            $scorecard["Safety Compliance"] = 'nil';
        }

        return $scorecard;
    }

    private static function getScorecardForTransporter(User $user, Carbon $to, Carbon $from)
    {
        $transporters = Transporter::find($user->transporters->first()->id)->users->pluck(['id']);
        $transporters = implode(",",array_values($transporters->toArray())); 
        
        /*
         * ON TIME ARRIVAL
         */
        $tickets = DB::select('SELECT one.id,
                                        CASE WHEN TIMEDIFF(one.created_at,two.created_at) < "' . Config::get('constants.TRANSPORTER_TAT') . '" THEN 1 ELSE 0 END as diff
                                        FROM (SELECT t.id, st.created_at, st.status_id FROM tickets t
                                        JOIN status_ticket st ON st.ticket_id = t.id
                                        where (st.status_id = ' . Config::get('constants.VEHICLE_ARRIVED_BY_SUPPLIER') . ') )one
                                        JOIN (SELECT t.id, st.created_at, st.status_id FROM tickets t
                                        JOIN status_ticket st ON st.ticket_id = t.id
                                        WHERE t.created_at >= "' . $from . '" AND
                                        t.created_at <= "' . $to . '" AND
                                        (st.status_id = ' . Config::get('constants.APPROVE_BY_ADMIN'). '))two
                                        ON one.id = two.id
                                        JOIN (SELECT ticket_id from ticket_transporter WHERE transporter_id = '.$user->transporters->first()->id.') three 
                                        ON two.id = three.ticket_id');
        $onTime = 0;
        if (count($tickets)) {
            foreach ($tickets as $t) {
                $onTime += $t->diff;
            }
            $scorecard["On-Time Arrival"] = number_format($onTime * 100 / count($tickets), 2) . "%";
        } else {
            $scorecard["On-Time Arrival"] = 'nil';
        }

        /*
         * TRANSPORTER PUNCTUALITY
         */
        $tickets = DB::select('SELECT one.ticket_id,
                                        CASE WHEN TIMEDIFF(two.created_at,one.created_at) < SEC_TO_TIME(three.transit_time_hrs*60*60) THEN 1 ELSE 0 END as diff
                                        FROM (SELECT st.ticket_id, st.created_at, st.status_id FROM status_ticket st
                                        where (st.status_id = ' . Config::get('constants.VEHICLE_LOADED_BY_SUPPLIER') . ') )one
                                        JOIN (SELECT st.ticket_id, st.created_at, st.status_id FROM status_ticket st
                                        where st.created_at >= "' . $from . '" AND
                                        st.created_at <= "' . $to . '" AND
                                        (st.status_id = ' . Config::get('constants.VEHICLE_REACHED_AT_DESTINATION_BY_SITE_TEAM') . '))two
                                        ON one.ticket_id = two.ticket_id
                                        JOIN (SELECT ticket_id,transit_time_hrs FROM ticket_transporter tt
                                            JOIN (SELECT t.id,transit_time_hrs FROM `lanes` l
                                                JOIN tickets t ON t.site_id_to = l.site_id_to and t.site_id_from = l.site_id_from) lt 
                                            ON lt.id = tt.ticket_id 
                                        where transporter_id = '.$user->transporters->first()->id.') three
                                        ON three.ticket_id = two.ticket_id');
        $onTime = 0;
        if (count($tickets)) {
            foreach ($tickets as $t) {
                $onTime += $t->diff;
            }
            $scorecard["Transit Time Adherence"] = number_format($onTime * 100 / count($tickets), 2) . "%";
        } else {
            $scorecard["Transit Time Adherence"] = 'nil';
        }

        /*
         * Safety Compliance
         */
        $tickets = DB::select('SELECT count(three.created_at) as rejected,
                                COUNT(*) as total
                                FROM (SELECT ticket_id as id FROM ticket_transporter
                                where transporter_id = '.$user->transporters->first()->id.') two
                                JOIN (SELECT t.id, st.created_at, st.status_id FROM tickets t
                                JOIN status_ticket st ON st.ticket_id = t.id
                                where (st.status_id = '.Config::get('constants.VEHICLE_ARRIVED_BY_SUPPLIER').')   AND
                                t.created_at >= "' . $from . '" AND
                                t.created_at <= "' . $to . '") one
                                ON one.id = two.id
                                LEFT JOIN (SELECT t.id, st.created_at, st.status_id FROM tickets t
                                JOIN status_ticket st ON st.ticket_id = t.id
                                where (st.status_id = '.Config::get('constants.CANCELLED_BY_SUPPLIER').')) three
                                ON one.id = three.id');

        if ($tickets[0]->total) {
            $safe = $tickets[0]->total - $tickets[0]->rejected;
            $scorecard["Safety Compliance"] = number_format($safe * 100 / $tickets[0]->total, 2) . "%";
        } else {
            $scorecard["Safety Compliance"] = 'nil';
        }

        return $scorecard;
    }

    private static function getScorecardForSiteUser(User $user, Carbon $to, Carbon $from)
    {
        $siteUsers = DB::select("SELECT user_id FROM site_user WHERE site_id = (SELECT site_id FROM site_user  WHERE user_id = ".$user->id.")");
        $sUsers = [];
        foreach ($siteUsers as $key => $value) {
            foreach ($value as $k => $v) {
                array_push($sUsers, $v);
            }
        }
        $sUsers = implode(",", $sUsers);
        $tickets = DB::select('SELECT one.ticket_id,
                                        CASE WHEN TIMEDIFF(two.created_at,one.created_at) < "' . Config::get('constants.SITE_USER_TAT') . '" THEN 1 ELSE 0 END as diff
                                        FROM (SELECT st.ticket_id, st.created_at, st.status_id FROM status_ticket st
                                        where st.user_id IN (' . $sUsers . ') AND
                                        (st.status_id = ' . Config::get('constants.VEHICLE_REACHED_AT_DESTINATION_BY_SITE_TEAM') . ') )one
                                        JOIN (SELECT st.ticket_id, st.created_at, st.status_id FROM status_ticket st
                                        where st.user_id IN (' . $sUsers . ') AND
                                        st.created_at >= "' . $from . '" AND
                                        st.created_at <= "' . $to . '" AND
                                        (st.status_id = ' . Config::get('constants.VEHICLE_OFFLOADED_BY_SITE_TEAM') . '))two
                                        ON one.ticket_id = two.ticket_id');
        $onTime = 0;
        if (count($tickets)) {
            foreach ($tickets as $t) {
                $onTime += $t->diff;
            }
            $scorecard["Turn-Around Time"] = number_format($onTime * 100 / count($tickets), 2) . "%";
        } else {
            $scorecard["Turn-Around Time"] = 'nil';
        }
        return $scorecard;
    }

    public function scorecardRequest(){
        $user =User::find(request()->username);
        $from= is_null(request()->start)? null : Carbon::parse(request()->start);
        $to = is_null(request()->end)? null : Carbon::parse(request()->end);

        $scorecard = BackendDashboardController::getScorecard($user,$to,$from);

        $html = '<div style="padding-left:16px;">
                        <h3>'.$user->name.' <small style="color:white">('.$user->role->title.')</small></h3>
                        <ul style="list-style-type: none;margin-left: -24px; margin-top:16px;">';
        $stars = 0;
        foreach ($scorecard as $key => $value) {
            $stars += (double) ($value) / 100;
            $html = $html.'<li>
                                <div class="row">
                                    <div class="col-md-9 font-weight-bold">'.$key.'
                                    </div>
                                    <div class="col-md-3">
                                        '.$value.'
                                    </div>
                                </div>
                            </li>';
        }
        if(!count($scorecard)){
            $html = $html.'<li> No KPI specified</li>';
            
        }else{
            $html = $html . '<br>
                            <div class="row" style="font-size: 1.1em;">
                                <div class="col-md-9"><b>Overall Score</b>
                                </div>
                                <div class="col-md-3">
                                <span class="glyphicon glyphicon-star"></span>' . ' ' . number_format($stars * 5 / count($scorecard), 2) . '
                                </div>
                            </div>';
        }

        $html = $html.' </ul>
                    </div>';
        return $html;    
    }
}
