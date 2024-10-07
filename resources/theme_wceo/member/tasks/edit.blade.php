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
{{--    {{ dd($task) }}--}}
@if(!is_null($task->project) && $task->project->isProjectAdmin || $user->can('edit_projects'))
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
                                <textarea id="description" name="description" class="form-control summernote">{{ $task->description }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-label-group">
                                <input placeholder="-"  type="text" name="start_date" id="start_date2" class="form-control form-control-lg" autocomplete="off" value="@if($task->start_date != '-0001-11-30 00:00:00' && $task->start_date != null) {{ $task->start_date->format($global->date_format) }} @endif">
                                <label for="start_date2" class="required">@lang('app.startDate')</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-label-group">
                                <input placeholder="-"  type="text" name="due_date" id="due_date2" class="form-control form-control-lg" autocomplete="off" value="@if($task->due_date != '-0001-11-30 00:00:00') {{ $task->due_date->format($global->date_format) }} @endif">
                                <label for="due_date2" class="control-label required">@lang('app.dueDate')</label>
                            </div>
                        </div>

                        
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="form-group form-label-group">
                                <select class="select2 select2-multiple " multiple="multiple" data-placeholder="@lang('modules.tasks.chooseAssignee')"  name="user_id[]" id="user_id2">
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
                                <label class="control-label select-required required">@lang('modules.tasks.assignTo')</label>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="form-group form-label-group">
                                <select class="select2  form-control form-control-lg" name="category_id" id="category_id"
                                        data-style="form-control">
                                    @forelse($categories as $category)
                                        <option value="{{ $category->id }}"
                                                @if($task->task_category_id == $category->id)
                                                selected
                                                @endif
                                        >{{ ucwords($category->category_name) }}</option>
                                    @empty
                                        <option value="">@lang('messages.noTaskCategoryAdded')</option>
                                    @endforelse
                                </select>
                                <label for="category_id" class="control-label">@lang('modules.tasks.taskCategory')  </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-label-group">
                                <select placeholder="-"  name="status" id="status" class="select2  form-control form-control-lg">
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

                        
                        
                        <!--/span-->
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

    @else
            <div class="card-body">
                <div class="form-body user-profile">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="ttl-info text-left ttl-border">
                                <h6>@lang('app.title')</h6>
                                <span>{{ ucfirst($task->heading) }}</span>
                            </div>
                        </div>
                       
                        <!--/span-->
                        
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="ttl-info text-left ttl-border">
                                <h6>@lang('app.dueDate')</h6>
                                <span>  {{  $task->due_date->format('d-M-Y')  }} </span>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="ttl-info text-left ttl-border">
                                <h6>@lang('modules.tasks.assignTo')</h6>
                                <span>
                                    @foreach ($task->users as $item)
                                        <img src="{{ $item->image_url }}" data-toggle="tooltip"
                                            data-original-title="{{ ucwords($item->name) }}" data-placement="right"
                                            class="img-circle" width="25" height="25" alt="">
                                    @endforeach
                                </span>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="ttl-info text-left ttl-border">
                                <h6>@lang('modules.tasks.priority')</h6>
                                    <label for="radio13" class="text-@if($task->priority == 'high')danger @elseif($task->priority == 'medium')warning @else success @endif ">
                                        @if($task->priority == 'high') @lang('modules.tasks.high') @elseif($task->priority == 'medium') @lang('modules.tasks.medium') @else @lang('modules.tasks.low') @endif</label>

                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="ttl-info text-left ttl-border">
                                <h6>@lang('app.status')</h6>
                                    <label for="radio13"  style="color: {{ $task->board_column->label_color }};"> {{ $task->board_column->column_name }}</label>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="ttl-info text-left ttl-border">
                                <h6>@lang('app.description')</h6>
                                <span>  {!! ucfirst($task->description) !!} </span>
                            </div>
                        </div>  
                        <!--/span-->
                    </div>
                    <!--/row-->

                </div>
                <div class="form-actions">
                </div>
            </div>
    @endif
</div>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script>
$(".select2").select2({
       formatNoMatches: function () {
           return "{{ __('messages.noRecordFound') }}";
       }
   });
    $('#hide-edit-task-panel').click(function () {
        newTaskpanel.addClass('hide').removeClass('show');
        taskListPanel.switchClass("col-md-6", "col-md-12", 1000, "easeInOutQuad");
    });
</script>
<script>
     $("#dependent_task_id_project, #user_id2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    
    //    update task
    $('#update-task').click(function () {

        var status = '{{ $task->board_column->slug }}';
        var currentStatus =  $('#status').val();

        if(status == 'incomplete' && currentStatus == 'completed'){

            $.easyAjax({
                url: '{{route('member.tasks.checkTask', [$task->id])}}',
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
            url: '{{route('member.tasks.update', [$task->id])}}',
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

    

    jQuery('#due_date2 , #start_date2').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
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


</script>
