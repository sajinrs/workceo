@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/select2.css')}}">
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.all-credit-notes.index') }}">{{ __($pageTitle) }}</a></li>
                            <li  class="breadcrumb-item active">@lang('app.addNew')</li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <!-- <div class="col">
                    <div class="bookmark pull-right">
                        <a href="{{ route('admin.all-invoices.create') }}" class="btn btn-primary btn-sm">@lang('modules.invoices.addInvoice') <i class="fa fa-plus" aria-hidden="true"></i></a>
                    </div>
                </div> -->
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>


    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>@lang('modules.estimates.convertInvoiceTitle')</h5>
                    </div>
                    {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}

                    <div class="card-body">
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{--<label class="control-label">@lang('app.invoice') #</label>--}}
                                        <div>
                                            <div class="input-group ">
                                                <div class="input-group-addon  form-control-lg f-16"><span class="invoicePrefix" data-prefix="{{ $creditNoteSetting->credit_note_prefix }}">{{ $creditNoteSetting->credit_note_prefix }}</span>#<span class="noOfZero" data-zero="{{ $creditNoteSetting->credit_note_digit }}">{{ $zero }}</span></div>
                                                <input type="text"  class="form-control  form-control-lg readonly-background" readonly name="cn_number" id="cn_number" value="@if(is_null($lastCreditNote))1 @else{{ ($lastCreditNote) }}@endif">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                @if(in_array('projects', $modules))
                                    <div class="col-md-4">

                                        <div class="form-group form-label-group" >
                                            <select placeholder="-" class="select2 form-control form-control-lg" data-placeholder="Choose Project" name="project_id" id="project_id">
                                            <option value="" >--</option>
                                            @foreach($projects as $project)
                                                <option
                                                        @if($creditNote->project_id == $project->id) selected
                                                        @endif
                                                        value="{{ $project->id }}">{{ ucwords($project->project_name) }}</option>
                                            @endforeach
                                        </select>
                                        <label for="project_id" class="control-label">@lang('app.project')</label>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <div class="form-group form-label-group" id="client_company_div">
                                        <select placeholder="-" class="form-control form-control-lg" name="currency_id" id="currency_id">
                                            @foreach($currencies as $currency)
                                                <option
                                                        @if($creditNote->currency_id == $currency->id) selected
                                                        @endif
                                                        value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                                            @endforeach
                                        </select>
                                        <label for="currency_id" class="control-label">@lang('modules.credit-notes.currency')</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group form-label-group" >
                                        <input placeholder="-" type="text" class="form-control form-control-lg" name="issue_date" id="invoice_date" value="{{ $creditNote->issue_date ? $creditNote->issue_date->format($global->date_format) : '' }}">
                                        <label for="invoice_date" class="control-label">@lang('modules.credit-notes.creditNoteDate')</label>   
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-" type="text" class="form-control form-control-lg" name="due_date" id="due_date" value="{{ $creditNote->due_date ? $creditNote->due_date->format($global->date_format) : '' }}">
                                        <label for="due_date" class="control-label">@lang('app.dueDate')</label>
                                    </div>
                                </div>
                                <!-- <div class="col-md-4">
                                    <div class="form-group form-label-group">
                                        <select class="form-control form-control-lg" placeholder="-" name="recurring_payment" id="recurring_payment" onchange="recurringPayment()">
                                            <option value="no">@lang('app.no')</option>
                                            <option value="yes">@lang('app.yes')</option>
                                        </select>
                                        <label for="recurring_payment" class="control-label">@lang('modules.invoices.currency')</label>
                                    </div>

                                </div> -->

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group form-label-group" >
                                        <select placeholder="-" class="form-control form-control-lg" name="recurring_payment" id="recurring_payment" onchange="recurringPayment()">
                                            <option value="no">@lang('app.no')</option>
                                            <option value="yes">@lang('app.yes')</option>
                                        </select>
                                        <label for="recurring_payment" class="control-label">@lang('modules.invoices.isRecurringPayment') </label>
                                    </div>
                                </div>

                                <div class="col-md-3 recurringPayment" style="display: none;">
                                    <div class="form-group form-label-group">
                                        <select placeholder="-" class="form-control form-control-lg" name="billing_frequency" id="billing_frequency">
                                            <option value="day">@lang('app.day')</option>
                                            <option value="week">@lang('app.week')</option>
                                            <option value="month">@lang('app.month')</option>
                                            <option value="year">@lang('app.year')</option>
                                        </select>
                                        <label for="billing_frequency" class="control-label">@lang('modules.invoices.billingFrequency')</label>
                                    </div>
                                </div>

                                <div class="col-md-3 recurringPayment" style="display: none;">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-"  type="text" class="form-control form-control-lg" name="billing_interval" id="billing_interval" value="">
                                        <label for="billing_interval" class="control-label">@lang('modules.invoices.billingInterval')</label>
                                    </div>
                                </div>

                                <div class="col-md-3 recurringPayment" style="display: none;">
                                    <div class="form-group form-label-group">
                                        <input  placeholder="-"  type="text" class="form-control form-control-lg" name="billing_cycle" id="billing_cycle" value="">
                                        <label for="billing_cycle" class="control-label">@lang('modules.invoices.billingCycle')</label>
                                    </div>
                                </div>
                            </div>
                            

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group m-b-10">
                                        <button aria-expanded="false" data-toggle="dropdown" class="btn btn-info dropdown-toggle waves-effect waves-light" type="button">@lang('app.menu.products') <span class="caret"></span></button>
                                        <ul role="menu" class="dropdown-menu dropdown-content">
                                            @foreach($products as $product)
                                                <li class="m-b-10">
                                                    <div class="row m-t-10">
                                                        <div class="col-md-6" style="padding-left: 30px">
                                                            {{ $product->name }}
                                                        </div>
                                                        <div class="col-md-6" style="text-align: right;padding-right: 30px;">
                                                            <a href="javascript:;" data-pk="{{ $product->id }}" class="btn btn-success btn btn-outline btn-xs waves-effect add-product">@lang('app.add') <i class="fa fa-plus" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="d-md-block d-lg-block ">

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
                                    @foreach($creditNote->items as $key => $item)
                                    <div class="row item-row margin-top-5">

                                        <div class="col-md-4">
                                            {{--<div class="row">--}}
                                                <div class="form-group">
                                                    <label class="control-label d-md-none d-lg-none">@lang('modules.credit-notes.item')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                                                        <input type="text" class="form-control item_name" name="item_name[]" value="{{ $item->item_name }}">
                                                    </div>
                                                </div>                                                
                                            {{--</div>--}}

                                        </div>

                                        <div class="col-md-1">

                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.credit-notes.qty')</label>
                                                <input type="number" min="1" class="form-control quantity" value="1" name="quantity[]" value="{{ $item->quantity }}">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            {{--<div class="row">--}}
                                                <div class="form-group">
                                                    <label class="control-label d-md-none d-lg-none">@lang('modules.credit-notes.unitPrice')</label>
                                                    <input type="text"  class="form-control cost_per_item" name="cost_per_item[]" value="{{ number_format((float)$item->unit_price, 2, '.', '')  }}" >
                                                </div>
                                            {{--</div>--}}

                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.tax') <a href="javascript:;" id="tax-settings2" ><i class="icofont icofont-gear"></i></a></label>
                                                <select id="multiselect" name="taxes[{{ $key }}][]"  multiple="multiple" class="select2 form-control type">
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
                                            <label class="control-label d-md-none d-lg-none">@lang('modules.credit-notes.amount')</label>

                                            <p class="form-control-static"><span class="amount-html">{{ number_format((float)$item->amount, 2, '.', '') }}</span></p>
                                            <input type="hidden" class="amount" name="amount[]" value="{{ $item->amount }}">
                                        </div>

                                        <div class="col-md-1 text-right d-md-block d-lg-block d-none">
                                            <button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i></button>
                                        </div>
                                        <div class="col-md-1 d-md-none d-lg-none m-b-20">
                                            <button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>
                                        </div>

                                    </div>
                                    @endforeach
                                </div>

                                <div class="row">
                                    <div class="col-12 m-t-5">
                                        <button type="button" class="btn btn-info" id="add-item"><i class="fa fa-plus"></i> @lang('modules.invoices.addItem')</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 ">
                                        <div class="row">
                                            <div class="offset-md-8 col-xs-6 col-md-2 text-right p-t-10 resTextLeft" >@lang('modules.credit-notes.subTotal')</div>

                                            <p class="form-control-static col-xs-6 col-md-2" >
                                                <span class="sub-total">{{ number_format((float)$creditNote->sub_total, 2, '.', '') }}</span>
                                            </p>
                                            <input type="hidden" class="sub-total-field" name="sub_total" value="{{ $creditNote->sub_total }}">
                                        </div>

                                        <div class="row">
                                            <div class="offset-md-8 col-md-2 text-right p-t-10 resTextLeft">
                                                @lang('modules.credit-notes.discount')
                                            </div>
                                            <div class="form-group col-xs-6 col-md-1" >
                                                <input type="number" min="0" value="{{$discount}}" name="discount_value" class="form-control discount_value">
                                            </div>
                                            <div class="form-group col-xs-6 col-md-1" >
                                                <select class="form-control" name="discount_type" id="discount_type">
                                                    <option @if ($discountType == 'percent')
                                                        selected
                                                    @endif value="percent">%</option>
                                                    <option @if ($discountType == 'fixed')
                                                        selected
                                                    @endif value="fixed">@lang('modules.credit-notes.amount')</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row m-t-5" id="invoice-taxes">
                                            <div class="offset-md-8 col-md-2 text-right resTextLeft p-t-10">
                                                @lang('modules.credit-notes.tax')
                                            </div>

                                            <p class="form-control-static col-xs-6 col-md-2" >
                                                <span class="tax-percent">{{$totalTax}}</span>
                                            </p>
                                        </div>

                                        <div class="row m-t-5 font-bold">
                                            <div class="offset-md-8 col-md-2 col-xs-6 text-right p-t-10 resTextLeft" >@lang('modules.credit-notes.total')</div>

                                            <p class="form-control-static col-xs-6 col-md-2" >
                                                <span class="total">{{ number_format((float)$creditNote->sub_total-($totalTax+$totalDiscount), 2, '.', '') }}</span>
                                            </p>
                                            <input type="hidden" class="total-field" name="total" value="0">
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-actions row">
                            <button type="button" id="save-form" class="btn btn-primary col-md-3 offset-md-9">@lang('app.save')</button>

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
                    <h5 class="modal-title" id="modelHeading">Modal title</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    @lang('app.loading')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary gray" data-dismiss="modal">Close</button>
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
<script src="{{ asset('themes/wceo/assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/select2/select2-custom.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/popover-custom.js')}}"></script>

<script>
    $(function () {
        $( "#sortable" ).sortable();
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    jQuery('#invoice_date, #due_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });
    calculateTotal();
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
            url:'{{route('admin.all-credit-notes.store')}}',
            container:'#storePayments',
            type: "POST",
            redirect: true,
            data:$('#storePayments').serialize(),
            error: function (err) {
                var errors = err.responseJSON.errors;
                for (var error in errors) {
                    $.showToastr(errors[error][0], 'error')
                }
            }
        })
    });

    $('#add-item').click(function () {
        var i = $(document).find('.item_name').length;
        var item = '<div class="row item-row margin-top-5">'

            +'<div class="col-md-4">'
           // +'<div class="row">'
            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.item')</label>'
            +'<div class="input-group">'
            +'<div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>'
            +'<input type="text" class="form-control item_name" name="item_name[]" >'
            +'</div>'
            +'</div>'
            
          //  +'</div>'

            +'</div>'

            +'<div class="col-md-1">'

            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.qty')</label>'
            +'<input type="number" min="1" class="form-control quantity" value="1" name="quantity[]" >'
            +'</div>'


            +'</div>'

            +'<div class="col-md-2">'
          //  +'<div class="row">'
            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.unitPrice')</label>'
            +'<input type="text" min="0" class="form-control cost_per_item" value="0" name="cost_per_item[]">'
            +'</div>'
           // +'</div>'

            +'</div>'


            +'<div class="col-md-2">'

            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.tax')</label>'
            +'<select id="multiselect'+i+'" name="taxes['+i+'][]"  multiple="multiple" class="select2 form-control type">'
                @foreach($taxes as $tax)
            +'<option data-rate="{{ $tax->rate_percent }}" value="{{ $tax->id }}">{{ $tax->tax_name.': '.$tax->rate_percent }}%</option>'
                @endforeach
            +'</select>'
            +'</div>'


            +'</div>'

            +'<div class="col-md-2 text-center resTextLeft">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.amount')</label>'
            +'<p class="form-control-static"><span class="amount-html">0.00</span></p>'
            +'<input type="hidden" class="amount" name="amount[]">'
            +'</div>'

            +'<div class="col-md-1 text-right d-md-block d-lg-block d-none">'
            +'<button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i></button>'
            +'</div>'

            +'<div class="col-md-1 d-md-none d-lg-none m-b-20">'
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

        $(this).closest('.item-row').find('.amount').val(amount.toFixed(2));
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount).toFixed(2));

        calculateTotal();


    });

    $('#storePayments').on('change','.type, #discount_type', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(amount.toFixed(2));
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

                tax = tax+'<div class="offset-md-8 col-md-2 text-right p-t-10 resTextLeft">'
                    +key
                    +'</div>'
                    +'<p class="form-control-static col-xs-6 col-md-2" >'
                    +'<span class="tax-percent">'+(decimalupto2(value)).toFixed(2)+'</span>'
                    +'</p>';

                taxTotal = taxTotal+value;
            }

        });

        if(isNaN(subtotal)){  subtotal = 0; }

        $('.sub-total').html(decimalupto2(subtotal).toFixed(2));
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

//       show tax
        $('#invoice-taxes').html(tax);

//            calculate total
        var totalAfterDiscount = decimalupto2(subtotal-discount);

        totalAfterDiscount = (totalAfterDiscount < 0) ? 0 : totalAfterDiscount;

        var total = decimalupto2(totalAfterDiscount+taxTotal);

        $('.total').html(total.toFixed(2));
        $('.total-field').val(total.toFixed(2));

    }

    function recurringPayment() {
        var recurring = $('#recurring_payment').val();

        if(recurring == 'yes')
        {
            $('.recurringPayment').show().fadeIn(300);
        } else {
            $('.recurringPayment').hide().fadeOut(300);
        }
    }

    $('#tax-settings, #tax-settings2').click(function () {
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
            url:'{{ route('admin.all-credit-notes.update-item') }}',
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

