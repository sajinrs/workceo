@extends('layouts.client-app')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} #{{ $project->id }} - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('client.dashboard.index') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('client.projects.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.details')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <section>
                <div class="sttabs tabs-style-line"> @include('client.projects.show_project_menu')  </div>
            </section>
        </div>
    </div>
    <!-- .row -->

<div class="container-fluid product-wrapper">
    <div class="row">
        <div class="col-md-4 p-l-0">
            <div class="row">
                <div class="col-md-12">
                    <div class="card user-profile">
                        <div class="left-filter wceo-left-filter" style="top: 68px;">
                            <div class="card-header m-b-10">
                                <h4 class="card-title mb-0 company_name">{{ ucwords($project->client->company_name) }}</h4>
                            </div>
                            <div class="card-body">

                                <div class=" ttl-info text-left ttl-border">
                                    <h6>@lang('modules.jobs.jobName')</h6>
                                    <span>{{ ucwords($project->project_name) }}</span>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class=" ttl-info text-left ttl-border">
                                            <h6>@lang('modules.projects.startDate')</h6>
                                            <span>{{ $project->start_date->format($global->date_format) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class=" ttl-info text-left ttl-border">
                                            <h6>@lang('modules.projects.endDate')</h6>
                                            @if($project->deadline)
                                                <span>{{ $project->deadline->format($global->date_format) }} </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class=" ttl-info text-left ttl-border">
                                            <h6>@lang('modules.projects.startTime')</h6>
                                            <span>{{ \Carbon\Carbon::createFromFormat('H:i:s', $project->start_time)->format($global->time_format) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class=" ttl-info text-left ttl-border">
                                            <h6>@lang('modules.projects.endTime')</h6>
                                            <span>{{ \Carbon\Carbon::createFromFormat('H:i:s', $project->end_time)->format($global->time_format) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class=" ttl-info text-left ttl-border">
                                            <h6>@lang('modules.projects.projectBudget')</h6>
                                            <span>{{ $project->currency->currency_symbol}}{{ currencyFormat($project->project_budget) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class=" ttl-info text-left ttl-border">
                                            <h6>@lang('modules.jobs.estHours')</h6>
                                            @if(empty($project->hours_allocated))
                                                <span>&nbsp;</span>
                                            @else
                                                <span>{{ $project->hours_allocated }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class=" ttl-info text-left ttl-border">
                                    <h6>@lang('modules.projects.projectCategory')</h6>
                                    @if(!empty($project->category))
                                        <span>{{$project->category->category_name}}</span>
                                    @else
                                        <span>&nbsp;</span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                
                <!--Map-->
                <div class="col-md-12">
                    <div class="card">
                        <div class="left-filter wceo-left-filter">
                            <div class="card-body">
                            {{--<div class="map-js-height" id="map12"></div>--}}
                                <img src="https://maps.googleapis.com/maps/api/staticmap?zoom=10&size=250x250&scale=2&maptype=roadmap
&markers=color:blue%7Clabel:S%7C{{$project->client->address}}&key=AIzaSyCsLN7tz9Ww5Lt2hDS4KqaBrb8clNSwdkQ" alt="{{$project->client->address}}" border="0" width="100%">

                            </div>
                        </div>
                    </div>
                </div>

                <!--Team Members-->
                <div class="col-md-12">
                    <div class="card">
                        <div class="left-filter wceo-left-filter">
                            <div class="card-body teamMembers new-users">
                                <h4 class="m-b-20">@lang('modules.jobs.teamMembers')</h4>
                                @if($project->members)
                                    @foreach($project->members as $member)
                                    <div class="media mb-0">
                                        <img width="58" height="58" class="rounded-circle image-radius m-r-15" src="{{$member->user->image_url}}" alt="" data-original-title="" title="">
                                        <div class="media-body"><h6 class="mb-0">{{$member->user->name}}</h6></div>
                                    </div>
                                    <hr />
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!--Job Decription-->
                <div class="col-md-12">
                    <div class="card">
                        <div class="left-filter wceo-left-filter">
                            <div class="card-body">
                                <h4>@lang('modules.jobs.jobdescription')</h4>
                                {{ $project->project_summary }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12 pr-0">                    

                    <div class="card">
                    <div class="card-header m-b-10">
                            @if($project->status == 'finished')
                                @php $schedulestatus = $omwStatus = $startStatus = $finishStatus = $invoiceStatus = $paidStatus = 'processed'; @endphp
                            @else
                                @php $jobStatus = $project->job_status; @endphp
                                @switch($jobStatus)
                                    @case('schedule')
                                            @php $schedulestatus = 'active'; 
                                                $omwStatus = $startStatus = $finishStatus = $invoiceStatus = $paidStatus = 'pending';
                                            @endphp
                                        @break

                                    @case('omw')
                                            @php $schedulestatus = 'processed'; 
                                                $omwStatus = 'active'; 
                                                $startStatus = $finishStatus = $invoiceStatus = $paidStatus = 'pending'; 
                                            @endphp
                                        @break

                                    @case('start')
                                            @php $schedulestatus = $omwStatus = 'processed'; 
                                                $startStatus = 'active'; 
                                                $finishStatus = $invoiceStatus = $paidStatus = 'pending'; 
                                            @endphp
                                        @break

                                    @case('finish')
                                            @php $schedulestatus = $omwStatus = $startStatus = 'processed'; 
                                                $finishStatus = 'active'; 
                                                $invoiceStatus = $paidStatus = 'pending'; 
                                            @endphp
                                        @break

                                    @case('invoice')
                                            @php $schedulestatus = $omwStatus = $startStatus = $finishStatus = 'processed'; 
                                                $invoiceStatus = 'active'; 
                                                $paidStatus = 'pending'; 
                                            @endphp
                                        @break
                                    
                                    @default
                                            @php $schedulestatus = $omwStatus = $startStatus = $finishStatus = $invoiceStatus = 'processed'; 
                                                $paidStatus = 'active'; 
                                            @endphp
                                    
                                @endswitch   
                            @endif                         

                            <h5 class="card-title mb-0">Job Status 
                           
                            
                        
                    </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10 pr-0 projSteps">
                                    <a class="sqr {{$schedulestatus}}" href="javascript:;" data-status="schedule" data-percentage="0" data-projstatus="not started">
                                        <i class="fas fa-calendar-check"></i>
                                        <h5>SCHEDULE</h5>
                                    </a>

                                    <a class="sqr {{$omwStatus}}" href="javascript:;" data-status="omw" data-percentage="17" data-projstatus="not started">
                                        <i class="fas fa-shipping-fast"></i>
                                        <h5>OMW</h5>
                                    </a>

                                    <a class="sqr {{$startStatus}}" href="javascript:;" data-status="start" data-percentage="33" data-projstatus="in progress">
                                        <i class="fas fa-play-circle"></i>
                                        <h5>START</h5>
                                    </a>

                                    <a class="sqr {{$finishStatus}}" href="javascript:;" data-status="finish" data-percentage="50" data-projstatus="awaiting invoice">
                                        <i class="fas fa-check-square"></i>
                                        <h5>FINISH</h5>
                                    </a>

                                    <a class="sqr {{$invoiceStatus}}" href="javascript:;" data-status="invoice" data-percentage="66" data-projstatus="awaiting pay">
                                        <i class="fas fa-file-export"></i>
                                        <h5>INVOICE</h5>
                                    </a>

                                    <a class="sqr {{$paidStatus}}" href="javascript:;" data-status="paid" data-percentage="83" data-projstatus="paid">
                                        <i class="fas fa-credit-card"></i>
                                        <h5>PAID</h5>
                                    </a>
                                </div>

                            <div class="col-md-2 text-center p-0">
                                <b>Project Progress</b>
                                <div id="jobProgress" class="progress m-t-10" data-value='{{$project->completion_percent}}'>
                                    <span class="progress-left">
                                        <span class="progress-bar border-green"></span>
                                    </span>
                                    <span class="progress-right">
                                        <span class="progress-bar border-green"></span>
                                    </span>
                                    <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                        <h4>{{$project->completion_percent}}%</h4>
                                    </div>
                                </div>                                
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(!empty($invoices))
                @foreach($invoices as $invoice)
                <div class="col-md-12 pr-0">
                    <div class="card">
                        <div class="card-body invoiceDetails">                            
                            <div class="row">
                                <div class="col-md-5">
                                    <h5 class="primary-color">{{ $invoice->invoice_number }}</h5> 
                                    <strong>{{ ucwords($global->company_name) }}</strong><br />
                                    {!! nl2br($global->address) !!}
                                </div>

                                <div class="col-md-5 pull-right text-right offset-md-2">
                                    @if(!is_null($invoice->project) && !is_null($invoice->project->client))
                                    <section>
                                        <strong>Bill to</strong><br />
                                        <span class="primary-color">{{ ucwords($project->client->name) }}</span><br />
                                        {!! nl2br($project->client->address) !!}
                                    </section>

                                    @if($invoice->show_shipping_address === 'yes')
                                    <section class="m-t-35">
                                        <strong>Ship to</strong><br />
                                        {!! nl2br($project->client->shipping_address) !!}
                                    </section>
                                    @endif
                                    @endif

                                    <section class="inoiceDates m-t-35">
                                        <span>Invoice Date:</span> {{ $invoice->issue_date->format($global->date_format) }}<br />
                                        <span>Due Date:</span> {{ $invoice->due_date->format($global->date_format) }}<br />
                                        <span>Sales Agent:</span>
                                    </section>
                                </div>
                            </div>

                            <div class="table-responsive user-profile m-t-35">
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                        <th scope="col">INVOICE</th>
                                        <th scope="col">QTY/HRS</th>
                                        <th scope="col">UNIT PRICE</th>
                                        <th scope="col">Tax</th>
                                        <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($invoice->items as $key => $item)
                                        <tr>
                                            <th scope="row">
                                                <div class="ttl-info text-left ttl-border m-t-10">
                                                    <h6>@lang('modules.invoices.item')</h6>
                                                    <span>{{ $item->item_name }}</span>
                                                </div>
                                                <div class=" ttl-info text-left ">
                                                    <h6>@lang('app.description')</h6>
                                                    <span>{{ $item->item_summary }}</span>
                                                </div>
                                            </th>
                                            <td>
                                                <div class=" ttl-info text-left ttl-border  m-t-10">
                                                    <h6>@lang('modules.invoices.qty')</h6>
                                                    <span>{{ $item->quantity }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class=" ttl-info text-left ttl-border  m-t-10">
                                                    <h6>@lang('modules.invoices.unitPrice')</h6>
                                                    <span>{{ currencyFormat($item->unit_price) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class=" ttl-info text-left ttl-border  m-t-10">
                                                    <h6>@lang('modules.invoices.type')</h6>
                                                    @foreach($taxes as $tax)
                                                        @if (isset($item->taxes) && array_search($tax->id, json_decode($item->taxes)) !== false)
                                                            <span>{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</span>
                                                        @else
                                                            <span>&nbsp;</span>
                                                        @endif
                                                    @endforeach
                                                    
                                                </div>
                                            </td>
                                            <td>
                                                <div class=" ttl-info text-left ttl-border  m-t-10">
                                                    <h6>@lang('modules.invoices.amount')</h6>
                                                    <span>{{ currencyFormat($item->amount) }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                        

                                        <tr class="tblFooter graybg">
                                            <td colspan="4">@lang('modules.invoices.total')</th>                              
                                            <td>{{ $invoice->currency->currency_symbol.''.currencyFormat($invoice->total) }}</td>
                                        </tr>
                                    
                                        
                                    </tbody>
                                </table>
                            </div>    

                            
                            <div class="row">
                                <div class="col-md-12 m-t-20">
                                    <div class="pull-right job-payment">
                                        @if($invoice->status == 'unpaid' && ($credentials->paypal_status == 'active' || $credentials->stripe_status == 'active'))
                                            <p class="red">Amount Due: {{ $invoice->currency->currency_symbol.''.currencyFormat($invoice->total) }}</p>
                                        

                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-primary dropdown-toggle" id="btnGroupVerticalDrop3" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="credit-card"></i> Add Card Payment</button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop3">
                                                @if($credentials->stripe_status == 'active')
                                                    <a class="dropdown-item stripePaymentButton" href="javascript:void(0);" data-amount="{{$invoice->total}}" data-invoice-id="{{$invoice->id}}"><i class="fa fa-credit-card"></i> @lang('modules.invoices.payStripe') </a>
                                                @endif

                                                @if($credentials->paypal_status == 'active' && !empty($credentials->paypal_client_id && $credentials->paypal_secret) )
                                                    <a class="dropdown-item" href="{{ route('client.paypal', [$invoice->id]) }}"><i class="fa fa-credit-card"></i> @lang('modules.invoices.payPaypal') </a>
                                                @endif                                                

                                                @if($credentials->razorpay_status == 'active')
                                                    <a class="dropdown-item razorpayPaymentButton" href="javascript:void(0);" data-amount="{{$invoice->total}}" data-invoice-id="{{$invoice->id}}"><i class="fa fa-credit-card"></i>  @lang('modules.invoices.payRazorpay')  </a>
                                                @endif                                                            
                                            </div>
                                        </div>
                                        @else
                                            <p class="text-success"><b>Amount Paid</b></h4>
                                        @endif
                                    </div>
                                </div>  
                            </div>                   
                                                  
                            
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

    </div>
</div>


{{--Ajax Modal--}}
<div class="modal fade bs-modal-md in" id="projectStatusModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" id="modal-data-application">
        <div class="modal-content">
            <div class="modal-header">
                <h5  class="modal-title">Update Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->.
</div>

<input type="hidden" id="invoiceID" />
{{--Ajax Modal Ends--}}
@endsection
 @push('footer-script')
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script type="text/javascript">

$('#UpdateStatus').click(function () {
    var id = '{{$project->id}}';
    var url = '{{ route('admin.projects.job-status', ":id")}}';
    url = url.replace(':id', id);
    $('#modelHeading').html('Update Status');
    $.ajaxModal('#projectStatusModal', url);
});


    $('ul.showProjectTabs .projects .nav-link').addClass('active');
</script>

<script>
$(function() {

    $(".progress").each(function() {

        var value = $(this).attr('data-value');
        var left = $(this).find('.progress-left .progress-bar');
        var right = $(this).find('.progress-right .progress-bar');

        if (value > 0) {
            if (value <= 50) {
            right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)')
            } else {
            right.css('transform', 'rotate(180deg)')
            left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)')
            }
        }

    })

    function percentageToDegrees(percentage) {
        return percentage / 100 * 360
    }

    

});

@if(!empty($invoices))

    @if($credentials->stripe_status == 'active')
        var handler = StripeCheckout.configure({
                key: '{{ $credentials->stripe_client_id }}',
                image: '{{ $global->logo_url }}',
                locale: 'auto',
                token: function(token) {
                    // You can access the token ID with `token.id`.
                    // Get the token ID to your server-side code for use.
                    var invoiceID = $('#invoiceID').val();
                    var url = "{{ route('client.stripe',':id') }}";
                        url = url.replace(':id', invoiceID);
                    $.easyAjax({

                        url: url,
                        container: '#invoice_container',
                        type: "POST",
                        redirect: true,
                        data: {token: token, "_token" : "{{ csrf_token() }}"}
                    })
                }
            });

        $('.stripePaymentButton').click(function(e){

            var amount    = $(this).data('amount'),
                invoiceID = $(this).data('invoice-id');
            
            $('#invoiceID').val(invoiceID);
            // Open Checkout with further options:
            handler.open({
                name: '{{ $companyName }}',
                amount: amount * 100,
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
                var amount    = $(this).data('amount');
                var invoiceId = $(this).data('invoice-id');
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
                    url:'{{route('client.pay-with-razorpay')}}',
                    data: {paymentId: id,invoiceId: invoiceId,rData: rData,_token:'{{csrf_token()}}'}
                })
            }

        @endif
    @endif

    

    
</script>

@endpush
