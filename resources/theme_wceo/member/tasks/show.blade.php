@extends('layouts.member-app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/date-picker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} #{{ $project->id }} - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('member.projects.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.menu.tasks')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @include('member.projects.show_project_menu')

        <div class="row">
            <div class="col-sm-12"  id="task-list-panel">
                <div class="card" id="new-task-panel">
                    <div class="card-header">
                      <div class="h5">  <i class="fa fa-plus"></i> @lang('modules.tasks.newTask')

                        <div class="card-header-right p-0">
                            <ul class="list-unstyled card-option">
                                <li><i class="icofont icofont-error closeNewTask"></i></li>

                            </ul>
                        </div>
                        </div>
                    </div>
                    {!! Form::open(['id'=>'createTask','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="card-body">

                        {!! Form::hidden('project_id', $project->id) !!}

                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-"  type="text" id="heading" name="heading" class="form-control form-control-lg">
                                        <label for="heading" class="required">@lang('app.title')</label>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.description')</label>
                                        <textarea id="description" name="description"
                                                  class="form-control summernote"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="checkbox checkbox-info">
                                            <input id="dependent-task" name="dependent" value="yes"
                                                   type="checkbox">
                                            <label for="dependent-task">@lang('modules.tasks.dependent')</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" id="dependent-fields" style="display: none">
                                    <div class="form-group form-label-group">
                                        <select placeholder="-" class=" form-control form-control-lg" data-placeholder="@lang('modules.tasks.chooseTask')" name="dependent_task_id" id="dependent_task_id" >
                                            <option value=""></option>
                                            @foreach($allTasks as $allTask)
                                                <option value="{{ $allTask->id }}">{{ $allTask->heading }} (@lang('app.dueDate'): {{ $allTask->due_date->format($global->date_format) }})</option>
                                            @endforeach
                                        </select>
                                        <label for="dependent_task_id">@lang('modules.tasks.dependentTask')</label>

                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-"  type="text" name="start_date" id="start_date" class="form-control form-control-lg" autocomplete="off" value="">
                                        <label for="start_date" class="required">@lang('modules.projects.startDate')</label>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-4">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-" type="text" name="due_date" id="due_date" autocomplete="off" class="form-control form-control-lg">
                                        <label for="due_date" class="required">@lang('app.dueDate')</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-label-group">
                                        <select placeholder="-" class="select2 form-control form-control-lg" name="milestone_id" id="milestone_id">
                                            <option value="">--</option>
                                            @foreach($project->milestones as $milestone)
                                                <option value="{{ $milestone->id }}">{{ $milestone->milestone_title }}</option>
                                            @endforeach
                                        </select>
                                        <label for="milestone_id" class="control-label">@lang('modules.projects.milestones')</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-label-group">
                                        <select placeholder="-" class="select2 select2-multiple  form-control form-control-lg" multiple="multiple" data-placeholder="@lang('modules.tasks.chooseAssignee')"  name="user_id[]" id="user_id">
                                            <option value=""></option>
                                            @foreach($project->members as $member)
                                                <option value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="user_id" class="control-label select-required">@lang('modules.tasks.assignTo')</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-label-group">

                                        <select placeholder="-"  class="select2 form-control form-control-lg" name="category_id" id="category_id"
                                                data-style="form-control form-control-lg">
                                            @forelse($categories as $category)
                                                <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noTaskCategoryAdded')</option>
                                            @endforelse
                                        </select>
                                        <label for="category_id" >@lang('modules.tasks.taskCategory')
                                        </label>
                                    </div>
                                </div>
                               
                                <!--/span-->
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="control-label col-12">@lang('modules.tasks.priority')</label>

                                        <div class="col-4 radio radio-danger">
                                            <input type="radio" name="priority" id="radio13"
                                                   value="high">
                                            <label for="radio13" class="text-danger">
                                                @lang('modules.tasks.high') </label>
                                        </div>
                                        <div class="col-4 radio radio-warning">
                                            <input type="radio" name="priority" checked
                                                   id="radio14" value="medium">
                                            <label for="radio14" class="text-warning">
                                                @lang('modules.tasks.medium') </label>
                                        </div>
                                        <div class="col-4 radio radio-success">
                                            <input type="radio" name="priority" id="radio15"
                                                   value="low">
                                            <label for="radio15" class="text-success">
                                                @lang('modules.tasks.low') </label>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->

                        </div>


                    </div>
                    <div class="card-footer">
                        <div class="form-actions row">
                            <button type="submit" id="save-task" class="btn btn-primary col-md-3 offset-md-9">  @lang('app.save')
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div class="card d-none" id="edit-task-panel">
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="h5 pull-left">@lang('app.menu.tasks')</div>

                            <div class="pull-right taskTopBtn">
                                <a href="javascript:;" id="show-new-task-panel" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i>
                                    @lang('modules.tasks.newTask')
                                </a>
                               
                            </div>


                    </div>
                    <div class="card-body">
                        <div class="dt-ext table-responsive">
                            <table class="display" id="tasks-table">
                                <thead>
                                <tr>
                                    <th>@lang('app.id')</th>
                                    <th>@lang('app.task')</th>
                                    <th>@lang('app.client')</th>
                                    <th>@lang('modules.tasks.assignTo')</th>
                                    <th>@lang('modules.tasks.assignBy')</th>
                                    <th>@lang('app.dueDate')</th>
                                    <th>@lang('app.status')</th>
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

    <!-- .row -->

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
<script src="{{ asset('themes/wceo/assets/js/jquery.ui.min.js')}}"></script>

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
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.custom.js')}}"></script>

<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
    
<script type="text/javascript">
    var newTaskpanel = $('#new-task-panel');
    var taskListPanel = $('#task-list-panel');
    var editTaskPanel = $('#edit-task-panel');

    $('#new-task-panel').hide();

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen"]]
        ]
    });

    var table = '';

    function showTable() {
        var url = '{!!  route('member.tasks.data', [':projectId']) !!}?_token={{ csrf_token() }}';

        url = url.replace(':projectId', '{{ $project->id }}');

        table = $('#tasks-table').dataTable({
            destroy: true,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: url,
            deferRender: true,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function (oSettings) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            "order": [[0, "desc"]],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'heading', name: 'heading'},
                {data: 'clientName', name: 'client.name', bSort: false},
                {data: 'username', name: 'users.name', searchable: false},
                {data: 'created_by', name: 'creator_user.name'},
                {data: 'due_date', name: 'due_date'},
                {data: 'column_name', name: 'taskboard_columns.column_name'},
                {data: 'action', name: 'action', "searchable": false}
            ]
        });
    }
    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('task-id');

        var buttons = {
            cancel: "No, cancel please!",
            confirm: {
                text: "Yes, delete it!",
                value: 'confirm',
                visible: true,
                className: "danger",
            }
        };

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted task!",
            dangerMode: true,
            icon: 'warning',
            buttons: buttons
        }).then(function (isConfirm) {
            if (isConfirm == 'confirm') {

                var url = "{{ route('member.all-tasks.destroy',':id') }}";
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

    $('#tasks-table').on('click', '.show-task-detail', function () {
        $(".right-sidebar").addClass("right-sidebar-width-auto");
        //$('.right_side_toggle').trigger('click');
        $(".right-sidebar").slideDown(50).addClass("show shw-rside");
        var id = $(this).data('task-id');
        var url = "{{ route('member.all-tasks.show',':id') }}";
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

    

    jQuery('#due_date, #start_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });
    showTable();

    //    save new task
    $('#save-task').click(function () {
        $.easyAjax({
            url: '{{route('member.tasks.store')}}',
            container: '#section-line-3',
            type: "POST",
            data: $('#createTask').serialize(),
            success: function (data) {
                $('#createTask').trigger("reset");
                $('.summernote').summernote('code', '');
                $('#task-list-panel ul.list-group').html(data.html);
                newTaskpanel.switchClass("show", "hide", 300, "easeInOutQuad");
                showTable();
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            }
        })
    });

    //    save new task
    taskListPanel.on('click', '.edit-task', function () {
        var id = $(this).data('task-id');
        var url = "{{route('member.tasks.edit', ':id')}}";
        url = url.replace(':id', id);

        $.easyAjax({
            url: url,
            type: "GET",
            container: '#task-list-panel',
            data: {taskId: id},
            success: function (data) {
                editTaskPanel.html(data.html);
                newTaskpanel.addClass('d-none').removeClass('d-block');
                editTaskPanel.switchClass("d-none", "d-block", 300, "easeInOutQuad");

                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });

                $('html, body').animate({
                    scrollTop: $("#task-list-panel").offset().top
                }, 1000);
            }
        })
    });

    

    //    change task status
    taskListPanel.on('click', '.task-check', function () {
        if ($(this).is(':checked')) {
            var status = 'completed';
        }else{
            var status = 'incomplete';
        }

        var sortBy = $('#sort-task').val();

        var id = $(this).data('task-id');

        if(status == 'completed'){
            var checkUrl = '{{route('member.tasks.checkTask', ':id')}}';
            checkUrl = checkUrl.replace(':id', id);
            $.easyAjax({
                url: checkUrl,
                type: "GET",
                container: '#task-list-panel',
                data: {},
                success: function (data) {
                    console.log(data.taskCount);
                    if(data.taskCount > 0){
                        swal({
                            title: "Are you sure?",
                            text: "There is a incomplete sub-task in this task do you want to mark complete!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, complete it!",
                            cancelButtonText: "No, cancel please!",
                            closeOnConfirm: true,
                            closeOnCancel: true
                        }, function (isConfirm) {
                            if (isConfirm) {
                                updateTask(id,status,sortBy)
                            }
                        });
                    }
                    else{
                        updateTask(id,status,sortBy)
                    }

                }
            });
        }
        else{
            updateTask(id,status,sortBy)
        }


    });

    // Update Task
    function updateTask(id,status,sortBy){
        var url = "{{route('member.tasks.changeStatus')}}";
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: url,
            type: "POST",
            container: '#section-line-3',
            data: {'_token': token, taskId: id, status: status, sortBy: sortBy},
            success: function (data) {
                $('#task-list-panel ul.list-group').html(data.html);
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            }
        })
    }

    //    save new task
    $('#sort-task, #hide-completed-tasks').change(function() {
        var sortBy = $('#sort-task').val();
        var id = $('#sort-task').data('project-id');

        var url = "{{route('member.tasks.sort')}}";
        var token = "{{ csrf_token() }}";

        if ($('#hide-completed-tasks').is(':checked')) {
            var hideCompleted = '1';
        }else {
            var hideCompleted = '0';
        }

        $.easyAjax({
            url: url,
            type: "POST",
            container: '#task-list-panel',
            data: {'_token': token, projectId: id, sortBy: sortBy, hideCompleted: hideCompleted},
            success: function (data) {
                $('#task-list-panel ul.list-group').html(data.html);
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            }
        })
    });

    $('#show-new-task-panel').click(function () {
        editTaskPanel.addClass('d-none').removeClass('d-block');
        newTaskpanel.switchClass("d-none", "d-block", 300, "easeInOutQuad");

        $('html, body').animate({
            scrollTop: $("#task-list-panel").offset().top
        }, 1000);
    });

    $('.closeNewTask').click(function () {
        newTaskpanel.addClass('d-none').removeClass('d-block');
    });

    $('#hide-new-task-panel').click(function () {
        newTaskpanel.addClass('d-none').removeClass('d-block');
        taskListPanel.switchClass("col-md-6", "col-md-12", 1000, "easeInOutQuad");
    });

    

    editTaskPanel.on('click', '#hide-edit-task-panel', function () {
        editTaskPanel.addClass('d-none').removeClass('d-block');
        taskListPanel.switchClass("col-md-6", "col-md-12", 1000, "easeInOutQuad");
    });

    $('#dependent-task').change(function () {
        if($(this).is(':checked')){
            $('#dependent-fields').show();
        }
        else{
            $('#dependent-fields').hide();
        }
    })

    $('ul.showProjectTabs .projectTasks .nav-link').addClass('active');
</script>
@endpush