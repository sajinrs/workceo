@extends('layouts.member-app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/daterange-picker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/date-picker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap-datetimepicker.min.css') }}">
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
                    <a href="javascript:;" id="show-add-form" class="btn btn-primary btn-sm"><i class="fa fa-clock"></i> @lang('modules.timeLogs.logTime')</a>

                    </div>
                </div>
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->

            
            <div class="col-sm-12">
            <div class="d-none" id="hideShowTimeLogForm">
                <div class="card">
                {!! Form::open(['id'=>'logTime','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="card-body">                        
                        <div class="form-body">
                            <div class="row m-t-30">
                                <div class="col-md-3 ">
                                    <div class="form-group">

                                        <label class="required">@if($logTimeFor->log_time_for == 'task')
                                                @lang('app.selectTask')
                                            @else
                                                @lang('app.selectProject')
                                            @endif
                                        </label>
                                        @if($logTimeFor->log_time_for == 'task')
                                            <select class="select2 form-control" name="task_id" data-placeholder="@lang('app.selectTask')" id="task_id2">
                                                <option value=""></option>
                                                @foreach($timeLogTasks as $task)
                                                    <option value="{{ $task->id }}">{{ ucwords($task->heading) }}</option>
                                                @endforeach

                                            </select>
                                        @else
                                            <select class="select2 form-control" name="project_id" data-placeholder="@lang('app.selectProject')" id="project_id2">
                                                <option value=""></option>
                                                @foreach($timeLogProjects as $project)
                                                    <option value="{{ $project->id }}">{{ ucwords($project->project_name) }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3 " id="employeeBox">
                                    <div class="form-group">
                                        <label>@lang('modules.timeLogs.employeeName')</label>
                                        <select class="form-control" name="user_id"
                                                id="user_id" data-style="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label>@lang('modules.timeLogs.startDate')</label>
                                        <input id="start_date" name="start_date" type="text"
                                               class="form-control"
                                               value="{{ \Carbon\Carbon::today()->format('d M Y') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label>@lang('modules.timeLogs.endDate')</label>
                                        <input id="end_date2" name="end_date" type="text"
                                               class="form-control"
                                               value="{{ \Carbon\Carbon::today()->format('d M Y') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group timepicker">
                                        <label class="required">@lang('modules.timeLogs.startTime')</label>
                                        <input type="text" name="start_time" id="start_time" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group timepicker">
                                        <label class="required">@lang('modules.timeLogs.endTime')</label>
                                        <input type="text" name="end_time" id="end_time" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="">@lang('modules.timeLogs.totalHours')</label>

                                    <p id="total_time" class="form-control-static">0 Hrs</p>
                                </div>
                            </div>

                            <div class="row m-t-20">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="required" for="memo">@lang('modules.timeLogs.memo')</label>
                                        <input type="text" name="memo" id="memo" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions col-md-3  offset-md-9 ">
                        <button type="button" id="save-form" class="btn btn-primary">@lang('app.save')</button>
                        </div>
                    </div>
                    {!! Form::close() !!}


                    </div>
                    </div>
                </div>

            <div class="col-sm-12">
                <div class="product-grid">
                    <div class="feature-products">
                        <div class="row">
                            <div class="col-sm-3 p-absolute">
                                <div class="product-sidebar">
                                    <div class="filter-section">
                                        <div class="card">
                                            
                                            <div class="left-filter wceo-left-filter">
                                                <div class="card-body filter-cards-view animate-chk">
                                                    <div class="product-filter">
                                                        {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}
                                                            <div class="row"  id="ticket-filters">
                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.selectDateRange')</label>
                                                                        <div class="input-daterange input-group" id="date-range">
                                                                            <input type="text" class="form-control" autocomplete="off" id="start-date" placeholder="@lang('app.startDate')" />
                                                                            
                                                                            <div class="input-group-prepend input-group-append"><span class="input-group-text">@lang('app.to')</span></div>
                                                                            <input type="text" class="form-control" id="end-date"  autocomplete="off" placeholder="@lang('app.endDate')" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">
                                                                            @if($logTimeFor->log_time_for == 'task')
                                                                                @lang('app.selectTask')
                                                                            @else
                                                                                @lang('app.selectProject')
                                                                            @endif
                                                                        </label>
                                                                        @if($logTimeFor->log_time_for == 'task')
                                                                            <select class="select2 form-control" id="task_id">
                                                                                <option value="all">@lang('modules.client.all')</option>
                                                                                @foreach($tasks as $task)
                                                                                    <option value="{{ $task->id }}">{{ ucwords($task->heading) }}</option>
                                                                                @endforeach

                                                                            </select>
                                                                        @else
                                                                            <select class="select2 form-control" id="project_id">
                                                                                <option value="all">@lang('modules.client.all')</option>
                                                                                @foreach($projects as $project)
                                                                                    <option value="{{ $project->id }}">{{ ucwords($project->project_name) }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('modules.employees.title')</label>
                                                                        <select class="form-control select2" name="employee" id="employee" data-style="form-control">
                                                                            <option value="all">@lang('modules.client.all')</option>
                                                                            @forelse($employees as $employee)
                                                                                <option value="{{$employee->id}}">{{ ucfirst($employee->name) }}</option>
                                                                            @empty
                                                                            @endforelse
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="product-filter wceo-filter col-sm-6  pr-0">
                                                                    <button type="button" id="filter-results" class="btn btn-primary btn-block"> @lang('app.apply')</button>
                                                                </div>
                                                                <div class="product-filter wceo-filter col-sm-6">
                                                                    <button type="button" id="reset-filters" class="btn btn-outline-secondary btn-block pull-right"> @lang('app.reset')</button>
                                                                </div>
                                                                
                                                            </div>
                                                            {!! Form::close() !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="product-wrapper-grid">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header d-none">
                                        <span class="d-none-productlist filter-toggle">
                                            <button class="btn btn-primary toggle-data">Filters<span class="ml-2"><i class="" data-feather="chevron-down"></i></span></button>
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="dt-ext table-responsive">
                                        {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
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
    <!-- .row -->

@endsection

@push('footer-script')


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
    <script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/daterangepicker.js')}}"></script>

    <script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js') }}"></script>


    {!! $dataTable->scripts() !!}

    <script>
   
    $('#reset-filters').click(function () {
        $('#storePayments')[0].reset();
            $('.select2').val('all');
            $('#storePayments').find('select').select2();
            $('#start-date, #end-date').val('');

            window.LaravelDataTables["timelog-table"].draw();
    })
    
    

    
    jQuery('#start-date, #end-date').daterangepicker({
            locale: {
                format: 'MM-DD-YYYY'
            },
            "alwaysShowCalendars": true,
            autoApply: true,
            autoUpdateInput: false,
            language: '{{ $global->locale }}',
            weekStart:'{{ $global->week_start }}',
        }, function(start, end, label) {
            var selectedStartDate = start.format('MM-DD-YYYY'); // selected start
            var selectedEndDate = end.format('MM-DD-YYYY'); // selected end

            $startDateInput = $('#start-date');
            $endDateInput = $('#end-date');

            // Updating Fields with selected dates
            $startDateInput.val(selectedStartDate);
            $endDateInput.val(selectedEndDate);
            var endDatePicker = $endDateInput.data('daterangepicker');
            endDatePicker.setStartDate(selectedStartDate);
            endDatePicker.setEndDate(selectedEndDate);
            var startDatePicker = $startDateInput.data('daterangepicker');
            startDatePicker.setStartDate(selectedStartDate);
            startDatePicker.setEndDate(selectedEndDate);

        });

    var table;


    $('#timelog-table').on('preXhr.dt', function (e, settings, data) {
        var employee = $('#employee').val();
        var startDate = $('#start-date').val();

        if(startDate == ''){
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if(endDate == ''){
            endDate = null;
        }
        var projectID;

        @if($logTimeFor->log_time_for == 'task')
            projectID = $('#task_id').val();
        @else
            projectID = $('#project_id').val();
        @endif

        if(projectID == ''){
            projectID = 0;
        }
        

        data['startDate'] = startDate;
        data['endDate'] = endDate;
        data['projectID'] = projectID;
        data['employeeId'] = employee;
    });

    
    

    $('#filter-results').click(function () {
        $('#timelog-table').on('preXhr.dt', function (e, settings, data) {
            var employee = $('#employee').val();
            var startDate = $('#start-date').val();

            if(startDate == ''){
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if(endDate == ''){
                endDate = null;
            }
            var projectID;

            @if($logTimeFor->log_time_for == 'task')
                projectID = $('#task_id').val();
            @else
                projectID = $('#project_id').val();
            @endif

            if(projectID == ''){
                projectID = 0;
            }
            

            data['startDate'] = startDate;
            data['endDate'] = endDate;
            data['projectID'] = projectID;
            data['employeeId'] = employee;
        });
        window.LaravelDataTables["timelog-table"].draw();
    });

    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('time-id');
        swal({

            title: "Are you sure?",
            text: "You will not be able to recover the deleted time log!",
            icon: "warning",
            buttons: ["No, cancel please!", "Yes, delete it!"],
            dangerMode: true
    })
    .then((willDelete) => {
            if (willDelete) {

                var url = "{{ route('member.all-time-logs.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            window.LaravelDataTables["timelog-table"].draw();
                        }
                    }
                });
            }
        });
    });       
    

    showTable();

    jQuery('#start_date, #end_date2').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        //dateFormat: '{{ $global->date_picker_format }}',
        dateFormat: 'dd M yyyy',
        language: 'en',
        onSelect: function() {
            calculateTime();
        }
    });

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
            close: 'fa fa-times',
          },
        }).on("dp.change", function (e) {
            calculateTime();
    });                  
    

    function calculateTime() {
        var format = '{{ $global->date_picker_format }}';
        var startDate = $('#start_date').val();
        var endDate = $('#end_date2').val();
        var startTime = $("#start_time").val();
        var endTime = $("#end_time").val();

        
        //startDate = moment(startDate, format.toUpperCase()).format('YYYY-MM-DD');
        //endDate = moment(endDate, format.toUpperCase()).format('YYYY-MM-DD');

        var timeStart = new Date(startDate + " " + startTime);
        var timeEnd = new Date(endDate + " " + endTime);


        var diff = (timeEnd - timeStart) / 60000; //dividing by seconds and milliseconds

        var minutes = diff % 60;
        var hours = (diff - minutes) / 60;

        if (hours < 0 || minutes < 0) {
            var numberOfDaysToAdd = 1;
            timeEnd.setDate(timeEnd.getDate() + numberOfDaysToAdd);
            var dd = timeEnd.getDate();

            if (dd < 10) {
                dd = "0" + dd;
            }

            var mm = timeEnd.getMonth() + 1;

            if (mm < 10) {
                mm = "0" + mm;
            }

            var y = timeEnd.getFullYear();

//            $('#end_date').val(mm + '/' + dd + '/' + y);
            calculateTime();
        } else {
            $('#total_time').html(hours + "Hrs " + minutes + "Mins");
        }

//        console.log(hours+" "+minutes);
    }

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('member.all-time-logs.store')}}',
            container: '#logTime',
            type: "POST",
            data: $('#logTime').serialize(),
            success: function (data) {
                if (data.status == 'success') {
                    $('#logTime')[0].reset();
                    $('#timelog-table').DataTable().ajax.reload();
                    $('#hideShowTimeLogForm').toggleClass('d-none', 'show');
                }
            }
        })
    });

    $('#show-add-form').click(function () {
        $('#hideShowTimeLogForm').toggleClass('d-none', 'show');
    });

    $('#project_id2').change(function () {
        var id = $(this).val();
        var url = '{{route('member.all-time-logs.members', ':id')}}';
        url = url.replace(':id', id);
        // $('employeeBox').show();
        $.easyAjax({
            url: url,
            type: "GET",
            redirect: true,
            success: function (data) {
                $('#user_id').html(data.html);
            }
        })
    });

    $('#task_id2').change(function () {
        var id = $(this).val();
        var url = '{{route('member.all-time-logs.task-members', ':id')}}';
        url = url.replace(':id', id);
        // $('employeeBox').show();
        $.easyAjax({
            url: url,
            type: "GET",
            redirect: true,
            success: function (data) {
                $('#user_id').html(data.html);
            }
        })
    });

    function showTable(){
        //window.LaravelDataTables["timelog-table"].draw();
    }
</script>
@endpush