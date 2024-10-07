@extends('layouts.app')

@push('head-script')

<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap-datetimepicker.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.addNew')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->

            <div class="col-sm-12">
                <div class="card">                   
                    <div class="card-header">
                        <h4 class="card-title mb-0">{{ $invoice->invoice_number }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($invoice->project_id)
                                <div class="col-md-12">
                                    <div class="col-md-4 form-group  p-l-0">
                                        <label>@lang('app.project')</label>
                                        <h6>{{ $invoice->project->project_name }}</h6>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>@lang('modules.invoices.amount')</label>
                                    <h5 class="form-control-static">{{ $invoice->currency->currency_symbol.' '.currencyFormat($invoice->total) }}</h5>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>@lang('modules.invoices.paid')</label>
                                    <h5 class="form-control-static">{{ $invoice->currency->currency_symbol}} {{ currencyFormat($paidAmount) }}</h5>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>@lang('modules.invoices.due')</label>
                                    <h5 class="form-control-static">{{ $invoice->currency->currency_symbol}} {{ max((currencyFormat($invoice->total-$paidAmount)),0) }}</h5>
                                </div>
                            </div>
                            </div>

                                <div class="col-md-12">
                                    <div class="pull-right job-payment m-t-20">
                                       
                                        
                                        @if($invoice->status == 'unpaid' && ($credentials->paypal_status == 'active' || $credentials->stripe_status == 'active'))
                                        <a class="btn btn-sm btn-primary waves-effect" onclick="offlinePayment({{$invoice->id}}); return false;">Add Offline Payment</a>

                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-primary dropdown-toggle" id="btnGroupVerticalDrop3" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="credit-card"></i> Add Card Payment</button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop3">
                                                @if($credentials->stripe_status == 'active')
                                                    <a class="dropdown-item stripePaymentButton" href="javascript:void(0);" data-amount="{{$invoice->total}}" data-invoice-id="{{$invoice->id}}"><i class="fa fa-credit-card"></i> @lang('modules.invoices.payStripe') </a>
                                                @endif

                                                @if($credentials->paypal_status == 'active' && !empty($credentials->paypal_client_id && $credentials->paypal_secret) )
                                                    <a class="dropdown-item" href="{{ route('client.paypal-public', [$invoice->id]) }}"><i class="fa fa-credit-card"></i> @lang('modules.invoices.payPaypal') </a>
                                                @endif                                                

                                                @if($credentials->razorpay_status == 'active')
                                                    <a class="dropdown-item razorpayPaymentButton" href="javascript:void(0);" data-amount="{{$invoice->total}}" data-invoice-id="{{$invoice->id}}"><i class="fa fa-credit-card"></i>  @lang('modules.invoices.payRazorpay')  </a>
                                                @endif                                                            
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div> 
                        
                            </div>
                    </div>
                </div>
            </div>

            {{--<div class="col-sm-12">
                <div class="card">
                    {!! Form::open(['id'=>'createPayment','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.payments.addPayment')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                        {!! Form::hidden('invoice_id', $invoice->id) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                        
                                        <input type="number" placeholder="-" name="amount" id="amount" value="{{ max(($invoice->total-$paidAmount),0) }}" class="form-control form-control-lg">
                                        <label for="amount" class="control-label">@lang('modules.payments.amount')</label>
                                        <span class="help-block"> </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                      
                                        <input type="text" name="gateway" id="gateway"  placeholder="-" class="form-control form-control-lg">
                                        <label for="gateway" class="control-label">@lang('modules.payments.paymentGateway')</label>
                                        <span class="help-block"> Paypal, Authorize.net, Stripe, Bank Transfer, Cash or others.</span>
                                    </div>
                                </div>
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                     
                                        <input type="text" name="transaction_id" id="transaction_id" placeholder="-" class="form-control form-control-lg">    
                                        <label for="transaction_id" class="control-label">@lang('modules.payments.transactionId')</label>                                                         
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                       
                                        <input type="text" class="form-control form-control-lg" name="paid_on" id="paid_on" placeholder="-" value="{{ Carbon\Carbon::now($global->timezone)->format('d/m/Y H:i') }}">                                                                             
                                        <label for="paid_on" class="control-label">@lang('modules.payments.paidOn')</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group">                                      
                                        <textarea class="form-control form-control-lg" name="remarks" id="remark"></textarea>
                                        <label for="remark" class="control-label">@lang('modules.payments.remark')</label>
                                    </div>
                                </div>
                            </div>
                            
                            

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions col-md-3 offset-md-9">
                            <button type="submit" id="save-form-2" class="btn btn-primary form-control">@lang('app.save')</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>--}}
        </div>
    </div>

<!--Offline Payment-->
<div class="modal fade bs-modal-md in" id="offlinePayment" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" id="modal-data-application">
        <div class="modal-content">
            <div class="modal-body">
                Loading....
            </div>
            
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection


@push('footer-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js') }}"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());

    });   

    jQuery('#paid_on').datetimepicker({
        format: 'D/M/Y HH:mm',
        icons: {
            time: 'fa fa-clock',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-check',
            clear: 'fa fa-trash',
            close: 'fa fa-times'
        },
    });

    $('#save-form-2').click(function () {
        $.easyAjax({
            url: '{{route('admin.payments.store')}}',
            container: '#createPayment',
            type: "POST",
            redirect: true,
            data: $('#createPayment').serialize()
        })
    });
