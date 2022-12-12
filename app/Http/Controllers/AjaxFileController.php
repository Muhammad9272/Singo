<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\Tempfile;
use App\Models\User;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AjaxFileController extends Controller
{

    public function __construct() {
        return $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {


    }



    public function upload_file($name = null, Request $request){

        if($name){
            $file = $request->$name;
            $file_name = $file->getClientOriginalName();
            $folder = uniqid().now()->timestamp;
            $file->storeAs('file/tmp/'.$folder, $file_name);

            $temp = new Tempfile;
            $temp->file = $file_name;
            $temp->folder = $folder;
            $temp->created_by = auth()->user()->id;
            $temp->save();

            return $temp->id;
        }

        return '';

    }


    public function temp_clear(){
        $temp = Tempfile::latest()->where('created_at', '<', Carbon::now()->subMinutes(30)->toDateTimeString())->get();
        foreach($temp as $tmp){
            if(Storage::exists('app/file/tmp/'.$tmp->folder)){
                rmdir(storage_path('app/file/tmp/'.$tmp->folder));
            }
        }
        return Storage::url('app/file/tmp/'.$tmp->folder);
    }




}
