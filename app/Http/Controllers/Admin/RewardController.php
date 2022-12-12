<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 

       $rewards=Reward::all();
       return view('admin.reward.index',compact('rewards'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
  
        return view('admin.reward.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required','max:255'],
            'subtitle' => ['required'],
            'points' => ['required'],
            'rank' => ['required'],
            'file'      => 'image|mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:5000',
            // 'photo' => ['required'],
            
        ]);

        $data=new Reward();
        $input = $request->all();


        if ($request->hasFile('photo')) {            
            $photo = $request->photo;
            $img_name = time().'_.'.$photo->getClientOriginalExtension();
            $photo->move('uploads/images/', $img_name);
            $data->photo = 'uploads/images/'.$img_name;
            // dd($user->profile_picture);
        }

        if ($request->hasFile('badge')) {            
            $photo = $request->badge;
            $img_name = time().'_.'.$photo->getClientOriginalExtension();
            $photo->move('uploads/images/', $img_name);
            $data->badge = 'uploads/images/'.$img_name;
            // dd($user->profile_picture);
        }
        if($request->is_physical==1){
            $input['is_physical']=1;
        }else{
            $input['is_physical']=0;
        }

        $data->fill($input)->save();
        return redirect()->route('admin.rewards.index')->with('success', 'Created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Reward $reward)
    {   
        //dd($reward);
        return view('admin.reward.edit', ['reward' => $reward]);
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

        $validatedData = $request->validate([
            'title' => ['required','max:255'],
            'subtitle' => ['required'],
            'points' => ['required'],
            'rank' => ['required'],
        ]);

        $data=Reward::find($id);
        $input = $request->all();


        if ($request->hasFile('photo')) {   
            if (isset($data->photo)) {
                $previous_photo = $data->photo;
                if (File::exists($previous_photo)) {
                    File::delete($previous_photo);
                }
            }         
            $photo = $request->photo;
            $img_name = time().'_.'.$photo->getClientOriginalExtension();
            $photo->move('uploads/images/', $img_name);
            $data->photo = 'uploads/images/'.$img_name;
            // dd($user->profile_picture);
        }

        if ($request->hasFile('badge')) {   
            if (isset($data->badge)) {
                $previous_badge = $data->badge;
                if (File::exists($previous_badge)) {
                    File::delete($previous_badge);
                }
            }         
            $badge = $request->badge;
            $img_name = time().'_.'.$badge->getClientOriginalExtension();
            $badge->move('uploads/images/', $img_name);
            $data->badge = 'uploads/images/'.$img_name;
            // dd($user->profile_picture);
        }

        if($request->is_physical==1){
            $input['is_physical']=1;
        }else{
            $input['is_physical']=0;
        }

        $data->update($input);
        return redirect()->route('admin.rewards.index')->with('success', 'Updated successfully.');

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
