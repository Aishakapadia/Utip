<?php 
namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\ChecklistSubmitRequest;
use App\Checklist;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Config;
use DB;
use App\Transporter;
use App\Question;
use App\Site;
use App\VehicleType;
use App\ChecklistFile;
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\LaravelAdapter;
use App\Http\Middleware\Notify;

class ChecklistController extends BackendController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    
    const types = ["Inbound","Outbound-Primary","Outbound-Secondary"];

    public function index()
    {
        $user = User::find(\Auth::user()->id);

        $checklist = ($user->isAdmin() || $user->isSuper()) ? Checklist::getSummary() : null;

        $submitters = is_null($checklist) ? null : $checklist[1];
        $inspected = is_null($checklist) ? null : array_pop($checklist[0]);
        $checklist = is_null($checklist) ? ChecklistController::createChecklist() : $checklist;
        
        $types = ChecklistController::types;
        
        $vehicle_types = VehicleType::getVehicleTypesForDropDown();

        return view(admin_view('checklist'), compact('user','checklist', 'inspected', 'submitters','types','vehicle_types'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function show(Checklist $checklist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function edit(Checklist $checklist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checklist $checklist)
    {
        //
    }

    public function addChecklist(ChecklistSubmitRequest $request){
        ChecklistCOntroller::insertChecklist($request);
        $request->session()->flash('alert-success', 'Checklist submitted successfully.');

        return redirect(route('checklist-detail'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checklist $checklist)
    {
        //
    }

    public static function insertChecklist(Request $request){
        $user = User::find(\Auth::user()->id);

        $checklist = new Checklist();
        $checklist->ticket_id = -1;
        $checklist->status_id = -1;
        $checklist->status = NULL;
        $checklist->transporter = Transporter::find($request->transporter)->title;
        $checklist->driver_name = $request->driver_name;
        $checklist->driver_nic = $request->driver_nic;
        $checklist->type = ChecklistController::types[$request->type];
        $checklist->vehicle_type = VehicleType::find($request->vehicle_type)->title;
        $checklist->vehicle_number = $request->vehicle_number;
        $checklist->questions = json_encode(Question::orderBy('important','desc')->orderBy('id','asc')->pluck('question')->toArray());
        $checklist->responses = json_encode($request->question);
        $checklist->selected = ChecklistController::checkVehicleStatus(strtolower($checklist->responses));
        $checklist->site_to = Site::find($request->site_id_to)->title;
        $checklist->site_from = Site::find($request->site_id_from)->title;
        $checklist->inspection_site =  Site::find($request->inspection_site)->title;
        $checklist->comments = $request->comments;
        $checklist->submitted_by = $user->name;
        $checklist->save();

        //save uploaded file and update database
        if ($request->file('file_upload')) {
            $fileName = "chk".$checklist->id . '_' . time() .
            '.' . $request->file('file_upload')->getClientOriginalExtension();

            $request->file('file_upload')->move(
                base_path() . '/public/uploads/files/', $fileName
            );

            $file = new ChecklistFile();
            $file->checklist_id = $checklist->id;
            $file->file = $fileName;
            $file->save();
        }

        if(!$checklist->selected){
            $message = "The vehicle with reg. number ".$checklist->vehicle_number." by ".$checklist->transporter." was rejected on ".$checklist->created_at.
            " enroute to ".$checklist->site_to." from ".$checklist->site_from;
            foreach(User::where('role_id','=',Config::get('constants.ROLE_ID_ADMIN'))->get() as $user){
                Notify::sendSMSWithoutTicket($user->mobile, $message);
            }
        }
    }

    public static function checkVehicleStatus(String $response){
        $important = Question::where('important','=',true)->pluck('id')->toArray();
        $response = str_replace(['[',']','"'],"",$response);
        $response = explode(",",$response);
        foreach($important as $k=>$v){
            if ($response[$k] == Config::get('constants.FAILED_RESPONSE')){
                return False;
            } 
        }
        return True;
    }

    public function getManage(Request $request)
    {
        // Show the page
        
        $statusFilter = isset($request->statusFilter) ? htmlspecialchars_decode($request->statusFilter) : "";
        $siteFilter = isset($request->siteFilter) ? htmlspecialchars_decode($request->siteFilter) : "";
        $typeFilter = isset($request->typeFilter) ? htmlspecialchars_decode($request->typeFilter) : "";

        return view(admin_view('checklists.manage'),compact('siteFilter','statusFilter','typeFilter'));
    }

    public function getDetail(Checklist $checklist)
    {
       if(\Auth::user()->name == $checklist->submitted_by || \Auth::user()->isAdmin()){
            $files = DB::select("SELECT * FROM checklist_files WHERE checklist_id =" . $checklist->id);

            return view(admin_view('checklists.detail'), compact('checklist', 'files'));

       }else{
           abort(404); 
       }
       
    }

    public function getData(){

        $query = 'Select id,inspection_site,transporter,vehicle_number,
        case when selected = 1 then "Accepted" else "Rejected" end as selected,
        type,created_at,submitted_by from checklists';

        $dt = new Datatables(new LaravelAdapter);
        $dt->query($query);

        $dt->add('action', function ($data) {
        return '<a  class="btn btn-primary" href= "'.route("checklist-detail.checklist", $data["id"]).'">View Details</a>';
        });

        if(request()->filter == 1){
            $dt->filter('created_at', function () {
                $to = Carbon::now();
                $from = Carbon::now()->subDays(1);
                return $this->between($from, $to);
            });
        }

        $user = User::find(\Auth::user()->id);
        if(!$user->isAdmin()){
            $dt->filter('submitted_by', function () {
                return $this->whereIn([\Auth::user()->name]);
            });
        }

        echo $dt->generate();
    }


    private static function createChecklist()
    {
        $checklist = "<p  class='m-5'><b>Note: Vehicle are susceptible to rejection if questions colored red, fail!</b></p>";
        $questions = Question::where('id','>','0')->orderBy('important','desc')->orderBy('id','asc')->get();
        $count = 0;

        foreach ($questions as $q) {
            $style = $q->important ? 'style="color:red"' : '';
            $q_template_start = '
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-md-9">
                            <label '.$style.' >'.$q->question.'<span class="required" aria-required="true">*</span><br>
                            <span style="font-size:1.5em;">'.$q->localized.'</span><span class="required" aria-required="true">*</span>
                            </label>
                        </div>';
            $checklist = $checklist . $q_template_start;
            $options = json_decode($q->options);
            foreach ($options as $k => $v) {
                $checked = (!$q->important && strtolower($v) == 'pass') ? ' checked ' : '';

                $a_template = '
                    <div class="col-md-1">
                        <span>'.$v.'<input class="form-control" type="radio" style="height:20px;" name="question['.$count.']" value="'.$v.'" '.$checked.' required></span>
                    </div>';
                $checklist = $checklist . $a_template;
            }
            $q_template_end ='
                 </div>
                </div>
            </div>
            <hr>';
            $count++;
            $checklist = $checklist . $q_template_end;
        }

        return $checklist;
    }

    public function array2csv(Carbon $to,Carbon $from)
    {
        $keys = ["Ticket","Transporter","Driver Name","Driver NIC","Vehicle Number","Vehicle Type","Event","Date","Site To","Site From","Type","Inspection Site","Submitted By","Vehicle Passed","Comments"];
        $printHeader = true; 
        ob_start();
        $df = fopen("php://output", 'w');
        
        $checklist = Checklist::getChecklists($to,$from);

        foreach ($checklist as $k => $v) {
            $row = array();
            array_push($row, $v->ticket_id);
            array_push($row,$v->transporter);
            array_push($row,$v->driver_name);
            array_push($row,$v->driver_nic);
            array_push($row,$v->vehicle_number);
            array_push($row,$v->vehicle_type);
            array_push($row,$v->status);
            array_push($row, $v->created_at);
            array_push($row,$v->site_to);
            array_push($row, $v->site_from);
            array_push($row, $v->type);
            array_push($row, $v->inspection_site);
            array_push($row, $v->submitted_by);
            array_push($row, $v->selected ? "Passed":"Failed");
            array_push($row, str_replace("\n"," | ",$v->comments));


            $questions = json_decode($v->questions);
            if($printHeader){
                foreach($questions as $question){
                    array_push($keys,$question);
                }
                fputcsv($df, $keys);
                $printHeader = false;
            }
            $response = json_decode($v->responses);
            foreach ($questions as $k=>$r) {
                $row[$r] = $response[$k];
            }
            fputcsv($df, $row);
        }

        fclose($df);
        return ob_get_clean();

        return null;
    }

    public function download_send_headers($filename)
    {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

    public function exportSafetyRecords()
    {
        $to = is_null(request()->to) ? Carbon::now() : Carbon::parse(request()->to);
        $from = is_null(request()->from) ? Carbon::now()->subDays(Config::get('constants.REPORT_DURATION')) : Carbon::parse(request()->from);
        
        $this->download_send_headers('myutip_safety_checklist_' . date("Y-m-d") . ".csv");
        echo $this->array2csv($to,$from);

        die();
    }
}
