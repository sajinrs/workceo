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
                        <div class="card-title mb-0"> <h5 class="box-title"><i class="icofont icofont-chart-line"></i> @lang('app.menu.attendance') </h5></div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="reportTitle">@lang('app.menu.attendance') @lang('app.report')</h5>
                                <p>View all employees attendance report.</p>

                                <div class="product-filter">
                                    <form action="" id="filter-form">
                                        <div class="row"  id="ticket-filters">
                                            <div class="product-filter col-md-12">

                                                <div class="form-group">
                                                    <label class="f-w-600">@lang('app.selectDateRange')</label>
                                                    <div class="input-daterange input-group" id="date-range">
                                                        <input type="text" class="form-control" autocomplete="off" id="start-date" placeholder="@lang('app.startDate')" value="{{ \Carbon\Carbon::today()->startOfMonth()->format($global->date_format) }}"/>
                                                        <div class="input-group-prepend input-group-append"><span class="input-group-text">@lang('app.to')</span></div>
                                                        <input type="text" class="form-control" id="end-date"  autocomplete="off" placeholder="@lang('app.endDate')" value="{{ \Carbon\Carbon::today()->format($global->date_format) }}"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="f-w-600">@lang('app.select') @lang('app.employee')</label>
                                                    <select class="select2 form-control" data-placeholder="@lang('app.all')" id="employeeID" name="employee_id">
                                                        <option value="all">@lang('app.all')</option>
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
                                <div class="col-md-8 filter-section">
                                <div class="browser-widget">
                                    <div class="media card-body" style="padding: 10px">
                                        <div class="media-body align-self-center count_by_status count1">
                                            <div>
                                                <p> @lang('modules.attendance.totalWorkingDays')</p>
                                                <h4 id="totalDays"><span class="counter">9</span></h4>
                                            </div>                                                                                                                    
                                        </div>
                                    </div>
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
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>





{!! $dataTable->scripts() !!}
<script>

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('#attendance-report-table').on('preXhr.dt', function (e, settings, data) {
        var employeeID = $('#employeeID').val();
        var startDate = $('#start-date').val();
        var endDate = $('#end-date').val();

        data['startDate'] = startDate;
        data['endDate'] = endDate;
        data['employee'] = employeeID;
        data['_token'] = '{{ csrf_token() }}';
    });

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

    function showTable() {
        var employeeID = $('#employeeID').val();
        var startDate = $('#start-date').val();
        var endDate = $('#end-date').val();

        var url2 = '{!!  route('admin.attendance-report.report') !!}';

        url2 = url2.replace(':startDate', startDate);
        url2 = url2.replace(':endDate', endDate);
        url2 = url2.replace(':employeeID', employeeID);

        $.easyAjax({
            type: 'POST',
            url: url2,
            data: $('#filter-form').serialize(),
            success: function (response) {
                $('#totalDays').text(response.data);
            }
        });

        window.LaravelDataTables["attendance-report-table"].draw();
    }

    $('#filter-results').click(function () {
        showTable();
    });

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
        $('#status').val('all');
        $('.select2').val('all');
        $('#filter-form').find('select').select2();
        $('#filter-results').trigger("click");
    })

    showTable();

    $('#export-excel').click(function () {
        var employeeID = $('#employeeID').val();
        var startDate = $('#start-date').val();
        var endDate = $('#end-date').val();


        //refresh datatable
        var url2 = '{!!  route('admin.attendance-report.reportExport', [':startDate', ':endDate', ':employeeID']) !!}';

        url2 = url2.replace(':startDate', startDate);
        url2 = url2.replace(':endDate', endDate);
        url2 = url2.replace(':employeeID', employeeID);

        window.location = url2;
    })


    // showTable();

</script>
@endpush