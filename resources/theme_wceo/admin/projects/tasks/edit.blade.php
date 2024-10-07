
<div class="card">
    <div class="card-header">
        <div class="h5"><i class="ti-pencil"></i> @lang('modules.tasks.updateTask')
            <div class="card-header-right p-0">
                <ul class="list-unstyled card-option">
                    <li id="hide-edit-task-panel" ><i class="icofont icofont-error"></i></li>
                </ul>
            </div>
        </div>
    </div>
    {!! Form::open(['id'=>'updateTask','class'=>'ajax-form','method'=>'PUT']) !!}

    <div class="card-body">

            {!! Form::hidden('project_id', $task->project_id) !!}

            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-label-group">
                            <input placeholder="-" type="text" id="heading" name="heading" class="form-control form-control-lg" value="{{ $task->heading }}">
                            <label for="heading" class="required">@lang('app.title')</label>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">@lang('app.description')</label>
                            <textarea id="description" name="description" class="form-control" rows="8">{{ $task->description }}</textarea>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-12">
                        <div class="form-group">

                            <div class="checkbox checkbox-info">
                                <input placeholder="-"  id="dependent-task-project" name="dependent" value="yes"
                                       type="checkbox" @if($task->dependent_task_id != '') checked @endif onclick="dependedSelected(this)">
                                <label for="dependent-task-project">@lang('modules.tasks.dependent')</label>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12" id="dependent-fields-project" @if($task->dependent_task_id == null) style="display: none" @endif>
                        <div class="form-group form-label-group">
                            <select placeholder="-"  class="select2  form-control form-control-lg" data-placeholder="@lang('modules.tasks.chooseTask')" name="dependent_task_id" id="dependent_task_id_project" >
                                @foreach($allTasks as $allTask)
                                    <option value="{{ $allTask->id }}" @if($allTask->id == $task->dependent_task_id) selected @endif>{{ $allTask->heading }} (@lang('app.dueDate'): {{ $allTask->due_date->format($global->date_format) }})</option>
                                @endforeach
                            </select>
                            <label for="dependent_task_id_project" class="control-label">@lang('modules.tasks.dependentTask')</label>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group form-label-group">
                            <input placeholder="-"  type="text" name="start_date" id="start_date2" class="form-control form-control-lg" autocomplete="off" value="@if($task->start_date != '-0001-11-30 00:00:00' && $task->start_date != null) {{ $task->start_date->format($global->date_format) }} @endif">
                            <label for="start_date2" class="required">@lang('app.startDate')</label>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group form-label-group">
                            <input placeholder="-"  type="text" name="due_date" id="due_date2" class="form-control form-control-lg" autocomplete="off" value="@if($task->due_date != '-0001-11-30 00:00:00') {{ $task->due_date->format($global->date_format) }} @endif">
                            <label for="due_date2" class="control-label required">@lang('app.dueDate')</label>
                        </div>
                    </div>
                    <!--/span-->

                    <div class="col-md-4">
                        <div class="form-group form-label-group">
                            <select placeholder="-"  class="select2  form-control form-control-lg" name="milestone_id" id="milestone_id"
                                    data-style="form-control">
                                <option value="">--</option>
                                @foreach($task->project->milestones as $milestone)
                                    <option
                                    @if($milestone->id == $task->milestone_id) selected @endif
                                        value="{{ $milestone->id }}">{{ $milestone->milestone_title }}</option>
                                @endforeach
                            </select>
                            <label for="milestone_id" class="control-label">@lang('modules.projects.milestones')</label>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group form-label-group">
                             <select placeholder="-"  class="select2  select2-multiple " multiple="multiple" data-placeholder="@lang('modules.tasks.chooseAssignee')"  name="user_id[]" id="user_id2">
                                @if(is_null($task->project_id))
                                    @foreach($employees as $employee)

                                        @php
                                            $selected = '';
                                        @endphp

                                        @foreach ($task->users as $item)
                                            @if($item->id == $employee->id)
                                                @php
                                                    $selected = 'selected';
                                                @endphp
                                            @endif

                                        @endforeach

                                        <option {{ $selected }}
                                                value="{{ $employee->id }}">{{ ucwords($employee->name) }}
                                        </option>

                                    @endforeach
                                @else
                                    @foreach($task->project->members as $member)
                                        @php
                                            $selected = '';
                                        @endphp

                                        @foreach ($task->users as $item)
                                            @if($item->id == $member->user->id)
                                                @php
                                                    $selected = 'selected';
                                                @endphp
                                            @endif

                                        @endforeach

                                        <option {{ $selected }}
                                            value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="user_id2" class="control-label select-required required">@lang('modules.tasks.assignTo')</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form-label-group">
                            <select placeholder="-"  name="status" id="status" class="select2  form-control form-control-lg hide-search">
                                @foreach($taskBoardColumns as $taskBoardColumn)
                                    <option @if($task->board_column_id == $taskBoardColumn->id) selected @endif value="{{$taskBoardColumn->id}}">{{ $taskBoardColumn->column_name }}</option>
                                @endforeach
                            </select>
                            <label for="status">@lang('app.status')</label>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-12 control-label">@lang('modules.tasks.priority')</label>

                            <div class="col-4 radio radio-danger">
                                <input type="radio" name="priority" id="radio13"
                                       @if($task->priority == 'high') checked @endif
                                       value="high">
                                <label for="radio13" class="text-danger">
                                    @lang('modules.tasks.high') </label>
                            </div>
                            <div class="col-4 radio radio-warning">
                                <input type="radio" name="priority"
                                       @if($task->priority == 'medium') checked @endif
                                       id="radio14" value="medium">
                                <label for="radio14" class="text-warning">
                                    @lang('modules.tasks.medium') </label>
                            </div>
                            <div class="col-4 radio radio-success">
                                <input type="radio" name="priority" id="radio15"
                                       @if($task->priority == 'low') checked @endif
                                       value="low">
                                <label for="radio15" class="text-success">
                                    @lang('modules.tasks.low') </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group form-label-group">
                            <select placeholder="-"  class="select2  form-control form-control-lg" name="category_id" id="category_id"
                                    data-style="form-control">
                                @forelse($categories as $category)
                                    <option value="{{ $category->id }}"  @if($task->task_category_id == $category->id) selected @endif >{{ ucwords($category->category_name) }}</option>
                                @empty
                                    <option value="">@lang('messages.noTaskCategoryAdded')</option>
                                @endforelse
                            </select>
                            <label for="category_id" class="control-label">@lang('modules.tasks.taskCategory')  </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <a
                                    href="javascript:;" id="createTaskCategory"
                                    class="btn btn-sm btn-secondary"><i
                                        class="fa fa-plus"></i> @lang('modules.taskCategory.addTaskCategory')</a>

                        </div>
                    </div>

                </div>
                <!--/row-->

            </div>


    </div>
    <div class="card-footer">
        <div class="form-actions row">
            <button type="button" id="update-task" class="btn btn-primary col-md-3 offset-md-9">@lang('app.save')</button>
        </div>
    </div>
    {!! Form::close() !!}

