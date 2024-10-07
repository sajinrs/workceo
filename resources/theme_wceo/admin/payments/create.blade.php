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
                    {!! Form::open(['id'=>'createPayment','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.payments.addPayment')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                @if(in_array('projects', $modules))
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                        
                                        <select class="select2 form-control form-control-lg" name="client_id" id="client_id" data-placeholder="@lang('modules.projects.selectClient')">
                                            <option value=""></option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}">{{ ucwords($client->company_name) }}</option>
                                            @endforeach
                                        </select>
                                        <label for="client_id" class="col-form-label required">@lang('modules.projects.selectClient')</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                        
                                        <select class="select2 form-control-lg form-control" data-placeholder="@lang('app.selectProject')" placeholder="-" name="project_id" id="project_id">
                                            <option value=""></option>
                                            
                                        </select>
                                        <label for="project_id" class="control-label required">@lang('app.selectProject')</label>
                                    </div>
                                </div>
                                @endif
                                
                               <!--  <div class="col-md-6">
                                    <div class="form-label-group form-group">                                         
                                        <input type="text" placeholder="-" class="form-control form-control-lg" name="paid_on" id="paid_on" value="{{ Carbon\Carbon::now()->timezone($global->timezone)->format('d/m/Y H:i') }}">
                                        <label for="paid_on" class="control-label">@lang('modules.payments.paidOn')</label>                                        
                                    </div>
                                </div> -->
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-6 d-none">
                                    <div class="form-label-group form-group">                                     
                                        <select class="form-control form-control-lg" placeholder="-" name="currency_id" id="currency_id">
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                                            @endforeach
                                        </select>
                                        <label for="currency_id" class="control-label">@lang('modules.invoices.currency')</label>                                                              
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                           
                                        <input type="text" placeholder="-" name="amount" id="amount" class="form-control form-control-lg">    
                                        <label for="amount" class="required">@lang('modules.invoices.amount')</label>                                                                             
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right job-payment m-t-20">
                                       
                                        
                                        @if($credentials->paypal_status == 'active' || $credentials->stripe_status == 'active')
                                        <a class="btn btn-sm btn-primary waves-effect" onclick="offlinePayment(); return false;">Add Offline Payment</a>

                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-primary dropdown-toggle" id="btnGroupVerticalDrop3" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="credit-card"></i> Add Card Payment</button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop3">
                                                @if($credentials->stripe_status == 'active')
                                                    <a class="dropdown-item stripePaymentButton" href="javascript:void(0);" ><i class="fa fa-credit-card"></i> @lang('modules.invoices.payStripe') </a>
                                                @endif

                                                @if($credentials->paypal_status == 'active' && !empty($credentials->paypal_client_id && $credentials->paypal_secret) )
                                                    {{--<a class="dropdown-item" href="{{ route('client.paypal-public', [$invoice->id]) }}"><i class="fa fa-credit-card"></i> @lang('modules.invoices.payPaypal') </a>--}}
                                                @endif                                                

                                                @if($credentials->razorpay_status == 'active')
                                                    {{--<a class="dropdown-item razorpayPaymentButton" href="javascript:void(0);"><i class="fa fa-credit-card"></i>  @lang('modules.invoices.payRazorpay')  </a>--}}
                                                @endif                                                            
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>  
                            </div> 

                            <!-- <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                         
                                        <input type="text" name="gateway" id="gateway" class="form-control form-control-lg" placeholder="-">
                                        <label for="gateway" class="control-label">@lang('modules.payments.paymentGateway')</label>
                                        <span  class="help-block"> Paypal, Authorize.net, Stripe, Bank Transfer, Cash or others.</span>                         
                                    </div>  
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                    
                                        <input type="text" name="transaction_id" id="transaction_id" class="form-control-lg form-control" placeholder="-">
                                        <label for="transaction_id" class="control-label">@lang('modules.payments.transactionId')</label>                               
                                    </div>  
                                </div>
                            </div> -->
                            
                            <!-- <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group">                                      
                                        <textarea id="remarks" name="remarks" class="form-control-lg form-control" placeholder="-" ></textarea>     
                                        <label for="remarks" class="control-label">@lang('app.remark')</label>
                                    </div>
                                </div>
                            </div> -->                          
                        </div>
                    </div>
                    <!-- <div class="card-footer text-right">
                        <div class="form-actions col-md-3 offset-md-9">
                            <button type="submit" id="save-form-2" class="btn btn-primary form-control">@lang('app.save')</button>
                        </div>
                    </div> -->
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

        <!--Offline Payment-->
<div class="modal fade bs-modal-md in" id="offlinePayment" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" id="modal-data-application">
        <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><i class="fa fa-times-circle"></i></button>
                    <h5>{{$companyName}}</h5>
                    <h5 id="projectName"></h5>       
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

            <input type="hidden" name="project_id" id="project_new_id" />
            <input type="hidden" name="client_id" id="client_new_id" />
            <input type="hidden" name="amount" id="new_amount" />
            <input type="hidden" name="currency_id" id="new_currency_id" />

            @if($methods->count() > 0)
            <div class="form-group">
                <button type="button" onclick="addOfflinePayment(); return false;" class="offline-btn">Pay</button>
            </div>
            @endif
        </div>
        {{ Form::close() }}
            
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



@endsection



@push('footer-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js') }}"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>    

