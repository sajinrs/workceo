<?php

namespace App\Http\Controllers\Admin;

use App\Currency;
use App\Event;
use App\EventAttendee;
use App\Helper\Reply;
use App\Http\Requests\Events\StoreEvent;
use App\Http\Requests\Events\UpdateEvent;
use App\Notifications\EventInvite;
use App\Project;
use App\ProjectCategory;
use App\ProductCategory;
use App\User;
use App\Tax;
use App\Country;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AdminEventCalendarController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.Schedules';
        $this->pageIcon = 'fas fa-calendar-check';
        $this->middleware(function ($request, $next) {
            if(!in_array('events',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });

    }

    public function index(){
        $this->employees = User::all();
        $this->clients = User::allClients();
        $this->events = Event::all();
        $this->projects = Project::with('ClientDetails')->get();
        $this->categories = ProjectCategory::all();
        $this->productCategories = ProductCategory::all();
        $this->taxes = Tax::all();
        $this->countries = Country::all(['id', 'name']);
        $this->currencies = Currency::all();

        /* $this->employees = User::doesntHave('member', 'and', function($query){
            $query->where('project_id', "''");
        })
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'employee')
            ->get(); */

        return view('admin.event-calendar.index', $this->data);
    }

    public function store(StoreEvent $request){
        $event = new Event();
        $event->event_name = $request->event_name;
        $event->where = $request->where;
        $event->description = $request->description;
        $event->start_date_time = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d').' '.Carbon::createFromFormat($this->global->time_format, $request->start_time)->format('H:i:s');
        $event->end_date_time = Carbon::createFromFormat($this->global->date_format, $request->end_date)->format('Y-m-d').' '.Carbon::createFromFormat($this->global->time_format, $request->end_time)->format('H:i:s');

        if($request->repeat){
            $event->repeat = $request->repeat;
        }
        else{
            $event->repeat = 'no';
        }

        $event->repeat_every = $request->repeat_count;
        $event->repeat_cycles = $request->repeat_cycles;
        $event->repeat_type = $request->repeat_type;
        $event->label_color = $request->label_color;
        $event->save();

        /* if($request->all_employees){
            $attendees = User::allEmployees();
            foreach($attendees as $attendee){
                EventAttendee::create(['user_id' => $attendee->id, 'event_id' => $event->id]);
            }
        // uncomment this in server
            Notification::send($attendees, new EventInvite($event));
        } */

        if($request->user_id){
            foreach($request->user_id as $userId){
                EventAttendee::firstOrCreate(['user_id' => $userId, 'event_id' => $event->id]);
            }
            $attendees = User::whereIn('id', $request->user_id)->get();
            // uncomment this in server
            Notification::send($attendees, new EventInvite($event));
        }

        return Reply::success(__('messages.eventCreateSuccess'));
    }

    public function edit($id){
        $this->employees = User::doesntHave('attendee', 'and', function($query) use ($id){
            $query->where('event_id', $id);
        })
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->get();
        $this->event = Event::findOrFail($id);
        $view = view('admin.event-calendar.edit', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function update(UpdateEvent $request, $id){
        $event = Event::findOrFail($id);
        $event->event_name = $request->event_name;
        $event->where = $request->where;
        $event->description = $request->description;
        $event->start_date_time = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d').' '.Carbon::createFromFormat($this->global->time_format, $request->start_time)->format('H:i:s');;
        $event->end_date_time = Carbon::createFromFormat($this->global->date_format, $request->end_date)->format('Y-m-d').' '.Carbon::createFromFormat($this->global->time_format, $request->end_time)->format('H:i:s');

        if($request->repeat){
            $event->repeat = $request->repeat;
        }
        else{
            $event->repeat = 'no';
        }

        $event->repeat_every = $request->repeat_count;
        $event->repeat_cycles = $request->repeat_cycles;
        $event->repeat_type = $request->repeat_type;
        $event->label_color = $request->label_color;
        $event->save();

        /* if($request->all_employees){
            $attendees = User::allEmployees();
            foreach($attendees as $attendee){
                $checkExists = EventAttendee::where('user_id', $attendee->id)->where('event_id', $event->id)->first();
                if(!$checkExists){
                    EventAttendee::create(['user_id' => $attendee->id, 'event_id' => $event->id]);

                    //      Send notification to user
                    $notifyUser = User::withoutGlobalScope('active')->findOrFail($attendee->id);
                    $notifyUser->notify(new EventInvite($event));

                }
            }
        } */

        if($request->user_id){
            foreach($request->user_id as $userId){
                $checkExists = EventAttendee::where('user_id', $userId)->where('event_id', $event->id)->first();
                if(!$checkExists){
                    EventAttendee::create(['user_id' => $userId, 'event_id' => $event->id]);

                    //      Send notification to user
                    $notifyUser = User::withoutGlobalScope('active')->findOrFail($userId);
                    $notifyUser->notify(new EventInvite($event));
                }
            }
        }

        return Reply::success(__('messages.eventCreateSuccess'));
    }

    public function show($id){
        $this->event = Event::findOrFail($id);
        //$this->start = Carbon::createFromFormat('Y-m-d H:i:s', request()->start);
        //$this->end = Carbon::createFromFormat('Y-m-d H:i:s', request()->end);
        
        return view('admin.event-calendar.show', $this->data);
    }

    public function showProject($id){
        $this->project = Project::findOrFail($id);
        return view('admin.event-calendar.project', $this->data);
    }

    public function removeAttendee(Request $request){
        EventAttendee::destroy($request->attendeeId);
        return Reply::dataOnly(['status' => 'success']);
    }

    public function destroy($id){
        Event::destroy($id);
        return Reply::success(__('messages.eventDeleteSuccess'));
    }
}
