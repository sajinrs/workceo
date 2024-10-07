
<div class="media">
    <div class="media-body">
        <h6 class="mt-0 mb-0"><span class="badge badge-primary"><i class="fas fa-money-bill-wave-alt"></i> </span>@lang('email.expenseStatus.text') {{ $notification->data['status'] }}</h6>
        <small class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</small>
        <p class="mb-0 m-l-40"> Your expense "{{ ucfirst($notification->data['item_name']) }}" status updated to  {{ $notification->data['status'] }}</p>
    </div>
</div>
