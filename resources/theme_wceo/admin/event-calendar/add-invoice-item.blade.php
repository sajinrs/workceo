<div class="row">
    <div class="col-md-1 p-r-0">
        <div class="event-icon"></div>
    </div>
    
    <div class="col-md-11">
        <div class="form-group form-label-group">
            <input type="text" class="form-control form-control-lg" id="item_name" name="item_name[]" placeholder="@lang('modules.invoices.item')" value="{{ $items->name }}" />
            <label for="item_name" class="required">Invoice Item </label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-1 p-r-0">
        <div class="event-icon"><i data-feather="dollar-sign"></i></div>
    </div>
    
    <div class="col-md-11">
        <div class="row">
            <div class="col-md-4 p-r-0">
                <div class="form-group form-label-group">
                    <input placeholder="-" type="number" min="1" name="quantity[]" id="quantity" data-item-id="{{ $items->id }}" class="quantity form-control form-control-lg" value="1" />
                    <label for="quantity" class="required">@lang('modules.invoices.qty')</label>
                </div>
            </div>

            <div class="col-md-4 p-r-0">
                <div class="form-group form-label-group">
                    <input placeholder="-" type="text" name="cost_per_item[]" id="cost_per_item" class="cost_per_item form-control form-control-lg" data-item-id="{{ $items->id }}" value="{{ $items->price }}" />
                    <label for="cost_per_item" class="required">@lang('modules.invoices.unitPrice')</label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    


                    <select id="multiselect" class="form-control select2 m-b-10 select2-multiple col-md-12 type" multiple="multiple" placeholder="_"
                            data-placeholder="@lang('modules.invoices.tax')" name="taxes[0][]">
                            @foreach($taxes as $tax)
                            <option data-rate="{{ $tax->rate_percent }}"
                                @if (isset($items->taxes) && array_search($tax->id, json_decode($items->taxes)) !== false)
                                selected
                                @endif
                                value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                            @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group form-label-group">
                    <textarea placeholder="-" name="item_summary[]" id="item_summary" class="form-control form-control-lg">{{ $items->description }}</textarea>
                    <label for="item_summary">@lang('app.description')</label>
                </div>
                <input type="hidden" class="amount" name="amount[]" data-item-id="{{ $items->id }}">
            </div>
        </div>
    </div>
</div>
{{--<div class="row item-row margin-top-5">     

        <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label d-md-none d-lg-none hidden-md hidden-lg">@lang('modules.invoices.item')</label>
                    <div class="input-group">
                        <div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                        <input type="text" class="form-control item_name" name="item_name[]" value="{{ $items->name }}">
                    </div>
                </div>
                <div class="form-group">
                    <textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2">{{ $items->description }}</textarea>

                </div>

        </div>
    

    <div class="col-md-1">
        <div class="form-group">
            <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.qty')</label>
            <input type="number" min="1" class="form-control quantity" data-item-id="{{ $items->id }}" value="1" name="quantity[]" >
        </div>
    </div>

    <div class="col-md-2">
        <div class="row">
            <div class="form-group">
                <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>
                <input type="text"  class="form-control cost_per_item" name="cost_per_item[]" data-item-id="{{ $items->id }}" value="{{ $items->price }}">
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label d-md-none d-lg-none hidden-md hidden-lg">@lang('modules.invoices.type')</label>
            <select id="multiselect" name="taxes[0][]"  multiple="multiple" class="js-example-basic-multiple form-control type">
                @foreach($taxes as $tax)
                <option data-rate="{{ $tax->rate_percent }}"
                    @if (isset($items->taxes) && array_search($tax->id, json_decode($items->taxes)) !== false)
                    selected
                    @endif
                    value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-2 border-dark  text-center">
        <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.amount')</label>

        <p class="form-control-static"><span class="amount-html" data-item-id="{{ $items->id }}">0</span></p>
        <input type="hidden" class="amount" name="amount[]" data-item-id="{{ $items->id }}">
    </div>

    <div class="col-md-1 text-right d-md-block d-lg-block visible-md visible-lg">
        <button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i></button>
    </div>
    <div class="col-md-1 d-md-none d-lg-none hidden-md hidden-lg">
        <div class="row">
            <button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>
        </div>
    </div>

    

    
</div>--}}