$('#client_id').change(function (e) {
    var client_id = $(this).val();
    var url = "{{ route('admin.all-invoices.get-client-company') }}";
        if(client_id != '' && client_id !== undefined )
        {
            url = "{{ route('admin.payments.get-client-projects',':id') }}";
            url = url.replace(':id', client_id);
        }
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: url,
            success: function (data) {
                $('#project_id').html('');
                $.each(data, function (index, data) {
                    $('#project_id').append('<option value="' + data.id + '">' + data.project_name + '</option>');
                });
            }
        });
});

function offlinePayment()
{
    var amount = $('#amount').val(),
        proj_id = $('#project_id option:selected').val(),
        proj_name = $('#project_id option:selected').text(),
        client_id = $('#client_id option:selected').val(),
        currency_id = $('#currency_id option:selected').val();

        if(amount == '' || proj_id == '' || client_id == '' || proj_id == undefined){
            toastr.error('Fill all the fields',"Error");
            return false;
        }

    $('#offlinePayment .offline-btn').html('Pay $'+amount);
    $('#project_new_id').val(proj_id);
    $('#client_new_id').val(client_id);
    $('#projectName').html(proj_name);
    $('#new_amount').val(amount);
    $('#new_currency_id').val(currency_id);

    $('#offlinePayment').modal('show');
}

function addOfflinePayment()
{
    $.easyAjax({
        url: '{{ route('admin.payments.offline-payment-submit') }}',
        type: "POST",
        container:'#saveDetail',
        messagePosition:'inline',
        file:true,
    })
}

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
                var amount  = $('#amount').val();
                var proj_id = $('#project_id option:selected').val();
                var currency_id = $('#currency_id option:selected').val();
                $.easyAjax({
                    url: '{{route('client.stripe', 0)}}',
                    container: '#invoice_container',
                    type: "POST",
                    redirect: true,
                    data: {token: token, amount:amount, project_id:proj_id, currency_id:currency_id, company_id:'{{$global->id}}', "_token" : "{{ csrf_token() }}"}
                })
            }
        });

    $('.stripePaymentButton').click(function(e){
        
        var amount = $('#amount').val(),
            proj_id = $('#project_id option:selected').val(),
            client_id = $('#client_id option:selected').val();
        if(amount == '' || proj_id == '' || client_id == '' || proj_id == undefined){
            toastr.error('Fill all the fields',"Error");
            return false;
        }
        // Open Checkout with further options:
        handler.open({
            name: '{{ $companyName }}',
            amount: amount*100,
            currency: 'USD',
            email: "",            

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
</script>
@endpush
