@extends('layouts.app')
@push('head-script')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
.dropdown-content {width: 250px;max-height: 250px;overflow-y: scroll;overflow-x: hidden;}
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.all-invoices.index') }}">{{ __($pageTitle) }}</a></li>
                            <li  class="breadcrumb-item active">@lang('app.addNew')</li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="{{ route('admin.all-invoices.create') }}" class="btn btn-primary btn-sm">@lang('modules.invoices.addInvoice') <i class="fa fa-plus" aria-hidden="true"></i></a>
                    </div>
                </div>
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
                        <h5>@lang('modules.invoices.addInvoice')</h5>
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
                                                <div class="input-group-addon  form-control-lg f-16"><span class="invoicePrefix" data-prefix="{{ $invoiceSetting->invoice_prefix }}">{{ $invoiceSetting->invoice_prefix }}</span>#<span class="noOfZero" data-zero="{{ $invoiceSetting->invoice_digit }}">{{ $zero }}</span></div>
                                                <input type="text"  class="form-control  form-control-lg readonly-background" readonly name="invoice_number" id="invoice_number" value="@if(is_null($lastInvoice))1 @else{{ ($lastInvoice) }}@endif">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                @if(in_array('projects', $modules))
                                    <div class="col-md-4">
                                        <div class="form-group form-label-group" >
                                            <select placeholder="-" class="select2 form-control form-control-lg" onchange="getCompanyName()" data-placeholder="Choose Project" id="project_id" name="project_id">
                                                <option value="">--</option>
                                                @foreach($projects as $project)
                                                    <option
                                                            value="{{ $project->id }}">{{ ucwords($project->project_name) }}</option>
                                                @endforeach
                                            </select>
                                            <label for="project_id" class="control-label">@lang('app.project')</label>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <div class="form-group form-label-group" id="client_company_div">
                                        <input placeholder="-" type="text" readonly class="form-control form-control-lg" name="" id="company_name" value="">
                                        <label for="company_name" class="control-label" id="companyClientName">@lang('app.client_name')</label>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group form-label-group" >
                                        <input placeholder="-" type="text" class="form-control form-control-lg" name="issue_date" id="invoice_date" value="{{ Carbon\Carbon::today()->format($global->date_format) }}">
                                        <label for="invoice_date" class="control-label">@lang('modules.invoices.invoiceDate')</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-" type="text" class="form-control form-control-lg" name="due_date" id="due_date" value="{{ Carbon\Carbon::today()->addDays($invoiceSetting->due_after)->format($global->date_format) }}">
                                        <label for="due_date" class="control-label">@lang('app.dueDate')</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-label-group">
                                        <select placeholder="-" class="form-control form-control-lg hide-search" name="currency_id" id="currency_id">
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                                            @endforeach
                                        </select>
                                        <label for="currency_id" class="control-label">@lang('modules.invoices.currency')</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group form-label-group" >
                                        <select placeholder="-" class="form-control form-control-lg hide-search" name="recurring_payment" id="recurring_payment" onchange="recurringPayment()">
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
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.invoices.showShippingAddress')
                                            <a class="example-popover text-primary" type="button" data-container="body"  data-trigger="hover" data-toggle="popover" data-placement="top" data-html="true" data-content="@lang('modules.invoices.showShippingAddressInfo')"><i class="fa fa-info-circle"></i></a>
                                        </label>
                                        <div class="switch-showcase icon-state">
                                            <label class="switch">
                                                <input type="checkbox" id="show_shipping_address" name="show_shipping_address" ><span class="switch-state"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div id="shippingAddress">

                                    </div>
                                </div>
                            </div>

                            
                            <div class="d-md-block d-lg-block ">
                                

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
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.item')</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                                                    <input type="text" class="form-control item_name" name="item_name[]">
                                                </div>
                                            </div>                                            
                                        </div>

                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.qty')</label>
                                                <input type="number" min="1" class="form-control quantity" value="1" name="quantity[]" >
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.unitPrice')</label>
                                                <input type="text"  class="form-control cost_per_item" name="cost_per_item[]" value="0" >
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none"> @lang('modules.invoices.tax') <a href="javascript:;" id="tax-settings2" ><i class="icofont icofont-gear"></i></a></label>
                                                <select id="multiselect" name="taxes[0][]"  multiple="multiple" class="select2 form-control type">
                                                    @foreach($taxes as $tax)
                                                        <option data-rate="{{ $tax->rate_percent }}" value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 border-dark  text-right invoiceAmount">
                                            <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.amount')</label>
                                            <p class="form-control-static">{{ $global->currency->currency_symbol }}<span class="amount-html">0.00</span></p>
                                            <input type="hidden" class="amount" name="amount[]" value="0">
                                        </div>

                                        <div class="col-md-1 text-right d-md-block d-lg-block d-none">
                                            <button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i></button>
                                        </div>
                                        <div class="col-md-1 d-md-none d-lg-none m-b-20">
                                            <button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>
                                        </div>
                                        <div class="col-md-9">                                            
                                            <div class="form-group">
                                                <textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-4 m-t-5">
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

                                    <div class="col-3 ">
                                        <div class="row">
                                            <div class="col-xs-6 col-md-4 p-b-10 text-left resTextLeft" >@lang('modules.invoices.subTotal')</div>

                                            <div class="col-xs-6 col-md-8 text-right" >
                                                {{ $global->currency->currency_symbol }}<span class="sub-total">0.00</span>
                                            </div>


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
                                                <select class="form-control hide-search" name="discount_type" id="discount_type">
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
                                            <div class="col-md-6 col-xs-6 text-left resTextLeft" >@lang('modules.invoices.total')</div>

                                            <div class="col-xs-6 col-md-6 text-right" >
                                                {{ $global->currency->currency_symbol }}<span class="total">0.00</span>
                                            </div>


                                            <input type="hidden" class="total-field" name="total" value="0">
                                        </div>

                                    </div>
                                </div>
                                
                               
                            </div>

                            <div class="row">
                            <div class="col-md-12">

                                <div class="form-group" >
                                    <label class="control-label">@lang('app.note')</label>
                                    <textarea class="form-control" name="note" id="note" rows="5"></textarea>
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
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    @lang('app.loading')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('app.close')</button>
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
<script src="{{ asset('themes/wceo/assets/js/popover-custom.js')}}"></script>
<script>
    getCompanyName();

    function getCompanyName(){
        var projectID = $('#project_id').val();
        var url = "{{ route('admin.all-invoices.get-client-company') }}";
        if(projectID != '' && projectID !== undefined )
        {
            url = "{{ route('admin.all-invoices.get-client-company',':id') }}";
            url = url.replace(':id', projectID);
        }
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                if(projectID != '')
                {
                    $('#companyClientName').text('{{ __('app.company_name') }}');
                } else {
                    $('#companyClientName').text('{{ __('app.client_name') }}');
                }

                $('#client_company_div').html(data.html);
                if ($('#show_shipping_address').prop('checked') === true) {
                    checkShippingAddress();
                }
            }
        });
    }

    function checkShippingAddress() {
        var projectId = $('#project_id').val();
        var clientId = $('#client_company_id').length > 0 ? $('#client_company_id').val() : $('#client_id').val();
        var showShipping = $('#show_shipping_address').prop('checked') === true ? 'yes' : 'no';

        var url = `{{ route('admin.all-invoices.checkShippingAddress') }}?showShipping=${showShipping}`;
        if (clientId !== '') {
            url += `&clientId=${clientId}`;
        }

        $.ajax({
            type: 'GET',
            url: url,
            success: function (response) {
                if (response) {
                    if (response.switch === 'off') {
                        showShippingSwitch.click();
                    }
                    else {
                        if (response.show !== undefined) {
                            $('#shippingAddress').html('');
                        } else {
                            $('#shippingAddress').html(response.view);
                        }
                    }
                }
            }
        });
    }

    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

    $('.js-switch').each(function () {
        new Switchery($(this)[0], $(this).data());
    });

    var showShippingSwitch = document.getElementById('show_shipping_address');

    showShippingSwitch.onchange = function() {
        if (showShippingSwitch.checked) {
            checkShippingAddress();
        }
        else {
            $('#shippingAddress').html('');
        }
    }

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
            url:"{{route('admin.all-invoices.store')}}",
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

            +'<div class="col-md-2 text-right resTextLeft">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.amount')</label>'
            +'<p class="form-control-static">{{ $global->currency->currency_symbol }}<span class="amount-html">0.00</span></p>'
            +'<input type="hidden" class="amount" name="amount[]">'
            +'</div>'

            +'<div class="col-md-1 text-right d-md-block d-lg-block d-none">'
            +'<button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i></button>'
            +'</div>'

            +'<div class="col-md-1 d-md-none d-lg-none m-b-20">'
            +'<button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>'
            +'</div>'

            +'<div class="col-md-9">'           
            +'<div class="form-group">'
            +'<textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>'
            +'</div>'            
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

    function calculateTotal(){
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
                tax = tax+'<div class="col-md-4 p-b-10 text-left resTextLeft">'
                    +key
                    +'</div>'
                    +'<div class="text-right col-xs-6 col-md-8" >'
                    +'{{ $global->currency->currency_symbol }}<span class="tax-percent">'+value.toLocaleString()+'</span>'
                   // +'<span class="tax-percent">'+(decimalupto2(value)).toFixed(2)+'</span>'
                    +'</div>';
                taxTotal = taxTotal+decimalupto2(value);
            }
        });

        if(isNaN(subtotal)){  subtotal = 0; }

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

//       show tax
        $('#invoice-taxes').html(tax);

//            calculate total
        var totalAfterDiscount = decimalupto2(subtotal-discount);

        totalAfterDiscount = (totalAfterDiscount < 0) ? 0 : totalAfterDiscount;

        var total = decimalupto2(totalAfterDiscount+taxTotal);

        //$('.total').html(total.toFixed(2));
        $('.total').html(total.toLocaleString());
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

</script>

<script>
    $("body").on("click",".tax-settings, #tax-settings2", function(){
        var url = '{{ route('admin.taxes.create')}}';
        $('#modelHeading').html('Manage Project Category');
        $.ajaxModal('#taxModal', url);
    });

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

