<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">


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
    <style>
    #invoice_container h4 span {font-size: 18px; top: 10px; position: relative;}
    </style>
    
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
        <div class="container">

            <!-- .row -->
            <div class="row" style="margin-top: 70px; !important;">

                <div class="offset-md-1 col-md-10" id="estimates">
                    <div class="row m-b-20">
                        <div class="col-md-12 estimateBtn">
                            <div class="visible-xs">
                                <div class="clearfix"></div>
                            </div>
                            @if($estimate->status == 'waiting')
                            <button type="button" id="accept_action" class="btn btn-primary pull-right m-r-10" onclick="accept();return false;"> @lang('app.accept')</button>
                            <button type="submit" class="btn btn-danger pull-right m-r-10" onclick="decline();return false;"><i class="fa fa-remove"></i> @lang('app.decline')</button>

                            @elseif($estimate->status == 'accepted')
                                <a href="javascript:;" class="btn btn-primary pull-right m-r-10"> @lang('app.signed')</a>
                            @endif
                            <a href="{{ route("front.estimateDownload", md5($estimate->id)) }}" class="btn btn-secondary pull-right m-r-10"><i class="fa fa-file-pdf-o"></i> Download</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="card">
                        @if($estimate->status == 'waiting')
                            <div class="ribbon ribbon-bookmark ribbon-warning">@lang('modules.estimates.waiting')</div>
                        @endif
                        @if($estimate->status == 'declined')
                            <div class="ribbon ribbon-bookmark ribbon-danger">@lang('modules.estimates.declined')</div>
                        @endif

                        @if($estimate->status == 'accepted')
                            <div class="ribbon ribbon-bookmark ribbon-success">@lang('modules.estimates.accepted')</div>
                        @endif
                        <div class="card-body">
                            <div class="white-box printableArea ribbon-wrapper" style="background: #ffffff !important;">
                                <div class="ribbon-content " id="invoice_container">
                                   

                                    <h4><b>@lang('app.estimate')</b> <span class="pull-right">{{ $estimate->estimate_number }}</span></h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="pull-left">
                                                <address>
                                                    <h4> &nbsp;<b class="text-danger">{{ ucwords($global->company_name) }}</b></h4>
                                                    @if(!is_null($settings) && !is_null($global->address))
                                                        <p class="text-muted m-l-5">{!! nl2br($global->address) !!}</p>
                                                    @endif
                                                </address>
                                            </div>
                                            <div class="pull-right text-right">
                                                <address>
                                                    @if(!is_null($estimate->client))
                                                        <h5>@lang('app.to'),</h5>
                                                        <h5 class="font-bold">{{ ucwords($estimate->client->name) }}</h5>

                                                        @if(!is_null($estimate->client_details))
                                                            <p class="text-muted m-l-30">{!! nl2br($estimate->client_details->address) !!}</p>
                                                        @endif
                                                    @endif
                                                    <p class="m-t-30"><b>@lang('modules.estimates.validTill') :</b> <i
                                                                class="fa fa-calendar"></i> {{ $estimate->valid_till->format($settings->date_format) }}
                                                    </p>
                                                </address>
                                            </div>
                                        </div>
                                        <div class="col-md-12">

                                        <div class="table-responsive">
                                            <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center">#</th>
                                                    <th scope="col" class="text-center">@lang("modules.invoices.item")</th>
                                                    <th scope="col" class="text-center">@lang("modules.invoices.qty")</th>
                                                    <th scope="col" class="text-center">@lang("modules.invoices.unitPrice")</th>
                                                    <th scope="col" class="text-center">@lang("modules.invoices.price")</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $count = 0; ?>
                                                    @foreach($estimate->items as $item)
                                                    @if($item->type == 'item')
                                                    <tr>
                                                        <th scope="row" class="text-center">{{ ++$count }}</th>
                                                        <td class="text-left">{{ ucfirst($item->item_name) }}
                                                                @if(!is_null($item->item_summary))
                                                                    {{ $item->item_summary }}
                                                                @endif
                                                        </td>
                                                        <td class="text-center">{{ $item->quantity }}</td>
                                                        <td class="text-center"> {!! htmlentities($estimate->currency->currency_symbol)  !!}{{ currencyFormat($item->unit_price) }} </td>
                                                        <td class="text-center"> {!! htmlentities($estimate->currency->currency_symbol)  !!}{{ currencyFormat($item->amount) }} </td>
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
                                                    : {!! htmlentities($estimate->currency->currency_symbol)  !!}{{ currencyFormat($estimate->sub_total) }}</p>

                                                <p>@lang("modules.invoices.discount")
                                                    : {!! htmlentities($estimate->currency->currency_symbol)  !!}{{ $discount }} </p>
                                                @foreach($taxes as $key=>$tax)
                                                    <p>{{ strtoupper($key) }}
                                                        : {!! htmlentities($estimate->currency->currency_symbol)  !!}{{ $tax }} </p>
                                                @endforeach
                                                <hr>
                                                <h4><b>@lang("modules.invoices.total")
                                                        :</b> {!! htmlentities($estimate->currency->currency_symbol)  !!}{{ currencyFormat($estimate->total) }}
                                                </h4>
                                            </div>

                                           
                                            <div class="clearfix"></div>
                                            <hr>
                                            <div>
                                                <div class="col-md-12">
                                                    <span><p class="displayNone" id="methodDetail"></p></span>
                                                </div>
                                            </div>
                                        </div>

                                        @if(!is_null($estimate->note))
                                                <div class="col-md-12">
                                                    <p><strong>@lang('app.note')</strong>: {{ $estimate->note }}</p>
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


    {{--Timer Modal--}}
    <div class="modal fade bs-modal-md in" id="estimateAccept" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                    <button type="button" class="btn blue">@lang('app.accept')</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Timer Modal Ends--}}
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
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>    
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>   

<script>
    //Decline estimate
    function decline() {
        $.easyAjax({
            type:'POST',
            url:'{{route('front.estimate.decline', $estimate->id)}}',
            container:'#estimates',
            data: {_token: '{{ csrf_token() }}'},
            success: function(response){
                if(response.status == 'success') {
                    window.location.reload();
                }
            }
        })
    }

    //Accept estimate
    function accept() {
        var url = '{{ route('front.estimate.accept', $estimate->id) }}';
        $.ajaxModal('#estimateAccept', url);
    }
</script>

</body>
</html>
