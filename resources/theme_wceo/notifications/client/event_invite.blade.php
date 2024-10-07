<li>
    <div class="media">
        <div class="media-body">
        <a href="javascript:;" class="show-all-notifications">
        <h6 class="mt-0 mb-0 txt-dark"><span class="badge badge-primary"> <i class="fas icon-clock"></i></span>New event on {{ \Carbon\Carbon::parse($notification->data['start_date_time'])->format('d M, Y') }}
        </h6>
        <small class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</small>
        </a>
        </div>
    </div>
</li>




