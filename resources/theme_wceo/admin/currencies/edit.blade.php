@extends('layouts.app')

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
                             <li class="breadcrumb-item"><a  href="{{ route('admin.settings.index') }}">@lang('app.menu.settings')</a></li>
                            <li class="breadcrumb-item active"><a  href="{{ route('admin.currency.index') }}">{{ __($pageTitle) }}</a></li>
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
                          <h5>@lang('modules.currencySettings.updateTitle') </h5>
                        </div>

            {!! Form::open(['id'=>'updateCurrency','class'=>'ajax-form','method'=>'PUT']) !!}
            <div  class="card-body">
                <div class="form-body" >
                <div class="row">
                    <div class="col-sm-12 col-xs-12">                        
                        <div class="form-label-group form-group">
                         
                            <input type="text" class="form-control form-control-lg" id="currency_name" name="currency_name" value="{{ $currency->currency_name }}" placeholder="*">
                            <label for="currency_name" class="col-form-label required">@lang("modules.currencySettings.currencyName")</label>
                        </div>
                        <div class="form-group">
                            <label>@lang('modules.currencySettings.isCryptoCurrency')?</label>
                            <div class="radio-list">
                                <label class="radio-inline p-0">
                                    <div class="radio radio-info">
                                        <input type="radio" name="is_cryptocurrency" @if($currency->is_cryptocurrency == 'yes') checked @endif id="crypto_currency_yes" value="yes">
                                        <label for="crypto_currency_yes">@lang('app.yes')</label>
                                    </div>
                                </label>&nbsp;&nbsp;&nbsp;
                                <label class="radio-inline">
                                    <div class="radio radio-info">
                                        <input type="radio" name="is_cryptocurrency" @if($currency->is_cryptocurrency == 'no') checked @endif id="crypto_currency_no" value="no">
                                        <label for="crypto_currency_no">@lang('app.no')</label>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="form-label-group form-group" >
                         
                            <input type="text" class="form-control form-control-lg" id="currency_symbol" name="currency_symbol" value="{{ $currency->currency_symbol }}" placeholder="*">
                            <label for="currency_symbol" class="col-form-label required">@lang("modules.currencySettings.currencySymbol")</label>
                        </div>
                        <div class="form-label-group form-group">
                          
                            <input type="text" class="form-control form-control-lg" id="currency_code" name="currency_code" value="{{ $currency->currency_code }}" placeholder="*">
                            <label for="currency_code" class="col-form-label required">@lang("modules.currencySettings.currencyCode")</label>
                        </div>

                        <div class="form-label-group form-group crypto-currency" @if($currency->is_cryptocurrency == 'no') style="display: none" @endif>
                            
                            <input type="text" class="form-control form-control-lg" id="usd_price" name="usd_price" value="{{ $currency->usd_price }}" placeholder="*">
                            <label for="usd_price" class="col-form-label">@lang('modules.currencySettings.usdPrice') </label>

                            <a class="mytooltip" href="javascript:void(0)"> <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">@lang('modules.currencySettings.usdPriceInfo')</span></span></span></a>
                        </div>

                        <div class="form-label-group form-group regular-currency"  @if($currency->is_cryptocurrency == 'yes') style="display: none;" @endif>
                            <input type="text" class="form-control form-control-lg" id="exchange_rate" name="exchange_rate" value="{{ $currency->exchange_rate }}" placeholder="*">
                            <label for="exchange_rate" class="col-form-label">@lang("modules.currencySettings.exchangeRate")</label>

                            <a href="javascript:;" id="fetch-exchange-rate"><i class="icofont icofont-refresh icon-spin"></i> Fetch latest exchange rate</a>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="card-footer text-right">
            <div class="form-actions row">
                <div class="col-md-3 offset-md-6">
                    <button type="submit" id="save-form" class="btn btn-primary  form-control"> @lang('app.save')</button>
                </div>
                <div class="col-md-3">
                    <button type="reset" id="resetForm" class="btn btn-secondary form-control">@lang('app.reset')</button>
                </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
<script>
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.currency.update', $currency->id )}}',
            container: '#updateCurrency',
            type: "POST",
            data: $('#updateCurrency').serialize()
        })
    });    

    $("input[name=is_cryptocurrency]").click(function () {
        if($(this).val() == 'yes'){
            $('.regular-currency').hide();
            $('.crypto-currency').show();
        }
        else{
            $('.crypto-currency').hide();
            $('.regular-currency').show();
        }
    })

    $('#fetch-exchange-rate').click(function () {
        var currencyCode = $('#currency_code').val();
        var url = '{{route('admin.currency.exchange-rate', '#cc' )}}';
        url = url.replace('#cc', currencyCode);

        $.easyAjax({
            url: url,
            type: "GET",
            data: {currencyCode: currencyCode},
            success: function (response) {
                $('#exchange_rate').val(response);
            }
        })
    });
</script>
@endpush

