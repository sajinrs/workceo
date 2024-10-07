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
                            <li  class="breadcrumb-item active">@lang('app.update')</li>
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
                        <h5>@lang('app.update') @lang('app.invoice')</h5>
                    </div>
                    {!! Form::open(['id'=>'updatePayments','class'=>'ajax-form','method'=>'PUT']) !!}

                    <div class="card-body">
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div>
                                            <div class="input-group ">
                                                <input type="text"  class="form-control  form-control-lg readonly-background" readonly name="invoice_number" id="invoice_number" value="{{ $invoice->invoice_number }}">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                @if(in_array('projects', $modules))
                                    <div class="col-md-4">
                                        <div class="form-group form-label-group" >
                                            <select class="select2 form-control form-control-lg" onchange="getCompanyName()" placeholder="-" data-placeholder="Choose Project" name="project_id" id="project_id">
                                                <option value="">--</option>
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
                                @endif
                                <div class="col-md-4">
                                    <div class="form-group form-label-group" id="client_company_div">
                                        @if($invoice->project_id == '')
                                        <select class="form-control select2 form-control-lg" placeholder="-" name="client_id" id="client_id" data-style="form-control">
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}" @if($client->id == $invoice->client_id) selected @endif>{{ ucwords($client->name) }}
                                                        @if($client->company_name != '') {{ '('.$client->company_name.')' }} @endif</option>
                                                @endforeach
                                            </select>
                                        @else
                                                <input type="text" placeholder="-" readonly class="form-control form-control-lg" name="" id="company_name" value="{{ $companyName }}">
                                        @endif
                                        <label class="control-label" id="companyClientName"> @if($invoice->project_id == '') @lang('app.client_name') @else @lang('app.company_name') @endif</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group form-label-group" >
                                        <input placeholder="-" type="text" class="form-control form-control-lg" name="issue_date" id="invoice_date" value="{{ $invoice->issue_date->format($global->date_format) }}">
                                        <label for="invoice_date" class="control-label">@lang('modules.invoices.invoiceDate')</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-" type="text" class="form-control form-control-lg" name="due_date" id="due_date" value="{{ $invoice->due_date->format($global->date_format) }}">
                                        <label for="due_date" class="control-label">@lang('app.dueDate')</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-label-group">
                                        <select placeholder="-" class="form-control form-control-lg hide-search" name="currency_id" id="currency_id">
                                            @foreach($currencies as $currency)
                                                <option
                                                    @if($invoice->currency_id == $currency->id) selected
                                                    @endif
                                                    value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="currency_id" class="control-label">@lang('modules.invoices.currency')</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group form-label-group" >
                                        <select class="form-control form-control-lg hide-search" name="status" id="status" placeholder="-">
                                            <option
                                                    @if($invoice->status == 'paid') selected @endif
                                            value="paid">@lang('modules.invoices.paid')
                                            </option>
                                            <option
                                                    @if($invoice->status == 'unpaid') selected @endif
                                            value="unpaid">@lang('modules.invoices.unpaid')
                                            </option>
                                            <option
                                                    @if($invoice->status == 'canceled') selected @endif
                                            value="canceled">@lang('modules.invoices.cancel')
                                            </option>
                                            {{--<option
                                                    @if($invoice->status == 'partial') selected @endif
                                            value="partial">@lang('modules.invoices.partial')
                                            </option>--}}
                                        </select>
                                        <label for="recurring_payment" class="control-label">@lang('app.status') </label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group form-label-group" >
                                        <select placeholder="-" class="form-control form-control-lg hide-search" name="recurring_payment" id="recurring_payment" onchange="recurringPayment()">
                                            <option value="no" @if($invoice->recurring == 'no') selected @endif>@lang('app.no')</option>
                                            <option value="yes" @if($invoice->recurring == 'yes') selected @endif>@lang('app.yes')</option>
                                        </select>
                                        <label for="recurring_payment" class="control-label">@lang('modules.invoices.isRecurringPayment') </label>
                                    </div>
                                </div>

                                <div class="col-md-2 recurringPayment" style="display: none;">
                                    <div class="form-group form-label-group">
                                        <select placeholder="-" class="form-control form-control-lg" name="billing_frequency" id="billing_frequency">
                                            <option value="day" @if($invoice->billing_frequency == 'day') selected @endif>@lang('app.day')</option>
                                            <option value="week" @if($invoice->billing_frequency == 'week') selected @endif>@lang('app.week')</option>
                                            <option value="month" @if($invoice->billing_frequency == 'month') selected @endif>@lang('app.month')</option>
                                            <option value="year" @if($invoice->billing_frequency == 'year') selected @endif>@lang('app.year')</option>
                                        </select>
                                        <label for="billing_frequency" class="control-label">@lang('modules.invoices.billingFrequency')</label>
                                    </div>
                                </div>

                                <div class="col-md-3 recurringPayment" style="display: none;">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-"  type="text" class="form-control form-control-lg" name="billing_interval" id="billing_interval" value="{{ $invoice->billing_interval }}">
                                        <label for="billing_interval" class="control-label">@lang('modules.invoices.billingInterval')</label>
                                    </div>
                                </div>

                                <div class="col-md-2 recurringPayment" style="display: none;">
                                    <div class="form-group form-label-group">
                                        <input  placeholder="-"  type="text" class="form-control form-control-lg" name="billing_cycle" id="billing_cycle" value="{{ $invoice->billing_cycle }}">
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
                                                <input type="checkbox" id="show_shipping_address" name="show_shipping_address" @if($global->show_shipping_address == 'yes') checked @endif><span class="switch-state"></span>
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
                                    @foreach($invoice->items as $key => $item)
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
                                                    <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.item')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                                                        <input type="text" class="form-control item_name" name="item_name[]" value="{{ $item->item_name }}">
                                                    </div>
                                                </div>                                                
                                            {{--</div>--}}

                                        </div>

                                        <div class="col-md-1">

                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.qty')</label>
                                                <input type="number" min="1" class="form-control quantity" name="quantity[]" value="{{ $item->quantity }}">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.unitPrice')</label>
                                                <input type="text"  class="form-control cost_per_item" name="cost_per_item[]" value="{{ $item->unit_price }}" >
                                            </div>
                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.type')</label>
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

                                        <div class="col-md-2 border-dark  text-right ">
                                            <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.amount')</label>
                                            <p class="form-control-static">{{ $global->currency->currency_symbol }}<span class="amount-html">{{ currencyFormat($item->amount)}}</span></p>
                                            <input type="hidden" class="amount" name="amount[]" value="{{ $item->amount }}">
                                        </div>

                                        <div class="col-md-1 text-right d-md-block d-lg-block d-none">
                                            <button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i></button>
                                        </div>
                                        <div class="col-md-1 d-md-none d-lg-none">
                                            <div class="row">
                                                <button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>
                                            </div>
                                        </div>
                                        <div class="col-md-9">                                            
                                            <div class="form-group">
                                                <textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2">{{ $item->item_summary }}</textarea>
                                            </div>                                          
                                        </div>

                                    </div>
                                    @endforeach
                                </div>

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

                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-xs-6 col-md-4 text-left p-b-10 resTextLeft" >@lang('modules.invoices.subTotal')</div>

                                            <div class="col-xs-6 col-md-8 text-right" >
                                                {{ $global->currency->currency_symbol }}<span class="sub-total">{{ currencyFormat($invoice->sub_total) }}</span>
                                            </div>
                                            <input type="hidden" class="sub-total-field" name="sub_total" value="{{ $invoice->sub_total }}">
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 text-left p-t-10 resTextLeft">
                                                @lang('modules.invoices.discount')
                                            </div>
                                            <div class="form-group col-xs-6 col-md-4" >
                                                <input type="number" min="0" value="{{ $invoice->discount }}" name="discount_value" class="form-control discount_value">
                                            </div>
                                            <div class="form-group col-xs-6 col-md-4" >
                                                <select class="form-control hide-search" name="discount_type" id="discount_type">
                                                    <option
                                                        @if($invoice->discount_type == 'percent') selected @endif
                                                        value="percent">%</option>
                                                    <option
                                                        @if($invoice->discount_type == 'fixed') selected @endif
                                                        value="fixed">@lang('modules.invoices.amount')</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" id="invoice-taxes">
                                            <div class="col-md-8 text-left p-t-10">
                                                @lang('modules.invoices.tax')
                                            </div>

                                            <p class="form-control-static col-xs-6 col-md-4" >
                                            {{ $global->currency->currency_symbol }}<span class="tax-percent">0.00</span>
                                            </p>
                                        </div>

                                        <div class="row font-bold total-amount">
                                            <div class="col-md-4 col-xs-6 text-left resTextLeft" >@lang('modules.invoices.total')</div>

                                            <div class="col-xs-6 col-md-8 text-right" >
                                            {{ $global->currency->currency_symbol }}<span class="total">{{ currencyFormat($invoice->total) }}</span>
                                            </div>
                                            <input type="hidden" class="total-field" name="total" value="{{ round($invoice->total, 2) }}">
                                        </div>

                                    </div>
                                </div>
                               
                            </div>

                            <div class="row">
                            <div class="col-md-12">

                                <div class="form-group" >
                                    <label class="control-label">@lang('app.note')</label>
                                    <textarea class="form-control" name="note" id="note" rows="5">{{ $invoice->note }}</textarea>
                                </div>

                            </div>
                            </div>


                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <div class="form-actions col-md-6 offset-md-7">
                            <div class="row">
                                <div class="col-md-5 pr-0">
                                    <a href="{{ route('admin.all-invoices.index') }}" class="btn btn-outline-primary gray form-control" >@lang('app.cancel')</a>
                                </div>
                                <div class="col-md-5 pr-0">
                                <button type="submit" id="save-form" class="btn btn-primary form-control">@lang('app.save')</button>
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
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    @lang('app.loading')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">@lang('app.close')</button>
                    <button type="button" class="btn blue">@lang('app.save') @lang('changes')</button>
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
<script src="{{ asset('themes/wceo/assets/js/popover-custom.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>

