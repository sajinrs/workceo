<li class="top-notifications">
    <div class="message-center">
        <a href="javascript:;" class="show-all-notifications">
            <div class="user-img">
                <span class="btn btn-circle btn-warning"><i class="fas fa-money-bill-wave-alt"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">New Expense </span> <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>
