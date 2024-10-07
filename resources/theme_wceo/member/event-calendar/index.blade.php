@extends('layouts.member-app')

@push('head-script')
<link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
<link href="{{ asset('themes/wceo/assets/plugins/calendar/main.min.css')}}" rel='stylesheet' />
<link href="{{ asset('themes/wceo/assets/plugins/calendar/bootstrap/main.css')}}" rel='stylesheet' />
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorselector/bootstrap-colorselector.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/date-picker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap-datetimepicker.css') }}">
<style>
    .btn-colorselector {
        width: 48px;
        height: 48px;
    }
    .colorselector_label{
        line-height: 48px;
        padding-right: 10px;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        @if($user->can('add_events'))
                            <a href="#" data-toggle="modal" data-target="#my-event" class="btn btn-primary">
                                <i class="fa fa-plus"></i> @lang('modules.events.addEvent')
                            </a>
                        @endif
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
                    <h5 class="modal-title"><i class="fa fa-plus"></i> @lang('modules.events.addEvent')</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['id'=>'createEvent','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-8 ">
                                <div class="form-group form-label-group">
                                    <input placeholder="-" type="text" name="event_name" id="event_name" class="form-control form-control-lg">
                                    <label for="event_name" class="required">@lang('modules.events.eventName')</label>
                                </div>
                            </div>

                            <div class="col-md-2 ">
                                <span class="pull-left colorselector_label">@lang('modules.sticky.colors')</span>
                                <div class="form-group pull-left">
                                    <select id="colorselector" name="label_color">
                                        {{--<option value="bg-info" data-color="#5475ed" selected>Blue</option>--}}
                                        <option value="yellow" data-color="#f1c411">Yellow</option>
                                        {{--bg-warning--}}
                                        <option value="red" data-color="#ed4040">Red</option>
                                        {{--bg-danger--}}
                                        <option value="green" data-color="#00c292">Green</option>
                                        {{--bg-success--}}
                                        {{--<option value="bg-inverse" data-color="#4c5667">Grey</option>--}}
                                    </select>
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group form-label-group">
                                    <textarea placeholder="-" name="description" id="description" class="form-control form-control-lg"></textarea>
                                    <label for="description" class="required">@lang('modules.events.eventDescription')</label>
                                </div>
                            </div>
                            <div class="col-md-4 ">
                                <div class="form-group form-label-group">
                                    <textarea placeholder="-" name="where" id="where" class="form-control form-control-lg"></textarea>
                                    <label for="where" class="required">@lang('modules.events.address')</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-md-3 ">
                                <div class="form-group form-label-group">
                                    <input placeholder="-" type="text" name="start_date" id="start_date" class="form-control form-control-lg">
                                    <label for="start_date" class="required">@lang('modules.events.startDate')</label>
                                </div>
                            </div>
                            <div class="col-xs-5 col-md-3">
                                <div class="bootstrap-timepicker timepicker form-group form-label-group">
                                    <input placeholder="-" type="text" name="start_time" id="start_time" class="form-control form-control-lg">
                                    <label for="start_time">@lang('modules.events.startTime')</label>
                                </div>
                            </div>

                            <div class="col-xs-6 col-md-3">
                                <div class="form-group form-label-group">
                                    <input placeholder="-" type="text" name="end_date" id="end_date" class="form-control form-control-lg">
                                    <label for="end_date" class="required">@lang('modules.events.endDate')</label>
                                </div>
                            </div>
                            <div class="col-xs-5 col-md-3">
                                <div class="bootstrap-timepicker timepicker form-group form-label-group">
                                    <input placeholder="-" type="text" name="end_time" id="end_time" class="form-control form-control-lg">
                                    <label for="end_time">@lang('modules.events.endTime')</label>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="attendees">
                            <div class="col-md-3" >
                                <div class="form-group">
                                    {{--<label class="col-xs-3 m-t-10 col-form-label">@lang('modules.events.addAttendees')</label>--}}
                                    <div class="col-md-12">
                                        <div class="checkbox checkbox-info">
                                            <input style="position: absolute" id="all-employees" name="all_employees" value="true" type="checkbox">
                                            <label for="all-employees">@lang('modules.events.allTeamMembers')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9" >
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
                            <div class="col-md-3">
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
                            <div class="col-md-3 repeat-fields">
                                <div class="form-group form-label-group">
                                    <input placeholder="-" type="number" min="1" value="1" id="repeat_count" name="repeat_count" class="form-control form-control-lg">
                                    <label for="repeat_count">@lang('modules.events.repeatFrequency')</label>
                                </div>
                            </div>
                            <div class="col-md-3 repeat-fields">
                                <div class="form-group form-label-group">
                                    <select placeholder="-" name="repeat_type" id="" class="form-control form-control-lg">
                                        <option value="day">Day(s)</option>
                                        <option value="week">Week(s)</option>
                                        <option value="month">Month(s)</option>
                                        <option value="year">Year(s)</option>
                                    </select>
                                    <label for="repeat_type">@lang('modules.events.repeatInterval')</label>
                                </div>
                            </div>

                            <div class="col-md-3 repeat-fields">
                                <div class="form-group form-label-group">

                                    <input placeholder="-" type="text" name="repeat_cycles" id="repeat_cycles" class="form-control form-control-lg">
                                    <label for="repeat_cycles">
                                        @lang('modules.events.repeatCycle')
                                        <a class="example-popover text-primary" type="button" data-container="body"  data-trigger="hover" data-toggle="popover" data-placement="top" data-html="true" data-content="@lang('modules.events.cyclesToolTip')"><i class="fa fa-info-circle"></i></a>

                                    </label></div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
                <div class="modal-footer">
                    <div class="row width-100">
                        <div class="col-xs-6">
                            <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">@lang('app.close')</button>
                        </div>
                        <div class="col-xs-6">
                            <button type="button" class="btn btn-primary save-event form-control">@lang('modules.events.saveEvent')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="eventDetailModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
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
    <div class="modal fade bs-modal-md in" id="projectDetailModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
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
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<!-- rrule lib -->
<script src='https://cdn.jsdelivr.net/npm/rrule@2.6.4/dist/es5/rrule.min.js'></script>
<!-- fullcalendar bundle -->
<script src="{{ asset('themes/wceo/assets/plugins/calendar/main.min.js')}}"></script>
<!-- the rrule-to-fullcalendar connector. must go AFTER the rrule lib -->
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/rrule@5.2.0/main.global.min.js'></script>

<script>
    var taskEvents = [];
    var projectEvents = [];
    var taskResources = [];
    let events = {!! $events !!};
    @if($projects)
        let projects = {!! $projects !!};
    @else
        let projects = [];
    @endif

    let event_colours = {'red':'#ed4040','yellow':'#f1c411','green':'#00c292'}

    var calendarLocale = '{{ $global->locale }}';

    var firstDay = '{{ $global->week_start }}';

    var getEventDetail = function (id, duration) {
        var dstart = '';
        var dend = '';

        var url = `{{ route('member.events.show', ':id')}}`;

        url = url.replace(':id', id);

        //$('#modelHeading').html('Event');
        $.ajaxModal('#eventDetailModal', url);
    }

    var getProjectDetail = function (id, duration) {
        var dstart = '';
        var dend = '';

        var url = `{{ route('member.events.show-project', ':id')}}`;

        url = url.replace(':id', id);

        $('#modelHeading').html('Event');
        $.ajaxModal('#projectDetailModal', url);
    }

</script>

<script src="{{ asset('js/event-calendar.js') }}"></script>



{{--<script src="{{ asset('plugins/bower_components/calendar/jquery-ui.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/jquery.fullcalendar.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/locale-all.js') }}"></script>
<script src="{{ asset('js/event-calendar.js') }}"></script>--}}

{{--<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>--}}
{{--<script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>--}}

<script src="{{ asset('js/cbpFWTabs.js') }}"></script>

<script src="{{ asset('plugins/bootstrap-colorselector/bootstrap-colorselector.min.js') }}"></script>


<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>


<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>

<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js')}}"></script>

<script src="{{ asset('themes/wceo/assets/js/popover-custom.js')}}"></script>
{{--<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>--}}
{{--<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>--}}
{{--<script src="{{ asset('plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>--}}

<script>
    jQuery('#start_date, #end_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language:'en'
    })

    $('#colorselector').colorselector();

    /*$('#start_time, #end_time').timepicker({
        {{--@if($global->time_format == 'H:i')--}}
        showMeridian: false
        {{--@endif--}}
    });*/

    $('#start_time, #end_time').datetimepicker({
        format: 'LT',
        icons: {
            time: 'fa fa-clock',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-check',
            clear: 'fa fa-trash',
            close: 'fa fa-times'
        },
        //onChangeDateTime:calculateTime
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
                dateFormat: '{{ $global->date_picker_format }}',
                language:'en'
            })
        }

        $('#my-event').modal('show');

    }

    $('.save-event').click(function () {
        $.easyAjax({
            url: '{{route('member.events.store')}}',
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
    $('.repeat-fields').hide();
    $('#repeat-event').change(function () {
        if($(this).is(':checked')){
            $('.repeat-fields').show();
        }
        else{
            $('.repeat-fields').hide();
        }
    })

</script>
@endpush
