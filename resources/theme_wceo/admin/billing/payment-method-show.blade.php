@extends('layouts.app')

@push('head-script')
<style>

</style>
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
                    {{--<div class="col">
                        <div class="bookmark pull-right">
                            <a href="{{ route('admin.billing.packages') }}" class="btn btn-outline btn-primary btn-sm">@lang('modules.billing.changePlan')</a>
                        </div>
                    </div>--}}

                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
@include('sections.account_billing_menu')
    <div class="container-fluid">
        <div class="page-header p-t-0">
            <div class="card">
                
                <div class="card-body">               

                    <div class="d-flex flex-row">
                        <div class="col-md-4 p-l-0">
                            <div class="checkout-sidebar">
                                <img src="{{ $package->image_url}}" alt="{{$package->name}}" />
                                <p>You will be charged ${{$zoho_plan['data']['recurring_price']}} {{ $zoho_plan['data']['name'] == 'Monthly' ? 'monthly' : 'yearly'}}, until you cancel your subscription. You can get a refund within 14 days of starting your subscription.</p>
                                <img src="{{$global->checkout_left_image_url}}" />
                            </div>
                        </div>
                        <div class="col-md-8 p-r-0">
                            <div class="checkout-wrap">
                                <div class="heading">
                                    <h4>Billed Now: {{ $company->package->currency->currency_symbol }}{{ $amount }}</h4>
                                    <h2>{{$company->package->name}} Plan</h2>
                                </div>
                                @if($checkout_url)
                                    <iframe id="myiFrame" style="min-height: 950px" width="100%" frameborder="0"  src="{{$checkout_url}}"></iframe>
                                @elseif($error)
                                    <div class="alert alert-danger">{{$error}}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                   

                </div>
            </div>
        </div>
    </div>



@endsection

@push('footer-script')

@endpush

