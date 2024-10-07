
<div class="media">
    <div class="media-body">
        <h6 class="mt-0 mb-0"><span class="badge badge-primary"><i class="fa fa-exclamation-triangle"></i> </span>New event {{ $notification->data['event_name'] }} on {{ \Carbon\Carbon::parse($notification->data['start_date_time'])->format('d M, Y') }}.</h6>
        <small class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</small>
    </div>
</div>

