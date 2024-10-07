@extends('layouts.super-admin')
@section('page-title')
    <div class="col-md-12">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3><i class="{{ $pageIcon }}"></i> {{ ($pageTitle) }}</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                            href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                                <li class="breadcrumb-item active"><a
                                            href="{{ route('super-admin.currency.index') }}">{{ __($pageTitle) }}</a>
                                </li>
                                <li class="breadcrumb-item active">@lang('app.addNew')</li>
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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="panel panel-inverse">

                        <div class="card-header">
                            <h5>@lang('modules.currencySettings.addNewCurrency')</h5>


                        </div>

                        <div class="vtabs customvtab m-t-10">
                            <div class="tab-content">
                                <div id="vhome3" class="tab-pane active">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">

                                            {!! Form::open(['id'=>'createCurrency','class'=>'ajax-form','method'=>'POST']) !!}
                                            <div class=" card-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="company_name">@lang('modules.currencySettings.currencyName')</label>
                                                            <input type="text" class="form-control" id="currency_name"
                                                                   name="currency_name"
                                                                   placeholder="Enter Currency Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label>@lang('modules.currencySettings.isCryptoCurrency')
                                                                ?</label>
                                                            <div class="radio-list">
                                                                <label class="radio-inline p-0">
                                                                    <div class="radio radio-info">
                                                                        <input type="radio" name="is_cryptocurrency"
                                                                               id="crypto_currency_yes" value="yes">
                                                                        <label for="crypto_currency_yes">@lang('app.yes')</label>
                                                                    </div>
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <div class="radio radio-info">
                                                                        <input type="radio" name="is_cryptocurrency"
                                                                               checked id="crypto_currency_no"
                                                                               value="no">
                                                                        <label for="crypto_currency_no">@lang('app.no')</label>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-xs-12">

                                                        <div class="form-group">
                                                            <label for="company_email">@lang('modules.currencySettings.currencySymbol')</label>
                                                            <input type="text" class="form-control" id="currency_symbol"
                                                                   name="currency_symbol">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-xs-12">

                                                        <div class="form-group ">
                                                            <label for="company_phone">@lang('modules.currencySettings.currencyCode')</label>
                                                            <input type="text" class="form-control" id="currency_code"
                                                                   name="currency_code">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-xs-12">

                                                        <div class="form-group crypto-currency" style="display: none">
                                                            <label for="usd_price">@lang('modules.currencySettings.usdPrice')
                                                                <a class="mytooltip" href="javascript:void(0)"> <i
                                                                            class="fa fa-info-circle"></i><span
                                                                            class="tooltip-content5"><span
                                                                                class="tooltip-text3"><span
                                                                                    class="tooltip-inner2">@lang('modules.currencySettings.usdPriceInfo')</span></span></span></a></label>
                                                            <input type="text" class="form-control" id="usd_price"
                                                                   name="usd_price">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer  text-right">

                                                    <button type="submit" id="save-form"
                                                            class="btn btn-primary waves-effect waves-light m-r-10">
                                                        @lang('app.save')
                                                    </button>
                                                    <button type="reset"
                                                            class="btn btn-success waves-effect waves-light">@lang('app.reset')</button>
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
        <!-- .row -->

        @endsection

        @push('footer-script')
            <script>

                $("input[name=is_cryptocurrency]").click(function () {
                    if ($(this).val() == 'yes') {
                        $('.regular-currency').hide();
                        $('.crypto-currency').show();
                    } else {
                        $('.crypto-currency').hide();
                        $('.regular-currency').show();
                    }
                })


                $('#save-form').click(function () {
                    $.easyAjax({
                        url: '{{route('super-admin.currency.store')}}',
                        container: '#createCurrency',
                        type: "POST",
                        redirect: true,
                        data: $('#createCurrency').serialize()
                    })
                });
            </script>
    @endpush

