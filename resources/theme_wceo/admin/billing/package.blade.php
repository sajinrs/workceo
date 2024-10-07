@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.billing') }}">@lang('app.menu.billing')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>               

            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="page-header">
            <div class="card">
                <div class="card-header"> <h5>@lang('app.monthly') @lang('app.menu.packages')</h5> </div>
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
                            @if($stripeSettings->paypal_status == 'inactive'  && $stripeSettings->stripe_status == 'inactive'  && $stripeSettings->razorpay_status == 'deactive')
                            <div class="col-md-12">
                                <div class="alert alert-danger">
                                    {{__('messages.noPaymentGatewayEnabled')}}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="priceTable table table-hover table-bordered text-center" >
                                    <thead class="thead-light">
                                        <tr class="active">
                                            <th style="background:#fff !important; min-width:80px;"></th>
                                            @foreach($packages as $package)
                                                <th style="@if(($package->id == $company->package->id && $company->package_type == 'monthly')) background-color:#a6ebff5e !important; @endif">
                                                    <center>
                                                        <h4 >{{ucfirst($package->name)}}</h4>
                                                    </center>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><br>@lang('app.price')</td>
                                            @foreach($packages as $package)
                                                <td class="@if(($package->id == $company->package->id && $company->package_type == 'monthly')) selected-plan @endif"><h3 class="panel-title price ">{{ $package->currency->currency_symbol }}{{ round($package->monthly_price) }}</h3></td>
                                            @endforeach
                                        </tr>

                                        <tr>
                                            <td>@lang('app.menu.employees')</td>
                                            @foreach($packages as $package)
                                                <td class="@if(($package->id == $company->package->id && $company->package_type == 'monthly')) selected-plan @endif">{{ $package->max_employees }} @lang('modules.projects.members')</td>
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
                                                <td>{{ ucfirst($key) }}</td>
                                                @foreach($packages as $package)
                                                    @php $available = in_array(strtoupper(trim($package->name)), $module); @endphp
                                                    <td class="@if(($package->id == $company->package->id && $company->package_type == 'monthly')) selected-plan @endif"><i class="fa {{ $available ? 'fa-check text-megna' : 'fa-times text-danger'}} fa-lg"></i></td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        <tr><td></td>

                                            @foreach($packages as $package)
                                                <td>
                                                    @if(round($package->monthly_price) > 0)
                                                        @if(!($package->id == $company->package->id && $company->package_type == 'monthly')  && ($stripeSettings->paypal_status == 'active' || $stripeSettings->stripe_status == 'active' || $stripeSettings->razorpay_status == 'active' || $offlineMethods > 0))
                                                            <button type="button" data-package-id="{{ $package->id }}" data-package-type="monthly" class="btn btn-primary waves-effect waves-light selectPackage" title="Choose Plan"><i class="icon-anchor display-small"></i><span class="display-big">@lang('modules.billing.choosePlan')</span></button>
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

                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid">
        <div class="page-header">
            <div class="card">
                <div class="card-header"> <h5>@lang('app.annual') @lang('app.menu.packages')</h5> </div>
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="priceTable table table-hover table-bordered text-center" >
                                    <thead class="thead-light">
                                        <tr class="active">
                                            <th style="background:#fff !important"><center></center></th>
                                            @foreach($packages as $package)
                                                <th style="@if(($package->id == $company->package->id && $company->package_type == 'annual')) background-color:#a6ebff5e !important; @endif"><center><h4>{{ucfirst($package->name)}}</h4></center></th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                        <tbody>
                                            <tr>
                                                <td><br>@lang('app.price')</td>
                                                @foreach($packages as $package)
                                                    <td class="@if(($package->id == $company->package->id && $company->package_type == 'annual')) selected-plan @endif"><h3 class="panel-title price">{{ $package->currency->currency_symbol }}{{ round($package->annual_price) }}</h3></td>
                                                @endforeach
                                            </tr>

                                            <tr>
                                                <td>@lang('app.menu.employees')</td>
                                                @foreach($packages as $package)
                                                    <td class="@if(($package->id == $company->package->id && $company->package_type == 'annual')) selected-plan @endif">{{ $package->max_employees }} @lang('modules.projects.members')</td>
                                                @endforeach
                                            </tr>

                                            @foreach($moduleArray as $key => $module)
                                                <tr>
                                                    <td>{{ ucfirst($key) }}</td>
                                                    @foreach($packages as $package)
                                                        @php $available = in_array(strtoupper(trim($package->name)), $module); @endphp
                                                        <td class="@if(($package->id == $company->package->id && $company->package_type == 'annual')) selected-plan @endif"><i class="fa {{ $available ? 'fa-check text-megna' : 'fa-times text-danger'}} fa-lg"></i></td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            <tr><td></td>

                                                @foreach($packages as $package)
                                                    <td>
                                                        @if(round($package->annual_price) > 0)
                                                            @if(!($package->id == $company->package->id && $company->package_type == 'annual')
                                                            && ($stripeSettings->paypal_status == 'active'  || $stripeSettings->stripe_status == 'active'  || $stripeSettings->razorpay_status == 'active' || $offlineMethods > 0))
                                                                <button type="button" data-package-id="{{ $package->id }}" data-package-type="annual" class="btn btn-primary waves-effect waves-light selectPackage" title="Choose Plan"><i class="icon-anchor display-small"></i><span class="display-big">@lang('modules.billing.choosePlan')</span></button>
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

                </div>
            </div>
        </div>
    </div>


{{--Ajax Modal--}}
<div class="modal fade bs-modal-lg in" id="package-select-form" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal-data-application">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelHeading">Change Plan</h5>
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

{{--Ajax Modal--}}
<div class="modal fade bs-modal-lg in" id="package-offline" role="dialog" aria-labelledby="myModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg" id="modal-data-application">
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
{{--Ajax Modal Ends--}}
@endsection

@push('footer-script')
    <script src="https://js.stripe.com/v3/"></script>
   
<script>
// $(document).ready(function() {
// show when page load
@if(\Session::has('message'))
toastr.success({{  \Session::get('message') }});
@endif
// });

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
$.ajaxModal('#package-select-form', url);
});
</script>
@endpush
