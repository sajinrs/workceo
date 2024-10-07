<div class="row">
    <div class="col-sm-12">
        <div class="f-z-10">{!! $pp_rate !!} from prior period</div>
        <div class="f-z-10">{{$currency->currency_symbol}}{{number_format($ytd)}} total YTD</div>
    </div>
    <div class="col-sm-12">
        <h1 class="font-primary text-center" style="{{$style}}">{{$currency->currency_symbol}}<span class="counter">{{$payment_total}}</span></h1>
        <h6 class="mb-0 text-center">Revenue</h6>
    </div>
</div>