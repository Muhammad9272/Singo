<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Reward;
use App\Models\RewardRequest;
use Auth;
use Illuminate\Http\Request;

class RewardRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

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

        $rewards=Reward::all();
        return view('reward',compact('rewards','totalstreamss'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,$id)
    {   
        
        $rewardrq=RewardRequest::where('user_id',Auth::id())->where('reward_id',$id)->first();
        if($rewardrq){
          return redirect()->back()->with('info', 'Already Claimed.');
        }

        
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
        $rewardex=Reward::find($id);
        if($totalstreamss<$rewardex->points){
            return redirect()->back()->with('danger', 'You dont have enough streams to claim that reward.');
        }

        $data=new RewardRequest();
        $data->reward_id=$id;
        if($rewardex->is_physical==1)
        {
            $data->fname=$request->fname;
            $data->lname=$request->lname;
            $data->street_no=$request->street_no;
            $data->contact_no=$request->contact_no;
            $data->zip_code=$request->zip_code;
            $data->city=$request->city;
            $data->country=$request->country;
            $data->info=$request->info;
        }
        $data->user_id=Auth::user()->id;
        $data->save();
        return redirect()->back()->with('success', 'Claimed successfully.');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,$id2)
    {  
       $rewarddata=Reward::find($id); 
       return view('components.load.reward',compact('rewarddata','id2'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
