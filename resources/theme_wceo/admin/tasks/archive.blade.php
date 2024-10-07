@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/daterange-picker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
<style>
    .swal-footer {text-align: center !important;}
    
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
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="{{ route('admin.all-tasks.index') }}" class="btn btn-primary btn-sm">View Task <i class="fas fa-list-alt"></i></a>

                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(7)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>

                    </div>
                </div>
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>


    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="product-grid">
                    <div class="feature-products">

                        <div class="row">
                            <div class="col-sm-3 p-absolute product-sidebar-col">
                                <div class="product-sidebar" style="top: 60px !important;">
                                    <div class="filter-section">
                                        <div class="card">
                                            {{--<div class="card-header"  style="padding-top: 24px; padding-bottom: 24px">
                                                <h6 class="mb-0 f-w-600">Filters<span class="pull-right"><i class="fa fa-chevron-down toggle-data"></i></span></h6>
                                            </div>--}}
                                            <div class="left-filter wceo-left-filter taskFilter">
                                                <div class="card-body filter-cards-view animate-chk">
                                                    <div class="product-filter">
                                                        {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}

                                                        <div class="row"  id="ticket-filters">

                                                                {{--<div class="product-filter wceo-filter col-sm-6">
                                                                    <button type="button" id="apply-filters" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> @lang('app.apply')</button>
                                                                </div>
                                                                <div class="product-filter wceo-filter col-sm-6">
                                                                    <button type="button" id="reset-filters" class="btn btn-dark btn-sm"><i class="fa fa-refresh"></i> @lang('app.reset')</button>

                                                                </div>
--}}



                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.selectDateRange')</label>

                                                                        <div class="input-daterange input-group" id="date-range">
                                                                            <input type="text" class="form-control" id="start-date" placeholder="@lang('app.startDate')"
                                                                                   value=""/>
                                                                                   <div class="input-group-prepend input-group-append"><span class="input-group-text">@lang('app.to')</span></div>
                                                                            <input type="text" class="form-control" id="end-date" placeholder="@lang('app.endDate')"
                                                                                   value=""/>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="product-filter col-md-12">

                                                                    <div class="form-group">
                                                                        <label  class="f-w-600">@lang('app.selectProject')</label>

                                                                        <select class="select2 form-control" data-placeholder="@lang('app.selectProject')" id="project_id">
                                                                                    <option value="all">@lang('app.all')</option>
                                                                                    @foreach($projects as $project)
                                                                                        <option
                                                                                                value="{{ $project->id }}">{{ ucwords($project->project_name) }}</option>
                                                                                    @endforeach
                                                                                </select>

                                                                    </div>
                                                                </div>
                                                                <div class="product-filter col-md-12">

                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.select') @lang('app.client')</label>
                                                                        <select class="select2 form-control" data-placeholder="@lang('app.client')" id="clientID">
                                                                                    <option value="all">@lang('app.all')</option>
                                                                                    @foreach($clients as $client)
                                                                                        <option
                                                                                                value="{{ $client->id }}">{{ ucwords($client->name) }}</option>
                                                                                    @endforeach
                                                                                </select>

                                                                    </div>
                                                                </div>
                                                                <div class="product-filter col-md-12">

                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.select') @lang('modules.tasks.assignTo')</label>

                                                                        <select class="select2 form-control" data-placeholder="@lang('modules.tasks.assignTo')" id="assignedTo">
                                                                                    <option value="all">@lang('app.all')</option>
                                                                                    @foreach($employees as $employee)
                                                                                        <option
                                                                                                value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                                                                    @endforeach
                                                                                </select>

                                                                    </div>
                                                                </div>
                                                                <div class="product-filter col-md-12">

                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.select') @lang('modules.tasks.assignBy')</label>

                                                                        <select class="select2 form-control" data-placeholder="@lang('modules.tasks.assignBy')" id="assignedBY">
                                                                                    <option value="all">@lang('app.all')</option>
                                                                                    @foreach($employees as $employee)
                                                                                        <option
                                                                                                value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                                                                    @endforeach
                                                                                </select>

                                                                    </div>
                                                                </div>
                                                                <div class="product-filter col-md-12">

                                                                    <div class="form-group">

                                                                        <label class="f-w-600">@lang('app.select') @lang('app.status')</label>
                                                                        <select class="select2 form-control" data-placeholder="@lang('status')" id="status">
                                                                                    <option value="all">@lang('app.all')</option>
                                                                                    @foreach($taskBoardStatus as $status)
                                                                                        <option value="{{ $status->id }}">{{ ucwords($status->column_name) }}</option>
                                                                                    @endforeach
                                                                                </select>

                                                                    </div>
                                                                </div>

                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                    <div class="checkbox checkbox-info">
                                                                        <input type="checkbox" id="hide-completed-tasks">
                                                                        <label for="hide-completed-tasks">@lang('app.hideCompletedTasks')</label>
                                                                    </div>
                                                                    </div>
                                                                </div>

                                                            <div class="product-filter wceo-filter col-sm-6 pr-0">
                                                                <button type="button" class="btn btn-primary btn-block" id="filter-results"> @lang('app.apply')
                                                                </button> </div>
                                                            <div class="product-filter wceo-filter col-sm-6">
                                                                <button type="button" id="reset-filters" class="btn btn-outline-secondary btn-block"> @lang('app.reset')</button>
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
                                                {!! $dataTable->table(['class' => 'display']) !!}
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
<script src="{{ asset('themes/wceo/assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/select2/select2-custom.js')}}"></script>
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

