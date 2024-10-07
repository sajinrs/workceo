<li>
    <div class="media">
        <div class="media-body">
            <a href="javascript:;" class="show-all-notifications">            
                <h6 class="mt-0 mb-0 txt-dark"><span class="badge badge-primary"> <i class="fas fa-money-bill-wave-alt"></i></span>
                    @lang('app.new') @lang('app.invoice') -
                        @if(isset($notification->data['project']['project_name']))
                            @lang('app.project') {{ ucwords($notification->data['project']['project_name']) }}
                        @elseif(isset($notification->data['project_name']))
                            @lang('app.project') {{ ucwords($notification->data['project_name']) }}
                        @elseif(isset($notification->data['invoice_number']))
                            {{ $notification->data['invoice_number'] }}
                        @else
                            @lang('messages.newInvoiceCreated')
                        @endif
                    </h6>
                <small class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</small>
            </a>
        </div>
    </div>
</li>


