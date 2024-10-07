@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/daterange-picker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/date-picker.css') }}">
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
            <!-- Zero Configuration  Starts-->

            @include('sections.report_menu')

            <div class="col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title mb-0"> <h5 class="box-title"><i class="icofont icofont-chart-line"></i> @lang('app.grossRevenue') </h5></div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="reportTitle">@lang('app.grossRevenue') @lang('app.report')</h5>
                                <p>A detailed list of revenue report.</p>

                                <div class="product-filter">
                                    <form action="" id="filter-form">
                                        <div class="row"  id="ticket-filters">
                                            <div class="product-filter col-md-12">

                                                {{--<div class="form-group">
                                                    <label class="f-w-600">@lang('app.selectDateRange')</label>
                                                    <div class="input-daterange input-group" id="date-range">
                                                        <input type="text" class="form-control" autocomplete="off" id="start-date" placeholder="@lang('app.startDate')" value=""/>
                                                        <div class="input-group-prepend input-group-append"><span class="input-group-text">@lang('app.to')</span></div>
                                                        <input type="text" class="form-control" id="end-date"  autocomplete="off" placeholder="@lang('app.endDate')" value=""/>
                                                    </div>
                                                </div>--}}

                                                <div class="form-group">
                                                    <label class="f-w-600">@lang('app.project')</label>
                                                    <select class="form-control" name="project" id="project" data-style="form-control">
                                                        <option value="all">@lang('modules.client.all')</option>
                                                        @forelse($projects as $project)
                                                            <option value="{{$project->id}}">{{ $project->project_name }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="f-w-600">@lang('app.client')</label>
                                                    <select class="form-control" name="client" id="client" data-style="form-control">
                                                        <option value="all">@lang('modules.client.all')</option>
                                                        @forelse($clients as $client)
                                                            <option
                                                            value="{{ $client->id }}">{{ ucwords($client->name) }}{{ ($client->company_name != '') ? " [".$client->company_name."]" : "" }}</option>
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

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select class="form-control" id="Time" data-style="form-control" placeholder="Time">
                                                    <option value="this_week">This Week</option>
                                                    <option selected value="this_month">This Month</option>
                                                    <option value="last_12_month">Last 12 Months</option>
                                                    <option value="last_30_days">Last 30 Days</option>
                                                    <option value="this_year">This Calendar Year</option>
                                                    <option value="custom">Custom</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 customTIme d-none">
                                            <div class="form-group">
                                                <input type="text" placeholder="@lang('modules.projects.startDate')" name="custom_start_date" id="custom_start_date" autocomplete="off" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-3 customTIme d-none">
                                            <div class="form-group">
                                                <input type="text" placeholder="@lang('modules.projects.endDate')" name="custom_end_date" id="custom_end_date" autocomplete="off" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <button class="btn btn-block btn-secondary" id="timeFilter">Apply</span></button>
                                        </div>

                                    </div>

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
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>



{!! $dataTable->scripts() !!}
<script>
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    
    jQuery('#start-date, #end-date').daterangepicker({
            locale: {
                format: 'DD ddd MMM YYYY'
            },
            "alwaysShowCalendars": true,
            autoApply: true,
            autoUpdateInput: false,
            language: '{{ $global->locale }}',
            weekStart:'{{ $global->week_start }}',
        }, function(start, end, label) {
            var selectedStartDate = start.format('DD ddd MMM YYYY'); // selected start
            var selectedEndDate = end.format('DD ddd MMM YYYY'); // selected end

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

        jQuery('#custom_start_date, #custom_end_date').datepicker({
            autoclose: true,
            todayHighlight: true,
            weekStart:'{{ $global->week_start }}',
            dateFormat: '{{ $global->date_picker_format }}',
            language: 'en'
        });

       



    $('#payments-table').on('preXhr.dt', function (e, settings, data) {
        //var startDate = $('#start-date').val();
        var startDate;
        var endDate;
        var period = $('#Time option:selected').val();

        if(period == 'custom')
        {
            startDate = $('#custom_start_date').val();
            endDate = $('#custom_end_date').val();
        }        

        var status = $('#status').val();
        var project = $('#project').val();
        var client = $('#client').val();
        
        data['status'] = status;
        data['project'] = project;
        data['client'] = client;
        data['time'] = period;
        data['customStartDate'] = startDate;
        data['customEndDate'] = endDate;
    });  

    $('#Time').on('change', function() {
        if(this.value == 'custom'){
            $('.customTIme').removeClass('d-none');
        } else {
            $('.customTIme').addClass('d-none');
        }
    });

    $('#filter-results, #timeFilter').click(function () {
        var token = '{{ csrf_token() }}';
        var url = '{{ route('admin.finance-report.store') }}';

        var startDate;
        var endDate;
        var period = $('#Time option:selected').val();

        if(period == 'custom')
        {
            startDate = $('#custom_start_date').val();
            endDate = $('#custom_end_date').val();
        }        

        var project = $('#project').val();
        var client = $('#client').val();
        var period = $('#Time option:selected').val();

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {_token: token, period:period, startDate:startDate, endDate:endDate, project: project, client: client},
            success: function (response) {
                if(response.status == 'success'){
                    chartData = $.parseJSON(response.chartData);
                    $('#morris-bar-chart').html('');
                    barChart();
                    window.LaravelDataTables["payments-table"].draw();
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

    function loadTable(){
        window.LaravelDataTables["payments-table"].draw();
    }

</script>

<script>
    var chartData = {!!  $chartData !!};
    function barChart() {

        Morris.Bar({
            element: 'morris-bar-chart',
            data: chartData,
            xkey: 'date',
            ykeys: ['total'],
            labels: ['Earning'],
            barColors:['#1ea6ec'],
            hideHover: 'auto',
            gridLineColor: '#eef0f2',
            resize: true
        });

    }

    barChart();
    loadTable();

</script>
@endpush