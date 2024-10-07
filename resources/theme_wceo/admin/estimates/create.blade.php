@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/select2.css')}}">    
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
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.estimates.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.addNew')</li>
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
                        <h4 class="card-title mb-0">@lang('modules.estimates.createEstimate')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-3">

                                <div class="form-group">
                                        <div>
                                            <div class="input-group ">
                                                <div class="input-group-addon  form-control-lg f-16"><span class="invoicePrefix" data-prefix="{{ $invoiceSetting->estimate_prefix }}">{{ $invoiceSetting->estimate_prefix }}</span>#<span class="noOfZero" data-zero="{{ $invoiceSetting->estimate_digit }}">{{ $zero }}</span></div>
                                                <input type="text"  class="form-control  form-control-lg readonly-background" readonly name="estimate_number" id="estimate_number" value="@if(is_null($lastEstimate))1 @else{{ ($lastEstimate) }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-label-group form-group">
                                        <select class="select2 form-control-lg form-control" placeholder="-" name="client_id" id="client_id">
                                            @foreach($clients as $client)
                                                <option value="{{ $client->user_id }}">{{ ucwords($client->company_name) }}</option>
                                            @endforeach
                                        </select>                                        
                                        <label for="client_id" class="control-label">@lang('app.client')</label>
                                    </div>

                                </div>

                                <div class="col-md-2">
                                    <div class="form-label-group form-group">                                    
                                        <select class="hide-search form-control form-control-lg" name="currency_id" id="currency_id" placeholder="-">
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                                            @endforeach
                                        </select>
                                        <label for="currency_id" class="control-label">@lang('modules.invoices.currency')</label>                                        
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-label-group form-group">                                   
                                        <input placeholder="-" type="text" class="form-control form-control-lg" name="valid_till" id="valid_till" value="{{ Carbon\Carbon::today()->addDays(30)->format($global->date_format) }}">
                                        <label for="valid_till" class="control-label">@lang('modules.estimates.validTill')</label>
                                    </div>
                                </div>

                            </div>

                            <hr>

                            

                                
                                <div id="sortable">
                                    <div class="row item-row margin-top-5">

                                        <div class="col-md-12 invoice-head">
                                            <div class="row">

                                                <div class="col-md-4 d-none d-sm-block font-bold d-none d-sm-block label-block">
                                                    @lang('modules.invoices.item')
                                                </div>

                                                <div class="col-md-1 d-none d-sm-block font-bold d-none d-sm-block label-block">
                                                    @lang('modules.invoices.qty')
                                                </div>

                                                <div class="col-md-2 d-none d-sm-block font-bold d-none d-sm-block label-block">
                                                    @lang('modules.invoices.unitPrice')
                                                </div>

                                                <div class="col-md-2 font-bold d-none d-sm-block d-none d-sm-block label-block">
                                                    @lang('modules.invoices.tax') <a href="javascript:;" class="tax-settings" ><i class="icofont icofont-gear"></i></a>
                                                </div>

                                                <div class="col-md-2 text-right d-none d-sm-block font-bold d-none d-sm-block label-block-last">
                                                    @lang('modules.invoices.amount')
                                                </div>

                                                <div class="col-md-1 d-none d-sm-block d-none d-sm-block">
                                                    &nbsp;
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            {{--<div class="row">--}}
                                                <div class="form-group">
                                                    <label class="control-label d-md-none d-lg-none hidden-md hidden-lg">@lang('modules.invoices.item')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                                                        <input type="text" class="form-control item_name" name="item_name[]">
                                                    </div>
                                                </div>                                                
                                            {{--</div>--}}
                                        </div>

                                        <div class="col-md-1">

                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none hidden-md hidden-lg">@lang('modules.invoices.qty')</label>
                                                <input type="number" min="0" class="form-control quantity" value="1" name="quantity[]" >
                                            </div>


                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>
                                                <input type="text" min="" class="form-control cost_per_item" name="cost_per_item[]" value="0" >
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none hidden-md hidden-lg">@lang('modules.invoices.tax') <a href="javascript:;" id="tax-settings2" ><i class="icofont icofont-gear"></i></a></label>
                                                <select id="multiselect" name="taxes[0][]"  multiple="multiple" class="js-example-basic-multiple form-control type">
                                                    @foreach($taxes as $tax)
                                                        <option data-rate="{{ $tax->rate_percent }}" value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>                                        

                                        <div class="col-md-2 border-dark  text-right resTextLeft">
                                            <label class="control-label d-md-none d-lg-none hidden-md hidden-lg">@lang('modules.invoices.amount')</label>

                                            <p class="form-control-static">{{ $global->currency->currency_symbol }}<span class="amount-html">0.00</span></p>
                                            <input type="hidden" class="amount" name="amount[]">
                                        </div>

                                        <div class="col-md-1 text-right d-none d-md-block d-lg-block visible-md visible-lg">
                                            <button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i></button>
                                        </div>
                                        <div class="col-md-1 d-md-none d-lg-none hidden-md hidden-lg m-b-20">
                                            <button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>
                                            </div>                                          
                                        </div>

                                    </div>
                                </div>

                                <div  id="item-list"></div>

                                <div class="row">
                                    <div class="col-4">
                                        <button type="button" class="btn btn-primary" id="add-item"><i class="fa fa-plus"></i> Add Item or Service</button>
                                    </div>

                                    <div class="col-md-5">
                                        @if(in_array("products", $modules) )
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group form-label-group">
                                                    <select placeholder="-" class="form-control form-control-lg" name="cat_id" id="cat_id">
                                                        <option value="">--</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->category_name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="cat_id" class="control-label">Product Category</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group form-label-group">
                                                    <select placeholder="-" class="form-control form-control-lg" id="products">
                                                        <option value="">--</option>
                                                    </select>
                                                    <label for="products" class="control-label">@lang('app.menu.products') </label>
                                                </div>
                                            </div>

                                            <div class="offset-md-6 col-md-6">
                                                <button type="button" class="btn btn-primary pull-right" id="addProduct">Add Product</button>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="col-xs-6 col-md-4 text-left resTextLeft" >@lang('modules.invoices.subTotal')</div>

                                            <p class="text-right col-xs-6 col-md-8" >
                                                {{ $global->currency->currency_symbol }}<span class="sub-total">0.00</span>
                                            </p>
                                            <input type="hidden" class="sub-total-field" name="sub_total" value="0">
                                        </div>                                

                                        <div class="row">
                                            <div class="col-md-4 text-left p-t-10 resTextLeft">
                                                @lang('modules.invoices.discount')
                                            </div>
                                            <div class="form-group col-xs-6 col-md-4" >
                                                <input type="number" min="0" value="0" name="discount_value" class="form-control discount_value">
                                            </div>
                                            <div class="form-group col-xs-6 col-md-4" >
                                                <select class="hide-search form-control" name="discount_type" id="discount_type">
                                                    <option value="percent">%</option>
                                                    <option value="fixed">@lang('modules.invoices.amount')</option>
                                                </select>
                                            </div>
                                        </div>                                

                                        <div class="row" id="invoice-taxes">
                                            <div class="col-md-4 text-left p-b-10 resTextLeft">
                                                @lang('modules.invoices.tax')
                                            </div>

                                            <div class="text-right col-xs-6 col-md-8" >
                                                {{ $global->currency->currency_symbol }}<span class="tax-percent">0.00</span>
                                            </div>
                                        </div>

                                    <div class="row m-t-5 font-bold total-amount">
                                        <div class="col-md-4 col-xs-6 text-left resTextLeft" >@lang('modules.invoices.total')</div>

                                        <p class="text-right col-xs-6 col-md-8" >
                                            {{ $global->currency->currency_symbol }}<span class="total">0.00</span>
                                        </p>
                                        <input type="hidden" class="total-field" name="total" value="0">
                                    </div>

                                </div>
                                </div>
                                

                            <div class="row">
                                



                    </div>

                    <div class="row">

                                <div class="col-sm-12">

                                    <div class="form-label-group form-group">

                                        <textarea placeholder="-" name="note" class="form-control" rows="5"></textarea>
                                        <label for="note" class="control-label">@lang('app.note')</label>
                                    </div>

                                </div>

                            </div>


                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-actions col-md-3 offset-md-9">
                            <button type="button" id="save-form" class="btn btn-primary form-control"> @lang('app.save')</button>
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
                    <h5  class="modal-title" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>                    
                </div>
                <div class="modal-body">
                    @lang('app.loading')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">@lang('app.close')</button>
                    <button type="button" class="btn blue">@lang('app.save') @lang('app.changes')</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection


