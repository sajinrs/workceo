<div class="media">
    <div class="media-body">
        <h6 class="mt-0 mb-0"><span class="badge badge-primary"><i class="fas fa-money-bill-wave-alt"></i> </span>New expense "{{ $notification->data['item_name'] }}" added</h6>
        <small class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</small>
        <p class="mb-0 m-l-40"> Item price is {{ $notification->data['price'] }}</p>
    </div>
</div>