</div>

<script>

    $(".select2").select2({
       formatNoMatches: function () {
           return "{{ __('messages.noRecordFound') }}";
       }
   });

   $('.hide-search').select2({
        minimumResultsForSearch: -1
    });

    //    update task

    //    update task
    $('#update-task').click(function () {

        var status = '{{ $task->board_column->slug }}';
        var currentStatus =  $('#status').val();

        if(status == 'incomplete' && currentStatus == 'completed'){

            $.easyAjax({
                url: '{{route('admin.tasks.checkTask', [$task->id])}}',
                type: "GET",
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
                                updateTask();
                            }
                        });
                    }
                    else{
                        updateTask();
                    }

                }
            });
        }
        else{
            updateTask();
        }

    });

    function updateTask(){
        $.easyAjax({
            url: '{{route('admin.tasks.update', [$task->id])}}',
            container: '#updateTask',
            type: "POST",
            data: $('#updateTask').serialize(),
            success: function (data) {
                $('#task-list-panel ul.list-group').html(data.html);

                $('#edit-task-panel').switchClass("show", "hide", 300, "easeInOutQuad");
                showTable();
            }
        })
    }

    jQuery('#due_date2, #start_date2').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });

    /* $('.summernote').summernote({
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
    }); */

    $('#dependent-task-project').change(function () {
        if($(this).is(':checked')){
            $('#dependent-fields-project').show();
        }
        else{
            $('#dependent-fields-project').hide();
        }
    })
</script>