{!! $dataTable->scripts() !!}

<script>   

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $(".filter-toggle").click(function(){
        if($(".product-sidebar").hasClass("open")){
            $('.product-sidebar-col').show();
            $('.taskFilter').slimScroll({
                height: '500px'
            });
        }else{
            $('.product-sidebar-col').hide();
        }
    });    

    $('#allTasks-table').on('preXhr.dt', function (e, settings, data) {
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
        var clientID = $('#clientID').val();
        var assignedBY = $('#assignedBY').val();
        var assignedTo = $('#assignedTo').val();
        var status = $('#status').val();
        var category_id = $('#category_id').val();

        if ($('#hide-completed-tasks').is(':checked')) {
            var hideCompleted = '1';
        } else {
            var hideCompleted = '0';
        }

        data['clientID'] = clientID;
        data['assignedBY'] = assignedBY;
        data['assignedTo'] = assignedTo;
        data['status'] = status;
        data['category_id'] = category_id;
        data['hideCompleted'] = hideCompleted;
        data['projectId'] = projectID;
        data['startDate'] = startDate;
        data['endDate'] = endDate;
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

    table = '';

    function showTable() {
        window.LaravelDataTables["allTasks-table"].search('').draw();
    }

    $('#filter-results').click(function () {
        showTable();
    });

    $('#reset-filters').click(function () {
        $('.select2').val('all');
        $('.select2').trigger('change');
        
        $('#start-date').val('');
        $('#end-date').val('');

        showTable();
    })


    function restore(id)
    {
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "Do you want to revert this Task.",
            icon: "{{ asset('img/warning.png')}}",
            buttons: ["@lang('messages.confirmNoArchive')", "@lang('messages.confirmRevert')"],
            dangerMode: true
        }).then((willDelete) => {
            if (willDelete) {


                var url = "{{ route('admin.task.archive-restore',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'GET',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            window.LaravelDataTables["allTasks-table"].search('').draw();
                        }
                    }
                });
            }
        });
    }

    $('#allTasks-table').on('click', '.show-task-detail', function () {
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

    showTable();
    $('#createTaskCategory').click(function(){
        var url = '{{ route('admin.taskCategory.create')}}';
        $('#modelHeading').html("@lang('modules.taskCategory.manageTaskCategory')");
        $.ajaxModal('#taskCategoryModal',url);
    })
    function exportData(){

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

        if ($('#hide-completed-tasks').is(':checked')) {
            var hideCompleted = '1';
        } else {
            var hideCompleted = '0';
        }

        var url = '{!!  route('admin.all-tasks.export', [':startDate', ':endDate', ':projectId', ':hideCompleted']) !!}';

        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':hideCompleted', hideCompleted);
        url = url.replace(':projectId', projectID);

        window.location.href = url;
    }
</script>
@endpush
