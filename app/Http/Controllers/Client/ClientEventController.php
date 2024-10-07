<?php

namespace App\Http\Controllers\Client;

use App\Event;
use App\ModuleSetting;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ClientEventController extends ClientBaseController
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
        $this->events = Event::join('event_attendees', 'event_attendees.event_id', '=', 'events.id')
            ->where('event_attendees.user_id', $this->user->id)
            ->select('events.*')
            ->get();

        if(in_array('projects',$this->user->modules)){
            $projects = Project::select('projects.*')->with('ClientDetails');
            $projects = $projects->where('client_id', '=', $this->user->id);
            $this->projects = $projects->get();
        }else{
            $this->projects = null;
        }

        return view('client.event-calendar.index', $this->data);
    }

    public function show($id){
        $this->event = Event::findOrFail($id);
        //$this->start = Carbon::createFromFormat('Y-m-d H:i:s', request()->start);
        //$this->end = Carbon::createFromFormat('Y-m-d H:i:s', request()->end);

        return view('client.event-calendar.show', $this->data);
    }

    public function showProject($id){
        $this->project = Project::findOrFail($id);
        return view('client.event-calendar.project', $this->data);
    }
}
