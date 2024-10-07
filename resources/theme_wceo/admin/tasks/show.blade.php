<div class="container p-0">
    <div class="modal-header p-l-20 p-r-20">
        <div class="col-sm-8 p-0">
            <h6 class="modal-title font-weight-bold">@lang('app.task')</h6>
        </div>
        <div class="col-sm-4 text-right p-0"><a class="right_side_toggle" href="javascript:void(0);"><i class="fa fa-times" ></i></a></div>
    </div>
    <div class="modal-body">

                <div class="col-md-12">
                    <a href="{{route('admin.all-tasks.edit',$task->id)}}" class="btn btn-info btn-sm m-b-10 btn-rounded btn-outline pull-right m-l-5"> <i class="fa fa-edit"></i> Edit</a>
                    <a href="javascript:;" id="completedButton" class="btn btn-secondary btn-sm m-b-10 btn-rounded btn-outline @if($task->board_column->slug == 'completed') d-none @endif "  onclick="markComplete('completed')" ><i class="fa fa-check"></i> @lang('modules.tasks.markComplete')</a>
                    <a href="javascript:;" id="inCompletedButton" class="btn btn-danger btn-outline btn-sm m-b-10 btn-rounded @if($task->board_column->slug != 'completed') d-none @endif"  onclick="markComplete('incomplete')"><i class="fa fa-times"></i> @lang('modules.tasks.markIncomplete')</a>
                    <a href="javascript:;" id="reminderButton" class="btn btn-info btn-sm m-b-10 btn-rounded btn-outline pull-right @if($task->board_column->slug == 'completed') d-none @endif" title="@lang('messages.remindToAssignedEmployee')"><i class="fa fa-envelope"></i> @lang('modules.tasks.reminder')</a>
                </div>
                <div class="col-md-12 m-t-40">
                    <h5>{{ ucwords($task->heading) }}
                        @if($task->task_category_id)
                            <label class="badge badge-primary m-l-5 f-10 font-weight-light">{{ ucwords($task->category->category_name) }}</label>
                        @endif

                        <label class="m-l-5 f-10 font-weight-light badge
                    @if($task->priority == 'high')
                                badge-danger @elseif($task->priority == 'medium') badge-warning @else badge-success @endif ">
                            <span>@lang('modules.tasks.priority') ></span>  {{ ucfirst($task->priority) }}
                        </label>


                    </h5>
                    @if(!is_null($task->project_id))
                        <h6 class="m-t-20"><i class="icon-layers"></i> {{ ucfirst($task->project->project_name) }}</h6>
                    @endif
                </div>

               <ul class="customtab-sm nav nav-tabs border-tab mb-0 taskTabs" id="top-tab" role="tablist">


                    <li  class="nav-item active"><a  class="nav-link active" href="#home1" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true">@lang('app.task')</a></li>
                    <li  class="nav-item"><a class="nav-link" href="#profile1" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false">@lang('modules.tasks.subTask')({{ count($task->subtasks) }})</a></li>
                    <li  class="nav-item"><a class="nav-link" href="#messages1" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false">@lang('app.file') ({{ sizeof($task->files) }})</a></li>
                    <li class="nav-item"><a class="nav-link" href="#settings1" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false">@lang('modules.tasks.comment') ({{ count($task->comments) }})</a></li>
                    <li  class="nav-item pull-right">  <a href="javascript:;" id="view-task-history" data-task-id="{{ $task->id }}" class="nav-link pull-right text-muted font-12 btn btn-sm btn-default btn-outline"> <i class="fa fa-history"></i> <span class="d-xs-none">@lang('app.view') @lang('modules.tasks.history')</span></a></li>
                    <li  class="nav-item pull-right" >  <a href="javascript:;" class="nav-link close-task-history pull-right text-muted" style="display:none"><span class="d-xs-none">@lang('app.close') @lang('modules.tasks.history')</span> <i class="fa fa-times"></i></a></li>



                </ul>

                <div class="tab-content" id="task-detail-section">
                    <div class="tab-pane fade active show" id="home1" role="tabpanel" aria-labelledby="top-home-tab">
                        <div class="col-12 m-t-20">
                            <div class="row">
                                <div class="col-6 col-md-3 font-12">
                                    <label class="font-12" for="">@lang('modules.tasks.assignTo')</label><br>
                                    @foreach ($task->users as $item)
                                        <img src="{{ $item->image_url }}" data-toggle="tooltip"
                                             data-original-title="{{ ucwords($item->name) }}" data-placement="right"
                                             class="img-circle" width="25" height="25" alt="">
                                    @endforeach
                                </div>
                                @if($task->create_by)
                                    <div class="col-6 col-md-3 font-12">
                                        <label class="font-12" for="">@lang('modules.tasks.assignBy')</label><br>
                                        <img src="{{ $task->create_by->image_url }}" class="img-circle" width="25" height="25" alt="">

                                        {{ ucwords($task->create_by->name) }}
                                    </div>
                                @endif

                                @if($task->start_date)
                                    <div class="col-6 col-md-3 font-12">
                                        <label class="font-12" for="">@lang('app.startDate')</label><br>
                                        <span class="text-success" >{{ $task->start_date->format($global->date_format) }}</span><br>
                                    </div>
                                @endif
                                <div class="col-6 col-md-3 font-12">
                                    <label class="font-12" for="">@lang('app.dueDate')</label><br>
                                    <span @if($task->due_date->isPast()) class="text-danger" @endif>
                                    {{ $task->due_date->format($global->date_format) }}
                                </span>
                                    <span style="color: {{ $task->board_column->label_color }}" id="columnStatus"> {{ $task->board_column->column_name }}</span>

                                </div>
                                <div class="col-12 task-description b-all p-10 m-15 m-t-20">
                                    {!! ucfirst($task->description) !!}
                                </div>

                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="profile1">
                        <div class="col-12 m-t-20">
                            <div class="f-16 m-b-20"><i class="fa fa-check-square"></i> @lang('modules.tasks.subTask')
                                @if (count($task->subtasks) > 0)
                                    <span class="pull-right"><span class="donut" data-peity='{ "fill": ["#00c292", "#eeeeee"],    "innerRadius": 5, "radius": 8 }'>{{ count($task->completedSubtasks) }}/{{ count($task->subtasks) }}</span> <span class="text-muted font-12">{{ floor((count($task->completedSubtasks)/count($task->subtasks))*100) }}%</span></span>
                                @endif
                            </div>
                            <ul class="list-group b-t" id="sub-task-list">
                                @foreach($task->subtasks as $subtask)
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-9">
                                                <div class="checkbox checkbox-success checkbox-circle task-checkbox">
                                                    <input class="task-check" data-sub-task-id="{{ $subtask->id }}" id="checkbox{{ $subtask->id }}" type="checkbox"
                                                           @if($subtask->status == 'complete') checked @endif>
                                                    <label for="checkbox{{ $subtask->id }}">&nbsp;</label>
                                                    <span>{{ ucfirst($subtask->title) }}</span>
                                                </div>
                                                @if($subtask->due_date)<span class="text-muted m-l-5"> - @lang('modules.invoices.due'): {{ $subtask->due_date->format($global->date_format) }}</span>@endif
                                            </div>

                                            <div class="col-3 text-right">
                                                <a href="javascript:;" data-sub-task-id="{{ $subtask->id }}" class="edit-sub-task"><i class="fa fa-pen"></i></a>&nbsp;
                                                <a href="javascript:;" data-sub-task-id="{{ $subtask->id }}" class="delete-sub-task"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </div>

                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-12 m-t-20 m-b-10">
                            <a href="javascript:;"  data-task-id="{{ $task->id }}" class="add-sub-task"><i class="icon-plus"></i> @lang('app.add') @lang('modules.tasks.subTask')</a>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="messages1">
                        <div class="col-12 m-t-20">
                            <a href="javascript:;" id="uploadedFiles" class="btn btn-primary btn-sm btn-rounded btn-outline"><i class="fa fa-image"></i> @lang('modules.tasks.uplodedFiles') (<span id="totalUploadedFiles">{{ sizeof($task->files) }}</span>) </a>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="settings1">
                        <div class="col-12  m-t-20 m-b-20">
                            <h5>@lang('modules.tasks.comment')</h5>
                        </div>

                        <div class="col-12" id="comment-container">
                            <div class="upcoming-event"  id="comment-list">
                                @forelse($task->comments as $comment)
                                    <div class="upcoming-innner media">
                                        <div class="bg-primary left m-r-20">
                                            <img width="50" height="50" class="rounded-circle float-right chat-user-img" src="{{ ucwords($comment->user->image_url) }}" alt="" data-original-title="" title="">
                                        </div>
                                        <div class="media-body">
                                            <p class="mb-0"><span class="pull-right">{{ ucfirst($comment->created_at->diffForHumans()) }}</span></p>
                                            <h6 class="f-w-600 f-14">{{ ucwords($comment->user->name) }}</h6>
                                            <p class="mb-0">{!! ucfirst($comment->comment)  !!}</p>
                                            <p class="mb-0"><span class="pull-right">     <a href="javascript:;" data-comment-id="{{ $comment->id }}" class="text-danger" onclick="deleteComment('{{ $comment->id }}');return false;">@lang('app.delete')</a>
                                      </span></p>

                                        </div>
                                    </div>
                                @empty
                                    <div class="col-xs-12">
                                        @lang('messages.noCommentFound')
                                    </div>
                                @endforelse
                            </div>

                        </div>

                        <div class="col-12 m-t-20" id="comment-box">
                            <div class="form-group ">
                                <textarea name="comment" id="task-comment" class="summernote form-control" placeholder="@lang('modules.tasks.comment')"></textarea>
                            </div>
                            <div class="form-group">
                                <a href="javascript:;" id="submit-comment" class="btn btn-primary btn-sm"><i class="fa fa-send"></i> @lang('app.submit')</a>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="col-md-12" id="task-history-section">
                </div>

    </div>

