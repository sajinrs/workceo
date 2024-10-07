
<div class="media">
    <div class="media-body">
        <h6 class="mt-0 mb-0"><span class="badge badge-primary"><i class="fa fa-exclamation-triangle"></i> </span>@if($notification->data['status'] == 'approved') @lang('email.leave.approve') @else @lang('email.leave.reject') @endif</h6>
        <small class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</small>
    </div>
</div>
