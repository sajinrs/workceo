@extends('layouts.super-admin')

@section('page-title')
    <div class="col-md-12">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                            href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                                <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                            </ol>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="panel panel-inverse">

                        <div class="card-header">

                            <div class="col-sm-12">
                                @include('sections.super_admin_payment_setting_menu')


                                <div class="tab-content">
                                    <div id="vhome3" class="tab-pane active">

                                        <div class="card-header">
                                            <h5> @lang('app.menu.onlinePayment')</h5>

                                            <div class="row">
                                                <div class="col-sm-12 col-xs-12 ">


                                                    {!! Form::open(['id'=>'updateSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                                                    <div class="card-header">
                                                        <h3 class="box-title text-success">Paypal</h3>

                                                    </div>

                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                                <div class="form-body">
                                                                    <div class="row">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label>@lang('modules.paymentSetting.paypalClientId')</label>
                                                                    <input type="text" name="paypal_client_id"
                                                                           id="paypal_client_id"
                                                                           class="form-control"
                                                                           value="{{ $credentials->paypal_client_id }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label>@lang('modules.paymentSetting.paypalSecret')</label>
                                                                    <input type="password" name="paypal_secret"
                                                                           id="paypal_secret"
                                                                           class="form-control"
                                                                           value="{{ $credentials->paypal_secret }}">
                                                                    <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-6 col-xs-12">

                                                                <div class="form-group ">
                                                                    <label>Select environment</label>
                                                                    <select class="form-control" name="paypal_mode"
                                                                            id="paypal_mode" data-style="form-control">
                                                                        <option value="sandbox"
                                                                                @if($credentials->paypal_mode == 'sandbox') selected @endif>
                                                                            Sandbox
                                                                        </option>
                                                                        <option value="live"
                                                                                @if($credentials->paypal_mode == 'live') selected @endif>
                                                                            Live
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-12 col-md-6 col-xs-12">

                                                                <div class="form-group ">
                                                                    <label class="control-label">@lang('modules.payments.paypalStatus')</label>


                                                                    <div class="switchery-demo media-body text-left icon-state">
                                                                        <label class="switch">
                                                                            <input type="checkbox"
                                                                                   data-type-name="paypal"
                                                                                   name="paypal_status"
                                                                                   @if($credentials->paypal_status == 'active') checked @endif />
                                                                            <span class="switch-state"></span>
                                                                        </label>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-6 col-xs-12">

                                                                <div class="form-group ">
                                                                    <label for="mail_from_name">@lang('app.webhook')</label>
                                                                    <p class="text-bold">{{ route('verify-billing-ipn') }}</p>
                                                                    <p class="text-info">
                                                                        (@lang('messages.addPaypalWebhookUrl')
                                                                        )</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 m-t-20">
                                                                <h3 class="box-title text-warning">@lang('modules.paymentSetting.stripe')</h3>
                                                                <hr class="m-t-0 m-b-20">
                                                            </div>

                                                            <div class="col-sm-12 col-md-6 col-xs-12">

                                                                <div class="form-group ">
                                                                    <label>@lang('modules.paymentSetting.stripeClientId')</label>
                                                                    <input type="text" name="api_key"
                                                                           id="stripe_client_id"
                                                                           class="form-control"
                                                                           value="{{ $credentials->api_key }}">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label>@lang('modules.paymentSetting.stripeSecret')</label>
                                                                    <input type="password" name="api_secret"
                                                                           id="stripe_secret"
                                                                           class="form-control"
                                                                           value="{{ $credentials->api_secret }}">
                                                                    <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label>@lang('modules.paymentSetting.stripeWebhookSecret')</label>
                                                                    <input type="password" name="webhook_key"
                                                                           id="stripe_webhook_secret"
                                                                           class="form-control"
                                                                           value="{{ $credentials->webhook_key }}">
                                                                    <input type="hidden" name="bothUncheck"
                                                                           id="bothUncheck">
                                                                    <input type="hidden" name="type" id="type">
                                                                    <input type="hidden" name="_method" id="method">

                                                                    <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label for="mail_from_name">@lang('app.webhook')</label>
                                                                    <p class="text-bold">{{ route('save_webhook') }}</p>
                                                                    <p class="text-info">
                                                                        (@lang('messages.addStripeWebhookUrl'))</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/span-->

                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">@lang('modules.payments.stripeStatus')</label>


                                                                    <div class="switchery-demo media-body text-left icon-state">
                                                                        <label class="switch">
                                                                            <input type="checkbox"
                                                                                   data-type-name="stripe"
                                                                                   name="stripe_status"
                                                                                   @if($credentials->stripe_status == 'active') checked @endif />
                                                                            <span class="switch-state"></span>
                                                                        </label>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-12 col-xs-12">
                                                                <h3 class="box-title text-info">@lang('modules.paymentSetting.razorpay')</h3>
                                                                <hr class="m-t-0 m-b-20">

                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="">Razorpay Key</label>
                                                                    <input type="text" name="razorpay_key"
                                                                           id="razorpay_key"
                                                                           class="form-control"
                                                                           value="{{ $credentials->razorpay_key }}">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label>Razorpay Secret Key</label>
                                                                    <input type="password" name="razorpay_secret"
                                                                           id="razorpay_secret"
                                                                           class="form-control"
                                                                           value="{{ $credentials->razorpay_secret }}">
                                                                    <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label>Razorpay Webhook Secret Key</label>
                                                                    <input type="password"
                                                                           name="razorpay_webhook_secret"
                                                                           id="razorpay_webhook_secret"
                                                                           class="form-control"
                                                                           value="{{ $credentials->razorpay_webhook_secret }}">
                                                                    <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">@lang('modules.payments.razorpayStatus')</label>


                                                                    <div class="switchery-demo media-body text-left icon-state">
                                                                        <label class="switch">
                                                                            <input type="checkbox"
                                                                                   name="razorpay_status"
                                                                                   @if($credentials->razorpay_status == 'active') checked @endif />
                                                                            <span class="switch-state"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>

                                                        <!--/row-->

                                                    </div>
                                                    <div class="card-footer  text-right form-actions">
                                                        <button type="submit" id="save-form" class="btn btn-primary"><i
                                                                    class="fa fa-check"></i>
                                                            @lang('app.save')
                                                        </button>

                                                    </div>
                                                    {!! Form::close() !!}
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

    <!-- .row -->

    <!-- .row -->


@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
    <script>

        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());
        });

        $('#save-form').click(function () {
            var url = '{{route('super-admin.stripe-settings.update', $credentials->id)}}';
            $('#method').val('PUT');
            $.easyAjax({
                url: url,
                type: "POST",
                container: '#updateSettings',
                data: $('#updateSettings').serialize()
            })
        });

    </script>
@endpush