<script>   
    var showShippingSwitch = document.getElementById('show_shipping_address');

    @if($invoice->show_shipping_address === 'yes')
        showShippingSwitch.click();
    @else
        getCompanyName();
    @endif

    showShippingSwitch.onchange = function() {
        if (showShippingSwitch.checked) {
            checkShippingAddress();
        }
        else {
            $('#shippingAddress').html('');
        }
    }

    function getCompanyName(){
        var projectID = $('#project_id').val();
        var url = "{{ route('admin.all-invoices.get-client-company') }}";
        if(projectID != ''  && projectID !== undefined )
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

    $(function () {
        recurringPayment();
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

    $('#save-form').click(function () {

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
            url: '{{route('admin.all-invoices.update', $invoice->id)}}',
            container: '#updatePayments',
            type: "POST",
            redirect: true,
            data: $('#updatePayments').serialize()
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

    $('#updatePayments').on('click', '.remove-item', function () {
        $(this).closest('.item-row').fadeOut(300, function () {
            $(this).remove();
            calculateTotal();
        });
    });

    $('#updatePayments').on('keyup change', '.quantity,.cost_per_item,.item_name, .discount_value', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity * perItemCost);

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount).toFixed(2));
        $(this).closest('.item-row').find('.amount-html').html(amount.toLocaleString());
        //$(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount).toFixed(2));

        calculateTotal();


    });

    $('#updatePayments').on('change','.type, #discount_type', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount).toFixed(2));
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount).toFixed(2));

        calculateTotal();


    });


    //calculate subtotal
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

                tax = tax+'<div class="col-md-4 p-b-10 text-left">'
                    +key
                    +'</div>'
                    +'<div class="col-xs-6 col-md-8 text-right" >'
                    //+'{{ $global->currency->currency_symbol }}<span class="tax-percent">'+(decimalupto2(value)).toFixed(2)+'</span>'
                    +'{{ $global->currency->currency_symbol }}<span class="tax-percent">'+value.toLocaleString(2)+'</span>'
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

//       show tax
        $('#invoice-taxes').html(tax);

//            calculate total
        var totalAfterDiscount = subtotal-discount;

        totalAfterDiscount = (totalAfterDiscount < 0) ? 0 : totalAfterDiscount;

        var total = decimalupto2(totalAfterDiscount+taxTotal);

        //$('.total').html(total.toFixed(2));
        $('.total').html(total.toLocaleString(2));
        $('.total-field').val(total.toFixed(2));

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

    function decimalupto2(num) {
        var amt =  Math.round(num * 100,2) / 100;
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

    $("body").on("click",".tax-settings, #tax-settings2", function(){
        var url = '{{ route('admin.taxes.create')}}';
        $('#modelHeading').html('Manage Project Category');
        $.ajaxModal('#taxModal', url);
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

