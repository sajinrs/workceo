    
<div class="media">
    <div class="media-body">
        <h6 class="mt-0 mb-0"><span class="badge badge-primary"><i class="fas fa-money-bill-wave-alt"></i> </span>@lang('email.invoices.paymentReceived') {{ $notification->data['original_invoice_number'] }}</h6>
        <small class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</small>
    </div>
</div>
