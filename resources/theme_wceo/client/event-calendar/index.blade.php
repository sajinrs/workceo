@extends('layouts.client-app')

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
                            <li class="breadcrumb-item"><a href="{{ route('client.dashboard.index') }}">@lang('app.menu.home')</a></li>
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

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="eventDetailModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
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

            var url = `{{ route('client.events.show', ':id')}}`;

            url = url.replace(':id', id);

            //$('#modelHeading').html('Event');
            $.ajaxModal('#eventDetailModal', url);
        }

        var getProjectDetail = function (id, duration) {
            var dstart = '';
            var dend = '';

            var url = `{{ route('client.events.show-project', ':id')}}`;

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

@endpush
