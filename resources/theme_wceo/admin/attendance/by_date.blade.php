@extends('layouts.app')

@push('head-script')

<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap-datetimepicker.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
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
                        <a href="{{ route('admin.attendances.create') }}" class="btn btn-primary btn-sm">@lang('modules.attendance.markAttendance') <i data-feather="plus"></i> </a>

                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(15)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>
                    </div>
                </div>
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>

    <div class="container-fluid product-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs border-tab nav-primary" id="info-tab" role="tablist">
                            <li class="nav-item"><a class="nav-link" id="info-summary-tab" href="{{ route('admin.attendances.summary') }}" aria-selected="true">@lang('app.summary')</a></li>

                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.attendances.index') }}">@lang('modules.attendance.attendanceByMember')</a></li>

                            <li class="nav-item"><a class="nav-link active" href="{{ route('admin.attendances.attendanceByDate') }}">@lang('modules.attendance.attendanceByDate')</a></li>
                        </ul>

                        <div class="tab-content" id="info-tabContent">
                            <div class="tab-pane fade show active" id="info-summary" role="tabpanel" aria-labelledby="info-summary-tab">
                                <div class="form-body">
                                    <div class="row">    
                                        <div class="col-md-4">
                                            <div class="form-group">                              
                                            <label class="control-label">@lang('app.menu.attendance') @lang('app.date')</label>
                                            <input type="text" class="form-control" name="attendance_date" id="attendance_date" value="{{ \Carbon\Carbon::today()->timezone($global->timezone)->format($global->date_format) }}">
                                            </div>
                                        </div> 
                                        
                                        <div class="col-md-8 col-sm-12">
                                            <div class="product-sidebar">
                                                <div class="filter-section">
                                                    <div class="browser-widget">
                                                        <div class="media" style="padding: 10px">
                                                            <div class="media-body align-self-center">
                                                            <div>
                                            <p>@lang('modules.dashboard.totalEmployees')</p>
                                            <h4><span class="counter" id="totalEmployees">0</span></h4>
                                        </div>
                                        <div>
                                            <p>@lang('modules.attendance.present') </p>
                                            <h4><span class="counter" id="employeesPresent">0</span></h4>
                                        </div>
                                        <div>
                                            <p>@lang('app.days') @lang('modules.attendance.absent') </p>
                                            <h4><span class="counter" id="employeesAbsent">0</span></h4>
                                        </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>                      

                                </div>
                            </div>                        
                        </div>
                        
                    </div>
                </div>
            </div>


            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div id="tableBox">
                            <table  id="attendance-table"></table>
                        </div>
                        <div id="holidayBox" style="display: none">
                            <div class="alert alert-primary"> @lang('modules.attendance.holidayfor') <span id="holidayReason"> </span>. </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


    
                



    
    <!-- .row -->

@endsection

@push('footer-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/custom.js')}}"></script>    
    <script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js') }}"></script>


    <script>       

        var currentDate = $('#attendance_date').val();

        if(currentDate){
            showTable();
            getAttendanceCount(currentDate);
        }

        jQuery('#attendance_date').datepicker({
            autoclose: true,
            todayHighlight: true,
            endDate: '+0d',
            language: 'en',
            weekStart:'{{ $global->week_start }}',
            dateFormat: '{{ $global->date_picker_format }}',
            onSelect: function(attendanceDate) {
                getAttendanceCount(attendanceDate);
            }
        });

        var table;

        function showTable(){
            table = $('#attendance-table').dataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{!! route('admin.attendances.byDateData') !!}",
                    data: function (d) {
                        d.date = $('#attendance_date').val();
                    }
                },
                "bStateSave": true,
                language: {
                    "url": "<?php echo __("app.datatable") ?>"
                },

                columns: [
                    { data: 'id', name: 'id', width:50 }],
                "fnDrawCallback": function (oSettings) {
                    $(oSettings.nTHead).hide();
                    $('.a-timepicker, .b-timepicker').datetimepicker({
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
                        });                    

                    $('#attendance-table_wrapper').removeClass( 'form-inline' );
                },
                "destroy" : true
            });
        }

        $('#attendance-table').on('click', '.save-attendance', function () {
            var userId = $(this).data('user-id');
            var clockInTime = $('#clock-in-'+userId).val();
            var clockInIp = $('#clock-in-ip-'+userId).val();
            var clockOutTime = $('#clock-out-'+userId).val();
            var clockOutIp = $('#clock-out-ip-'+userId).val();
            var workingFrom = $('#working-from-'+userId).val();
            var date = $('#attendance_date').val();

            var late = 'no';
            if($('#late-'+userId).is(':checked')){
                late = 'yes';
            }
            var halfDay = 'no';
            if($('#halfday-'+userId).is(':checked')){
                halfDay = 'yes';
            }
            var token = "{{ csrf_token() }}";

            $.easyAjax({
                url: '{{route('admin.attendances.store')}}',
                type: "POST",
                container: '#attendance-container-'+userId,
                data: {
                    user_id: userId,
                    clock_in_time: clockInTime,
                    clock_in_ip: clockInIp,
                    clock_out_time: clockOutTime,
                    clock_out_ip: clockOutIp,
                    late: late,
                    half_day: halfDay,
                    working_from: workingFrom,
                    date: date,
                    _token: token
                },
                success: function (response) {
                    if(response.status == 'success'){
                        showTable();
                    }
                }
            })
        });

        function getAttendanceCount(date){
            $.easyAjax({
                url: '{{route('admin.attendances.dateAttendanceCount')}}',
                type: "POST",
                data: {
                    date: date,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if(response.status == 'success'){
                        $('#totalEmployees').html(response.totalEmployees);

                        if(response.holiday == 1){
                            $('#presentBox').hide();
                            $('#absentBox').hide();
                            $('#holidayBox').show();
                            $('#tableBox').hide();
                            $('#holidayReason').html(response.holidayReason);
                        }
                        else{
                            showTable();
                            $('#employeesPresent').html(response.totalPresent);
                            $('#employeesAbsent').html(response.totalAbsent);
                            $('#presentBox').show();
                            $('#absentBox').show();
                            $('#holidayBox').hide();
                            $('#tableBox').show();

                        }
                    }
                }
            })

        }

    </script>
@endpush