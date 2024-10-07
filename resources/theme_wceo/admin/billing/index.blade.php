@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
@endpush
@section('page-title')
    <div class="col-md-12">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a  href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                                <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                            </ol>
                        </div>
                    </div>                  
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')


    

    <div class="container-fluid">
        @if (Session::has('message'))
        <div class="row">
            <div class="col-md-12">
                @if ( Session::has('message') && session('success'))
                    <div class="alert alert-success">{{ session('message') }}</div>

                @endif
                @if ( Session::has('message') && !session('success'))
                    <div class="alert alert-danger">{{ session('message') }}</div>

                @endif
            </div>
        </div>
            <?php Session::forget('success');Session::forget('message');?>
        @endif
    <div class="row">

        <div class="col-md-3">
        @include('sections.account_billing_menu')
       </div>

            <!-- Zero Configuration  Starts-->
            <div class="col-sm-9">
                <div class="card">
                    <div class="card-body plan-details">
                        <div class="row">
                            <div class="col-md-5">
                                <img src="{{ $company->package->image_url}}" />
                            </div>

                            
                            <div class="col-md-7">
                                <h3>@lang('modules.billing.yourCurrentPlan')</h3>
                                <h2>{{  $company->package->name }} 
                                @if(isset($zoho_subscription_details) && $zoho_subscription_details['status'] == 'trial')
                                <span class="text-danger">(Remaining: {{$zoho_subscription_details['trial_remaining_days']}} {{$zoho_subscription_details['trial_remaining_days'] == 1 ? 'Day' : 'Days'}})</span>
                                @endif
                                </h2>
                                <p>{{ $company->employees->count() }} of {{ $company->package->max_employees }} Users (Employees) </p>
                                <div class="progress" style="height: 12px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{$empPercentage}}%"></div>
                                </div>
                                @if($previousPaymentDate)<p>Previous Payment Date: <span class="date">{{ $previousPaymentDate }}</span> </p>@endif
                                <p>Next Payment Date: <span class="date">{{ $nextPaymentDate }}</span> </p>

                                <div class="row">
                                    <div class="col-md-6">
                                    <h2>{{ $company->package->currency->currency_symbol }}{{ $amount }}<span>/{{$interval}}</span></h2>
                                    </div>
                                    <div class="col-md-6">
                                    <a href="#packageList" class="pull-right btn btn-outline btn-primary btn-sm m-t-5">Choose Plan</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        

                    </div>
                </div>


                <div id="packageList">
        <div class="page-header">
            <div class="card">
                <!-- <div class="card-header"> <h5>@lang('app.monthly') @lang('app.menu.packages')</h5> </div> -->
                <div class="card-header"> 
                        <div class="col-md-12">
                        <div class="package-tabs">
                            <a id="monthBtn" class="btn btn-light active" href="javascript:;">Billed Monthly</a> <a id="annualBtn" class="btn btn-light" href="javascript:;">Billed Annually (Save Up to 30%)</a> 
                        </div>
                        </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                                <?php Session::forget('success');?>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                                <?php Session::forget('error');?>
                            @endif
{{--                            @if($stripeSettings->paypal_status == 'inactive'  && $stripeSettings->stripe_status == 'inactive'  && $stripeSettings->razorpay_status == 'deactive')--}}
{{--                                <div class="col-md-12">--}}
{{--                                    <div class="alert alert-danger">--}}
{{--                                        {{__('messages.noPaymentGatewayEnabled')}}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                       --}}

                        </div>
                    </div>

                    
                    <div id="monthlyPack">
                        <div class="row">
                            <div class="col-md-12">
                            
                                <div class="text-center"><img class="pack-banner" src="{{$global->package_banner_monthly_url}}" /></div>
                                <div class="table-responsive">
                                    <table class="priceTable table table-hover table-bordered text-center" >
                                        <thead class="thead-light">
                                        <tr class="active">
                                            <th style="background:#fff !important; min-width:80px;"></th>
                                            @foreach($packages as $package)
                                                <th style="width:235px;" class="@if(($package->id == $company->package->id && $company->package_type == 'monthly')) active-pack @endif">
                                                    <center>
                                                        <h4 >{{$package->name}}</h4>
                                                        {{--<img src="{{ $package->image_url}}" />--}}
                                                    </center>
                                                </th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="label no-border"><br>@lang('app.price')</td>
                                            @foreach($packages as $key=> $package)
                                                <td class="no-border item-{{$key}} @if(($package->id == $company->package->id && $company->package_type == 'monthly')) selected-plan @endif"><h3 class="panel-title price ">{{ $package->currency->currency_symbol }}{{ round($package->monthly_price) }}<span>/month</span></h3>
                                                @if(!($package->id == $company->package->id && $company->package_type == 'monthly')) 
                                                <button type="button" data-package-id="{{ $package->id }}" data-package-type="monthly" class="btn btn-block btn-primary waves-effect waves-light selectPackage m-t-5"><span class="display-big">{{ $package->free_trial_days == 0 ? 'Choose Plan' : 'Start Free Trial' }}</span></button>
                                                @endif
                                            </td>
                                            @endforeach
                                        </tr>

                                        <tr>
                                            <td class="label">@lang('app.menu.employees')</td>
                                            @foreach($packages as $package)
                                                <td class="@if(($package->id == $company->package->id && $company->package_type == 'monthly')) selected-plan @endif">{{ $package->max_employees }} Users</td>
                                            @endforeach
                                        </tr>

                                        <tr>
                                            @php
                                                $moduleArray = [];
                                                foreach($modulesData as $module) {
                                                    $moduleArray[$module->module_name] = [];
                                                }
                                            @endphp

                                            @foreach($packages as $package)
                                                @foreach((array)json_decode($package->module_in_package) as $MIP)
                                                    @if (array_key_exists($MIP, $moduleArray))
                                                        @php $moduleArray[$MIP][] = strtoupper(trim($package->name)); @endphp
                                                    @else
                                                        @php $moduleArray[$MIP] = [strtoupper(trim($package->name))]; @endphp
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </tr>

                                        @foreach($moduleArray as $key => $module)
                                            <tr>
                                                <td class="label">{{ ucfirst($key) }}</td>
                                                @foreach($packages as $package)
                                                    @php $available = in_array(strtoupper(trim($package->name)), $module); @endphp
                                                    <td class="@if(($package->id == $company->package->id && $company->package_type == 'monthly')) selected-plan @endif"><i class="fa {{ $available ? 'fa-check-circle text-megna' : 'fa-times-circle'}} fa-lg"></i></td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        <tr><td></td>

                                            @foreach($packages as $package)
                                                <td>
                                                    @if(round($package->monthly_price) > 0)
                                                        @if(!($package->id == $company->package->id && $company->package_type == 'monthly')  && ($stripeSettings->paypal_status == 'active' || $stripeSettings->stripe_status == 'active' || $stripeSettings->razorpay_status == 'active' || $offlineMethods > 0))
                                                            <button type="button" data-package-id="{{ $package->id }}" data-package-type="monthly" class="btn btn-block btn-primary waves-effect waves-light selectPackage"><span class="display-big">{{ $package->free_trial_days == 0 ? 'Choose Plan' : 'Start Free Trial' }}</span></button>
                                                        @endif
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div><!--Monthly pack end-->
                    
                    <div id="annualPack" class="d-none">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-center"><img class="pack-banner" src="{{$global->package_banner_annually_url}}" /></div>
                                <div class="table-responsive">
                                    <table class="priceTable table table-hover table-bordered text-center" >
                                        <thead class="thead-light">
                                        <tr class="active">
                                            <th style="background:#fff !important"><center></center></th>
                                            @foreach($packages as $package)
                                                <th style="width:235px;" class="@if(($package->id == $company->package->id && $company->package_type == 'annual')) active-pack @endif">
                                                    <center>
                                                        <h4>{{ucfirst($package->name)}}</h4>
                                                        {{--<img src="{{ $package->image_url}}" />--}}
                                                </center>
                                            </th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="label no-border"><br>@lang('app.price')</td>
                                            @foreach($packages as $key=> $package)

                                            
                                                <td class="no-border item-{{$key}} @if(($package->id == $company->package->id && $company->package_type == 'annual')) selected-plan @endif"><h3 class="panel-title price">{{ $package->currency->currency_symbol }}{{ round($package->annual_price) }}<span>/year</span></h3>

                                                @if(round($package->annual_price) > 0)
                                                        @if(!($package->id == $company->package->id && $company->package_type == 'annual')
                                                        && ($stripeSettings->paypal_status == 'active'  || $stripeSettings->stripe_status == 'active'  || $stripeSettings->razorpay_status == 'active' || $offlineMethods > 0))
                                                            <button type="button" data-package-id="{{ $package->id }}" data-package-type="annual" class="btn btn-block btn-primary waves-effect waves-light selectPackage"><span class="display-big">{{ $package->free_trial_days == 0 ? 'Choose Plan' : 'Start Free Trial' }}</span></button>
                                                        @endif
                                                    @endif

                                                
                                            </td>
                                            @endforeach
                                        </tr>

                                        <tr>
                                            <td class="label">@lang('app.menu.employees')</td>
                                            @foreach($packages as $package)
                                                <td class="@if(($package->id == $company->package->id && $company->package_type == 'annual')) selected-plan @endif">{{ $package->max_employees }} Users</td>
                                            @endforeach
                                        </tr>

                                        @foreach($moduleArray as $key => $module)
                                            <tr>
                                                <td class="label">{{ ucfirst($key) }}</td>
                                                @foreach($packages as $package)
                                                    @php $available = in_array(strtoupper(trim($package->name)), $module); @endphp
                                                    <td class="@if(($package->id == $company->package->id && $company->package_type == 'annual')) selected-plan @endif"><i class="fa {{ $available ? 'fa-check-circle text-megna' : 'fa-times-circle'}} fa-lg"></i></td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        <tr><td></td>

                                            @foreach($packages as $package)
                                                <td>
                                                    @if(round($package->annual_price) > 0)
                                                        @if(!($package->id == $company->package->id && $company->package_type == 'annual')
                                                        && ($stripeSettings->paypal_status == 'active'  || $stripeSettings->stripe_status == 'active'  || $stripeSettings->razorpay_status == 'active' || $offlineMethods > 0))
                                                            <button type="button" data-package-id="{{ $package->id }}" data-package-type="annual" class="btn btn-block btn-primary waves-effect waves-light selectPackage"><span class="display-big">{{ $package->free_trial_days == 0 ? 'Choose Plan' : 'Start Free Trial' }}</span></button>
                                                        @endif
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div><!--Annual pack end-->
                    <div class="text-center pack-banner">
                        <p class="info-text">Have Questions? For commonly asked questions, get more information on <a href="http://www.workceo.com/billing" target="_blank">billing at WorkCEO.</a></p>
                        <img class="" src="{{$global->billing_footer_image_url}}" />
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
                
                </div>
            </div>

        
    </div>

    

    

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-lg in" id="package-select-form" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">Choose Plan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/custom.js')}}"></script>--}}
    <script>

        @if(\Session::has('message'))
        toastr.success("{{  \Session::get('message') }}");
        @endif

        $('body').on('click', '.unsubscription', function(){
            var type = $(this).data('type');
            swal({
                title: "Are you sure?",
                text: "Do you want to unsubscribe this plan!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Unsubscribe it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.billing.unsubscribe') }}";
                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'POST', 'type': type},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                                table._fnDraw();
                            }
                        }
                    });
                }
            });
        });


        // Show Create Holiday Modal
        $('body').on('click', '.selectPackage', function(){
            var id = $(this).data('package-id');
            var type = $(this).data('package-type');
            var url = "{{ route('admin.billing.select-package',':id') }}?type="+type;
            url = url.replace(':id', id);
            //$.ajaxModal('#package-select-form', url);
            window.location.href = url;
        });

        $('#monthBtn').click(function(){
            $('.package-tabs a').removeClass('active');
            $(this).addClass('active');
            $('#annualPack').addClass('d-none');
            $('#monthlyPack').removeClass('d-none');
        });

        $('#annualBtn').click(function(){
            $('.package-tabs a').removeClass('active');
            $(this).addClass('active');
            $('#monthlyPack').addClass('d-none');
            $('#annualPack').removeClass('d-none');
        })

    </script>


@endpush