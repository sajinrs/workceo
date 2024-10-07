<li>
    <div class="media">
        <div class="media-body">
        <a href="javascript:;" class="show-all-notifications">
        <h6 class="mt-0 mb-0 txt-dark"><span class="badge badge-primary"> <i class="fas fa-list-alt"></i></span>{{ ucfirst($notification->data['heading']) }} - @lang('email.taskComplete.subject')!
        </h6>
        <small class="time">@if($notification->completed_on) {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->completed_on)->diffForHumans() }} @endif</small>
        </a>
        </div>
    </div>
</li>



