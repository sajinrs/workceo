@extends('layouts.client-app')

@push('head-script')
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
    <style>
        .ribbon-wrapper {background: #ffffff !important;}
    </style>
@endpush




@section('content')

<div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('client.dashboard.index') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('client.invoices.index') }}">{{ __($pageTitle) }}</a></li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        <a class="btn btn-secondary btn-outline btn-sm" href="{{ route('client.invoices.download', $invoice->id) }}"> <span><i class="fa fa-file-pdf"></i> @lang('modules.invoices.downloadPdf')</span> </a>
                    </div>
                </div>
                <!-- Bookmark Ends-->
            </div>
        </div>
    </div>

    <div class="container-fluid product-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                   <i class="fa fa-check"></i> {!! $message !!}
                </div>
                <?php Session::forget('success');?>
            @endif

            @if ($message = Session::get('error'))
                <div class="custom-alerts alert alert-danger fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    {!! $message !!}
                </div>
                <?php Session::forget('error');?>
            @endif


                <div class="ribbon-content " id="invoice_container">
                    @if($invoice->status == 'paid')
                        <div class="ribbon ribbon-bookmark ribbon-success">@lang('modules.invoices.paid')</div>
                    @elseif($invoice->status == 'partial')
                        <div class="ribbon ribbon-bookmark ribbon-info">@lang('modules.invoices.partial')</div>
                    @elseif($invoice->status == 'review')
                        <div class="ribbon ribbon-bookmark ribbon-warning">@lang('modules.invoices.review')</div>
                    @else
                        <div class="ribbon ribbon-bookmark ribbon-danger">@lang('modules.invoices.unpaid')</div>
                    @endif

                    <br /><br />
                    <h3><b>@lang('app.invoice')</b> <span class="pull-right">{{ $invoice->invoice_number }}</span></h3>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="pull-left">
                                <address>
                                    <h3> &nbsp;<b class="text-danger">{{ ucwords($global->company_name) }}</b></h3>
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
                                    @if(!is_null($invoice->project_id) && !is_null($invoice->project->client))
                                        <h3>@lang('app.to'),</h3>
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
                                        @if($invoiceSetting->show_gst == 'yes' && !is_null($invoice->project->client->client_details->gst_number))
                                            <p class="m-t-5"><b>@lang('app.gstIn')
                                                    :</b>  {{ $invoice->project->client->client_details->gst_number }}
                                            </p>
                                        @endif
                                    @elseif(!is_null($invoice->client_id))
                                        <h3>@lang('app.to'),</h3>
                                        <h4 class="font-bold">{{ ucwords($invoice->client->name) }}</h4>
                                        <p class="m-l-30">
                                            <b>@lang('app.address') :</b>
                                            <span class="text-muted">
                                                {!! nl2br($invoice->clientdetails->address) !!}
                                            </span>
                                        </p>
                                        @if($invoice->show_shipping_address === 'yes')
                                            <p class="m-t-5">
                                                <b>@lang('app.shippingAddress') :</b>
                                                <span class="text-muted">
                                                    {!! nl2br($invoice->clientdetails->shipping_address) !!}
                                                </span>
                                            </p>
                                        @endif
                                        @if($invoiceSetting->show_gst == 'yes' && !is_null($invoice->client->clientdetails->gst_number))
                                            <p class="m-t-5"><b>@lang('app.gstIn')
                                                    :</b>  {{ $invoice->client->clientdetails->gst_number }}
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
                                                <td>{{ ucfirst($item->item_name) }}</td>
                                                <td class="text-right">{{ $item->quantity }}</td>
                                                <td class="text-right"> {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $item->unit_price }} </td>
                                                <td class="text-right"> {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $item->amount }} </td>
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
                                    : {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $invoice->sub_total }}</p>

                                <p>@lang("modules.invoices.discount")
                                    : {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $discount }} </p>
                                @foreach($taxes as $key=>$tax)
                                    <p>{{ strtoupper($key) }}
                                        : {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $tax }} </p>
                                @endforeach
                                <hr>
                                <h3><b>@lang("modules.invoices.total")
                                        :</b> {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $invoice->total }}
                                </h3>
                                @if ($invoice->credit_notes()->count() > 0)
                                    <p>
                                        @lang('modules.invoices.appliedCredits'): {{ $invoice->currency->currency_symbol.''.$invoice->appliedCredits() }}
                                    </p>
                                @endif
                                <p>
                                    @lang('modules.invoices.amountPaid'): {{ $invoice->currency->currency_symbol.''.$invoice->amountPaid() }}
                                </p>
                                <p class="@if ($invoice->amountDue() > 0) text-danger @endif">
                                    @lang('modules.invoices.amountDue'): {{ $invoice->currency->currency_symbol.''.$invoice->amountDue() }}
                                </p>
                            </div>
                            @if(!is_null($invoice->note))
                                <div class="col-md-12">
                                    <p><strong>@lang('app.note')</strong>: {{ $invoice->note }}</p>
                                </div>
                            @endif
                            <div class="clearfix"></div>
                            <hr>
                            <div class="row">

                            <div class="col-md-12">
                                    <div class="pull-right job-payment">
                                        
                                        @if($invoice->status == 'unpaid' && ($credentials->paypal_status == 'active' || $credentials->stripe_status == 'active'))
                                        {{--<a class="btn btn-sm btn-primary waves-effect" onclick="offlinePayment({{$invoice->id}}); return false;">Add Offline Payment</a>--}}

                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-primary dropdown-toggle" id="btnGroupVerticalDrop3" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="credit-card"></i> Add Card Payment</button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop3">
                                                @if($credentials->stripe_status == 'active')
                                                    <a class="dropdown-item" href="javascript:void(0);" id="stripePaymentButton"><i class="fa fa-credit-card"></i> @lang('modules.invoices.payStripe') </a>
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

                                <div class="col-md-6 text-left">
                                    @if($invoice->status == 'unpaid' || $invoice->status == 'review')

                                    {{--<div class="form-group">
                                        <div class="radio-list">
                                            @if(($credentials->paypal_status == 'active'  || $credentials->razorpay_status == 'active' ||  $credentials->stripe_status == 'active'))
                                                <label class="radio-inline p-0">
                                                    <div class="radio radio-info">
                                                        <input checked onchange="showButton('online')" type="radio" name="method" id="radio13" value="high">
                                                        <label for="radio13">@lang('modules.client.online')</label>
                                                    </div>
                                                </label>
                                            @endif
                                            @if($methods->count() > 0)
                                                <label class="radio-inline">
                                                    <div class="radio radio-info">
                                                        <input type="radio" onchange="showButton('offline')"  name="method" id="radio15">
                                                        <label for="radio15">@lang('modules.client.offline')</label>
                                                    </div>
                                                </label>
                                            @endif
                                        </div>
                                    </div>--}}
                                    {{--<div class="clearfix"></div>--}}

                                    {{--<div class="col-md-12 p-l-0">
                                        @if(($credentials->paypal_status == 'active' || $credentials->razorpay_status == 'active' || $credentials->stripe_status == 'active'))
                                            <div class="btn-group displayNone" id="onlineBox">
                                                <div class="dropup">
                                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    @lang('modules.invoices.payNow') <span class="caret"></span>
                                                </button>
                                                <ul role="menu" class="dropdown-menu">
                                                    @if($credentials->paypal_status == 'active')
                                                        <li>
                                                            <a href="{{ route('client.paypal', [$invoice->id]) }}"><i
                                                                        class="icofont icofont-brand-paypal"></i> @lang('modules.invoices.payPaypal') </a>
                                                        </li>
                                                    @endif
                                                    @if($credentials->stripe_status == 'active')
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="javascript:void(0);" id="stripePaymentButton"><i
                                                                        class="icofont icofont-stripe"></i> @lang('modules.invoices.payStripe') </a>
                                                        </li>
                                                    @endif
                                                    @if($credentials->razorpay_status == 'active')
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="javascript:void(0);" id="razorpayPaymentButton"><i
                                                                        class="icofont icofont-credit-card"></i> @lang('modules.invoices.payRazorpay') </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                                </div>

                                            </div>
                                        @endif
                                        @if($methods->count() > 0)
                                            <div class="form-group displayNone" id="offlineBox">
                                                <div class="radio-list">
                                                    @forelse($methods as $key => $method)
                                                        <label class="radio-inline @if($key == 0) p-0 @endif">
                                                            <div class="radio radio-info" >
                                                                <input @if($key == 0) checked @endif onchange="showDetail('{{ $method->id }}')" type="radio" name="offlineMethod" id="offline{{$key}}"
                                                                    value="{{ $method->id }}">
                                                                <label for="offline{{$key}}" class="text-info" >
                                                                    {{ ucfirst($method->name) }} </label>
                                                            </div>
                                                            <div class="displayNone" id="method-desc-{{ $method->id }}">
                                                                {!! $method->description !!}
                                                            </div>
                                                        </label>
                                                    @empty
                                                    @endforelse
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 displayNone" id="methodDetail">
                                                    </div>

                                                @if(count($methods) > 0)
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-info save-offline" onclick="offlinePayment(); return false;">@lang('app.select')</button>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div> --}}
                                    @endif
                                </div>
                               
                            </div>
                        </div>
                    </div>
                    
                </div>
        </div>
    </div>
    </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title wceo-card-header  mb-0">
                        <div class="panel-heading"> @lang('modules.invoices.OfflinePaymentRequest')</div>
                    </h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table color-table info-table" id="users-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('app.menu.offlinePaymentMethod')</th>
                                <th>@lang('app.status')</th>
                                <th>@lang('app.description')</th>
                                <!-- <th>@lang('app.action')</th> -->
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $status = ['pending' => 'warning', 'approve' => 'success', 'reject' => 'danger'];
                                $statusString = ['pending' => 'Pending', 'approve' => 'approved', 'reject' => 'rejected'];
                            @endphp

                            @forelse($invoice->offline_invoice_payment as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>{{ $request->payment_method->name }}</td>
                                    <td><label class="label label-{{$status[$request->status]}}">{{ ucwords($statusString[$request->status]) }}</label></td>
                                    <td>{{ $request->description }}</td>
                                    <!-- <td><a class="btn btn-primary btn-sm btn-circle" target="_blank" href="{{ $request->slip }}"><i class="fa fa-eye"></i></a></td> -->
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="5">No data found !</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <div class="modal fade bs-modal-md in" id="package-offline" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
<script>
    $(function () {
        @if(($credentials->paypal_status == 'active' || $credentials->razorpay_status == 'active' || $credentials->stripe_status == 'active'))
            showButton('online');
        @else
                @if($methods->count() > 0)
        showButton('offline');
                @endif
        @endif
                if ($("#radio15").prop("checked")) {
                    showButton('offline');
                }

        var table = $('#invoices-table').dataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('client.invoices.create') }}',
            deferRender: true,
            "order": [[0, "desc"]],
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function (oSettings) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'project_name', name: 'projects.project_name'},
                {data: 'invoice_number', name: 'invoice_number'},
                {data: 'currency_symbol', name: 'currencies.currency_symbol'},
                {data: 'total', name: 'total'},
                {data: 'issue_date', name: 'issue_date'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

    });

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

        document.getElementById('stripePaymentButton').addEventListener('click', function(e) {
            // Open Checkout with further options:
            handler.open({
                name: '{{ $companyName }}',
                amount: {{ $invoice->total*100 }},
                currency: '{{ $invoice->currency->currency_code }}',
                //email: "{{ $user->email }}"
                email: ""

            });
            e.preventDefault();
        });

        // Close Checkout on page navigation:
        window.addEventListener('popstate', function() {
            handler.close();
        });



    @endif

    // Show offline method detail
    function showDetail(id){
        var detail = $('#method-desc-'+id).html();
        $('#methodDetail').html(detail);
        $('#methodDetail').show();
    }

    // Payment mode
    function showButton(type){

        if(type == 'online'){
            $('#methodDetail').hide();
            $('#offlineBox').hide();
            $('#onlineBox').show();
        }else{
            $('#offline0').change();
            $('#offlineBox').show();
            $('#onlineBox').hide();
        }
    }

    function offlinePayment(id)
    {
        var url = '{{ route('client.invoices.offline-payment', ':id')}}';
        url = url.replace(':id', id);
        $.ajaxModal('#offlinePayment', url);   
    }

     function offlinePaymentBackup() {

        let offlineId = $("input[name=offlineMethod]").val();

        $.ajaxModal('#package-offline', '{{ route('client.invoices.offline-payment')}}?offlineId='+offlineId+'&invoiceId='+'{{$invoice->id}}');

        {{--$.easyAjax({--}}
        {{--    url: '{{ route('client.invoices.store') }}',--}}
        {{--    type: "POST",--}}
        {{--    redirect: true,--}}
        {{--    data: {invoiceId: "{{ $invoice->id }}", "_token" : "{{ csrf_token() }}", "offlineId": offlineId}--}}
        {{--})--}}

    }

    @if($credentials->razorpay_status == 'active')
        $('#razorpayPaymentButton').click(function() {
            console.log('{{ $invoice->currency->currency_code }}');
                var amount = {{ $invoice->total*100 }};
                var invoiceId = {{ $invoice->id }};
                var clientEmail = "{{ $user->email }}";

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
                    url:'{{route('client.pay-with-razorpay')}}',
                    data: {paymentId: id,invoiceId: invoiceId,rData: rData,_token:'{{csrf_token()}}'}
                })
            }

    @endif
</script>
@endpush
