<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tempfile;
use App\Models\Ticket;
use App\Models\TicketMessage;

use App\Notifications\Notifications;
use App\Mail\TicketMail;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class TicketController extends Controller
{
    //

    public function __construct() {
        return $this->middleware('auth');
    }

    public function support(){
        $id = auth()->user()->id;
        $user = User::findOrFail($id);

        if($user->support_pin == null){
            $pin = random_int(100000, 999999);
            $user->support_pin = $pin;
            $user->save();
        }

        $tickets = Ticket::where('user_id', $id)->latest()->get();

        return view('ticket.user.index', ['user' => $user, 'tickets' => $tickets]);
    }
    public function support_new($id){
        $user = User::findOrFail($id);
        $pin = random_int(100000, 999999);
        $user->support_pin = $pin;
        $user->save();

        Session::flash('success', "New pin generated successfully");
        return redirect()->back();
    }

    public function ticket_create(){
        $id = auth()->user()->id;
        $user = User::findOrFail($id);

        return view('ticket.user.create', ['user' => $user]);
    }

    public function ticket_store(Request $request){

        $request->validate([
            'ticketType' => 'required',
            'priority' => 'required',
            'subject' => 'required|string|max:250',
            'message' => 'required',
            'file' => 'nullable',
        ]);

        $ticket = new Ticket;
        $ticket->user_id = auth()->user()->id;
        $ticket->type = $request->ticketType;
        $ticket->priority = $request->priority;
        $ticket->subject = $request->subject;
        $ticket->created_by = auth()->user()->id;
        $ticket->status = 3;
        $ticket->save();

        $message = new TicketMessage;
        $message->type = 1;
        $message->ticket_id = $ticket->id;
        $message->user_id = auth()->user()->id;
        $message->message = $request->message;

        if($request->file){

            $temp_file = Tempfile::findOrFail($request->file);
            $path = 'file/tmp/'.$temp_file->folder.'/'.$temp_file->file;
            if(Storage::exists($path) ){
                $from_path = 'file/tmp/'.$temp_file->folder.'/'.$temp_file->file;
                $to_path = 'public/message_file/'.$ticket->id.'/'.$temp_file->file;
                Storage::move($from_path, $to_path);
                rmdir(storage_path('app/file/tmp/'.$temp_file->folder));
                $message->files = $to_path;

            }else{
                return redirect()->back()->withErrors(['file' => 'no file detected']);
            }
        }


        $message->created_by = auth()->user()->id;
        $message->status = 3;
        $message->save();

        //send notification
        $route = "ticket.show";
        $type = "support ticket";
        $route_id = $ticket->id;
        $name = "";

        //notification for users
        $message = "We have received your support ticket. Ticket: #".$ticket->id."  ".$ticket->subject;
        $user = auth()->user();
        $user->notify(new Notifications($name, $route, $type, $message, $route_id));

        //notification for superadmin & admin & moderator

        $route = "ticket.open";
        $message = auth()->user()->name." created a ticket. Ticket: #".$ticket->id."  ".$ticket->subject;
        foreach (User::where('type', 3 )->get() as $admin) {
            $admin->notify(new Notifications($name, $route, $type, $message, $route_id));
        }
        foreach (User::where('type', 2 )->get() as $admin) {
            $admin->notify(new Notifications($name, $route, $type, $message, $route_id));
        }
        foreach (User::where('type', 1 )->get() as $admin) {
            $admin->notify(new Notifications($name, $route, $type, $message, $route_id));
        }

        //mail to user
        $mail = auth()->user()->email;
        $title = "Ticket: #".$ticket->id.$ticket->subject;
        $ticket_details = Ticket::findOrFail($ticket->id);
        $reply = null ;
        Mail::to($mail)->send(new TicketMail ($ticket_details, $reply , $title));

        Session::flash('success', "Ticket ID:#$ticket->id created successfully");
        return redirect()->route('support');
    }

    public function ticket_show($id){

        $ticket = Ticket::with('messages')->where('id', $id)->get();
        $user = auth()->user();
        foreach($ticket as $tk){
            if(($tk->user_id != $user->id) && $user->type == 0){
                Session::flash('error', "You are not allowed for this action.");
                return redirect()->route('support');
            }
        }

        // return $ticket;
        return view('ticket.user.show', ['user' => $user, 'ticket' => $ticket]);
    }


    public function ticket_message (Request $request){
        // return $request;
        $request->validate([
            'message' => 'required',
            'file' => 'nullable',
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);
        $ticket->status = 3;
        $ticket->save();

        $message = new TicketMessage;
        $message->type = 1;
        $message->ticket_id = $request->ticket_id;
        $message->user_id = auth()->user()->id;
        $message->message = $request->message;

        if($request->file){

            $temp_file = Tempfile::findOrFail($request->file);
            $path = 'file/tmp/'.$temp_file->folder.'/'.$temp_file->file;
            if(Storage::exists($path) ){
                $from_path = 'file/tmp/'.$temp_file->folder.'/'.$temp_file->file;
                $to_path = 'public/message_file/'.$ticket->id.'/'.$temp_file->file;
                Storage::move($from_path, $to_path);
                rmdir(storage_path('app/file/tmp/'.$temp_file->folder));
                $message->files = $to_path;

            }else{
                return redirect()->back()->withErrors(['file' => 'no file detected']);
            }
        }

        $message->created_by = auth()->user()->id;
        $message->status = 1;
        $message->save();

        Session::flash('success', "Reply send successfully");
        return redirect()->route('support');
    }

    public function ticket_close ($id){

        $ticket = ticket::findOrFail($id);
        if($ticket){
            $ticket->status = 0;
            $ticket->save();

            Session::flash('success', "Ticket ID:#$ticket->id closed successfully");
            return redirect()->route('support');

        }else{
            Session::flash('error', "Something went wrong");
            return redirect()->route('support');
        }
    }

    public function ticket(){
        // $tickets = Ticket::where('updated_at', '<', Carbon::now()->subMinutes(5)->toDateTimeString())->where('status',2)->get();
        // return $tickets;
        $tickets = Ticket::latest()->get();

        $openCount = Ticket::where('status', 3)->get()->count();
        $answeredCount = Ticket::where('status', 2)->get()->count();
        $csReplyCount = Ticket::where('status', 1)->get()->count();
        $closeCount = Ticket::where('status', 0)->get()->count();

        return view('ticket.admin.index', ['tickets' => $tickets, 'open' => $openCount, 'answere' => $answeredCount, 'csReply' => $csReplyCount, 'close' => $closeCount,]);
    }

    public function ticket_open($id)
    {
        $ticket = Ticket::with('messages')->where('id', $id)->get();
        $user = auth()->user();

        $check = Ticket::findOrFail($id);
        if ($check) {

            if ($check->open == null) {
                $check->open = $user->id;
                $check->save();
            }
            return view('ticket.admin.open', ['user' => $user, 'ticket' => $ticket]);
        }
    }

    public function ticket_open_store(Request $request){
        $request->validate([
            'message' => 'required',
            'status' => 'required',
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);
        $ticket->status = $request->status;
        $ticket->updated_at = Carbon::now();
        $ticket->updated_by = auth()->user()->id;
        $ticket->save();

        $message = new TicketMessage;
        $message->type = 2;
        $message->ticket_id = $request->ticket_id;
        $message->user_id = auth()->user()->id;
        $message->message = $request->message;
        $message->created_by = auth()->user()->id;
        $message->save();

        //mail to user
        $mail = User::findOrFail($ticket->user_id)->email;
        $title = "Ticket: #".$ticket->id.$ticket->subject;
        $ticket_details = Ticket::findOrFail($ticket->id);
        $reply = "Your ticket [#".$ticket->id.$ticket->subject."] got a reply. Please reply this ticket without any hesitation. Our support all the time with you.";
        Mail::to($mail)->send(new TicketMail ($ticket_details, $reply , $title));

        Session::flash('success', "Reply send successfully");
        return redirect()->route('ticket');
    }

    public function ticket_close_two ($id){

        $ticket = ticket::findOrFail($id);
        if($ticket){
            $ticket->status = 0;
            $ticket->save();

            Session::flash('success', "Ticket ID:#$ticket->id closed successfully");
            return redirect()->route('ticket');

        }else{
            Session::flash('error', "Something went wrong");
            return redirect()->route('ticket');
        }
    }

    public function cron_tickteCheck(){

        $tickets = Ticket::where('updated_at', '<', Carbon::now()->subMinutes(5)->toDateTimeString())->where('status',2)->get();
        if(!$tickets->isEmpty()){
            foreach($tickets as $tk){
                $tk->status = 0;
                $tk->save();

                //send notification
                $route = "ticket";
                $type = "test";
                $route_id = "";
                $name = "";
                $message = "Ticked: #".$tk->id." ".$tk->subject." market as closed by Singo";
                foreach (User::where('type', 3 )->get() as $admin) {
                    $admin->notify(new Notifications($name, $route, $type, $message, $route_id));
                }
            }
        }
    }


}
