<div class="media">
    <div class="media-body">
        <h6 class="mt-0 mb-0"><span class="badge badge-primary"><i class="fas icon-list"></i> </span>@lang('email.taskComment.subject') - {{ ucfirst($notification->data['heading']) }}</h6>
        <small class="time">@if($notification->created_at){{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }} @endif</small>
    </div>
</div>


