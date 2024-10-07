<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/date-picker.css') }}">

<div class="modal-header">
    <h5 class="modal-title"><i class="fa fa-plus"></i> @lang('modules.invoices.addInvoice') - @lang('app.project') # {{ $project->id.' '.$project->project_name }}</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<!-- BEGIN FORM-->
{!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}
<div class="modal-body">
     <div class="form-body">

         {!! Form::hidden('project_id', $project->id) !!}
         @if(isset($project->client))
         {!! Form::hidden('company_name', $project->client->company_name) !!}
         @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" >
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div>
                                        <div class="input-group">
                                            <div class="input-group-addon form-control-lg f-16"><span class="invoicePrefix" data-prefix="{{ $invoiceSetting->invoice_prefix }}">{{ $invoiceSetting->invoice_prefix }}</span>#<span class="noOfZero" data-zero="{{ $invoiceSetting->invoice_digit }}">{{ $zero }}</span></div>
                                            <input type="text"  class="form-control form-control-lg" name="invoice_number" id="invoice_number" value="@if(is_null($lastInvoice))1 @else{{ ($lastInvoice) }}@endif">
                                            {{--<label for="invoice_number" class="control-label">@lang('app.invoice') #</label>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-label-group">
                        <select placeholder="-" class="form-control form-control-lg" name="currency_id" id="currency_id">
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                            @endforeach
                        </select>
                        <label for="currency_id" class="control-label">@lang('modules.invoices.currency')</label>
                    </div>

                </div>

            </div>

            <div class="row">
                <div class="col-md-6">

                    <div class="form-group form-label-group" >

                                {{--<div class="input-icon">--}}
                                    <input placeholder="-" type="text" class="form-control form-control-lg" name="issue_date" id="invoice_date" value="{{ Carbon\Carbon::today()->format($global->date_format) }}">
                                    <label for="invoice_date" class="required">@lang('modules.invoices.invoiceDate')</label>
                                {{--</div>--}}

                    </div>

                </div>

                <div class="col-md-6">
                    <div class="form-group form-label-group">
                        {{--<div class="input-icon">--}}
                            <input placeholder="-" type="text" class="form-control form-control-lg" name="due_date" id="due_date" value="{{ Carbon\Carbon::today()->addDays($invoiceSetting->due_after)->format($global->date_format) }}">
                            <label for="due_date" class="required">@lang('app.dueDate')</label>
                        {{--</div>--}}
                    </div>

                </div>

            </div>

            <hr>

            <div class="d-md-block d-lg-block d-none">
                @if(in_array("products", $modules) )
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group form-label-group">
                            <select placeholder="-" class="form-control form-control-lg select2" name="cat_id" id="cat_id">
                                <option value="">--</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name}}</option>
                                @endforeach
                            </select>
                            <label for="cat_id" class="control-label">Product Category</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group form-label-group">
                            <select placeholder="-" class="form-control form-control-lg select2" id="products">
                                <option value="">--</option>
                            </select>
                            <label for="products" class="control-label">@lang('app.menu.products') </label>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">

                    <div class="col-md-3 font-bold" style="padding: 8px 15px">
                        @lang('modules.invoices.item')
                    </div>

                    <div class="col-md-2 font-bold" style="padding: 8px 15px">
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
            </div>

            <div id="sortable">
                <div class="row item-row margin-top-5">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.item')</label>
                            {{--<div class="input-group">--}}
                                {{--<div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>--}}
                                <input type="text" class="form-control item_name" name="item_name[]">
                            {{--</div>--}}
                        </div>
                        <div class="form-group">
                            <textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="col-md-2">

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
                            <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.tax') <a href="javascript:;" id="tax-settings2" ><i class="icofont icofont-gear"></i></a></label>
                            <select id="multiselect" name="taxes[0][]"  multiple="multiple" class="select2 form-control type">
                                @foreach($taxes as $tax)
                                    <option data-rate="{{ $tax->rate_percent }}" value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="col-md-2 border-dark  text-center resTextLeft">
                        <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.amount')</label>

                        <p class="form-control-static"><span class="amount-html">0.00</span></p>
                        <input type="hidden" class="amount" name="amount[]" value="0">
                    </div>

                    <div class="col-md-1 text-right">
                        <a href="javascript:void(0);" class="text-danger remove-item p-15 f-22"><i class="fa fa-times"></i></a>
                        {{--<button type="button" class="btn remove-item btn-circle btn-outline-danger"><i class="fa fa-times"></i></button>--}}
                    </div>
                   {{-- <div class="col-md-1 d-md-none d-lg-none">
                        <div class="row">
                            <button type="button" class="btn btn-circle remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>
                        </div>
                    </div>--}}

                </div>
            </div>

             <div class="row">
                <div class="col-12 m-t-5">
                    <button type="button" class="btn btn-primary" id="add-item"><i class="fa fa-plus"></i> @lang('modules.invoices.addItem')</button>
                </div>
             </div>
            <div class="row">
                <div class="col-12 ">

                    <div class="row">
                        <div class="offset-md-6 col-xs-6 col-md-2 text-right p-t-10 resTextLeft p-r-0" >@lang('modules.invoices.subTotal')</div>

                        <p class="form-control-static col-xs-6 col-md-2" >
                            <span class="sub-total">0.00</span>
                        </p>


                        <input type="hidden" class="sub-total-field" name="sub_total" value="0">
                    </div>

                    <div class="row">
                        <div class="offset-md-7 col-md-1 text-right p-t-10 resTextLeft">
                            @lang('modules.invoices.discount')
                        </div>
                        <div class="form-group col-6 col-md-2" >
                            <input type="number" min="0" value="0" name="discount_value" class="form-control discount_value">
                        </div>
                        <div class="form-group col-6 col-md-2" >
                            <select class="form-control" name="discount_type" id="discount_type">
                                <option value="percent">%</option>
                                <option value="fixed">@lang('modules.invoices.amount')</option>
                            </select>
                        </div>
                    </div>

                    <div class="row m-t-5" id="invoice-taxes">
                        <div class="offset-md-7 col-md-1 text-right p-t-10 resTextLeft">
                            @lang('modules.invoices.tax')
                        </div>

                        <p class="form-control-static col-xs-6 col-md-2" >
                            <span class="tax-percent">0.00</span>
                        </p>
                    </div>

                    <div class="row m-t-5 font-bold">
                        <div class="offset-md-7 col-md-1 col-xs-6 text-right p-t-10 resTextLeft" >@lang('modules.invoices.total')</div>

                        <p class="form-control-static col-xs-6 col-md-2" >
                            <span class="total">0.00</span>
                        </p>


                        <input type="hidden" class="total-field" name="total" value="0">
                    </div>

                </div>
            </div>

        </div>
