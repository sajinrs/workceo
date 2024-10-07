@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/date-picker.css') }}">    
@endpush

@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} </h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.all-invoices.index') }}">Invoices</a></li>
                            <li class="breadcrumb-item active">Convert {{ $pageTitle }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.estimates.convertEstimateTitle')</h4>
                    </div>

                    <input type="hidden" name="proposal_id" value="{{ $proposalId }}">
                    <div class="card-body">
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                            <div>
                                                <div class="input-group">
                                                    <div class="input-group-addon form-control-lg f-16"><span class="invoicePrefix" data-prefix="{{ $invoiceSetting->invoice_prefix }}">{{ $invoiceSetting->invoice_prefix }}</span>#<span class="noOfZero" data-zero="{{ $invoiceSetting->invoice_digit }}">{{ $zero }}</span></div>
                                                        <input placeholder="-" type="text"  class="form-control form-control-lg " name="invoice_number" id="invoice_number" value="@if(is_null($lastInvoice))1 @else{{ ($lastInvoice) }}@endif">
                                                </div>
                                            </div>                                 
                                    </div>                                  
                                </div>

                                <div class="col-md-4">
                                    <div class="form-label-group form-group">                                        
                                        @if(is_null($invoice->lead->client_id))
                                            <select placeholder="-" class="select2 form-control-lg form-control" data-placeholder="Choose Project"
                                                    name="client_id">
                                                @foreach($clients as $client)
                                                    <option @if($invoice->lead->client_id == $client->id) selected  @endif
                                                    value="{{ $client->id }}">{{ ucwords($client->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="client_id" class="control-label">@lang('app.client')</label>
                                        @else
                                            <span class="form-control form-control-lg">{{ ucwords($invoice->lead->client->name) }}</span>
                                            <input type="hidden" name="client_id" value="{{ $invoice->lead->client_id }}">
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-label-group form-group">                                        
                                        <select placeholder="-" class="form-control form-control-lg" name="currency_id" id="currency_id">
                                            @foreach($currencies as $currency)
                                                <option
                                                        @if($invoice->currency_id == $currency->id) selected
                                                        @endif
                                                        value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                                            @endforeach
                                        </select>
                                        <label for="currency_id" class="control-label">@lang('modules.invoices.currency')</label>
                                    </div>

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-label-group form-group">                                        
                                        <input type="text" class="form-control form-control-lg" placeholder="-" name="issue_date" id="invoice_date" value="{{ Carbon\Carbon::today()->format($global->date_format) }}">
                                        <label for="invoice_date" class="control-label">@lang('modules.invoices.invoiceDate')</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-label-group form-group">
                                        <input type="text" class="form-control form-control-lg" placeholder="-" name="due_date" id="due_date" value="{{ $invoice->valid_till->format($global->date_format) }}">
                                        <label for="due_date" class="control-label">@lang('app.dueDate')</label>

                                        <input type="hidden" name="recurring_payment" id="recurring_payment" value="no">
                                        <input type="hidden" name="project_id" id="project_id" value="">
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <div class="form-label-group form-group">                                        
                                        <select class="form-control form-control-lg" placeholder="-" name="status" id="status">
                                            <option
                                                    @if($invoice->status == 'paid') selected @endif
                                            value="paid">@lang('modules.invoices.paid')
                                            </option>
                                            <option
                                                    @if($invoice->status == 'unpaid') selected @endif
                                            value="unpaid">@lang('modules.invoices.unpaid')
                                            </option>
                                        </select>
                                        <label for="status" class="control-label">@lang('app.status')</label>
                                    </div>

                                </div>

                            </div>
                            <hr>
                            <div class="d-md-block d-lg-block">

                                <div class="row">

                                    <div class="col-md-4 font-bold" style="padding: 8px 15px">
                                        @lang('modules.invoices.item')
                                    </div>

                                    <div class="col-md-1 font-bold" style="padding: 8px 15px">
                                        @lang('modules.invoices.qty')
                                    </div>

                                    <div class="col-md-2 font-bold" style="padding: 8px 15px">
                                        @lang('modules.invoices.unitPrice')
                                    </div>

                                    <div class="col-md-2 font-bold" style="padding: 8px 15px">
                                        @lang('modules.invoices.tax') <a href="javascript:;" id="tax-settings" ><i class="icofont icofont-gear"></i></a>
                                    </div>

                                    <div class="col-md-2 text-center font-bold" style="padding: 8px 15px">
                                        @lang('modules.invoices.amount')
                                    </div>

                                    <div class="col-md-1" style="padding: 8px 15px">
                                        &nbsp;
                                    </div>

                                </div>

                                <div id="sortable">
                                    @foreach($invoice->items as $key => $item)
                                        <div class="row item-row margin-top-5">
                                            <div class="col-md-4">
                                                {{--<div class="row">--}}
                                                    <div class="form-group">
                                                        <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.item')</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                                                            <input type="text" class="form-control item_name" name="item_name[]"
                                                                   value="{{ $item->item_name }}" >
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2">{{ $item->item_summary }}</textarea>

                                                    </div>
                                                {{--</div>--}}

                                            </div>

                                            <div class="col-md-1">

                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.qty')</label>
                                                    <input type="number" min="1" class="form-control quantity"
                                                           value="{{ $item->quantity }}" name="quantity[]"
                                                    >
                                                </div>


                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>
                                                        <input type="text" min="" class="form-control cost_per_item"
                                                               name="cost_per_item[]" value="{{ number_format((float)$item->unit_price, 2, '.', '')  }}"
                                                        >
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-2">

                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.tax')</label>
                                                    <select id="multiselect" name="taxes[{{ $key }}][]"  multiple="multiple" class="js-example-basic-multiple form-control type">
                                                        @foreach($taxes as $tax)
                                                            <option data-rate="{{ $tax->rate_percent }}"
                                                                    @if (isset($item->taxes) && array_search($tax->id, json_decode($item->taxes)) !== false)
                                                                    selected
                                                                    @endif
                                                                    value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                            </div>

                                            <div class="col-md-2 border-dark  text-center">
                                                <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.amount')</label>

                                                <p class="form-control-static"><span
                                                            class="amount-html">{{ number_format((float)$item->amount, 2, '.', '') }}</span></p>
                                                <input type="hidden" value="{{ $item->amount }}" class="amount"
                                                       name="amount[]">
                                            </div>

                                            <div class="col-md-1 text-right d-md-block d-lg-block">
                                                <button type="button" class="btn remove-item btn-circle btn-outline-danger"><i
                                                            class="fa fa-times"></i></button>
                                            </div>
                                            <div class="col-xs-12 text-center hidden-md hidden-lg">
                                                <div class="row">
                                                    <button type="button" class="btn btn-circle remove-item btn-outline-danger"><i
                                                                class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>


                                <div class="col-xs-12 m-t-5">
                                    <button type="button" class="btn btn-info" id="add-item"><i class="fa fa-plus"></i> @lang('modules.invoices.addItem')</button>
                                </div>

                                <div class="col-xs-12 ">


                                    <div class="row">
                                        <div class="col-md-offset-9 col-xs-6 col-md-9 text-right p-t-10">@lang('modules.invoices.subTotal')</div>

                                        <p class="form-control-static col-xs-6 col-md-2">
                                            <span class="sub-total">{{ number_format((float)$invoice->sub_total, 2, '.', '') }}</span>
                                        </p>


                                        <input type="hidden" class="sub-total-field" name="sub_total" value="{{ $invoice->sub_total }}">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-offset-9 col-md-9 text-right p-t-10">
                                            @lang('modules.invoices.discount')
                                        </div>
                                        <div class="form-group col-xs-6 col-md-1" >
                                            <input type="number" min="0" value="{{ $invoice->discount }}" name="discount_value" class="form-control discount_value" >
                                        </div>
                                        <div class="form-group col-xs-6 col-md-2" >
                                            <select class="form-control" name="discount_type" id="discount_type">
                                                <option
                                                        @if($invoice->discount_type == 'percent') selected @endif
                                                value="percent">%</option>
                                                <option
                                                        @if($invoice->discount_type == 'fixed') selected @endif
                                                value="fixed">@lang('modules.invoices.amount')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row m-t-5" id="invoice-taxes">
                                        <div class="col-md-offset-9 col-xs-6 col-md-9 text-right p-t-10">
                                            @lang('modules.invoices.tax')
                                        </div>

                                        <p class="form-control-static col-xs-6 col-md-2" >
                                            <span class="tax-percent">0</span>
                                        </p>
                                    </div>

                                    <div class="row m-t-5 font-bold">
                                        <div class="col-md-offset-9 col-md-9 col-xs-6 text-right p-t-10">@lang('modules.invoices.total')</div>

                                        <p class="form-control-static col-xs-6 col-md-2">
                                            <span class="total">{{ number_format((float)$invoice->total, 2, '.', '') }}</span>
                                        </p>


                                        <input type="hidden" class="total-field" name="total"
                                               value="{{ round($invoice->total, 2) }}">
                                    </div>

                                </div>

                            </div>


                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" id="save-form" class="btn btn-primary"><i
                                                class="fa fa-check"></i> @lang('app.save')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>







    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="taxModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modelHeading" class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/jquery.ui.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script>
    $(function () {
        $( "#sortable" ).sortable();
    });

    jQuery('#invoice_date, #due_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });


    $('#save-form').click(function(){
        calculateTotal();

        var discount = $('.discount-amount').html();
        var total = $('.total-field').val();

        if(parseFloat(discount) > parseFloat(total)){
            $.toast({
                heading: 'Error',
                text: 'Discount cannot be more than total amount.',
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'error',
                hideAfter: 3500
            });
            return false;
        }

        $.easyAjax({
            url:'{{route('admin.all-invoices.store')}}',
            container:'#storePayments',
            type: "POST",
            redirect: true,
            data:$('#storePayments').serialize()
        })
    });
    $('#add-item').click(function () {
        var i = $(document).find('.item_name').length;
        var item = '<div class="row item-row margin-top-5">'+
                    '<div class="col-md-4">'+
                    // '<div class="row">'+
                    '<div class="form-group">'+
                    '<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.item')</label>'+
                    '<div class="input-group">'+
                    '<div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>'+
                    '<input type="text" class="form-control item_name" name="item_name[]">'+
                    '</div>'+
                    '</div>'+

                    '<div class="form-group">'+
                    '<textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>'+
                    '</div>'+

                    // '</div>'+

                    '</div>'+

                    '<div class="col-md-1">'+

                    '<div class="form-group">'+
                    '<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.qty')</label>'+
                    '<input type="number" min="1" class="form-control quantity" value="1" name="quantity[]" >'+
                    '</div>'+


                    '</div>'+

                    '<div class="col-md-2">'+
                    '<div class="row">'+
                    '<div class="form-group">'+
                    '<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>'+
                    '<input type="text"  class="form-control cost_per_item" name="cost_per_item[]" value="0" >'+
                    '</div>'+
                    '</div>'+

                    '</div>'+

                    '<div class="col-md-2">'+

            '<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.tax')</label>'
            +'<select id="multiselect'+i+'" name="taxes['+i+'][]"  multiple="multiple" class="js-example-basic-multiple form-control type">'
                @foreach($taxes as $tax)
            +'<option data-rate="{{ $tax->rate_percent }}" value="{{ $tax->id }}">{{ $tax->tax_name.': '.$tax->rate_percent }}%</option>'
                @endforeach
            +'</select>'
            +'</div>'+


                '</div>'+

                '<div class="col-md-2 border-dark  text-center">'+
                    '<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.amount')</label>'+

                    '<p class="form-control-static"><span class="amount-html">0.00</span></p>'+
                '<input type="hidden" class="amount" name="amount[]">'+
                    '</div>'+

                    '<div class="col-md-1 text-right d-md-block d-lg-block">'+
                    '<button type="button" class="btn remove-item btn-circle btn-outline-danger"><i class="fa fa-times"></i></button>'+
                '</div>'+
                '<div class="col-xs-12 text-center hidden-md hidden-lg">'+
                    '<div class="row">'+
                    '<button type="button" class="btn btn-circle remove-item btn-outline-danger"><i class="fa fa-times"></i></button>'+
                '</div>'+
                '</div>'+

                '</div>';

        $(item).hide().appendTo("#sortable").fadeIn(500);
        $('#multiselect'+i).select2();
    });

    $('#storePayments').on('click','.remove-item', function () {
        $(this).closest('.item-row').fadeOut(300, function() {
            $(this).remove();
            calculateTotal();
        });
    });

    $('#storePayments').on('keyup','.quantity, .cost_per_item, .item_name, .discount_value', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(amount);
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

        calculateTotal();


    });

    $('#storePayments').on('change','.type, #discount_type', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(amount);
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

        calculateTotal();
    });

    function calculateTotal()
    {
        var subtotal = 0;
        var discount = 0;
        var tax = '';
        var taxList = new Object();
        var taxTotal = 0;
        $(".quantity").each(function (index, element) {
            var itemTax = [];
            var itemTaxName = [];
            $(this).closest('.item-row').find('select.type option:selected').each(function (index) {
                itemTax[index] = $(this).data('rate');
                itemTaxName[index] = $(this).text();
            });
            var itemTaxId = $(this).closest('.item-row').find('select.type').val();
            var amount = parseFloat($(this).closest('.item-row').find('.amount').val());

            if(isNaN(amount)){ amount = 0; }

            subtotal = parseFloat(subtotal)+parseFloat(amount);

            if(itemTaxId != ''){
                for(var i = 0; i<=itemTaxName.length; i++)
                {
                    if(typeof (taxList[itemTaxName[i]]) === 'undefined'){
                        taxList[itemTaxName[i]] = ((parseFloat(itemTax[i])/100)*parseFloat(amount));
                    }
                    else{
                        taxList[itemTaxName[i]] = parseFloat(taxList[itemTaxName[i]]) + ((parseFloat(itemTax[i])/100)*parseFloat(amount));
                    }
                }
            }
        });

        $.each( taxList, function( key, value ) {
            if(!isNaN(value)){

                tax = tax+'<div class="col-md-offset-8 col-md-2 col-xs-6 text-right p-t-10">'
                    +key
                    +'</div>'
                    +'<p class="form-control-static col-xs-6 col-md-2" >'
                    +'<span class="tax-percent">'+decimalupto2(value)+'</span>'
                    +'</p>';

                taxTotal = taxTotal+value;
            }

        });

        if(isNaN(subtotal)){  subtotal = 0; }

        $('.sub-total').html(decimalupto2(subtotal));
        $('.sub-total-field').val(decimalupto2(subtotal));


        var discountType = $('#discount_type').val();
        var discountValue = $('.discount_value').val();

        if(discountValue != ''){
            if(discountType == 'percent'){
                discount = ((parseFloat(subtotal)/100)*parseFloat(discountValue));
            }
            else{
                discount = parseFloat(discountValue);
            }

        }

        $('#invoice-taxes').html(tax);

        var totalAfterDiscount = subtotal-discount;

        totalAfterDiscount = (totalAfterDiscount < 0) ? 0 : totalAfterDiscount;

        var total = decimalupto2(totalAfterDiscount+taxTotal);

        $('.total').html(total);
        $('.total-field').val(total);

    }
    calculateTotal();

    function recurringPayment() {
        var recurring = $('#recurring_payment').val();

        if(recurring == 'yes')
        {
            $('.recurringPayment').show().fadeIn(300);
        } else {
            $('.recurringPayment').hide().fadeOut(300);
        }
    }

    $('#tax-settings').click(function () {
        var url = '{{ route('admin.taxes.create')}}';
        $('#modelHeading').html('Manage Project Category');
        $.ajaxModal('#taxModal', url);
    });

    function decimalupto2(num) {
        var amt =  Math.round(num * 100) / 100;
        return parseFloat(amt.toFixed(2));
    }


    $('.add-product').on('click', function(event) {
        event.preventDefault();
        var id = $(this).data('pk');
        $.easyAjax({
            url:'{{ route('admin.all-invoices.update-item') }}',
            type: "GET",
            data: { id: id },
            success: function(response) {
                $(response.view).hide().appendTo("#sortable").fadeIn(500);
                var noOfRows = $(document).find('#sortable .item-row').length;
                var i = $(document).find('.item_name').length-1;
                var itemRow = $(document).find('#sortable .item-row:nth-child('+noOfRows+') select.type');
                itemRow.attr('id', 'multiselect'+i);
                itemRow.attr('name', 'taxes['+i+'][]');
                $(document).find('#multiselect'+i).select2();
            }
        });
    });


</script>
@endpush

