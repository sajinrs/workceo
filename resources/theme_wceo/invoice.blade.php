<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <title>Client Panel | {{ $pageTitle }}</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/fontawesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/themify.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/flag-icon.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/feather-icon.css') }}">
    
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/admin/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('themes/wceo/assets/css/light-1.css') }}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/floating-labels.css') }}">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/responsive.css') }}">    

    <link href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    

    <!-- This is a Animation CSS -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    
    <link href="{{ asset('plugins/froiden-helper/helper.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    <link href="{{ asset('css/rounded.css') }}" rel="stylesheet">

@stack('head-script')


</head>
<body class="fix-sidebar">
<!-- Preloader -->
<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>
<div id="wrapper">

<!-- Left navbar-header end -->
    <!-- Page Content -->
    <div id="page-wrapper" style="margin-left: 0px !important;">
        <div class="container-fluid">

        <!-- .row -->
            <div class="row" style="margin-top: 70px; !important;">

                <div class="offset-md-2 col-md-8">
                    <div class="row m-b-20">
                        <div class="col-md-12">
                            <a href="{{ route("front.invoiceDownload", md5($invoice->id)) }}" class="btn btn-secondary pull-right m-r-10"><i class="fa fa-file-pdf-o"></i> Download</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                        @if($invoice->status == 'paid')
                                <div class="ribbon ribbon-bookmark ribbon-success">@lang('modules.invoices.paid')</div>
                            @elseif($invoice->status == 'partial')
                                <div class="ribbon ribbon-bookmark ribbon-info">@lang('modules.invoices.partial')</div>
                            @else
                                <div class="ribbon ribbon-bookmark ribbon-danger">@lang('modules.invoices.unpaid')</div>
                            @endif

                    @if ($message = Session::get('success'))
                        <p>&nbsp;</p>
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                            <i class="fa fa-check"></i> {!! $message !!}
                        </div>
                        <?php Session::forget('success');?>
                    @endif

                    @if ($message = Session::get('error'))
                        <p>&nbsp;</p>
                        <div class="custom-alerts alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                            {!! $message !!}
                        </div>
                        <?php Session::forget('error');?>
                    @endif


                    <div class="white-box printableArea ribbon-wrapper" style="background: #ffffff !important;">
                        <div class="ribbon-content " id="invoice_container">
                            

                            <h4><b>@lang('app.invoice')</b> <span class="pull-right">{{ $invoice->invoice_number }}</span></h4>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="pull-left">
                                        <address>
                                            <h4> &nbsp;<b class="text-danger">{{ ucwords($global->company_name) }}</b></h4>
                                            @if(!is_null($settings))
                                                <p class="text-muted m-l-5">{!! nl2br($global->address) !!}</p>
                                            @endif
                                            @if($invoiceSetting->show_gst == 'yes' && !is_null($invoiceSetting->gst_number))
                                                <p class="text-muted m-l-5"><b>@lang('app.gstIn')
                                                        :</b>{{ $invoiceSetting->gst_number }}</p>
                                            @endif
                                        </address>
                                    </div>
                                    <div class="pull-right text-right">
                                        <address>
                                            @if(!is_null($invoice->project) && !is_null($invoice->project->client))
                                                <h4>To,</h4>
                                                <h4 class="font-bold">{{ ucwords($invoice->project->client->name) }}</h4>
                                                <p class="m-l-30">
                                                    <b>@lang('app.address') :</b>
                                                    <span class="text-muted">
                                                        {!! nl2br($invoice->project->client->address) !!}
                                                    </span>
                                                </p>
                                                @if($invoice->show_shipping_address === 'yes')
                                                    <p class="m-t-5">
                                                        <b>@lang('app.shippingAddress') :</b>
                                                        <span class="text-muted">
                                                            {!! nl2br($invoice->project->client->shipping_address) !!}
                                                        </span>
                                                    </p>
                                                @endif
                                                @if($invoiceSetting->show_gst == 'yes' && !is_null($invoice->project->client->gst_number))
                                                    <p class="m-t-5"><b>@lang('app.gstIn')
                                                            :</b>  {{ $invoice->project->client->gst_number }}
                                                    </p>
                                                @endif
                                            @endif

                                            <p class="m-t-30"><b>@lang('modules.invoices.invoiceDate') :</b> <i
                                                        class="fa fa-calendar"></i> {{ $invoice->issue_date->format($global->date_format) }}
                                            </p>

                                            <p><b>@lang('modules.dashboard.dueDate') :</b> <i
                                                        class="fa fa-calendar"></i> {{ $invoice->due_date->format($global->date_format) }}
                                            </p>
                                            @if($invoice->recurring == 'yes')
                                                <p><b class="text-danger">@lang('modules.invoices.billingFrequency') : </b> {{ $invoice->billing_interval . ' '. ucfirst($invoice->billing_frequency) }} ({{ ucfirst($invoice->billing_cycle) }} cycles)</p>
                                            @endif
                                        </address>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive m-t-40" style="clear: both;">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>@lang("modules.invoices.item")</th>
                                                <th class="text-right">@lang("modules.invoices.qty")</th>
                                                <th class="text-right">@lang("modules.invoices.unitPrice")</th>
                                                <th class="text-right">@lang("modules.invoices.price")</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $count = 0; ?>
                                            @foreach($invoice->items as $item)
                                                @if($item->type == 'item')
                                                    <tr>
                                                        <td class="text-center">{{ ++$count }}</td>
                                                        <td>{{ ucfirst($item->item_name) }}
                                                            @if(!is_null($item->item_summary))
                                                                <p class="font-12">{{ $item->item_summary }}</p>
                                                            @endif
                                                        </td>
                                                        <td class="text-right">{{ $item->quantity }}</td>
                                                        <td class="text-right"> {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ currencyFormat($item->unit_price) }} </td>
                                                        <td class="text-right"> {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ currencyFormat($item->amount) }} </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="pull-right m-t-30 text-right">
                                        <p>@lang("modules.invoices.subTotal")
                                            : {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ currencyFormat($invoice->sub_total) }}</p>
                                        <p>@lang("modules.invoices.discount")
                                            : {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $discount }} </p>
                                        @foreach($taxes as $key=>$tax)
                                            <p>{{ strtoupper($key) }}
                                                : {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $tax }} </p>
                                        @endforeach
                                        <hr>
                                        <h4><b>@lang("modules.invoices.total")
                                                :</b> {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ currencyFormat($invoice->total) }}
                                        </h4>
                                        <hr>
                                        @if ($invoice->credit_notes()->count() > 0)
                                            <p>
                                                @lang('modules.invoices.appliedCredits'): {{ $invoice->currency->currency_symbol.''.$invoice->appliedCredits() }}
                                            </p>
                                        @endif
                                        <p class="@if ($invoice->amountDue() > 0) text-danger @endif">
                                            @lang('modules.invoices.amountDue'): {{ $invoice->currency->currency_symbol.''.currencyFormat($invoice->amountDue()) }}
                                        </p>
                                    </div>

                                    
                                    <div class="clearfix"></div>
                                    <hr>
                                    
                                    <div class="row">

                                    @if(!is_null($invoice->note))
                                        <div class="col-md-12">
                                            <p><strong>@lang('app.note')</strong>: {{ $invoice->note }}</p>
                                           <br /> 
                                        </div>
                                    @endif

                                        <div class="col-md-12 p-l-0 text-left">
                                            {{--<div class="clearfix"></div>--}}
                                            <div class="pull-right job-payment">
                                                @if($invoice->status == 'unpaid' && ($credentials->paypal_status == 'active' || $credentials->stripe_status == 'active'))

                                                    <div class="btn-group" id="onlineBox">

                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-primary dropdown-toggle" id="btnGroupVerticalDrop3" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg> Add Card Payment</button>
                                                        <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop3">
                                                            @if($credentials->stripe_status == 'active')
                                                                <a class="dropdown-item" href="javascript:void(0);" id="stripePaymentButton"><i class="fa fa-cc-stripe"></i> @lang('modules.invoices.payStripe') </a>
                                                            @endif

                                                            @if($credentials->paypal_status == 'active' && !empty($credentials->paypal_client_id && $credentials->paypal_secret) )
                                                                <a class="dropdown-item" href="{{ route('client.paypal-public', [$invoice->id]) }}"><i class="fa fa-cc-paypal"></i> @lang('modules.invoices.payPaypal') </a>
                                                            @endif                                                            

                                                            @if($credentials->razorpay_status == 'active')
                                                                <a class="dropdown-item" href="javascript:void(0);" id="razorpayPaymentButton"><i class="fa fa-cc-stripe"></i>  @lang('modules.invoices.payRazorpay')  </a>
                                                            @endif                                                            
                                                        </div>
                                                    </div>                                                       

                                                    </div>
                                                @endif
                                            </div>


                                        </div>
                                        <div class="col-md-6 text-right">

                                        </div>
                                    </div>
                                    <div>
                                        <div class="col-md-12">
                                            <span><p class="displayNone" id="methodDetail"></p></span>
                                        </div>
                                    </div>
                                </div>

                                
                                @if(count($invoice->payment) > 0)
                                    <div class="col-md-12">
                                    <p>&nbsp;</p>
                                        <h5>@lang("modules.invoices.paymentDetails")</h5>
                                        <div class="table-responsive m-t-40" style="clear: both;">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="text-center"><b>#</b></th>
                                                    <th class="text-right"><b>@lang("modules.invoices.amount")</b></th>
                                                    <th><b>@lang("modules.invoices.paymentGateway")</b></th>
                                                    <th><b>@lang("modules.invoices.transactionID")</b></th>
                                                    <th><b>@lang("modules.invoices.paidOn")</b></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $count = 0; ?>
                                                @forelse($invoice->payment as $payment)
                                                    <tr>
                                                        <td class="text-center">{{ $count=$count+1 }}</td>
                                                        <td class="text-right">{!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $payment->amount }}</td>
                                                        <td>{{ htmlentities($payment->gateway)  }}</td>
                                                        <td>{{ $payment->transaction_id }}</td>
                                                        <td>@if(!is_null($payment->paid_on)) {{ $payment->paid_on->format($global->date_format.' '.$global->time_format) }} @endif</td>
                                                    </tr>
                                                    @if($payment->remarks)
                                                        <tr><td colspan="5"><b>@lang("modules.invoices.remark")</b> : {!! $payment->remarks !!}</td></tr>
                                                    @endif
                                                @empty
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->

<script src="{{ asset('themes/wceo/assets/js/jquery-3.2.1.min.js')}}"></script>
<!-- Bootstrap js-->
<script src="{{ asset('themes/wceo/assets/js/bootstrap/popper.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/bootstrap/bootstrap.js')}}"></script>
<!-- feather icon js-->

<script src="{{ asset('themes/wceo/assets/js/notify/bootstrap-notify.min.js')}}"></script>

<script src="{{ asset('themes/wceo/assets/js/notify/index.js')}}"></script>

<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{ asset('themes/wceo/assets/js/script.js')}}"></script>


<script src="{{ asset('plugins/froiden-helper/helper.js')}}"></script>
<script src="{{ asset('plugins/bower_components/toast-master/js/jquery.toast.js')}}"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
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
                    url: '{{route('client.stripe-public', [$invoice->id])}}',
                    container: '#invoice_container',
                    type: "POST",
                    redirect: true,
                    data: {token: token, "_token" : "{{ csrf_token() }}"}
                })
            }
        });

    document.getElementById('stripePaymentButton').addEventListener('click', function(e) {
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
</script>

</body>
</html>