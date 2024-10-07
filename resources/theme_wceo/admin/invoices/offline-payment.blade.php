<div class="modal-header">
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><i class="fa fa-times-circle"></i></button>
    <h5>{{$company_name}}</h5>
    <h5>{{$project->project_name}}</h5>
    <h5>{{ $invoice->invoice_number }}</h5>                  
</div>

{!! Form::open(['id'=>'saveDetail','class'=>'ajax-form','method'=>'POST']) !!}
<div class="modal-body">
    <div class="form-group m-form__group">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-envelope"></i></span></div>
            <input class="form-control form-mail" type="text" name="email" placeholder="Email*">
        </div>
    </div>

    @if($methods->count() > 0)
    <div class="form-group pay-mode">
        <div class="radio-list">
            @foreach($methods as $key => $method)
            <label class="radio-inline">
                <div class="radio radio-info">
                    <input type="radio" name="offline_id" @if($key == 0) checked @endif id="paymode_{{$key}}" value="{{ $method->id }}" />
                    <label title="{{ucfirst($method->name)}}" for="paymode_1">{{ str_limit(ucfirst($method->name),9) }}</label>
                </div>
            </label>
            @endforeach
        </div>
    </div>
    @else
        <div class="form-group"><a href="{{ route('admin.offline-payment-setting.index') }}">Add offline payment method</a> </div>
    @endif

    <div class="form-group">
        <textarea class="form-control" name="description" placeholder="Payment Note*"></textarea>
    </div>

    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
    <input type="hidden" name="client_id" value="{{ $project->client_id }}">

    @if($methods->count() > 0)
    <div class="form-group">
        <button type="button" onclick="saveDetails(); return false;" class="offline-btn">Pay {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $invoice->total }}</button>
    </div>
    @endif
</div>
{{ Form::close() }}

<script>
    function saveDetails()
    {
        $.easyAjax({
            url: '{{ route('admin.invoices.offline-payment-submit') }}',
            type: "POST",
            container:'#saveDetail',
            messagePosition:'inline',
            file:true,
        })
    }

</script>