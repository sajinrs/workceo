@extends('layouts.app')


@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.expenses.index') }}">{{ __($pageTitle) }}</a></li>
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
                    {!! Form::open(['id'=>'createExpense','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.expenses.addExpense')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <select placeholder="-" id="user_id" class="select2 form-control-lg form-control" name="user_id">
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee['user']['id'] }}">{{ ucwords($employee['user']['name']) }}</option>
                                            @endforeach
                                        </select>                                     
                                        <label for="user_id" class="control-label">@lang('modules.messages.chooseMember')</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                    
                                        <select placeholder="-" class="select2 form-control-lg form-control" id="project_id" name="project_id">
                                            <option value="0">Select Project...</option>
                                            @if($employees)
                                                @forelse($employees[0]['user']['projects'] as $project)
                                                    <option value="{{ $project['id'] }}">
                                                        {{ $project['project_name'] }}
                                                    </option>
                                                @empty
                                                @endforelse
                                            @endif
                                        </select>
                                        <label for="project_id" class="control-label">@lang('modules.invoices.project')</label>
                                    </div>
                                </div>
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                        
                                        <select placeholder="-" class="form-control-lg form-control hide-search" id="currency_id" name="currency_id">
                                            @forelse($currencies as $currency)
                                                <option @if($currency->id == $global->currency_id) selected @endif value="{{ $currency->id }}">
                                                    {{ $currency->currency_name }} - ({{ $currency->currency_symbol }})
                                                </option>
                                            @empty
                                            @endforelse
                                        </select>
                                        <label for="currency_id" class="control-label">@lang('modules.invoices.currency')</label>                                       
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                   
                                        <input placeholder="-" type="text" name="item_name" id="item_name" class="form-control-lg form-control">
                                        <label for="item_name" class="required">@lang('modules.expenses.itemName')</label>                                       
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                        
                                        <input placeholder="-" type="text" name="price" id="price" class="form-control-lg form-control">     
                                        <label for="price" class="required">@lang('app.price')</label>                                   
                                    </div>  
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                     
                                        <input placeholder="-" type="text" name="purchase_from" id="purchase_from" class="form-control-lg form-control"> 
                                        <label for="purchase_from" class="control-label">@lang('modules.expenses.purchaseFrom')</label>                                  
                                    </div>  
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                        
                                        <input placeholder="-" type="text" class="form-control-lg form-control" name="purchase_date" id="purchase_date" value="{{ Carbon\Carbon::today()->format($global->date_format) }}">
                                        <label for="purchase_date" class="required">@lang('modules.expenses.purchaseDate')</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                    
                                        <input class="form-control form-control-lg" type="file" name="bill" id="bill">
                                        <label class="col-sm-3 col-form-label">@lang('app.invoice')</label>
                                    </div>
                                </div>                                                           

                                <!--/span-->
                            </div>
                            
                            

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions col-md-3 offset-md-9">
                            <button type="submit" id="save-form-2" class="btn btn-primary form-control">@lang('app.save')</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection



@push('footer-script')

<script>

    var employees = @json($employees);

    $('#user_id').change(function (e) {
        // get projects of selected users
        var opts = '';

        var employee = employees.filter(function (item) {
            return item.id == e.target.value
        });

        employee[0].user.projects.forEach(project => {
            opts += `<option value='${project.id}'>${project.project_name}</option>`
        })

        $('#project_id').html('<option value="0">Select Project...</option>'+opts)
    });    
    
    jQuery('#purchase_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });

    $('#save-form-2').click(function () {
        $.easyAjax({
            url: '{{route('admin.expenses.store')}}',
            container: '#createExpense',
            type: "POST",
            redirect: true,
            file: (document.getElementById("bill").files.length == 0) ? false : true,
            data: $('#createExpense').serialize()
        })
    });
</script>
@endpush
