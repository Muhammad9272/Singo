<?php
namespace App\Helpers;
use App\Models\Reward;
use Auth;
use Session;
use Carbon\Carbon;
class AppHelper
{


    public static function tstreams(){
         
        $totalstreamss=0;
        $client = new \GuzzleHttp\Client();
        
        $surl=AppHelper::streamsurl();
        $time=AppHelper::alltime();

        $response=$client->get($surl.'/api/chart/artist_streams_chart?date_range='.$time.'&user_id='.Auth::user()->id);
        
        $data=json_decode($response->getBody());
        foreach($data->datasets as $dataset) {
            if($dataset->name=="total"){
                $totalstreamss=$dataset->values[0];
            }
        }
        
        $min=Reward::orderBy('points','asc')->first();
        $badge=$min->badge;
        
        $rewards=Reward::where('points','<=',$totalstreamss)->orderBy('points','desc')->first();
        if($rewards){
             $badge=$rewards->badge;
        }
       
        // $rewards=Reward::all();
        // foreach ($rewards as $key => $reward) {

        //      if($totalstreamss>=$reward->points){
        //         $badge=$reward->badge;
        //      }
        // }
        // dd($badge);
        return $badge;
    }
    
    public static  function alltime($value='')
    {
        $time=Carbon::now()->subMonths(12)->format('Y-m-d').' - '.Carbon::now()->format('Y-m-d') ;
        return $time;
    }

    public static  function streamsurl()
    {
        $surl="https://app.singo.io";
        return $surl;
    }



}