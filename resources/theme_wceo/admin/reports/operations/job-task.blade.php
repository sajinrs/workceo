@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/daterange-picker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
<style>
    #morris-bar-chart{overflow:hidden}
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
                
                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(19)"><i data-feather="alert-circle"></i> <span>@lang('app.menu.pageTips')</span></a>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid product-wrapper">
        <div class="row">
            @include('sections.report_menu')

            <div class="col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title mb-0"> <h5 class="box-title"><i class="icofont icofont-chart-line"></i> @lang('app.jobTask') </h5></div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="reportTitle">@lang('app.jobTask') @lang('app.report')</h5>
                                <p>View detailed report of job tasks.</p>

                                <div class="product-filter">
                                    <form action="" id="filter-form">
                                        <div class="row"  id="ticket-filters">
                                            <div class="product-filter col-md-12">

                                                <div class="form-group">
                                                    <label class="f-w-600">@lang('app.selectDateRange')</label>
                                                    <div class="input-daterange input-group" id="date-range">
                                                        <input type="text" class="form-control" autocomplete="off" id="start-date" placeholder="@lang('app.startDate')" value="{{ $fromDate->format($global->date_format) }}"/>
                                                        <div class="input-group-prepend input-group-append"><span class="input-group-text">@lang('app.to')</span></div>
                                                        <input type="text" class="form-control" id="end-date"  autocomplete="off" placeholder="@lang('app.endDate')" value="{{ $toDate->format($global->date_format) }}"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="f-w-600">@lang('app.project')</label>
                                                    <select class="select2 form-control" data-placeholder="@lang('app.selectProject')" id="project_id">
                                                    <option value="0">@lang('modules.client.all')</option>
                                                    @foreach($projects as $project)
                                                        <option
                                                                value="{{ $project->id }}">{{ ucwords($project->project_name) }}</option>
                                                    @endforeach
                                                </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="f-w-600">@lang('modules.employees.employeeName')</label>
                                                    <select class="select2 form-control" data-placeholder="@lang('modules.employees.employeeName')" id="employeeId">
                                                        <option value="0">@lang('modules.client.all')</option>
                                                        @foreach($employees as $employee)
                                                            <option
                                                                    value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                                        @endforeach
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
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                            <div class="filter-section">
                                <div class="browser-widget">
                                    <div class="media card-body" style="padding: 10px">
                                        <div class="media-body align-self-center count_by_status count3">
                                            <div id="">
                                                <p>@lang('modules.taskReport.taskToComplete') </p>
                                                <h4><span id="total-counter" class="counter">{{ $totalTasks }}</span></h4>
                                            </div>
                                            <div id="4">
                                                <p>@lang('modules.taskReport.completedTasks') </p>
                                                <h4><span id="completed-counter" class="counter">{{ $completedTasks }}</span></h4>
                                            </div>
                                            <div id="3">
                                                <p>@lang('modules.taskReport.pendingTasks') </p>
                                                <h4><span id="pending-counter" class="counter">{{ $pendingTasks }}</span></h4>
                                            </div>                                                            
                                        </div>
                                    </div>
                                </div>
                                <p>&nbsp;</p>
                            </div>
                                <div>
                                    <canvas id="chart3" height="50"></canvas>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="product-grid">
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

<script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/daterangepicker.js')}}"></script>


<script src="{{ asset('themes/wceo/assets/js/chart/chartjs/chart.min.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/morris-chart/raphael.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/morris-chart/morris.js') }}"></script>

