<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\User;
use DB;
use Config;

class Checklist extends Model
{
    public static function getChecklists(Carbon $to, Carbon $from){
        $checklist = null;
        if(\Auth::user()->isAdmin()){
            $checklists = Checklist::where([['created_at', '<=', $to], ['created_at', '>=', $from]])->get();

        }else{
            $checklists = Checklist::where([['created_at', '<=', $to], ['created_at', '>=', $from], ['submitted_by','=',\Auth::user()->name]])->get();

        }
        
        return $checklists;
    }

    public static function getSummary(Carbon $to = NULL, Carbon $from = NULL){
        $to = is_null($to) ? Carbon::now() : $to;
        $from = is_null($from) ? Carbon::now()->subHours(24) : $from;

        $checklist = Checklist::where([['created_at','<=',$to],['created_at','>=',$from]])->get();
        $summary = [];
        foreach($checklist as $c){
            $questions = json_decode($c->questions);
            $responses = json_decode($c->responses);
            foreach($questions as $k=>$v){
                if(array_key_exists($v,$summary)){
                   array_push($summary[$v],$responses[$k]);
                }else{
                    $summary[$v] = [$responses[$k]];
                }
            }
        }

        //Get response count for each question
        $counts = [];
        foreach($summary as $k=>$v){
            $counts[$k] = array_count_values($v);
        }
        
        $counts["Vehicle Inspected"] = ["Total"=>count($checklist)];
        
        // //Get response count by submitters
        // $submitters = [];
        // foreach ($checklist as $value) {
        //     array_push($submitters,$value->submitted_by);
        // }
        // $submitters = array_count_values($submitters);

        $submittersInbound = DB::select('select two.site,coalesce(cancelled,0) as cancelled,total 
        from (select inspection_site as site,count(id) as cancelled from `checklists` where (`created_at` <= "'.$to.'" and `created_at` >= "'.$from.'" and selected = 0 and (type = "'.Config::get('constants.TYPE_INBOUND').'")) group by site) one
         right join (select inspection_site as site,count(id) as total from `checklists` where (`created_at` <= "'.$to.'" and `created_at` >= "'.$from.'" and (type = "'.Config::get('constants.TYPE_INBOUND').'")) group by site) two on one.site = two.site');
         
         
        $submittersPrimary = DB::select('select two.site,coalesce(cancelled,0) as cancelled,total 
        from (select inspection_site as site,count(id) as cancelled from `checklists` where (`created_at` <= "'.$to.'" and `created_at` >= "'.$from.'" and selected = 0 and type = "'.Config::get('constants.TYPE_OUTB_PRI').'") group by site) one
         right join (select inspection_site as site,count(id) as total from `checklists` where (`created_at` <= "'.$to.'" and `created_at` >= "'.$from.'" and type = "'.Config::get('constants.TYPE_OUTB_PRI').'") group by site) two on one.site = two.site');
         
         
        $submittersSecondary = DB::select('select two.site,coalesce(cancelled,0) as cancelled,total
        from (select inspection_site as site,count(id) as cancelled from `checklists` where (`created_at` <= "' . $to . '" and `created_at` >= "' . $from . '" and selected = 0 and type = "'.Config::get('constants.TYPE_OUTB_SEC').'") group by site) one
         right join (select inspection_site as site,count(id) as total from `checklists` where (`created_at` <= "' . $to . '" and `created_at` >= "' . $from . '" and type = "'.Config::get('constants.TYPE_OUTB_SEC').'") group by site) two on one.site = two.site');

         return [$counts,[$submittersInbound,$submittersPrimary,$submittersSecondary]];
    }
}
