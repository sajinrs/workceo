<div class="media">
    <div class="media-body">
        <h6 class="mt-0 mb-0"><span class="badge badge-primary"><i class="fas icon-clock"></i> </span>Timer Started for Project - {{ ucwords($notification->data['project']['project_name']) }}</h6>
        <small class="time">@if($notification->created_at){{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }} @endif</small>
    </div>
</div>



