<div class="card">
    <div class="card-header">
        <div class="h5"><i class="ti-pencil"></i> @lang('app.task') 
            <div class="card-header-right p-0">
                <ul class="list-unstyled card-option">
                    <li id="hide-edit-task-panel" ><i class="icofont icofont-error"></i></li>
                </ul>
            </div>
        </div>
    </div>

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

                <div class="col-12" id="comment-container">
                
                    <h4>@lang('modules.tasks.comment')</h4> <br />
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
                <!--/span-->
            </div>
            <!--/row-->

        </div>
        <div class="form-actions">
        </div>
    </div>
   
</div>

<script>
$(".select2").select2({
       formatNoMatches: function () {
           return "{{ __('messages.noRecordFound') }}";
       }
   });
    $('#hide-edit-task-panel').click(function () {
       $('#right_side_bar').hide();
    });
</script>
<script>
     $("#dependent_task_id_project, #user_id2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('#submit-comment').click(function () {
        var comment = $('#task-comment').val();
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            url: '{{ route("client.task-comment.store") }}',
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

        var url = '{{ route("client.task-comment.destroy", ':id') }}';
        url = url.replace(':id', commentId);

        $.easyAjax({
            url: url,
            type: "POST",
            data: {'_token': token, '_method': 'DELETE', commentId: commentId},
            success: function (response) {
                if (response.status == "success") {
                    $('#comment-list').html(response.view);
                }
            }
        })
    }   


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