@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/select2/select2-custom.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/jquery.ui.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>

<script>
    $(function () {
        $( "#sortable" ).sortable();
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    
    jQuery('#valid_till').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });


    jQuery('#valid_till2').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });

    $('#save-form').click(function(){
        calculateTotal();

        $.easyAjax({
            url:'{{route('admin.estimates.store')}}',
            container:'#storePayments',
            type: "POST",
            redirect: true,
            data:$('#storePayments').serialize()
        })
    });

    $('#add-item').click(function () {
        var i = $(document).find('.item_name').length;
        var item = '<div class="row item-row margin-top-5">'

            +'<div class="col-md-12 invoice-head">'
            +'<div class="row">'

            +'<div class="col-md-4 d-none d-sm-block font-bold d-none d-sm-block label-block">'
            +'@lang('modules.invoices.item')'
            +'</div>'

            +'<div class="col-md-1 d-none d-sm-block font-bold d-none d-sm-block label-block">'
            +'@lang('modules.invoices.qty')'
            +'</div>'

            +'<div class="col-md-2 d-none d-sm-block font-bold d-none d-sm-block label-block">'
            +'@lang('modules.invoices.unitPrice')'
            +'</div>'

            +'<div class="col-md-2 font-bold d-none d-sm-block d-none d-sm-block label-block">'
            +'@lang('modules.invoices.tax') <a href="javascript:;" class="tax-settings" ><i class="icofont icofont-gear"></i></a>'
            +'</div>'

            +'<div class="col-md-2 text-right d-none d-sm-block font-bold d-none d-sm-block label-block-last">'
            +'@lang('modules.invoices.amount')'
            +'</div>'

            +'<div class="col-md-1 d-none d-sm-block d-none d-sm-block">&nbsp;</div>'

            +'</div>'
            +'</div>'

            +'<div class="col-md-4">'
            // +'<div class="row">'
            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none hidden-md hidden-lg">@lang('modules.invoices.item')</label>'
            +'<div class="input-group">'
            +'<div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>'
            +'<input type="text" class="form-control item_name" name="item_name[]" >'
            +'</div>'
            +'</div>'           
            // +'</div>'
            +'</div>'

            +'<div class="col-md-1">'

            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none hidden-md hidden-lg">@lang('modules.invoices.qty')</label>'
            +'<input type="number" min="1" class="form-control quantity" value="1" name="quantity[]" >'
            +'</div>'


            +'</div>'

            +'<div class="col-md-2">'
            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>'
            +'<input type="text" min="0" class="form-control cost_per_item" value="0" name="cost_per_item[]">'
            +'</div>'

            +'</div>'


            +'<div class="col-md-2">'

            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none hidden-md hidden-lg">@lang('modules.invoices.tax')</label>'
            +'<select id="multiselect'+i+'" name="taxes['+i+'][]"  multiple="multiple" class="js-example-basic-multiple  form-control type">'
                @foreach($taxes as $tax)
            +'<option data-rate="{{ $tax->rate_percent }}" value="{{ $tax->id }}">{{ $tax->tax_name.': '.$tax->rate_percent }}%</option>'
                @endforeach
            +'</select>'
            +'</div>'


            +'</div>'
            

            +'<div class="col-md-2 text-right resTextLeft">'
            +'<label class="control-label d-md-none d-lg-none hidden-md hidden-lg">@lang('modules.invoices.amount')</label>'
            +'<p class="form-control-static">{{ $global->currency->currency_symbol }}<span class="amount-html">0.00</span></p>'
            +'<input type="hidden" class="amount" name="amount[]">'
            +'</div>'

            +'<div class="col-md-1 text-right d-none d-md-block d-lg-block visible-md visible-lg">'
            +'<button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i></button>'
            +'</div>'

            +'<div class="col-md-1 d-md-none d-lg-none hidden-md hidden-lg m-b-20">'
            +'<button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>'
            +'</div>'

            +'<div class="col-md-9">'            
            +'<div class="form-group">'
            +'<textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>'
            +'</div>'
            +'</div>'

            +'</div>';

        $(item).hide().appendTo("#sortable").fadeIn(500);
       // $('#multiselect'+i).selectpicker();
        $('#multiselect'+i).select2();
    });

    $('#storePayments').on('click','.remove-item', function () {
        $(this).closest('.item-row').fadeOut(300, function() {
            $(this).remove();
            calculateTotal();
        });
    });

    $('#storePayments').on('keyup change','.quantity,.cost_per_item,.item_name, .discount_value', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount).toFixed(2));
        $(this).closest('.item-row').find('.amount-html').html(amount.toLocaleString());
        //$(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount).toFixed(2));

        calculateTotal();


    });

    $('#storePayments').on('change','.type, #discount_type', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount).toFixed(2));
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount).toFixed(2));

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
            subtotal = (parseFloat(subtotal)+parseFloat(amount)).toFixed(2);

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

                tax = tax+'<div class="col-md-4 text-left p-b-10">'
                    +key
                    +'</div>'
                    +'<div class="text-right col-xs-6 col-md-8" >'
                    +'{{ $global->currency->currency_symbol }}<span class="tax-percent">'+(decimalupto2(value)).toFixed(2)+'</span>'
                    +'</div>';

                taxTotal = taxTotal+value;
            }

        });

        if(isNaN(subtotal)){  subtotal = 0; }

        //$('.sub-total').html(decimalupto2(subtotal).toFixed(2));
        $('.sub-total').html(decimalupto2(subtotal).toLocaleString(2));
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

        var totalAfterDiscount = decimalupto2(subtotal-discount);

        totalAfterDiscount = (totalAfterDiscount < 0) ? 0 : totalAfterDiscount;

        var total = decimalupto2(totalAfterDiscount+taxTotal);

        //$('.total').html(total.toFixed(2));
        $('.total').html(total.toLocaleString());
        $('.total-field').val(total.toFixed(2));

    }

    $("body").on("click",".tax-settings, #tax-settings2", function(){
        var url = '{{ route('admin.taxes.create')}}';
        $('#modelHeading').html('Manage Project Category');
        $.ajaxModal('#taxModal', url);
    })

    function decimalupto2(num) {
        var amt =  Math.round(num * 100) / 100;
        return parseFloat(amt.toFixed(2));
    }

    

    $('#addProduct').click(function(){
        var id = $('#products option:selected').val();     
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
                calculateTotal();
                $("#products")[0].selectedIndex = 0;
            }
        });
    });

    $('#cat_id').change(function (e) {
        var cat_id = $(this).val();        
        var url = "{{ route('admin.all-invoices.get-category-products',':id') }}";
        url = url.replace(':id', cat_id);
        $.easyAjax({
            type: 'GET',
            dataType: 'JSON',
            url: url,
            success: function (data) {
                $('#products').html('');
                $('#products').append('<option value="">--</option>');
                $.each(data, function (index, data) {
                    $('#products').append('<option value="' + data.id + '">' + data.name + '</option>');
                });
            }
        });
    });

</script>
@endpush