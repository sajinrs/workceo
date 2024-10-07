<?php
namespace App\Http\Controllers\Api\Admin;

use App\Event;
use App\EventAttendee;
use App\Helper\Reply;
use App\Http\Requests\Events\StoreEvent;
use App\Http\Requests\Events\UpdateEvent;
use App\Http\Resources\ApiResource;
use App\Http\Resources\EventCalendarCollection;
use App\Http\Resources\UserResource;
use App\Notifications\EventInvite;
use App\Project;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ApiAdminEventCalendarController extends ApiAdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if(!in_array('events',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(){

        $data['employees'] = User::all();
        $data['events'] = Event::all();
        $data['projects'] = Project::with('ClientDetails')->get();
        return EventCalendarCollection::collection($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'where' => 'required',
            'description' => 'required',
        ]);        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

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

        if($request->user_id){
            $users = json_decode($request->user_id);
            foreach($users as $userId){
                EventAttendee::firstOrCreate(['user_id' => $userId, 'event_id' => $event->id]);
            }
            $attendees = User::whereIn('id', $users)->get();
            // uncomment this in server
            Notification::send($attendees, new EventInvite($event));
        }


        $result = Event::with('attendee')->findOrFail($event->id);
        $response = [
            'success' => 1,
            "message" => __('messages.eventCreateSuccess'),
            'data'    => $result
        ];

        return new ApiResource($response);

        //return response()->json([ 'success' => 1, "message" => __('messages.eventCreateSuccess')]);
    }


    public function show($id)
    {
//        $data['employees'] = User::doesntHave('attendee', 'and', function($query) use ($id){
//            $query->where('event_id', $id);
//        })
//            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
//            ->get();
        $data = Event::with('attendee')->findOrFail($id);

        return new ApiResource($data);
    }

    public function update(UpdateEvent $request, $id)
    {
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

        
        if($request->user_id){
            $users = json_decode($request->user_id);
            foreach($users as $userId){
                $checkExists = EventAttendee::where('user_id', $userId)->where('event_id', $event->id)->first();
                if(!$checkExists){
                    EventAttendee::create(['user_id' => $userId, 'event_id' => $event->id]);

                    //      Send notification to user
                    $notifyUser = User::withoutGlobalScope('active')->findOrFail($userId);
                    $notifyUser->notify(new EventInvite($event));
                }
            }
        }

        $result = Event::with('attendee')->findOrFail($event->id);
        $response = [
            'success' => 1,
            "message" => 'Event Updated',
            'data'    => $result
        ];

        return new ApiResource($response);
        //return response()->json([ 'success' => 1, "message" => 'Event Updated']);
    }

    public function destroy($id)
    {
        Event::destroy($id);
        $response = [
            'success' => 1,
            "message" => __('messages.eventDeleteSuccess'),
        ];
        return new ApiResource($response);
        //return response()->json([ 'success' => 1, "message" => __('messages.eventDeleteSuccess')]);
    }


}
