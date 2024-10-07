@extends('layouts.member-app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/date-picker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap-datetimepicker.css') }}">

@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} #{{ $project->id }} - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">Time Logs</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @include('member.projects.show_project_menu')

        <div class="row">
            <div class="col-sm-12" id="issues-list-panel">
                <div class="card">
                    
                    <div class="card-header">
                        <h5 class="pull-left">@lang('app.menu.timeLogs')</h5>
                        @if($user->can('add_timelogs'))
                        <div class="pull-right">
                            <a href="javascript:;" id="show-add-form"
                               class="btn btn-secondary"><i
                                        class="fa fa-clock"></i> &nbsp; @lang('modules.timeLogs.logTime')
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        {!! Form::open(['id'=>'logTime','class'=>'ajax-form d-none m-b-50','method'=>'POST']) !!}

                        @if($logTimeFor->log_time_for == 'project') {!! Form::hidden('project_id', $project->id) !!} @endif

                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4 ">
                                    <div class="form-group form-label-group">
                                        @if($logTimeFor->log_time_for == 'task')
                                            <select placeholder="-" class="selectpicker form-control form-control-lg" name="task_id"
                                                    id="task_id" data-style="form-control">
                                                @forelse($tasks as $task)
                                                    <option value="{{ $task->id }}">{{ ucfirst($task->heading) }}</option>
                                                @empty
                                                    <option value="">@lang('messages.noTaskAddedToProject')</option>
                                                @endforelse
                                            </select>
                                            <label for="task_id">@lang('modules.timeLogs.task')</label>

                                        @else
                                            <select placeholder="-" class="selectpicker form-control form-control-lg" name="user_id"
                                                    id="user_id" data-style="form-control">
                                                @forelse($project->members as $member)
                                                    <option value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                                                @empty
                                                    <option value="">@lang('messages.noMemberAddedToProject')</option>
                                                @endforelse
                                            </select>
                                            <label for="user_id">@lang('modules.timeLogs.employeeName')</label>

                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-" type="text" name="memo" id="memo" class="form-control form-control-lg">
                                        <label for="memo">@lang('modules.timeLogs.memo')</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-3 ">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-" id="start_date" name="start_date" type="text"
                                               class="form-control form-control-lg"
                                               value="{{ \Carbon\Carbon::today()->format($global->date_format) }}">
                                        <label for="start_date">@lang('modules.timeLogs.startDate')</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group form-label-group bootstrap-timepicker timepicker">
                                        <input placeholder="-" type="text" name="start_time" id="start_time"
                                               class="form-control form-control-lg">
                                        <label for="start_time">@lang('modules.timeLogs.startTime')</label>
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-" id="end_date" name="end_date" type="text"
                                               class="form-control form-control-lg"
                                               value="{{ \Carbon\Carbon::today()->format($global->date_format) }}">
                                        <label for="end_date">@lang('modules.timeLogs.endDate')</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group form-label-group bootstrap-timepicker timepicker">
                                        <input placeholder="-" type="text" name="end_time" id="end_time"
                                               class="form-control form-control-lg">
                                        <label for="end_time">@lang('modules.timeLogs.endTime')</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-none">


                                <div class="col-md-4">
                                    <label for="">@lang('modules.timeLogs.totalHours')</label>

                                    <p id="total_time" class="form-control-static">0 Hrs</p>
                                </div>
                            </div>


                        </div>
                        <div class="form-actions row">
                            <button type="button" id="save-form" class="btn btn-primary col-md-3 offset-md-9"> @lang('app.save')</button>
                        </div>

                        {!! Form::close() !!}


                        <div class="table-responsive">
                            <table class="table table-bordered table-hover toggle-circle default footable-loaded footable"
                                   id="timelog-table">
                                <thead>
                                <tr>
                                    <th>@lang('app.id')</th>
                                    <th>@lang('modules.timeLogs.whoLogged')</th>
                                    <th>@lang('modules.timeLogs.startTime')</th>
                                    <th>@lang('modules.timeLogs.endTime')</th>
                                    <th>@lang('modules.timeLogs.totalHours')</th>
                                    <th>@lang('modules.timeLogs.memo')</th>
                                    <th>@lang('modules.timeLogs.lastUpdatedBy')</th>
                                    <th>@lang('app.action')</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
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

@endsection

@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/custom.js')}}"></script>  

<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js')}}"></script> 

<script>
    var table = $('#timelog-table').dataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: '{!! route('member.time-log.data', $project->id) !!}',
        deferRender: true,
        language: {
            "url": "<?php echo __("app.datatable") ?>"
        },
        "fnDrawCallback": function( oSettings ) {
            $("body").tooltip({
                selector: '[data-toggle="tooltip"]'
            });
        },
        "order": [[ 0, "desc" ]],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user_id', name: 'user_id' },
            { data: 'start_time', name: 'start_time' },
            { data: 'end_time', name: 'end_time' },
            { data: 'total_hours', name: 'total_hours' },
            { data: 'memo', name: 'memo' },
            { data: 'edited_by_user', name: 'edited_by_user' },
            {data: 'action', name: 'action'}
        ]
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
            close: 'fa fa-times'
        },
        //onChangeDateTime:calculateTime
    }).on('dp.change', function (event) {
        calculateTime();
    });
        

    jQuery('#start_date, #end_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language:'en'
    }).on('hide', function (e) {
        calculateTime();
    });

    function calculateTime() {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        var startTime = $("#start_time").val();
        var endTime = $("#end_time").val();

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

            $('#end_date').val(mm + '/' + dd + '/' + y);
            calculateTime();
        } else {
            $('#total_time').html(hours + "Hrs " + minutes + "Mins");
        }

//        console.log(hours+" "+minutes);
    }

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('member.time-log.store-time-log')}}',
            container: '#logTime',
            type: "POST",
            data: $('#logTime').serialize(),
            success: function (data) {
                if (data.status == 'success') {
                    table._fnDraw();
                }
            }
        })
    });

    $('#show-add-form').click(function () {
        $('#logTime').toggleClass('hide', 'show');
    });

    $('body').on('click', '.sa-params', function(){
        var id = $(this).data('time-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted time log!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

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
//                                    swal("Deleted!", response.message, "success");
                            table._fnDraw();
                        }
                    }
                });
            }
        });
    });

    $('body').on('click', '.edit-time-log', function () {
        var id = $(this).data('time-id');

        var url = '{{ route('member.time-log.edit', ':id')}}';
        url = url.replace(':id', id);

        $('#modelHeading').html('Update Time Log');
        $.ajaxModal('#editTimeLogModal',url);

    });

    $('#show-add-form').click(function () {
        $('#logTime').toggleClass('d-none', 'd-block');
    });

    $('ul.showProjectTabs .projectTimelogs .nav-link').addClass('active');
</script>
@endpush
