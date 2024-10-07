@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/daterange-picker.css') }}">
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
                        <div class="card-title mb-0"> <h5 class="box-title"><i class="icofont icofont-chart-line"></i> @lang('app.timeLog') </h5></div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="reportTitle">@lang('app.timeLog') @lang('app.report')</h5>
                                <p>A detailed list of all time tracked for the team.</p>

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
                                                    <label class="f-w-600">
                                                        @if($logTimeFor->log_time_for == 'task')
                                                            @lang('app.selectTask')
                                                        @else
                                                            @lang('app.selectProject')
                                                        @endif
                                                    </label>
                                                        @if($logTimeFor->log_time_for == 'task')
                                                            <select class="form-control" data-placeholder="@lang('app.selectTask')" id="project_id">
                                                                <option value="">@lang('modules.client.all')</option>
                                                                @foreach($tasks as $task)
                                                                    <option value="{{ $task->id }}">{{ ucwords($task->heading) }}</option>
                                                                @endforeach

                                                            </select>
                                                        @else
                                                            <select class="form-control" data-placeholder="@lang('app.selectProject')" id="project_id">
                                                                <option value="">@lang('modules.client.all')</option>
                                                                @foreach($projects as $project)
                                                                    <option value="{{ $project->id }}">{{ ucwords($project->project_name) }}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                </div>

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
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div id="morris-bar-chart"></div>
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
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

<script src="{{ asset('themes/wceo/assets/js/chart/morris-chart/raphael.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/morris-chart/morris.js') }}"></script>




{!! $dataTable->scripts() !!}
<script>

//format: 'DD ddd MMM YYYY'

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
        var url = '{{ route('admin.time-log-report.store') }}';

        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = null;
        }

        var projectID = $('#project_id').val();

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {_token: token, startDate: startDate, endDate: endDate, projectId: projectID},
            success: function (response) {
                if(response.status == 'success'){
                    chartData = $.parseJSON(response.chartData);
                    $('#morris-bar-chart').html('');
                    $('#morris-bar-chart').empty();
                    barChart();
                    //showTable();
                    window.LaravelDataTables["all-time-logs-table"].draw();
                }
            }
        });
    })

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
        $('#status').val('all');
        $('.select2').val('all');
        $('#filter-form').find('select').select2();
        $('#filter-results').trigger("click");
    })

    $('#all-time-logs-table').on('preXhr.dt', function (e, settings, data) {
        var startDate = $('#start-date').val();

        if(startDate == ''){
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if(endDate == ''){
            endDate = null;
        }

        var projectID = $('#project_id').val();
        var employee = $('#employee').val();

        data['startDate'] = startDate;
        data['endDate'] = endDate;
        data['projectId'] = projectID;
        data['employee'] = employee;
    });

    function showTable() {
        window.LaravelDataTables["all-time-logs-table"].draw();
    }

</script>

<script>
    var chartData = {!!  $chartData !!};
    function barChart() {

        Morris.Bar({
            element: 'morris-bar-chart',
            data: chartData,
            xkey: 'date',
            ykeys: ['total_hours'],
            labels: ['Hours Logged'],
            barColors:['#3594fa'],
            hideHover: 'auto',
            gridLineColor: '#ccccccc',
            resize: true
        });

    }

    @if($chartData != '[]')
    barChart();
    @endif


</script>
@endpush