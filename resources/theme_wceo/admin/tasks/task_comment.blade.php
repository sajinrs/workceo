@forelse($comments as $comment)
    <div class="upcoming-innner media">
        <div class="bg-primary left m-r-20"><i data-feather="help-circle"></i></div>
        <div class="media-body">
            <p class="mb-0"><span class="pull-right">{{ ucfirst($comment->created_at->diffForHumans()) }}</span></p>
            <h6 class="f-w-600">{{ ucwords($comment->user->name) }}</h6>
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