</div>
<div class="modal-footer">
    <div class="form-actions">
        <button type="button" class="btn btn-outline-primary gray" data-dismiss="modal">@lang('app.cancel')</button>
       <button type="button" id="save-form" class="btn btn-primary">@lang('app.save')</button>
    </div>
</div>
{!! Form::close() !!}
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>

{{--<script src="{{ asset('themes/wceo/assets/js/jquery.ui.min.js')}}"></script>--}}
<script>
    /*$(function () {
        $( "#sortable" ).sortable();
    });*/
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('#tax-settings, #tax-settings2').click(function () {
        var url = '{{ route('admin.taxes.create')}}';
        $('#modelHeading').html('Manage Project Category');
        $.ajaxModal('#taxModal', url);
    })

    jQuery('#invoice_date, #due_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });
    $('#invoice_number').on('keyup', function () {
        var invoiceNumber = $(this).val();
        var invoiceDigit = $('.noOfZero').data('zero');
        var invoiceZero = '';
        if(invoiceNumber.length < invoiceDigit){
            for ($i=0; $i<invoiceDigit-invoiceNumber.length; $i++){
                invoiceZero = invoiceZero+'0';
            }
        }

        // var invoice_no = invoicePrefix+'#'+invoiceZero;
        $('.noOfZero').text(invoiceZero);
    });

    $('#save-form').click(function(){

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
            url:'{{route('admin.invoices.store')}}',
            container:'#storePayments',
            type: "POST",
            redirect: true,
            data:$('#storePayments').serialize(),
            success: function (data) {
                if(data.status == 'success'){
                    $('#invoice-table').DataTable().ajax.reload();
                    $('#invoices-list-panel ul.list-group').html(data.html);
                    $('#add-invoice-modal').modal('hide');
                }
            }
        })
    });

    $('#add-item').click(function () {
        var i = $(document).find('.item_name').length;
        var item = '<div class="row item-row margin-top-5">'

            +'<div class="col-md-3">'

            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.item')</label>'
            // +'<div class="input-group">'
            // +'<div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>'
            +'<input type="text" class="form-control item_name" name="item_name[]" >'
            // +'</div>'
            +'</div>'

            +'<div class="form-group">'
            +'<textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>'
            +'</div>'



            +'</div>'

            +'<div class="col-md-2">'

            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.qty')</label>'
            +'<input type="number" min="1" class="form-control quantity" value="1" name="quantity[]" >'
            +'</div>'


            +'</div>'

            +'<div class="col-md-2">'

            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.unitPrice')</label>'
            +'<input type="text" min="0" class="form-control cost_per_item" value="0" name="cost_per_item[]">'
            +'</div>'
            

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

            +'<div class="col-md-1 text-right">'
                +'<a href="javascript:void(0);" class="text-danger remove-item  p-15 f-22"><i class="fa fa-times"></i></a>'

            // +'<button type="button" class="btn remove-item btn-circle btn-outline-danger"><i class="fa fa-times"></i></button>'
            +'</div>'

            {{--+'<div class="col-md-1 d-md-none d-lg-none">'--}}
            {{--+'<div class="row">'--}}
            {{--+'<button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>'--}}
            {{--+'</div>'--}}
            {{--+'</div>'--}}

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

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount));
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

        calculateTotal();


    });

    $('#storePayments').on('change','.type, #discount_type', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount));
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

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
                tax = tax+'<div class="offset-md-6 col-md-2 text-right p-t-10 resTextLeft">'
                    +key
                    +'</div>'
                    +'<p class="form-control-static col-xs-6 col-md-2" >'
                    +'<span class="tax-percent">'+decimalupto2(value)+'</span>'
                    +'</p>';
                taxTotal = taxTotal+decimalupto2(value);
            }
        });

        if(isNaN(subtotal)){  subtotal = 0; }

        $('.sub-total').html(decimalupto2(subtotal).toFixed(2));
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

        $('.total').html(total.toFixed(2));
        $('.total-field').val(total);

    }

    function decimalupto2(num) {
        var amt =  Math.round(num * 100) / 100;
        return parseFloat(amt.toFixed(2));
    }

    $('body').on('change', '#products', function () {
        var id = $(this).val(); 
        $.easyAjax({
            url:'{{ route('admin.all-invoices.update-item-project') }}',
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

</script>