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
                        <a href="{{ route('admin.task.archive') }}" class="btn btn-primary btn-sm">@lang('modules.tasks.viewArchive') <i class="feather-16" data-feather="archive"></i></a>
                        <a href="{{ route('admin.all-tasks.create') }}" class="btn btn-primary btn-sm">@lang('modules.tasks.addNewTask') <i data-feather="plus"></i></a>

                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(7)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>

                    </div>
                </div>
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>


    <div class="container-fluid product-wrapper">
        <div class="row">

            <div class="col-md-12 col-sm-12">
                <div class="filter-section">
                    <div class="card browser-widget bw-employee">
                        <div class="media card-body" style="padding: 10px">
                            <div class="media-body align-self-center count4 count_by_status">
                                <div id="incomplete">
                                    <p>Incomplete </p>
                                    <h4><span class="counter">{{$inCompleteCount}}</span></h4>
                                </div>
                                <div id="completed">
                                    <p>Completed</p>
                                    <h4><span class="counter">{{$completedCount}}</span></h4>
                                </div>
                                <div id="dueDate">
                                    <p>Overdue</p>
                                    <h4><span class="counter">{{$dueCount}}</span></h4>
                                </div>
                                <div id="highPriority">
                                    <p>High Priority</p>
                                    <h4><span class="counter">{{$highPriorityCount}}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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


                    @if($taskCount == 0)
                        <div class="col-md-12 m-t-20">
                            <div class="empty-content text-center">
                                <img src="{{ asset('img/empty/task.jpg') }}" alt="user-img" /><br />
                                <b>No Tasks</b><br />
                                No tasks added yet.<br />
                                <a href="{{ route('admin.all-tasks.create') }}" class="btn btn-primary btn-sm m-t-20">Add Task</a>
                            </div>
                        </div>
                    @else  
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
                    @endif
                </div>
            </div>
        </div>
    </div>



    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="editTimeLogModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
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
    <div class="modal fade bs-modal-md in" id="taskCategoryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
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
        <!-- /.modal-dialog -->.
    </div>
    {{--Ajax Modal Ends--}}
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in"  id="subTaskModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subTaskModelHeading">Sub Task e</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
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
        <!-- /.modal-dialog -->.
    </div>
    {{--Ajax Modal Ends--}}
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

    $("body").on("click",".Master", function(){
        if ($(this).is(':checked', true)) {
            $(".sub_chk").prop('checked', true);
        } else {
            $(".sub_chk").prop('checked', false);
        }
    });

    $("body").on("click",".btn-action", function(){

        var ids = [];
        var status = $(this).data('status');
        $(".sub_chk:checked").each(function() {
            ids.push($(this).val());
        });

        if (ids.length <= 0) {
            alert("Please select row.");
        } else {
            var token = "{{ csrf_token() }}";
            $.easyAjax({
                type: 'POST',
                url: "{{ route('admin.tasks.builk-action') }}",
                data: {'id':ids, 'action':status, '_token': token},
                success: function (response) {
                    if (response.status == "success") {
                        $.unblockUI();
                        window.LaravelDataTables["allTasks-table"].search('').draw();
                    }
                }
            });
        }
    });

    $("body").on("click",".btn-archive, .btn-delete", function(){
        var ids = [];
        var status = $(this).data('status');

        if(status == 'archive') {
            var warningText = 'Do you want to archive this task.';
            var actionBtn = 'ARCHIVE';
        } else {
            var warningText = 'You will not be able to recover the deleted task!';
            var actionBtn = 'DELETE';
        }

        $(".sub_chk:checked").each(function() {
            ids.push($(this).val());
        });

        if (ids.length <= 0) {
            alert("Please select row.");
        } else {
            swal({
                title: "Are you sure?",
                text: warningText,
                icon: "{{ asset('img/warning.png')}}",
                buttons: ["CANCEL", actionBtn],
                dangerMode: true
            }).then((willDelete) => {
                if (willDelete) {
                    var url = "{{ route('admin.tasks.builk-action') }}";
                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {
                            'id':ids,
                            'action':status,
                            '_token': token,
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                window.LaravelDataTables["allTasks-table"].search('').draw();
                            }
                        }
                    });

                }
            });
        }
    });

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
        data['status_by'] = null;
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
        $('#allTasks-table').on('preXhr.dt', function (e, settings, data) {
            data['status_by'] = null;
        });
        showTable();
    });

    $('.filter-section .count_by_status > div').on('click', function(event) {
        $('.filter-section .count_by_status > div').removeClass('active');
        $(this).addClass('active');
        showDataByStatus($(this).attr('id'));
    });

    function showDataByStatus(id) 
    {
        $('#allTasks-table').on('preXhr.dt', function (e, settings, data) {
            var status_by = id;
            data['status_by'] = status_by;
        });
        window.LaravelDataTables["allTasks-table"].search('').draw();
    } 


    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('task-id');
        var recurring = $(this).data('recurring');

        var buttons = {
            cancel: "CANCEL",
            confirm: {
                text: "DELETE",
                value: 'confirm',
                visible: true,
                className: "danger",
            }
        };

        if(recurring == 'yes')
        {
            buttons.recurring = {
                text: "{{ trans('modules.tasks.deleteRecurringTasks') }}",
                value: 'recurring'
            }
        }

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted task!",
            dangerMode: true,
            icon: "{{ asset('img/warning.png')}}",
            buttons: buttons
        }).then(function (isConfirm) {
            if (isConfirm == 'confirm' || isConfirm == 'recurring') {
                var url = "{{ route('admin.all-tasks.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";
                var dataObject = {'_token': token, '_method': 'DELETE'};

                if(isConfirm == 'recurring')
                {
                    dataObject.recurring = 'yes';
                }

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: dataObject,
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            window.LaravelDataTables["allTasks-table"].search('').draw();
                        }
                    }
                });
            }
        });
    });

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
