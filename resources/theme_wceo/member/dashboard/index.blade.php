@extends('layouts.member-app')

@section('content')

    <div class="container-fluid">
        <div class="page-header d-none">
            <div class="row">
                {{--<div class="col">
                    <div class="page-header-left">
                        <h3>WELCOME <b>{{ ($user->name) }}</b></h3>
                    </div>
                </div>--}}
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">

                    
            @if(isset($activeTimerCount) && $user->can('view_timelogs'))
            <span id="timer-section">
                <div class="nav navbar-top-links navbar-right m-t-10 m-r-10">
                    <a class="btn btn-rounded btn-default active-timer-modal" href="javascript:;">@lang("modules.projects.activeTimers")
                        <span class="label label-danger" id="activeCurrentTimerCount">@if($activeTimerCount > 0) {{ $activeTimerCount }} @else 0 @endif</span>
                    </a>
                </div>
            </span>
            @endif

                        
                    </div>
                </div>
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>

    <div class="container-fluid p-t-20">
                                    
        <div class="row">
            <div class="col-xl-5 m-b-20">
                
                <div class="new-users">
                    <div class="media">
                        @if(is_null($user->image))
                            <img class="rounded-circle user-110 image-radius m-r-15" src="{{ asset('img/default-profile-3.png') }}" alt="{{ ucwords($user->name) }}">

                        @else
                            <img class="rounded-circle user-110 image-radius m-r-15" src="{{ asset_url('avatar/'.$user->image) }}" alt="{{ ucwords($user->name) }}">

                        @endif

                    <div class="media-body m-t-0">
                        <h4 class="mb-0 f-w-500">{{ ucwords($user->name) }}</h4>
                        <p class="text-dark m-b-5">{{$user->email }} 
                            {{--<br />
                        {{$user->mobile }}--}} </p>                       

                        
                        <div class="clock-info">
                        <a title="Settings" href="{{ route('member.profile.index') }}" class="btn btn-primary setingBtn"><i class="fa fa-cog"></i></a>
                    @if(is_null($currenntClockIn))
                        <button class="btn btn-outline-primary btn-sm" id="clock-in"><i class="fa fa-clock"></i> @lang('modules.attendance.clock_in')</button>
                    @endif
                    @if(!is_null($currenntClockIn) && is_null($currenntClockIn->clock_out_time))
                        <button class="btn btn-primary btn-sm" id="clock-out"><i class="fa fa-clock"></i> @lang('modules.attendance.clock_out')</button>
                    @endif
                </div>
                    </div>
                    </div>
                        
                        
                </div>
            </div>
            
            <div class="col-xl-7 m-b-20">

                
                
                <span id="timer-section">
                
                @if(!is_null($timer))    
                @if(!empty($project))
                <h4 class="text-primary text-right m-b-0">{{$project->project_name}}</h4>
                @endif
                    <div class="nav navbar-top-links navbar-right pull-right m-t-10 timer-count">
                    
                        <a class="btn btn-rounded btn-light active stop-timer-modal" href="javascript:;" data-timer-id="{{ $timer->id }}">
                            <span id="active-timer">{{ $timer->timer }}</span>
                            <label class="badge badge-danger mb-0 stop_btn">@lang("app.stop")</label>
                        </a>
                    </div>
                @else
                <a class="btn btn-primary btn-sm timer-modal pull-right" href="javascript:;"><i class="fa fa-clock"></i> Start Project Timer </a>
                <div class="nav navbar-top-links navbar-right pull-right m-t-10 timer-count" style="clear: both;">
                        <a class="btn btn-rounded btn-light active stop-timer-modal" href="javascript:;" >                           
                            <span id="active-timer">00:00:00</span>
                           </a>
                    </div>
                @endif
            </span>
            </div>

            <div class="col-xl-12 xl-100">
                <div class="row wceo-dash-wigets emp-widget empWidget">

                    @if(in_array('projects',$modules))
                    <div class="col-sm-6 col-xl-3 col-lg-6">
                        <a href="{{ route('member.projects.index') }}" data-original-title="" title="">
                            <div class="card o-hidden">
                                <div class=" b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="bg-primary dash-wiget-icon align-self-center text-center"><i data-feather="layers"></i></div>
                                        <div class="media-body"><span class="m-0">@lang('modules.dashboard.totalProjects') @lang('app.thisMonth')</span>
                                            <h4 class="mb-0 counteNumer">{{ $totalProjects }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif

                    @if(in_array('timelogs',$modules))
                    <div class="col-sm-6 col-xl-3 col-lg-6">
                        <a href="{{ route('member.all-time-logs.index') }}">
                            <div class="card o-hidden">
                                <div class=" b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="bg-primary dash-wiget-icon align-self-center text-center"><i data-feather="clock"></i></div>
                                        <div class="media-body"><span class="m-0">@lang('modules.dashboard.totalHoursLogged') @lang('app.thisMonth')</span>
                                            <h4 class="mb-0">{{ $counts->totalHoursLogged }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif

                    @if(in_array('tasks',$modules))
                    <div class="col-sm-6 col-xl-3 col-lg-6">
                        <a href="{{ route('member.projects.index') }}">
                            <div class="card o-hidden">
                                <div class=" b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="bg-primary dash-wiget-icon align-self-center text-center"><i data-feather="alert-triangle"></i></div>
                                        <div class="media-body"><span class="m-0">@lang('modules.dashboard.totalPendingTasks')</span>
                                            <h4 class="mb-0 counteNumer">{{ $counts->totalPendingTasks }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-xl-3 col-lg-6">
                        <a href="{{ route('member.projects.index') }}">
                            <div class="card o-hidden">
                                <div class=" b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="bg-primary dash-wiget-icon align-self-center text-center"><i data-feather="check-square"></i></div>
                                        <div class="media-body"><span class="m-0">@lang('modules.dashboard.totalCompletedTasks')</span>
                                            <h4 class="mb-0 counteNumer">{{ $counts->totalCompletedTasks }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif               
                                            
                </div>
            </div>
        </div>
        
        
        <!-- Leaves fullcalendar-->
        
        <!-- Overdue Tasks , Pending FollowUp -->
        <div class="row">
                            
                @if(in_array('attendance',$modules))
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><h5>@lang('app.menu.attendance')</h5></div>
                            <div class="card-body">
                                <div class="row">
                                    <input type="hidden" id="current-latitude">
                                    <input type="hidden" id="current-longitude">

                                    @if (!isset($noClockIn))

                                        @if(!$checkTodayHoliday)
                                            @if($todayTotalClockin < $maxAttandenceInDay)
                                                <div class="col-5">
                                                    <h4>@lang('modules.attendance.clock_in')</h4>
                                                    @if(is_null($currenntClockIn))
                                                        {{ \Carbon\Carbon::now()->timezone($global->timezone)->format($global->time_format) }}
                                                    @else
                                                        {{ $currenntClockIn->clock_in_time->timezone($global->timezone)->format($global->time_format) }}
                                                    @endif
                                                </div>
                                                <div class="col-7">
                                                    <h4>@lang('modules.attendance.clock_in') IP</h4>
                                                    {{ $currenntClockIn->clock_in_ip ?? request()->ip() }}
                                                </div>
                                                

                                                @if(!is_null($currenntClockIn) && !is_null($currenntClockIn->clock_out_time))
                                                    <div class="col-md-6 m-t-20">
                                                        <label for="">@lang('modules.attendance.clock_out')</label>
                                                        <br>{{ $currenntClockIn->clock_out_time->timezone($global->timezone)->format($global->time_format) }}
                                                    </div>
                                                    <div class="col-md-6 m-t-20">
                                                        <label for="">@lang('modules.attendance.clock_out') IP</label>
                                                        <br>{{ $currenntClockIn->clock_out_ip }}
                                                    </div>
                                                @endif

                                                <div class="col-md-8 m-t-20">
                                                    <label for="">@lang('modules.attendance.working_from')</label>
                                                    @if(is_null($currenntClockIn))
                                                        <input type="text" class="form-control" id="working_from" name="working_from">
                                                    @else
                                                        <br> {{ $currenntClockIn->working_from }}
                                                    @endif
                                                </div>

                                                {{--<div class="col-md-4 m-t-25">
                                                    <label class="m-t-30">&nbsp;</label>
                                                   
                                                </div>--}}
                                            @else
                                                <div class="col-md-12">
                                                    <div class="alert alert-info">@lang('modules.attendance.maxColckIn')</div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="col-xs-12">
                                                <div class="alert alert-info alert-dismissable">
                                                    <b>@lang('modules.dashboard.holidayCheck') {{ ucwords($checkTodayHoliday->occassion) }}.</b> </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="col-xs-12 text-center">
                                            <h4><i class="ti-alert text-danger"></i></h4>
                                            <h4>@lang('messages.officeTimeOver')</h4>
                                        </div>
                                    @endif
                                </div>
                            </div>
                    </div>
                </div>
                @endif    

                @if(in_array('tasks',$modules))
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><h5>@lang('modules.dashboard.overdueTasks')</h5></div>
                        <div class="card-body">
                        <ul class="list-task list-group slimscroll" data-role="tasklist">
                            <li class="list-group-item" data-role="task">
                                <strong>@lang('app.title')</strong> <span class="pull-right"><strong>@lang('app.dueDate')</strong></span>
                            </li>
                            @forelse($pendingTasks as $key=>$task)
                                @if((!is_null($task->project_id) && !is_null($task->project) ) || is_null($task->project_id))
                                <li class="list-group-item" data-role="task">
                                    <div class="row">
                                        <div class="col-md-8">
                                            {!! ($key+1).'. <a href="javascript:;" data-task-id="'.$task->id.'" class="show-task-detail">'.ucfirst($task->heading).'</a>' !!}
                                            @if(!is_null($task->project_id) && !is_null($task->project))
                                                <a href="{{ route('member.projects.show', $task->project_id) }}"
                                                    class="text-danger">{{ ucwords($task->project->project_name) }}</a>
                                            @endif
                                        </div>
                                        <label class="badge p-t-10 badge-danger pull-right col-md-4">{{ $task->due_date->format($global->date_format) }}</label>
                                    </div>
                                </li>
                                @endif
                            @empty
                                <li class="list-group-item" data-role="task">
                                    <div  class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:20px"><i
                                                            class="fa fa-tasks"></i>
                                                </div>
                                                <div class="title m-b-15">@lang("messages.noOpenTasks")
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </li>
                            @endforelse
                        </ul>

                        </div>
                    </div>
                </div>
                @endif                                     
                                        
            </div>
        <!-- Project Activity Timeline, User activity timeline  -->
        <div class="row">
            @if(in_array('projects',$modules))
            <div class="col-md-6" id="project-timeline">
                <div class="card">
                    <div class="card-header"><h5>@lang('modules.dashboard.projectActivityTimeline')</h5></div>
                    <div class="card-body slimscroll">

                    <div class="timeline-small projActivity renewtimeLine ">
                        @forelse($projectActivities as $activity)
                        <div class="media">
                            <div class="timeline-round m-r-30 timeline-line-1 bg-primary"></div>
                            <div class="media-body">
                                <h6><a href="{{ route('member.projects.show', $activity->project_id) }}" class="text-danger">{{ ucwords($activity->project_name) }}
                                </a> <span class="pull-right f-12">{{ $activity->created_at->timezone($global->timezone)->diffForHumans() }}</span></h6>
                                <span class="sl-date"> {{ $activity->activity }}</span><br>
                            </div>
                        </div>   
                        @empty
                            <div class="text-center">
                                <div class="empty-space" style="height: 200px;">
                                    <div class="empty-space-inner">
                                        <div class="title m-b-15">@lang("messages.noprojectActivityTimeline") </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>

                        
                    </div>
                </div>
            </div>
            @endif

            @if(in_array('notices',$modules) && $user->can('view_notice'))
            <div class="col-md-6" id="notices-timeline">
                <div class="card">
                    <div class="card-header"><h5>@lang('modules.module.noticeBoard')</h5></div>
                    <div class="card-body slimscroll">

                    <div class="timeline-small projActivity renewtimeLine ">
                        @foreach($notices as $notice)
                        <div class="media">
                            <div class="timeline-round m-r-30 timeline-line-1 bg-primary"></div>
                            <div class="media-body">
                                <h6><a href="javascript:showNoticeModal({{ $notice->id }});" class="text-danger">{{ ucwords($notice->heading) }}
                                </a> <span class="pull-right f-12">{{ $notice->created_at->timezone($global->timezone)->diffForHumans() }}</span></h6>
                                <br>
                            </div>
                        </div>   
                        @endforeach
                    </div>

                        
                    </div>
                </div>
            </div>
            @endif
            
            @if(in_array('employees',$modules))
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h5>@lang('modules.dashboard.userActivityTimeline')</h5></div>
                        <div class="card-body slimscroll">

                            <div class="new-users userActivity">
                                @forelse($userActivities as $key=>$activity)
                                <div class="media">
                                    <img class="rounded-circle image-radius m-r-15" src="{{ $activity->user->image_url}}" width="58" height="58" alt="user">
                                    <div class="media-body">
                                        @if($user->can('view_employees'))
                                        <h6 class="mb-0 f-w-700">
                                            <a href="{{ route('member.employees.show', $activity->user_id) }}" class="text-success">{{ ucwords($activity->user->name) }}</a>
                                        </h6>
                                        @else
                                            <h6 class="mb-0 f-w-700">{{ ucwords($activity->user->name) }}</h6>
                                            
                                        @endif

                                        
                                        <p>{!! ucfirst($activity->activity) !!}</p>
                                    </div>
                                    <span class="pull-right f-12">{{ $activity->created_at->timezone($global->timezone)->diffForHumans() }}</span>
                                </div>
                                @if(count($userActivities) > ($key+1))
                                    <hr>
                                    @endif
                                @empty
                                    <div class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="title m-b-15">@lang("messages.noActivityByThisUser")</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        
    </div>


    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="eventDetailModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="subTaskModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subTaskModelHeading">Sub Task</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->.
    </div>
    {{--Ajax Modal Ends--}}

@endsection


@push('footer-script')
    
<script>

$(window).scroll(startCounter);
function startCounter() 
{
    $(window).off("scroll", startCounter);
    $('.counteNumer').each(function () {
        var $this = $(this);
        jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
                duration: 2000,
                easing: 'swing',
                step: function () {
                $this.text(Math.ceil(this.Counter));
                }
        });
    });
}
</script>


<script>
    $('#clock-in').click(function () {
        var workingFrom = $('#working_from').val();

        var currentLatitude = document.getElementById("current-latitude").value;
        var currentLongitude = document.getElementById("current-longitude").value;

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            url: '{{route('member.attendances.store')}}',
            type: "POST",
            data: {
                working_from: workingFrom,
                currentLatitude: currentLatitude,
                currentLongitude: currentLongitude,
                _token: token
            },
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    })

    @if(!is_null($currenntClockIn))
    $('#clock-out').click(function () {

        var token = "{{ csrf_token() }}";
        var currentLatitude = document.getElementById("current-latitude").value;
        var currentLongitude = document.getElementById("current-longitude").value;

        $.easyAjax({
            url: '{{route('member.attendances.update', $currenntClockIn->id)}}',
            type: "PUT",
            data: {
                currentLatitude: currentLatitude,
                currentLongitude: currentLongitude,
                _token: token
            },
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    })
    @endif

    function showNoticeModal(id) {
        var url = '{{ route('member.notices.show', ':id') }}';
        url = url.replace(':id', id);
        $.ajaxModal('#projectTimerModal', url);
    }

    $('.show-task-detail').click(function () {
            $(".right-sidebar").slideDown(50).addClass("shw-rside");

            var id = $(this).data('task-id');
            var url = "{{ route('member.all-tasks.show',':id') }}";
            url = url.replace(':id', id);

            $.easyAjax({
                type: 'GET',
                url: url,
                success: function (response) {
                    if (response.status == "success") {
                        $('#right-sidebar-content').html(response.view);
                    }
                }
            });
        })

</script>

@if ($attendanceSettings->radius_check == 'yes')
<script>
    var currentLatitude = document.getElementById("current-latitude");
    var currentLongitude = document.getElementById("current-longitude");
    var x = document.getElementById("current-latitude");
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
           // x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position) {
        // x.innerHTML = "Latitude: " + position.coords.latitude +
        // "<br>Longitude: " + position.coords.longitude;

        currentLatitude.value = position.coords.latitude;
        currentLongitude.value = position.coords.longitude;
    }
    getLocation();
    
    
</script>
@endif


@endpush

