@extends('layouts.app')

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
                <div class="col-md-4">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.all-invoices.index') }}">{{ __($pageTitle) }}</a></li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right invoice-btns">
                        <a class="btn btn-primary btn-outline btn-sm" href="{{ route('admin.all-invoices.download', $invoice->id) }}"> <span><i class="fa fa-file-pdf"></i> @lang('modules.invoices.downloadPdf')</span> </a>

                        <button type="button" onclick="showPayments()" class="btn btn-primary btn-sm">@lang('app.view') @lang('app.menu.payments')</button>

                        <button type="button" data-clipboard-text="{{ route('front.invoice', [md5($invoice->id)]) }}" class="btn btn-primary btn-sm btn-copy">
                            <i class="fa fa-copy"></i> 
                            <span id="copy_payment_text">
                                @lang('modules.invoices.copyPaymentLink')
                            </span>
                        </button>
                        @if ($invoice->credit_notes->count() > 0)
                            <a href="javascript:;" onclick="showAppliedCredits('{{ route('admin.all-invoices.applied-credits', $invoice->id) }}')" class="btn btn-primary  btn-sm">
                                @lang('app.appliedCredits')
                            </a>    
                        @endif

                        @if($invoice->status == 'unpaid')
                            <a href="{{ route('admin.all-invoices.edit', $invoice->id) }}" style="width: 122px;min-width: auto;" class="pull-right btn btn-sm btn-primary"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>
                        @endif
                    </div>
                </div>
                <!-- Bookmark Ends-->
            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="product-grid">
                    

                    <div class="product-wrapper-grid">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="product-sidebar">
                                    <div class="filter-section">
                                        <div class="card browser-widget">
                                            <div class="media card-body" style="padding: 10px">
                                                <div class="media-body align-self-center">
                                                    <div>
                                                        <p>@lang('modules.payments.totalAmount') </p>
                                                        <h4><span class="text-gray">{{ $invoice->currency->currency_symbol}}</span><span class="counter">{{ currencyFormat($invoice->total) }}</span></h4>
                                                    </div>
                                                    <div>
                                                        <p> @lang('modules.payments.totalPaid')</p>
                                                        <h4><span class="text-gray">{{ $invoice->currency->currency_symbol}}</span><span class="counter">{{ currencyFormat($invoice->amountPaid()) }}</span></h4>
                                                    </div>
                                                    <div>
                                                        <p>@lang('modules.payments.totalDue') </p>
                                                        <h4><span class="text-gray">{{ $invoice->currency->currency_symbol}}</span><span class="counter">{{ currencyFormat($invoice->amountDue()) }}</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
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

                                        <div class="ribbon-content ">
                    @if($invoice->credit_note)
                        <div class="ribbon ribbon-bookmark ribbon-warning">@lang('app.credit-note')</div>
                    @else
                        @if($invoice->status == 'paid')
                            <div class="ribbon ribbon-bookmark ribbon-success">@lang('modules.invoices.paid')</div>
                        @elseif($invoice->status == 'partial')
                            <div class="ribbon ribbon-bookmark ribbon-info">@lang('modules.invoices.partial')</div>
                        @elseif($invoice->status == 'review')
                            <div class="ribbon ribbon-bookmark ribbon-warning">@lang('modules.invoices.review')</div>
                        @else
                            <div class="ribbon ribbon-bookmark ribbon-danger">@lang('modules.invoices.unpaid')</div>
                        @endif
                    @endif
                    <br><br>

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
                                        <h3>To,</h3>
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
                                        @if($invoiceSetting->show_gst == 'yes' && !is_null($invoice->clientdetails->gst_number))
                                            <p class="m-t-5"><b>@lang('app.gstIn')
                                                    :</b>  {{ $invoice->clientdetails->gst_number }}
                                            </p>
                                        @endif
                                    @endif

                                    <p class="m-t-30"><b>@lang('app.invoice') @lang('app.date') :</b> <i
                                                class="fa fa-calendar"></i> {{ $invoice->issue_date->format($global->date_format) }}
                                    </p>

                                    <p><b>@lang('app.dueDate') :</b> <i
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
                                <h3><b>@lang("modules.invoices.total")
                                        :</b> {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ currencyFormat($invoice->total) }}
                                </h3>
                                <hr>
                                @if ($invoice->credit_notes()->count() > 0)
                                    <p>
                                        @lang('modules.invoices.appliedCredits'): {{ $invoice->currency->currency_symbol.''.$invoice->appliedCredits() }}
                                    </p>
                                @endif
                                <p>
                                    @lang('modules.invoices.amountPaid'): {{ $invoice->currency->currency_symbol.''.currencyFormat($invoice->amountPaid()) }}
                                </p>
                                <p class="@if ($invoice->amountDue() > 0) text-danger @endif">
                                    @lang('modules.invoices.amountDue'): {{ $invoice->currency->currency_symbol.''.currencyFormat($invoice->amountDue()) }}
                                </p>
                            </div>

                            @if(!is_null($invoice->note))
                                <div class="col-md-12">
                                    <p><strong>@lang('app.note')</strong>: {{ $invoice->note }}</p>
                                </div>
                            @endif
                            <div class="clearfix"></div>

                            <div class="row">
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

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-lg in" id="paymentDetail" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
         <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">@lang('app.credit-notes.uploadInvoice')</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}
    
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-lg in" id="appliedCredits" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    @lang('app.loading')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.close')</button>
                    <button type="button" class="btn btn-primary">@lang('app.save') @lang('app.changes')</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

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
<script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/custom.js')}}"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

    var clipboard = new ClipboardJS('.btn-copy');

    clipboard.on('success', function(e) {
        var copied = "<?php echo __("app.copied") ?>";
        // $('#copy_payment_text').html(copied);
        $.toast({
            heading: 'Success',
            text: copied,
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'success',
            hideAfter: 3500
        });
    });

    function showAppliedCredits(url) {
        $.ajaxModal('#appliedCredits', url);
    }

    function deleteAppliedCredit(invoice_id, id) {
        let url = '{{ route('admin.all-invoices.delete-applied-credit', [':id']) }}';
        url = url.replace(':id', id);

        $.easyAjax({
            url: url,
            type: 'POST',
            data: { invoice_id: invoice_id, _token: '{{csrf_token()}}'},
            success: function (response) {
                $('#appliedCredits .modal-content').html(response.view);
                $('#appliedCredits').on('hide.bs.modal', function (e) {
                    location.reload();
                })
            }
        })
    }

    $(function () {
        var table = $('#invoices-table').dataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.all-invoices.create') }}',
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

    // Show Payment detail modal
    function showPayments() {
        var url = '{{route('admin.all-invoices.payment-detail', $invoice->id)}}';
        $.ajaxModal('#paymentDetail', url);
    }

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