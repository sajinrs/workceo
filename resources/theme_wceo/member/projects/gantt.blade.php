@extends('layouts.member-app')



@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css') }}">
    <link href="//cdn.dhtmlx.com/gantt/edge/skins/dhtmlxgantt_broadway.css" rel="stylesheet">

    <style>

        .gantt_task_drag {
            width: 6px;
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAYAAAACCAYAAAB7Xa1eAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QYDDjkw3UJvAwAAABRJREFUCNdj/P//PwM2wASl/6PTAKrrBf4+lD8LAAAAAElFTkSuQmCC);
            z-index: 1;
            top: 0;
        }

        .gantt_task_drag.task_left{
            left: 0;
        }

        .gantt_task_drag.task_right{
            right: 0;
        }

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
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('member.projects.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">Gantt Chart</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <h5>@lang('modules.projects.viewGanttChart')</h5>
                    </div>
                    <div class="card-body">
                        <div id="gantt_here" style='width:100%; height:100vh;'></div>
                    </div>

                </div>
            </div>
        </div>
    </div>



    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="eventDetailModal" role="dialog" aria-labelledby="myModalLabel"
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
    <div class="modal fade bs-modal-md in"  id="subTaskModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="subTaskModelHeading">Sub Task e</span>
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
    <script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
    <script src="//cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
    <script src="//cdn.dhtmlx.com/gantt/edge/locale/locale_{{ $global->locale }}.js"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">

        gantt.config.xml_date = "%Y-%m-%d %H:%i:%s";

        gantt.templates.task_class = function (st, end, item) {
            return item.$level == 0 ? "gantt_project" : ""
        };

        gantt.config.scale_unit = "month";
        gantt.config.date_scale = "%F, %Y";

        gantt.config.scale_height = 50;

        gantt.config.subscales = [
            {unit: "day", step: 1, date: "%j, %D"}
        ];

        gantt.config.server_utc = false;

        // default columns definition
        gantt.config.columns=[
            {name:"text",       label:"Task name",  tree:true, width:'*' },
            {name:"start_date", label:"Start time", align: "center" },
            {name:"duration",   label:"Duration",   align: "center" },
            {name:"priority",        label:"Action",   width: 90, align: "center", template: function (item) {
                if(item.$level == 0){
                    return '<a href="javascript:addTask('+item.project_id+', '+item.id+');"><i class="fa fa-plus"></i></a>';
                } else {
                    return '';
                }

            }}
        ];

        //defines the text inside the tak bars
        gantt.templates.task_text = function (start, end, task) {
           
            return task.text;

        };

        gantt.attachEvent("onTaskCreated", function(task){
            //any custom logic here
            return false;
        });

        gantt.attachEvent("onBeforeTaskDrag", function(id, mode, e){
            var task = gantt.getTask(id);

            if(task.$level == 0)
            {
                return false;
            } else {
                return true;
            }
        });


        gantt.attachEvent("onAfterTaskDrag", function(id, mode, e){
            @if($user->can('edit_tasks'))
                var task = gantt.getTask(id);
                var taskId = task.taskid;
                var token = '{{ csrf_token() }}';
                var url = '{{route('member.projects.gantt-task-update', ':id')}}';
                url = url.replace(':id', taskId);
                var startDate = moment.utc(task.start_date.toDateString()).format('DD/MM/Y');
                var endDate = moment.utc(task.end_date.toDateString()).subtract(1, "days").format('DD/MM/Y');

                $.easyAjax({
                     url: url,
                    type: "POST",
                    container: '#gantt_here',
                    data: { '_token': token, 'start_date': startDate, 'end_date': endDate }
                })
            @endif
        });

        gantt.attachEvent("onBeforeLightbox", function(id) {
            var task = gantt.getTask(id);

            if ( task.$level > 0 ){
                $(".right-sidebar").slideDown(50).addClass("shw-rside");

                var taskId = task.taskid;
                var url = "{{ route('member.all-tasks.show',':id') }}";
                url = url.replace(':id', taskId);

                $.easyAjax({
                    type: 'GET',
                    url: url,
                    success: function (response) {
                        if (response.status == "success") {
                            $('#right-sidebar-content').html(response.view);
                        }
                    }
                });
            }
            return false;
        });

        gantt.init("gantt_here");

        @if($ganttProjectId == '')
            gantt.load('{{ route("member.projects.ganttData") }}');
        @else
            gantt.config.open_tree_initially = true;
            gantt.load('{{ route("member.projects.ganttData", $ganttProjectId) }}');
        @endif

        function addTask(id, parentId) {
            var url = '{{ route('member.projects.ajaxCreate', ':id')}}';
            url = url.replace(':id', id) + '?parent_gantt_id='+parentId;

            $('#modelHeading').html('Add Task');
            $.ajaxModal('#eventDetailModal', url);
        }

        //    update task
        function storeTask() {
            $.easyAjax({
                url: '{{route('member.all-tasks.store')}}',
                container: '#storeTask',
                type: "POST",
                data: $('#storeTask').serialize(),
                success: function (response) {
                    if (response.status == "success") {
                        $('#eventDetailModal').modal('hide');
                        var responseTasks = response.tasks;
                        var responseLinks = response.links;

                        responseTasks.forEach(function(responseTask) {
                            gantt.addTask(responseTask);
                        });

                        responseLinks.forEach(function(responseLink) {
                            gantt.addLink(responseLink);
                        });
                    }
                }
            })
        };

        function loadData() {
            var url = '{{ route("member.projects.ganttData") }}';

            @if($ganttProjectId != '')
                var url = '{{ route("member.projects.ganttData", $ganttProjectId) }}';
            @endif

            gantt.clearAll();
            gantt.load(url);
            $(".right-sidebar").slideDown(50).removeClass("shw-rside");
        }

        function limitMoveRight(task, limit) {
            var dur = task.end_date - task.start_date;
            task.start_date = new Date(limit.end_date);
            task.end_date = new Date(+task.start_date + dur);
        }

        function limitResizeRight(task, limit) {
            task.start_date = new Date(limit.end_date)
        }

        gantt.attachEvent("onTaskDrag", function (id, mode, task, original, e) {

            if(task.dependent_task_id !== null && task.dependent_task_id !== undefined)
            {
                var parent = gantt.getTask(task.dependent_task_id),
                    modes = gantt.config.drag_mode;

                var limitLeft = null,
                    limitRight = null;

                if (!(mode == modes.move || mode == modes.resize)) return;

                if (mode == modes.move) {
                    limitRight = limitMoveRight;
                } else if (mode == modes.resize) {
                    limitRight = limitResizeRight;
                }

                if (parent && +parent.end_date > +task.start_date) {
                    limitRight(task, parent);
                }
            }
        });

    </script>
@endpush

