<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Ticket;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\Mail\KEmail;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Notify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $tickets =  Session::get('middleware_ticket');
        $status = Session::get('middleware_notify_status');
        $summary = Session::get('middleware_notify_summary');

        if (($response->status() == 200 || $response->status() == 302) && !is_null($tickets)) {

            foreach ($tickets as $ticket) {
                if($response->status() && $ticket){
                    $admins = Notify::getAdmin();
                    $supplier = Notify::getSupplier($ticket);
                    $site_users = Notify::getSiteUsers($ticket);
                    $transporters = Notify::getTransporters($ticket,$status);
                    
                    $keys = Config::get('constants.NOTIFY_KEYS');
                    $key = $keys[$status];
                    if(strlen($key) > 1){
                        $subject = Notify::transformSubject(Config::get('constants.EMAIL_SUBJECT')[$key],$ticket);
                        $supplierMessage = Notify::transformMessage(Config::get('constants.EMAIL_RESPONSE_SUPPLIER')[$key],$ticket);
                        $adminMessage = Notify::transformMessage(Config::get('constants.EMAIL_RESPONSE_ADMIN')[$key],$ticket);
                        $transporterMessage = Notify::transformMessage(Config::get('constants.EMAIL_RESPONSE_TRANSPORTER')[$key],$ticket);
                        $site_userMessage = Notify::transformMessage(Config::get('constants.EMAIL_RESPONSE_SITE_TEAM')[$key],$ticket);
                        
                        if($supplier && strlen($supplierMessage) > 1 && strlen($subject)>1)
                            Notify::sendNotification($supplier,$subject,$supplierMessage,$ticket,$summary);
                        if($admins && strlen($adminMessage) > 1 && strlen($subject)>1)
                            Notify::sendNotification($admins,$subject,$adminMessage,$ticket,$summary);
                        if($transporters && strlen($transporterMessage) > 1 && strlen($subject)>1)
                            Notify::sendNotification($transporters,$subject,$transporterMessage,$ticket,$summary);
                        if($site_users && strlen($site_userMessage) > 1 && strlen($subject)>1)
                            Notify::sendNotification($site_users,$subject,$site_userMessage,$ticket,$summary);
                    }
                } 
            }
        }
        
        Session::remove('middleware_ticket');
        Session::remove('middleware_notify_summary');
        Session::remove('middleware_notify_status');
        return $response;
    }

    private static function getAdmin(){
       return DB::select("select * from users where role_id = 2");
    }

    private static function getSupplier(Ticket $ticket){
        return User::find(DB::select("Select user_id from tickets where id = ".$ticket->id)[0]->user_id);
    }

    private static function getSiteUsers(Ticket $ticket){
        $site_users = Array();
        $query = DB::select("select user_id from site_user where site_id = ".(DB::select("Select site_id_to from tickets where id = ".$ticket->id)[0]->site_id_to));
        if($query){
            foreach ($query as $site_user) {
                array_push($site_users,User::find($site_user->user_id));
            }
        }
        return $site_users;
    }

    private static function getTransporters(Ticket $ticket,Int $status){
        $transporters = Array();
        $userTransporter = DB::select("select transporter_id from transporter_user where user_id = ".Auth::user()->id);
        if ($userTransporter){
            $userTransporter = $userTransporter[0]->transporter_id;
        }

        $query = DB::select("select transporter_id from ticket_transporter as tt
        join (select ticket_id,max(transporter_status_id) as max from ticket_transporter GROUP by ticket_id) as t
        on t.ticket_id = tt.ticket_id && t.max = tt.transporter_status_id
        where tt.ticket_id = ".$ticket->id);
        if($query){
            if(is_array($query)){
                foreach ($query as $q) {
                    if ($status != Config::get('constants.ACCEPT_BY_TRANSPORTER') || $userTransporter == $q->transporter_id)
                        foreach (DB::select("select user_id from transporter_user where transporter_id = ".$q->transporter_id) as $transporter) {
                            array_push($transporters,User::find($transporter->user_id));
                        }
                }
            }else{
                if ($status != Config::get('constants.ACCEPT_BY_TRANSPORTER') || $userTransporter == $query[0]->transporter_id)
                    foreach (DB::select("select user_id from transporter_user where transporter_id = ".$query[0]->transporter_id) as $transporter) {
                        array_push($transporters,User::find($transporter->user_id));
                    }
            }
        }
        return $transporters;
    }

    private static function transformSubject(String $text, Ticket $ticket){
        $transform = $text;
        $transform = str_replace("ticket_num",$ticket->ticket_number,$transform);
        return $transform;
    }

    private static function transformMessage(String $text, Ticket $ticket){
        $transform = $text;
        $transform = str_replace("#vehicle_type",DB::select("select title from vehicle_types where id = ".$ticket->vehicle_type_id)[0]->title,$transform);
        $transform = str_replace("#destination",DB::select("select title from sites where id = ".$ticket->site_id_from)[0]->title,$transform);
        $transform = str_replace("#site",DB::select("select title from sites where id = ".$ticket->site_id_to)[0]->title,$transform);
        $transform = str_replace("#supplier",DB::select("select name from users where id = ".$ticket->user_id)[0]->name,$transform);
        
        $user = User::find(\Auth::user()->id);
        if ($user->isTransporter()) {
            $title = $user->transporters->first()->title;
            $transform = str_replace("#transporter",$title,$transform);
        } else {
            $query = DB::select("Select title from transporters 
            where id = (SELECT transporter_id FROM ticket_transporter where transporter_status_id > 1 and ticket_id = ".$ticket->id.")");
            $transform = str_replace("#transporter",count($query) == 0?"":$query[0]->title,$transform);
        }
        
        $query = DB::select("select delivery_challan_number  as dcn from tickets where id = ".$ticket->id);
        if(!is_null($query[0]->dcn)){
            $transform = str_replace("#dc_challan",$query[0]->dcn,$transform);
        }
        
        $query = DB::select("SELECT st.comments from status_ticket as st
        join (select max(id) as max from status_ticket group by ticket_id) as t 
        on st.id = t.max
        where st.ticket_id = ".$ticket->id);
        $transform = str_replace("#reasons",count($query) == 0?"":$query[0]->comments,$transform);

        $query = DB::select("SELECT st.created_at from status_ticket as st
        join (select max(id) as id from status_ticket group by ticket_id) as t 
        on t.id = st.id
        where ticket_id = ".$ticket->id." and st.status_id = ".$ticket->relationActiveStatus()->id);
        $transform = str_replace("#time",count($query) == 0?"":Carbon::parse($query[0]->created_at,'UTC')->format('jS F, Y g:i a'),$transform);

        $query = DB::select("select eta from ticket_transporter where ticket_id = ".$ticket->id." and transporter_status_id > 1");
        $transform = str_replace("#trans_time",count($query) == 0?"":Carbon::parse($query[0]->eta,'UTC')->format('jS F, Y g:i a'),$transform);
        return $transform;
    }

    private static function sendNotification($user,$subject,$message,$ticket,$summary = NULL){
        $emailData['ticket'] = $ticket;
        $emailData['subject'] = $subject;
        $emailData['summary'] = $summary;

        if(is_array($user)){
            foreach ($user as $u) {
				if(!is_null($u)){
                $emailData['message'] = str_replace("##user",$u->name,$message);
                if($ticket->relationActiveStatus()->id == Config::get('constants.VEHICLE_OFFLOADED_BY_SITE_TEAM')){
                    Mail::to($u->email)->send(new KEmail($emailData));
                }
                Notify::sendSMS($u->mobile,str_replace("##user",$u->name,$message),$ticket);
				}
            }
        }else{
			if(!is_null($user)){
            $emailData['message'] = str_replace("##user",$user->name,$message);
            if($ticket->relationActiveStatus()->id == Config::get('constants.VEHICLE_OFFLOADED_BY_SITE_TEAM')){
                Mail::to($user->email)->send(new KEmail($emailData));
            }
            Notify::sendSMS($user->mobile,str_replace("##user",$user->name,$message),$ticket);
			}
        }
    }

    private static function sendSMS($number,$message,$ticket){
        $session_id = Notify::getSessionID();
        if(strlen($number) > 0 && !is_null($session_id)){
            $msg = $ticket->ticket_number."%0D%0A".str_replace("<br><br>","%0D%0A",$message);
            $client = new Client(['verify' => false ]);
            $api = Config::get("constants.SMS_API");
            $api = str_replace("##message",htmlentities($msg),$api);
            $api = str_replace("##session_id",$session_id,$api);

            $numbers = explode(",",$number);
            if(is_array($numbers)){
                foreach ($numbers as $num) {
                    $t_api = str_replace("##number",Notify::convertPhoneNumber($num),$api);
                    $client->request('GET',$t_api);
                }
            }else{
                $api = str_replace("##number",Notify::convertPhoneNumber($numbers),$api);
                $client->request('GET',$api);
            }
        }
    }

    public static function sendSMSWithoutTicket($number,$message){
        $session_id = Notify::getSessionID();
        if(strlen($number) > 0 && !is_null($session_id)){
            $msg = str_replace("<br><br>","%0D%0A",$message);
            $client = new Client(['verify' => false ]);
            $api = Config::get("constants.SMS_API");
            $api = str_replace("##message",htmlentities($msg),$api);
            $api = str_replace("##session_id",$session_id,$api);

            $numbers = explode(",",$number);
            if(is_array($numbers)){
                foreach ($numbers as $num) {
                    $t_api = str_replace("##number",Notify::convertPhoneNumber($num),$api);
                    $client->request('GET',$t_api);
                }
            }else{
                $api = str_replace("##number",Notify::convertPhoneNumber($numbers),$api);
                $client->request('GET',$api);
            }
        }
    }

    private static function convertPhoneNumber($number){
        //remove hyphen
        $number = str_replace('-', ',', $number); 
        //remove starting zero and +
        $number = ltrim($number, '0');
        $number = ltrim($number, '+');
        //add country code
        $number = substr( $number, 0, 2 ) == "92" ? $number : "92".$number ;

        return $number;
    }

    private static function getSessionID(){
        $session_id = null;
        $client = new Client(['verify' => false ]);
        $api = Config::get("constants.SMS_SESSION_STRING");
        $responseBody = $client->request('GET', $api)->getBody();
        if($responseBody) {
            try {
                $responseJSON = \GuzzleHttp\json_decode(\GuzzleHttp\json_encode(simplexml_load_string($responseBody, "SimpleXMLElement", LIBXML_NOCDATA)), true);
                if(
                    (isset($responseJSON['response']) && $responseJSON['response'] == 'OK') &&
                    (isset($responseJSON['data']) && $responseJSON['data'])
                ) {
                    $session_id = $responseJSON['data'];
                } else {
                    $status = false;
                    $error_msg = 'Invalid credentials provided';
                }
            } catch (\Exception $e) {
                $status = false;
                $error_msg = 'Service is temporarily unavailable. Your patience is requested.';
            }
        } else {
            $status = false;
            $error_msg = 'Unable to connect with server.';
        }
        return $session_id;
    }
}