@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/date-picker.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
.product_item{font-size:13px;}
</style>
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.all-invoices.index') }}">{{ __($pageTitle) }}</a></li>
                            <li  class="breadcrumb-item active">@lang('app.addNew')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Container-fluid starts-->
    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">

                    {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.estimates.convertEstimateTitle')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group ">
                                            <div class="input-group-addon  form-control-lg f-16"><span class="invoicePrefix" data-prefix="{{ $invoiceSetting->invoice_prefix }}">{{ $invoiceSetting->invoice_prefix }}</span>#<span class="noOfZero" data-zero="{{ $invoiceSetting->invoice_digit }}">{{ $zero }}</span></div>
                                            <input type="text"  class="form-control  form-control-lg readonly-background" readonly name="invoice_number" id="invoice_number" value="@if(is_null($lastInvoice))1 @else{{ ($lastInvoice) }}@endif">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-label-group form-group">  
                                        <select class="select2 form-control form-control-lg" placeholder="-" data-placeholder="Choose Project" name="project_id" id="project_id">
                                            <option value="" >--</option>
                                            @foreach($projects as $project)
                                                <option
                                                    @if($invoice->project_id == $project->id) selected
                                                    @endif
                                                    value="{{ $project->id }}">{{ ucwords($project->project_name) }}</option>
                                            @endforeach
                                        </select>
                                        <label for="project_id" class="control-label">@lang('app.project')</label>                                                                                
                                    </div>                                  
                                </div>

                                <div class="col-md-4">
                                    <div class="form-label-group form-group">  
                                        <select class="form-control form-control-lg" name="currency_id" id="currency_id" placeholder="-">
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
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-label-group form-group">   
                                        <select class="form-control form-control-lg" placeholder="-" name="status" id="status">
                                            <option @if($invoice->status == 'paid') selected @endif value="paid">@lang('modules.invoices.paid') </option>
                                            <option @if($invoice->status == 'unpaid') selected @endif value="unpaid">@lang('modules.invoices.unpaid') </option>
                                        </select>
                                        <label for="status" class="control-label">@lang('app.status')</label>                                    
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-label-group form-group">   
                                        <select class="form-control form-control-lg" placeholder="-" name="recurring_payment" id="recurring_payment" onchange="recurringPayment()">
                                            <option value="no">@lang('app.no')</option>
                                            <option value="yes">@lang('app.yes')</option>
                                        </select>
                                        <label for="recurring_payment" class="control-label">@lang('modules.invoices.isRecurringPayment')</label>                                    
                                    </div>
                                </div>

                                <div class="col-md-3 recurringPayment" style="display: none;">
                                    <div class="form-label-group form-group">   
                                        <select class="form-control form-control-lg" placeholder="-" name="billing_frequency" id="billing_frequency">
                                            <option value="day">@lang('app.day')</option>
                                            <option value="week">@lang('app.week')</option>
                                            <option value="month">@lang('app.month')</option>
                                            <option value="year">@lang('app.year')</option>
                                        </select>
                                        <label for="billing_frequency" class="control-label">@lang('modules.invoices.isRecurringPayment')</label>
                                    </div>
                                </div>

                                <div class="col-md-3 recurringPayment" style="display: none;">
                                    <div class="form-label-group form-group">
                                        <input type="text" class="form-control form-control-lg" placeholder="-" name="billing_interval" id="billing_interval" value="">
                                        <label for="billing_interval" class="control-label">@lang('modules.invoices.billingInterval')</label>
                                    </div>
                                </div>

                                <div class="col-md-3 recurringPayment" style="display: none;">
                                    <div class="form-label-group form-group">
                                        <input type="text" class="form-control form-control-lg" placeholder="-" name="billing_cycle" id="billing_cycle" value="">
                                        <label for="billing_cycle" class="control-label">@lang('modules.invoices.billingCycle')</label>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                
                      
                                    <div class="btn-group col-md-6 m-b-10 pl-0">
                                        <button aria-expanded="false" data-toggle="dropdown" class="btn btn-info dropdown-toggle waves-effect waves-light" type="button">@lang('app.menu.products') <span class="caret"></span></button>
                                        <ul role="menu" class="dropdown-menu dropdown-content" style="width:90%">
                                            @foreach($products as $product)
                                                <li class="m-b-10 col-md-12 product_item">
                                                    <div class="row m-t-10">
                                                        <div class="col-md-6">
                                                            {{ $product->name }}
                                                        </div>
                                                        <div class="col-md-6" style="text-align: right;padding-right: 10px;">
                                                            <a href="javascript:;" data-pk="{{ $product->id }}" class="btn btn-success btn btn-outline btn-xs waves-effect add-product">@lang('app.add') <i class="fa fa-plus" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="d-md-block d-lg-block">

                                <div class="row">

                                    <div class="col-md-4 font-bold d-none d-sm-block" style="padding: 8px 15px">
                                        @lang('modules.invoices.item')
                                    </div>

                                    <div class="col-md-1 font-bold d-none d-sm-block" style="padding: 8px 15px">
                                        @lang('modules.invoices.qty')
                                    </div>

                                    <div class="col-md-2 font-bold d-none d-sm-block" style="padding: 8px 15px">
                                        @lang('modules.invoices.unitPrice')
                                    </div>

                                    <div class="col-md-2 font-bold d-none d-sm-block" style="padding: 8px 15px">
                                        @lang('modules.invoices.tax') <a href="javascript:;" id="tax-settings" ><i class="icofont icofont-gear"></i></a>
                                    </div>

                                    <div class="col-md-2 text-center font-bold d-none d-sm-block" style="padding: 8px 15px">
                                        @lang('modules.invoices.amount')
                                    </div>

                                    <div class="col-md-1 d-none d-sm-block" style="padding: 8px 15px">
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
                                                    <div class="form-group">
                                                        <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>
                                                        <input type="text" min="" class="form-control cost_per_item"
                                                               name="cost_per_item[]" value="{{ $item->unit_price }}"
                                                        >
                                                    </div>
                                            </div>

                                            <div class="col-md-2">

                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.type')</label>
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

                                            <div class="col-md-2 border-dark  text-center resTextLeft">
                                                <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.amount')</label>
                                                <p class="form-control-static"><span
                                                            class="amount-html">{{ number_format((float)$item->amount, 2, '.', '') }}</span></p>
                                                <input type="hidden" value="{{ $item->amount }}" class="amount"
                                                       name="amount[]">
                                            </div>

                                            <div class="col-md-1 text-right d-none d-md-block d-lg-block">
                                                <button type="button" class="btn remove-item btn-circle btn-outline-danger"><i
                                                            class="fa fa-times"></i></button>
                                            </div>
                                            <div class="col-md-1 hidden-md hidden-lg m-b-20">
                                                    <button type="button" class="btn btn-circle remove-item btn-outline-danger"><i
                                                                class="fa fa-times"></i> @lang('app.remove')
                                                    </button>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>

                                <div id="item-list">

                                </div>

                                <div class="col-xs-12 m-t-5">
                                    <button type="button" class="btn btn-secondary" id="add-item"><i class="fa fa-plus"></i>
                                        @lang('modules.invoices.addItem')
                                    </button>
                                </div>

                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-md-offset-9 col-xs-6 col-md-9 text-right p-t-10 resTextLeft">@lang('modules.invoices.subTotal')</div>

                                        <p class="form-control-static col-xs-6 col-md-2">
                                            <span class="sub-total">{{ number_format((float)$invoice->sub_total, 2, '.', '') }}</span>
                                        </p>


                                        <input type="hidden" class="sub-total-field" name="sub_total" value="{{ $invoice->sub_total }}">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-offset-9 col-md-9 text-right p-t-10 resTextLeft">
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
                                                value="fixed">@lang('modules.invoice.amount')</option>
                                            </select>
                                        </div>
                                    </div>

                                    
                                        <div id="invoice-taxes">
                                        <div class="row">
                                            <div class="col-md-offset-9 col-md-9 col-xs-6 text-right p-t-10 resTextLeft">
                                                @lang('modules.invoices.tax')
                                            </div>
                                             
                                             <p class="form-control-static col-xs-6 col-md-2" >
                                                <span class="tax-percent">0</span>
                                             </p>
                                        </div>
                                    </div>

                                    <div class="row m-t-5 font-bold">
                                        <div class="col-md-offset-9 col-md-9 col-xs-6 text-right p-t-10 resTextLeft">@lang('modules.invoices.total')</div>

                                        <p class="form-control-static col-xs-6 col-md-2">
                                            <span class="total">{{ number_format((float)$invoice->total, 2, '.', '') }}</span>
                                        </p>
                                        <input type="hidden" class="total-field" name="total" value="{{ round($invoice->total, 2) }}">
                                    </div>

                                </div>

                                

                            </div>

                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <div class="form-actions col-md-6 offset-md-7">
                            <div class="row">
                                <div class="col-md-5 pr-0">
                                    <a href="{{ route('admin.estimates.index') }}" class="btn btn-secondary form-control" data-original-title="" title="">Cancel</a>
                                </div>
                                <div class="col-md-5 pr-0">
                                <button type="submit" id="save-form" class="btn btn-primary form-control"> @lang('app.save')</button>
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
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    @lang('app.loading')
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
                text: "{{ __('messages.discountMoreThenTotal') }}",
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
        var item = '<div class="row item-row margin-top-5">'

            +'<div class="col-md-4">'
            // +'<div class="row">'
            +'<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.item')</label>'
            +'<div class="input-group">'
            +'<div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>'
            +'<input type="text" class="form-control item_name" name="item_name[]" >'
            +'</div>'

            +'</div>'
            +'<div class="form-group">'
            +'<textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>'
            +'</div>'
            // +'</div>'

            +'</div>'

            +'<div class="col-md-1">'

            +'<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.qty')</label>'
            +'<input type="number" min="1" class="form-control quantity" value="1" name="quantity[]" >'
            +'</div>'


            +'</div>'

            +'<div class="col-md-2">'
            +'<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>'
            +'<input type="text" min="0" class="form-control cost_per_item" value="0" name="cost_per_item[]">'
            +'</div>'

            +'</div>'


            +'<div class="col-md-2">'

            +'<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.tax')</label>'
            +'<select id="multiselect'+i+'" name="taxes['+i+'][]"  multiple="multiple" class="js-example-basic-multiple form-control type">'
                @foreach($taxes as $tax)
            +'<option data-rate="{{ $tax->rate_percent }}" value="{{ $tax->id }}">{{ $tax->tax_name.': '.$tax->rate_percent }}%</option>'
                @endforeach
            +'</select>'
            +'</div>'


            +'</div>'

            +'<div class="col-md-2 text-center resTextLeft">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.amount')</label>'
            +'<p class="form-control-static"><span class="amount-html">0.00</span></p>'
            +'<input type="hidden" class="amount" name="amount[]">'
            +'</div>'

            +'<div class="col-md-1 text-right d-none d-md-block d-lg-block">'
            +'<button type="button" class="btn remove-item btn-circle btn-outline-danger"><i class="fa fa-times"></i></button>'
            +'</div>'

            +'<div class="col-md-1 hidden-md hidden-lg m-b-20">'
            +'<button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>'
            +'</div>'

            +'</div>';

        $(item).hide().appendTo("#sortable").fadeIn(500);
        $('#multiselect'+i).select2();
    });

    $('#storePayments').on('click','.remove-item', function () {
        $(this).closest('.item-row').fadeOut(300, function() {
            $(this).remove();
            calculateTotal();
        });
    });

    $('#storePayments').on('keyup change','.quantity, .cost_per_item, .item_name, .discount_value', function () {
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

                tax = tax+'<div class="row"><div class="col-md-offset-9 col-md-9 col-xs-6 text-right p-t-10 resTextLeft">'
                    +key
                    +'</div>'
                    +'<p class="form-control-static col-xs-6 col-md-2" >'
                    +'<span class="tax-percent">'+(decimalupto2(value)).toFixed(2)+'</span>'
                    +'</p></div>';

                taxTotal = taxTotal+value;
            }

        });

        if(isNaN(subtotal)){  subtotal = 0; }

        $('.sub-total').html(decimalupto2(subtotal));
        $('.sub-total-field').val(subtotal);


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

        var totalAfterDiscount = decimalupto2(subtotal-discount);

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
        var amt =  Math.round(num * 100,2) / 100;
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
                $(document).find('#multiselect'+i).selectpicker();
                calculateTotal();
            }
        });
    });


</script>
@endpush

