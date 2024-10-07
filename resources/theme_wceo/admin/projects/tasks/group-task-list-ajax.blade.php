
    @if($tasks)
        @foreach($tasks as $key=> $task)
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label required">Title</label>
                    <input type="text" name="title[]" class="form-control item_name" value="{{$task->title}}" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">Description</label>
                    <textarea id="description" name="description[]" rows="10"
                        class="form-control">{{$task->description}}</textarea>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label required">@lang('modules.projects.startDate')</label>
                    <input type="text" name="start_date[]" class="form-control date_picker" />
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label required">@lang('app.dueDate')</label>
                    <input type="text" name="due_date[]" class="form-control date_picker" />
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Priority</label>
                    <select name="priority[]" class="hide-search form-control">
                        <option value="high" @if($task->priority == 'high') selected @endif>High</option>
                        <option value="medium" @if($task->priority == 'medium') selected @endif>Medium</option>
                        <option value="low" @if($task->priority == 'low') selected @endif>Low</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label required">@lang('modules.tasks.assignTo')</label>
                    <select placeholder="-" class="select2 select2-multiple  form-control form-control-lg" multiple="multiple" data-placeholder="@lang('modules.tasks.chooseAssignee')"  name="user_id[{{$key}}][]">
                        <option value=""></option>
                        @foreach($project->members as $member)
                            <option value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>            
            
            <div class="col-md-12">
                <hr />
            </div>
        </div>

        @endforeach
    @endif
    

<script>
    jQuery('.date_picker').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });

    $('.select2-multiple').select2();
</script>