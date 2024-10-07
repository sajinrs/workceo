

<li>
    <span style="display: flex;padding: 0 0 6px;font-weight: 600;">{{$notification->data['heading']}}</span>
    <div class="media">
        <div class="media-body">
        <a href="javascript:;" data-notice-id="{{$notification->data['id']}}" class="show-all-notifications2 noticeShow">
        <h6 class="mt-0 mb-0 txt-dark"><span class="badge badge-primary"> <i class="{{$notification->data['icon']}}"></i></span> New notice published.
        </h6>
        <small class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</small>
        </a>
        </div>
    </div>
    <?php //print_r($notification); ?>
</li>










