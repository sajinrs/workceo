<div class="modal-header">
    <h5 class="modal-title"><i class="fa fa-search"></i>  @lang('modules.payments.paymentDetails')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <div class="form-body">
        <div class="row">
            <div class="col-md-12">
                @forelse($invoice->payment as $payment)
                    <div class="list-group-item edit-task">
                        <h5 class="list-group-item-heading sbold">@lang('app.paymentOn'): {{ $payment->paid_on->format($global->date_format) }}</h5>
                        <p class="list-group-item-text">
                        <div class="row margin-top-5">
                            <div class="col-md-4">
                                <b>@lang('app.amount'):</b>  <br>
{{$invoice->currency->currency_symbol}} {{$payment->amount}}
                            </div>
                            <div class="col-md-4">
                                <b>@lang('app.gateway'):</b>  <br>
                                {{$payment->gateway}}
                            </div>
                            <div class="col-md-4">
                                <b>@lang('app.transactionId'):</b> <br>
                                {{$payment->transaction_id}}
                            </div>
                        </div>
                        <div class="row margin-top-5">
                            <div class="col-md-12">
                                <b>@lang('app.remark'):</b>  <br>
                                {!!  ($payment->remarks != '') ? ucfirst($payment->remarks) : "<span class='font-red'>--</span>" !!}
                            </div>
                        </div>

                        </p>
                    </div>
                @empty
                    <p>@lang('modules.payments.paymentDetailNotFound')</p>
                @endforelse
            </div>
        </div>
        <!--/row-->
    </div>
</div>
<div class="modal-footer">
    <!--<button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">@lang('app.close')</button>-->
    <button type="button" class="btn btn-outline-primary gray waves-effect" data-dismiss="modal">@lang('app.close')</button>
</div>


