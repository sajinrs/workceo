<div class="media">
    <div class="media-body">
        <h6 class="mt-0 mb-0"><span class="badge badge-primary"><i class="fa fa-exclamation-triangle"></i> </span>New Project Issue Reported</h6>
        <small class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</small>
        <p class="mb-0 m-l-40">{{ ucfirst($notification->data['description']) }} </p>
    </div>
</div>