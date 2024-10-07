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
                            <li class="breadcrumb-item"><a href="{{ route('admin.proposals.show', $perposal->lead->id) }}">{{ __($pageTitle) }}</a></li>
                            <li  class="breadcrumb-item active">@lang('app.update')</li>
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

                    {!! Form::open(['id'=>'updatePayments','class'=>'ajax-form','method'=>'PUT']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.proposal.updateProposal')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-label-group form-group" >
                                        <input placeholder="-" disabled type="text" class="form-control form-control-lg" value="{{ $perposal->lead->company_name }}">
                                        <label for="" class="control-label">@lang('app.lead')</label>
                                        {!! Form::hidden('lead_id', $perposal->lead->id) !!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-label-group form-group">                                        
                                        <select placeholder="-" class="form-control form-control-lg" name="currency_id" id="currency_id">
                                            @foreach($currencies as $currency)
                                                <option
                                                        @if($perposal->currency_id == $currency->id) selected
                                                        @endif
                                                        value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                                            @endforeach
                                        </select>
                                        <label for="currency_id" class="control-label">@lang('modules.invoices.currency')</label>
                                    </div>                                  
                                </div>

                                <div class="col-md-3">
                                    <div class="form-label-group form-group">                                      
                                        <input placeholder="-" type="text" class="form-control form-control-lg" name="valid_till" id="valid_till"
                                                value="{{ $perposal->valid_till->format($global->date_format) }}">
                                        <label for="valid_till" class="control-label">@lang('modules.proposal.validTill')</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-label-group form-group">                                        
                                        <select placeholder="-" class="form-control form-control-lg" name="status" id="status">
                                            <option
                                                    @if($perposal->status == 'accepted') selected @endif
                                            value="accepted">@lang('modules.proposal.accepted')
                                            </option>
                                            <option
                                                    @if($perposal->status == 'waiting') selected @endif
                                            value="waiting">@lang('modules.proposal.waiting')
                                            </option>
                                            <option
                                                    @if($perposal->status == 'declined') selected @endif
                                            value="declined">@lang('modules.proposal.declined')
                                            </option>
                                        </select>
                                        <label for="status" class="control-label">@lang('app.status')</label>
                                    </div>
                                </div>

                            </div>
                            

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-label-group form-group">                                        
                                        <textarea placeholder="-" name="note" id="note" class="form-control" rows="5">{{ $perposal->note }}</textarea>
                                        <label for="note" class="control-label">@lang('app.note')</label>
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
                                    @foreach($perposal->items as $key => $item)
                                        <div class="row item-row margin-top-5">
                                            <div class="col-md-4">
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
                                            </div>

                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.qty')</label>
                                                    <input type="number" min="1" class="form-control quantity" value="{{ $item->quantity }}" name="quantity[]">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>
                                                        <input type="text" min="" class="form-control cost_per_item" name="cost_per_item[]" value="{{ $item->unit_price }}">
                                                    </div>
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
                                            <div class="col-md-2 border-dark  text-center">
                                                <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.amount')</label>
                                                <p class="form-control-static"><span class="amount-html">{{ number_format((float)$item->amount, 2, '.', '') }}</span></p>
                                                <input type="hidden" value="{{ $item->amount }}" class="amount" name="amount[]">
                                            </div>

                                            <div class="col-md-1 text-right d-md-block d-lg-block">
                                                <button type="button" class="btn remove-item btn-circle btn-outline-danger"><i class="fa fa-times"></i></button>
                                            </div>
                                            <div class="col-md-1 hidden-md hidden-lg">
                                                <div class="row">
                                                    <button type="button" class="btn btn-circle remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')
                                                    </button>
                                                </div>
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
                                        <div class="col-md-offset-9 col-xs-6 col-md-9 text-right p-t-10">@lang('modules.invoices.subTotal')</div>

                                        <p class="form-control-static col-xs-6 col-md-2">
                                            <span class="sub-total">{{ number_format((float)$perposal->sub_total, 2, '.', '') }}</span>
                                        </p>


                                        <input type="hidden" class="sub-total-field" name="sub_total" value="{{ $perposal->sub_total }}">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-offset-9 col-md-9 text-right p-t-10">
                                            @lang('modules.invoices.discount')
                                        </div>
                                        <div class="form-group col-xs-6 col-md-1" >
                                            <input type="number" min="0" value="{{ $perposal->discount }}" name="discount_value" class="form-control discount_value" >
                                        </div>
                                        <div class="form-group col-xs-6 col-md-2" >
                                            <select class="form-control" name="discount_type" id="discount_type">
                                                <option
                                                        @if($perposal->discount_type == 'percent') selected @endif
                                                value="percent">%</option>
                                                <option
                                                        @if($perposal->discount_type == 'fixed') selected @endif
                                                value="fixed">@lang('modules.invoices.amount')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row m-t-5" id="invoice-taxes">
                                        <div class="col-md-offset-9 col-md-9 text-right p-t-10">
                                            @lang('modules.invoices.tax')
                                        </div>

                                        <p class="form-control-static col-xs-6 col-md-2" >
                                            <span class="tax-percent">0</span>
                                        </p>
                                    </div>

                                    <div class="row m-t-5 font-bold">
                                        <div class="col-md-offset-9 col-md-9 col-xs-6 text-right p-t-10">@lang('modules.invoices.total')</div>

                                        <p class="form-control-static col-xs-6 col-md-2">
                                            <span class="total">{{ number_format((float)$perposal->total, 2, '.', '') }}</span>
                                        </p>


                                        <input type="hidden" class="total-field" name="total" value="{{ round($perposal->total, 2) }}">
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
                    <h5  class="modal-title" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    @lang('app.loading')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.close')</button>
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
    
    jQuery('#valid_till').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.proposals.update', $perposal->id)}}',
            container: '#updatePayments',
            type: "POST",
            redirect: true,
            data: $('#updatePayments').serialize()
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
            +'<div class="row">'
            +'<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>'
            +'<input type="text" min="0" class="form-control cost_per_item" value="0" name="cost_per_item[]">'
            +'</div>'
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

            +'<div class="col-md-2 text-center">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.amount')</label>'
            +'<p class="form-control-static"><span class="amount-html">0.00</span></p>'
            +'<input type="hidden" class="amount" name="amount[]">'
            +'</div>'

            +'<div class="col-md-1 text-right d-md-block d-lg-block">'
            +'<button type="button" class="btn remove-item btn-circle btn-outline-danger"><i class="fa fa-times"></i></button>'
            +'</div>'

            +'<div class="col-md-1 hidden-md hidden-lg">'
            +'<div class="row">'
            +'<button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>'
            +'</div>'
            +'</div>'

            +'</div>';

        $(item).hide().appendTo("#sortable").fadeIn(500);
        $('#multiselect'+i).select2();
    });

    $('#updatePayments').on('click', '.remove-item', function () {
        $(this).closest('.item-row').fadeOut(300, function () {
            $(this).remove();
            calculateTotal();
        });
    });

    $('#updatePayments').on('keyup', '.quantity,.cost_per_item,.item_name, .discount_value', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity * perItemCost);

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount));
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

        calculateTotal();


    });

    $('#updatePayments').on('change','.type, #discount_type', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount));
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

    $('#tax-settings').click(function () {
        var url = '{{ route('admin.taxes.create')}}';
        $('#modelHeading').html('Manage Project Category');
        $.ajaxModal('#taxModal', url);
    })

    function decimalupto2(num) {
        var amt =  Math.round(num * 100) / 100;
        return parseFloat(amt.toFixed(2));
    }
</script>
@endpush