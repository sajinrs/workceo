
<li>
    <div class="media">
        <div class="media-body">
        <a href="javascript:;" class="show-all-notifications">
        <h6 class="mt-0 mb-0 txt-dark"><span class="badge badge-primary"> <i class="fas fa-list-alt"></i></span>@lang('email.taskComment.subject') - {{ ucfirst($notification->data['heading']) }} !
        </h6>
        <small class="time">@if($notification->created_at){{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}@endif</small>
        </a>
        </div>
    </div>
</li>