</div>

<div class="rpanel-title">  <span><i class="ti-close right-side-toggle"></i></span> </div>
<div class="r-panel-body">



</div>
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.custom.js')}}"></script>
<script>

    $('#uploadedFiles').click(function () {

        var url = '{{ route("admin.all-tasks.show-files", ':id') }}';

        var id = {{ $task->id }};
        url = url.replace(':id', id);

        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    });
    $('body').on('click', '.edit-sub-task', function () {
        var id = $(this).data('sub-task-id');
        var url = '{{ route('admin.sub-task.edit', ':id')}}';
        url = url.replace(':id', id);

        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    })

    $('.add-sub-task').click(function () {
        var id = $(this).data('task-id');
        var url = '{{ route('admin.sub-task.create')}}?task_id='+id;

        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    })

    $('#reminderButton').click(function () {
        swal({
            title: "Are you sure?",
            text: "Do you want to send reminder to assigned employee?",
            dangerMode: true,
            icon: 'warning',
            buttons: {
                cancel: "No, cancel please!",
                confirm: {
                    text: "Yes, send it!",
                    value: true,
                    visible: true,
                    className: "danger",
                }
            }
        }).then( function (isConfirm) {
            if (isConfirm) {

                var url = '{{ route('admin.all-tasks.reminder', $task->id)}}';

                $.easyAjax({
                    type: 'GET',
                    url: url,
                    success: function (response) {
                       //
                    }
                });
            }
        });
    })

    $('body').on('click', '.delete-sub-task', function () {
        var id = $(this).data('sub-task-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted sub task!",
            dangerMode: true,
            icon: 'warning',
            buttons: {
                cancel: "CANCEL",
                confirm: {
                    text: "DELETE",
                    value: true,
                    visible: true,
                    className: "danger",
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.sub-task.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $('#sub-task-list').html(response.view);
                        }
                    }
                });
            }
        });
    });

    $('#view-task-history').click(function () {
        var id = $(this).data('task-id');

        var url = '{{ route('admin.all-tasks.history', ':id')}}';
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            type: "GET",
            success: function (response) {
                $('#task-detail-section').hide();
                $('#task-history-section').html(response.view)
                $('#view-task-history').hide();
                $('.close-task-history').show();
            }
        })

    })

    $('.close-task-history').click(function () {
        $(this).hide();
        $('#task-detail-section').show();
        $('#view-task-history').show();
        $('#task-history-section').html('');
    })

    function saveSubTask() {
        $.easyAjax({
            url: '{{route('admin.sub-task.store')}}',
            container: '#createSubTask',
            type: "POST",
            data: $('#createSubTask').serialize(),
            success: function (response) {
                $('#subTaskModal').modal('hide');
                $('#sub-task-list').html(response.view)
            }
        })
    }

    function updateSubTask(id) {
        var url = '{{ route('admin.sub-task.update', ':id')}}';
            url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            container: '#createSubTask',
            type: "POST",
            data: $('#createSubTask').serialize(),
            success: function (response) {
                $('#subTaskModal').modal('hide');
                $('#sub-task-list').html(response.view)
            }
        })
    }

    $('.summernote').summernote({
        height: 100,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,                 // set focus to editable area after initializing summernote,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen", "codeview"]]
        ]
    });

    //    change sub task status
    $('#sub-task-list').on('click', '.task-check', function () {
        if ($(this).is(':checked')) {
            var status = 'complete';
        }else{
            var status = 'incomplete';
        }

        var id = $(this).data('sub-task-id');
        var url = "{{route('admin.sub-task.changeStatus')}}";
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            url: url,
            type: "POST",
            data: {'_token': token, subTaskId: id, status: status},
            success: function (response) {
                if (response.status == "success") {
                    $('#sub-task-list').html(response.view);
                }
            }
        })
    });

    $('#submit-comment').click(function () {
        var comment = $('#task-comment').val();
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            url: '{{ route("admin.task-comment.store") }}',
            type: "POST",
            data: {'_token': token, comment: comment, taskId: '{{ $task->id }}'},
            success: function (response) {
                if (response.status == "success") {
                    $('#comment-list').html(response.view);
                    $('.note-editable').html('');
                    $('#task-comment').val('');
                }
            }
        })
    })

    function deleteComment(id) {
        var commentId = id;
        var token = '{{ csrf_token() }}';

        var url = '{{ route("admin.task-comment.destroy", ':id') }}';
        url = url.replace(':id', commentId);

        $.easyAjax({
            url: url,
            type: "POST",
            container: '#comment-list',
            data: {'_token': token, '_method': 'DELETE', commentId: commentId},
            success: function (response) {
                if (response.status == "success") {
                    $('#comment-list').html(response.view);
                    $('.note-editable').html('');
                }
            }
        })
    }
    //    change task status
   function markComplete(status) {

        var id = '{{ $task->id }}';

        if(status == 'completed'){
            var checkUrl = '{{route('admin.tasks.checkTask', ':id')}}';
            checkUrl = checkUrl.replace(':id', id);
            $.easyAjax({
                url: checkUrl,
                type: "GET",
                container: '#task-list-panel',
                data: {},
                success: function (data) {
                    if(data.taskCount > 0){
                        swal({
                            title: "Are you sure?",
                            text: "There is a incomplete sub-task in this task do you want to mark complete!",
                            dangerMode: true,
                            icon: 'warning',
                            buttons: {
                                cancel: "No, cancel please!",
                                confirm: {
                                    text: "Yes, complete it!",
                                    value: true,
                                    visible: true,
                                    className: "danger",
                                }
                            }
                        }).then(function (isConfirm) {
                            if (isConfirm) {
                                updateTask(id,status)
                            }
                        });
                    }
                    else{
                        updateTask(id,status)
                    }

                }
            });
        }
        else{
            updateTask(id,status)
        }


    }

    // Update Task
    function updateTask(id,status){
        var url = "{{route('admin.tasks.changeStatus')}}";
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: url,
            type: "POST",
            container: '.r-panel-body',
            data: {'_token': token, taskId: id, status: status, sortBy: 'id'},
            success: function (data) {
                $('#columnStatus').css('color', data.textColor);
                $('#columnStatus').html(data.column);
                if(status == 'completed'){

                    $('#inCompletedButton').removeClass('d-none');
                    $('#completedButton').addClass('d-none');
                    $('#reminderButton').addClass('d-none');
                }
                else{
                    $('#completedButton').removeClass('d-none');
                    $('#inCompletedButton').addClass('d-none');
                    $('#reminderButton').removeClass('d-none');
                }

                if( typeof table !== 'undefined'){
                    window.LaravelDataTables["allTasks-table"].draw();
                }
                else if(typeof loadData !== 'undefined' && $.isFunction(loadData)){
                    loadData();
                }
            }
        })
    }



</script>
