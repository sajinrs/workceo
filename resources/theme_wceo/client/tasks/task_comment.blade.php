@foreach($comments as $comment)
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
@endforeach