@extends('layouts.app')

@push('head-script')


<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/multiselect/css/multi-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorselector/bootstrap-colorselector.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/calendar/dist/fullcalendar.css') }}">



<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">

{{--<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/calendar.css') }}">--}}
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="#" data-toggle="modal" data-target="#my-event" class="btn btn-sm btn-primary">
                            <i class="fa fa-plus"></i> @lang('modules.events.addSchedule')
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card wceo-calendar-div">
                    <div class="card-body">
                        <div class="calendar-wrap">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- .row -->

    <!-- BEGIN MODAL -->
    <div class="modal fade bs-modal-md in" id="my-event" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-plus"></i> @lang('modules.events.addSchedule')</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::open(['id'=>'createEvent','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label class="col-form-label">@lang('modules.events.eventName')</label>
                                            <input type="text" name="event_name" id="event_name" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-2 ">
                                        <div class="form-group">
                                            <label class="col-form-label">@lang('modules.sticky.colors')</label>
                                            <select id="colorselector" name="label_color">
                                                <option value="bg-info" data-color="#5475ed" selected>Blue</option>
                                                <option value="bg-warning" data-color="#f1c411">Yellow</option>
                                                <option value="bg-purple" data-color="#ab8ce4">Purple</option>
                                                <option value="bg-danger" data-color="#ed4040">Red</option>
                                                <option value="bg-success" data-color="#00c292">Green</option>
                                                <option value="bg-inverse" data-color="#4c5667">Grey</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 ">
                                        <div class="form-group">
                                            <label class="col-form-label">@lang('modules.events.where')</label>
                                            <input type="text" name="where" id="where" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="col-form-label">@lang('app.description')</label>
                                            <textarea name="description" id="description" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-md-3 ">
                                        <div class="form-group">
                                            <label class="col-form-label">@lang('modules.events.startOn')</label>
                                            <input type="text" name="start_date" id="start_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-5 col-md-3">
                                        <div class="bootstrap-timepicker timepicker form-group">
                                            <label class="col-form-label">&nbsp;</label>
                                            <input type="text" name="start_time" id="start_time" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-md-3">
                                        <div class="form-group">
                                            <label class="col-form-label">@lang('modules.events.endOn')</label>
                                            <input type="text" name="end_date" id="end_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-5 col-md-3">
                                        <div class="bootstrap-timepicker timepicker form-group">
                                            <label class="col-form-label">&nbsp;</label>
                                            <input type="text" name="end_time" id="end_time"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-md-12"  id="attendees">
                                        <div class="form-group">
                                            <label class="col-xs-3 m-t-10 col-form-label">@lang('modules.events.addAttendees')</label>
                                            <div class="col-xs-7">
                                                <div class="checkbox checkbox-info">
                                                    <input id="all-employees" name="all_employees" value="true" type="checkbox">
                                                    <label for="all-employees">@lang('modules.events.allEmployees')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <select class="select2 m-b-10 select2-multiple col-md-12" multiple="multiple"
                                                    data-placeholder="@lang('modules.messages.chooseMember'), @lang('modules.projects.selectClient')" name="user_id[]">
                                                @foreach($employees as $emp)
                                                    <option value="{{ $emp->id }}">{{ ucwords($emp->name) }} @if($emp->id == $user->id)
                                                            (YOU) @endif</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="form-group">
                                            <div class="col-xs-7">
                                                <div class="checkbox checkbox-info">
                                                    <input id="repeat-event" name="repeat" value="yes"
                                                           type="checkbox">
                                                    <label class="col-form-label" for="repeat-event">@lang('modules.events.repeat')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="repeat-fields" style="display: none">
                                    <div class="col-xs-6 col-md-3 ">
                                        <div class="form-group">
                                            <label class="col-form-label">@lang('modules.events.repeatEvery')</label>
                                            <input type="number" min="1" value="1" name="repeat_count" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <select name="repeat_type" id="" class="form-control">
                                                <option value="day">Day(s)</option>
                                                <option value="week">Week(s)</option>
                                                <option value="month">Month(s)</option>
                                                <option value="year">Year(s)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-md-3">
                                        <div class="form-group">
                                            <label class="col-form-label">@lang('modules.events.cycles') <a class="mytooltip" href="javascript:void(0)"> <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">@lang('modules.events.cyclesToolTip')</span></span></span></a></label>
                                            <input type="text" name="repeat_cycles" id="repeat_cycles" class="form-control">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">@lang('app.close')</button>
                    <button type="button" class="btn btn-primary save-event waves-effect waves-light">@lang('app.submit')</button>
                </div>
            </div>
        </div>
    </div>

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="eventDetailModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary default" data-dismiss="modal">Close</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')

<script>
    var taskEvents = [];
    let events = {!! $events !!};

    events.forEach(event => {
        let taskEvent = {
            id: event.id,
            title: event.event_name,
            start:  event.start_date_time,
            end:  event.end_date_time,
            className: event.label_color,
            repeat: event.repeat,
            repeat_time: event.repeat_every,
            repeat_type: event.repeat_type,
            repeat_cycles: event.repeat_cycles
        };
        taskEvents.push(taskEvent);
    });

    
    var options = {
        dayRender: function( date, cell ) {
            // Get all events
            // var events = $('#calendar').fullCalendar('clientEvents').length ? $('#calendar').fullCalendar('clientEvents') : taskEvents;
            var events = taskEvents;
                // Start of a day timestamp
            var dateTimestamp = date.startOf('day');
            var recurringEvents = new Array();
            
            // find all events with monthly repeating flag, having id, repeating at that day few months ago  
            var dailyEvents = events.filter(function (event) {
            return event.repeat === 'yes' && event.repeat_type === 'day' &&
                event.id &&
                moment(event.start).hour(0).minutes(0).diff(dateTimestamp, 'days', true) % event.repeat_time == 0
                && moment(event.start).startOf('day').isSameOrBefore(dateTimestamp);
            });

            // find all events with monthly repeating flag, having id, repeating at that day few months ago  
            var weeklyEvents = events.filter(function (event) {
            return event.repeat === 'yes' && event.repeat_type === 'week' &&
                event.id &&
                moment(event.start).hour(0).minutes(0).diff(dateTimestamp, 'weeks', true) % event.repeat_time == 0
                && moment(event.start).startOf('day').isSameOrBefore(dateTimestamp);
            });

            // find all events with monthly repeating flag, having id, repeating at that day few months ago  
            var monthlyEvents = events.filter(function (event) {
            return event.repeat === 'yes' && event.repeat_type === 'month' &&
                event.id &&
                moment(event.start).hour(0).minutes(0).diff(dateTimestamp, 'months', true) % event.repeat_time == 0
                && moment(event.start).startOf('day').isSameOrBefore(dateTimestamp);
            });
            
            // find all events with monthly repeating flag, having id, repeating at that day few years ago  
            var yearlyEvents = events.filter(function (event) {
            return event.repeat === 'yes' && event.repeat_type === 'year' &&
                event.id &&
                moment(event.start).hour(0).minutes(0).diff(dateTimestamp, 'years', true) % event.repeat_time == 0
                && moment(event.start).startOf('day').isSameOrBefore(dateTimestamp);
            });
            recurringEvents = [ ...monthlyEvents, ...yearlyEvents, ...weeklyEvents, ...dailyEvents ];

            $.each(recurringEvents, function(key, event) {
                if (event.repeat_cycles !== null) {
                    if(event.repeat_cycles > 0) {
                        event.repeat_cycles--;
                    }else {
                        return false;
                    }
                }
                var timeStart = moment(event.start).utc();
                var timeEnd = moment(event.end).utc();
                var diff = timeEnd.diff(timeStart, 'days', true);

                // Refething event fields for event rendering 
                var eventData = {
                    id: event.id,
                    title: event.title,
                    start: date.hour(timeStart.hour()).minutes(timeStart.minutes()).format("YYYY-MM-DD HH:mm:ss"),
                    end: event.end && diff >= 1 ? date.clone().add(diff, 'days').hour(timeEnd.hour()).minutes(timeEnd.minutes()).format("YYYY-MM-DD HH:mm:ss") : date.hour(timeEnd.hour()).minutes(timeEnd.minutes()).format("YYYY-MM-DD HH:mm:ss"),
                    className: event.className,
                    repeat: event.repeat,
                    repeat_time: event.repeat_time,
                    repeat_type: event.repeat_type,
                    repeat_cycles: event.repeat_cycles
                };
                
                // Removing events to avoid duplication
                $('#calendar').fullCalendar( 'removeEvents', function (event) {
                    return eventData.id === event.id &&
                    moment(event.start).isSame(date, 'day');      
                });
                // Render event
                $('#calendar').fullCalendar('renderEvent', eventData, true);
            });
        }
    }

    var getEventDetail = function (id, duration) {
        var dstart = '';
        var dend = '';
        if(duration.start){
            dstart = duration.start.format('YYYY-MM-DD+HH:mm:ss');
        }
        if(duration.end){
            dend = duration.end.format('YYYY-MM-DD+HH:mm:ss');
        }else{
            dend = dstart;
        }
        var url = `{{ route('admin.events.show', ':id')}}?start=${dstart}&end=${dend}`;

        url = url.replace(':id', id);

        $('#modelHeading').html('Event');
        $.ajaxModal('#eventDetailModal', url);
    }

    var calendarLocale = '{{ $global->locale }}';
    var firstDay = '{{ $global->week_start }}';
</script>
{{--<script src="{{ asset('themes/wceo/assets/js/calendar/moment.min.js') }}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/calendar/fullcalendar.min.js') }}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/calendar/fullcalendar-custom.js') }}"></script>--}}

<script src="{{ asset('plugins/bower_components/calendar/jquery-ui.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/jquery.fullcalendar.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/locale-all.js') }}"></script>
<script src="{{ asset('js/event-calendar.js') }}"></script>

<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>

<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-colorselector/bootstrap-colorselector.min.js') }}"></script>




<script>
    jQuery('#start_date, #end_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    })

    $('#colorselector').colorselector();

    $('#start_time, #end_time').timepicker({
        @if($global->time_format == 'H:i')
        showMeridian: false
        @endif
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    function addEventModal(start, end, allDay){
        if(start){
            var sd = new Date(start);
            var curr_date = sd.getDate();
            if(curr_date < 10){
                curr_date = '0'+curr_date;
            }
            var curr_month = sd.getMonth();
            curr_month = curr_month+1;
            if(curr_month < 10){
                curr_month = '0'+curr_month;
            }
            var curr_year = sd.getFullYear();

            $('#start_date').val('{{ \Carbon\Carbon::now()->format($global->date_format) }}');

            var ed = new Date(start);
            var curr_date = sd.getDate();
            if(curr_date < 10){
                curr_date = '0'+curr_date;
            }
            var curr_month = sd.getMonth();
            curr_month = curr_month+1;
            if(curr_month < 10){
                curr_month = '0'+curr_month;
            }
            var curr_year = ed.getFullYear();
            $('#end_date').val('{{ \Carbon\Carbon::now()->format($global->date_format) }}');

            $('#start_date, #end_date').datepicker('destroy');
            jQuery('#start_date, #end_date').datepicker({
                autoclose: true,
                todayHighlight: true,
                weekStart:'{{ $global->week_start }}',
                format: '{{ $global->date_picker_format }}',
            })
        }

        $('#my-event').modal('show');

    }

    $('.save-event').click(function () {
        $.easyAjax({
            url: '{{route('admin.events.store')}}',
            container: '#createEvent',
            type: "POST",
            data: $('#createEvent').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    })

    $('#repeat-event').change(function () {
        if($(this).is(':checked')){
            $('#repeat-fields').show();
        }
        else{
            $('#repeat-fields').hide();
        }
    })

</script>

@endpush