<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>
{!! $dataTable->scripts() !!}
<script>

    

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

    $('#filter-results').click(function () {

        var token = '{{ csrf_token() }}';
        var url = '{{ route('admin.task-report.store') }}';

        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = null;
        }

        var projectID = $('#project_id').val();
        var employeeId = $('#employeeId').val();
        

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {_token: token, startDate: startDate, endDate: endDate, projectId: projectID, employeeId: employeeId},
            success: function (response) {
                // console.log(response);

                $('#completed-counter').html(response.completedTasks);
                $('#total-counter').html(response.totalTasks);
                $('#pending-counter').html(response.pendingTasks);

                pieChart(response.taskStatus);
                //initConter();
                window.LaravelDataTables["tasks-table"].draw();
            }
        });
    });

    $('#reset-filters').click(function () {
            $('#filter-form')[0].reset();
           
            $('.select2').val('all');
            $('#filter-form').find('select').select2();
            $('.count_by_status > div').removeClass('active');
            $('#tasks-table').on('preXhr.dt', function (e, settings, data) {
                data['task_status'] = null;
            });
            showTable();
            pieChart(jQuery.parseJSON('{!! $taskStatus !!}'));
    });

    $('.count_by_status div').on('click', function(event) {
        $('.count_by_status > div').removeClass('active');
        $(this).addClass('active');
        showDataByStatus($(this).attr('id'));
    }); 

    function showDataByStatus(id) 
    {
        $('#tasks-table').on('preXhr.dt', function (e, settings, data) {
            data['projectID'] = 0;
            data['employeeId'] = 0;
            data['task_status'] = id;
        });
        window.LaravelDataTables["tasks-table"].draw();
    }
</script>

<script>

    pieChart(jQuery.parseJSON('{!! $taskStatus !!}'));

    var table;

    $('#tasks-table').on('preXhr.dt', function (e, settings, data) {
        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = null;
        }

        var projectID = $('#project_id').val();
        if (!projectID) {
            projectID = 0;
        }

        var employeeId = $('#employeeId').val();
        if (!employeeId) {
            employeeId = 0;
        }
        

        data['startDate'] = startDate;
        data['endDate'] = endDate;
        data['projectID'] = projectID;
        data['employeeId'] = employeeId;
    });

    function showTable(){
        window.LaravelDataTables["tasks-table"].draw();
    }

    

    $('#tasks-table').on('click', '.show-task-detail', function () {
        $(".right-sidebar").addClass("right-sidebar-width-auto");
        $(".right-sidebar").slideDown(50).addClass("show shw-rside");

        var id = $(this).data('task-id');
        var url = "{{ route('admin.all-tasks.show',':id') }}";
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

    function exportData(){
        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = 0;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = 0;
        }

        var projectID = $('#project_id').val();
        if (!projectID) {
            projectID = 0;
        }

        var employeeId = $('#employeeId').val();
        if (!employeeId) {
            employeeId = 0;
        }
        {{--var url = '{!!  route('admin.task-report.export', [':startDate', ':endDate', ':employeeId', ':projectId']) !!}';--}}

        {{--url = url.replace(':startDate', startDate);--}}
        {{--url = url.replace(':endDate', endDate);--}}
        {{--url = url.replace(':employeeId', employeeId);--}}
        {{--url = url.replace(':projectId', projectID);--}}

        {{--window.location.href = url;--}}

        $('#startDateField').val(startDate);
        $('#endDateField').val(endDate);
        $('#projectIdField').val(projectID);
        $('#employeeIdField').val(employeeId);
        $('#leaveID').val(id);

        // TODO:: Search a batter method for jquery post request
        $( "#exportForm" ).submit();
    }

    function pieChart(taskStatus) {
        console.log(taskStatus);

        var ctx3 = document.getElementById("chart3").getContext("2d");
        var data3 = new Array();
        $.each(taskStatus, function(key,val){
            // console.log("key : "+key+" ; value : "+val);
            data3.push(
                {
                    value: parseInt(val.count),
                    color: val.color,
                    highlight: "#2750fe",
                    label: val.label
                }
            );
        });

        var myPieChart = new Chart(ctx3).Pie(data3,{
            segmentShowStroke : true,
            segmentStrokeColor : "#fff",
            segmentStrokeWidth : 0,
            animationSteps : 100,
            tooltipCornerRadius: 0,
            animationEasing : "easeOutBounce",
            animateRotate : true,
            animateScale : false,
            legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
            responsive: true
        });

        //showTable();
    }
    showTable();
</script>
@endpush