</script>

<script>
@if($credentials->stripe_status == 'active')
    var handler = StripeCheckout.configure({
            key: '{{ $credentials->stripe_client_id }}',
            image: '{{ $global->logo_url }}',
            locale: 'auto',
            token: function(token) {
                // You can access the token ID with `token.id`.
                // Get the token ID to your server-side code for use.
               
                $.easyAjax({
                    url: '{{route('client.stripe', [$invoice->id])}}',
                    container: '#invoice_container',
                    type: "POST",
                    redirect: true,
                    data: {token: token, "_token" : "{{ csrf_token() }}"}
                })
            }
        });

    $('.stripePaymentButton').click(function(e){
        
        // Open Checkout with further options:
        handler.open({
            name: '{{ $companyName }}',
            amount: {{ $invoice->total*100 }},
            currency: '{{ $invoice->currency->currency_code }}',
            email: ""

        });
        e.preventDefault();
    });

    // Close Checkout on page navigation:
    window.addEventListener('popstate', function() {
        handler.close();
    });



    @endif

    @if($credentials->razorpay_status == 'active')
        $('#razorpayPaymentButton').click(function() {
            console.log('{{ $invoice->currency->currency_code }}');
            var amount = {{ $invoice->total*100 }};
            var invoiceId = {{ $invoice->id }};
            /* var amount = {{ $invoice->total*100 }};
            var invoiceId = {{ $invoice->id }}; */
            var clientEmail = "";

            var options = {
                "key": "{{ $credentials->razorpay_key }}",
                "amount": amount,
                "currency": 'INR',
                "name": "{{ $companyName }}",
                "description": "Invoice Payment",
                "image": "{{ $global->logo_url }}",
                "handler": function (response) {
                    confirmRazorpayPayment(response.razorpay_payment_id,invoiceId,response);
                },
                "modal": {
                    "ondismiss": function () {
                        // On dismiss event
                    }
                },
                "prefill": {
                    "email": clientEmail
                },
                "notes": {
                    "purchase_id": invoiceId //invoice ID
                }
            };
            var rzp1 = new Razorpay(options);

            rzp1.open();

        })

        //Confirmation after transaction
        function confirmRazorpayPayment(id,invoiceId,rData) {
            $.easyAjax({
                type:'POST',
                url:'{{route('public.pay-with-razorpay')}}',
                data: {paymentId: id,invoiceId: invoiceId,rData: rData,_token:'{{csrf_token()}}'}
            })
        }

    @endif

    // Show offline method detail
    function showDetail(id){
        var detail = $('#method-desc-'+id).html();
        $('#methodDetail').html(detail);
        $('#methodDetail').show();
    }

    function offlinePayment(id)
    {
        var url = '{{ route('admin.invoices.offline-payment', ':id')}}';
        url = url.replace(':id', id);
        $.ajaxModal('#offlinePayment', url);   
    }
    
</script>
@endpush
