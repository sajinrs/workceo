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
                            <li class="breadcrumb-item active">@lang('app.update')</li>
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
                    {!! Form::open(['id'=>'updateExpense','class'=>'ajax-form','method'=>'PUT']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.expenses.updateExpense')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <select class="select2 form-control-lg form-control" placeholder="-" id="user_id" name="user_id">
                                            @foreach($employees as $employee)
                                                <option
                                                        @if($employee['user']['id'] == $expense->user_id) selected @endif
                                                value="{{ $employee['user']['id'] }}">{{ ucwords($employee['user']['name']) }}</option>
                                            @endforeach
                                        </select>                                                                          
                                        <label for="user_id" class="control-label">@lang('modules.messages.chooseMember')</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                  
                                        <select class="select2 form-control-lg form-control" id="project_id" name="project_id">
                                            @forelse($employees[0]['user']['projects'] as $project)
                                                <option @if($project['id'] == $expense->project_id) selected @endif value="{{ $project['id'] }}">
                                                    {{ $project['project_name'] }}
                                                </option>
                                            @empty
                                            @endforelse
                                        </select>
                                        <label for="project_id" class="control-label">@lang('modules.invoices.project')</label>
                                    </div>
                                </div>
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                       
                                        <select class="form-control-lg form-control" id="currency_id" name="currency_id">
                                            @forelse($currencies as $currency)
                                                <option @if($currency->id == $expense->currency_id) selected @endif value="{{ $currency->id }}">
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
                                        <input placeholder="-" type="text" name="item_name" id="item_name" class="form-control-lg form-control" value="{{ $expense->item_name }}">
                                        <label for="item_name" class="required">@lang('modules.expenses.itemName')</label>                                       
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                        
                                        <input placeholder="-" type="text" name="price" id="price" class="form-control-lg form-control" value="{{ $expense->price }}">     
                                        <label for="price" class="required">@lang('app.price')</label>                                   
                                    </div>  
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                     
                                        <input placeholder="-" type="text" name="purchase_from" id="purchase_from" class="form-control-lg form-control" value="{{ $expense->purchase_from }}"> 
                                        <label for="purchase_from" class="control-label">@lang('modules.expenses.purchaseFrom')</label>                                  
                                    </div>  
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                        
                                        <input placeholder="-" type="text" class="form-control-lg form-control" name="purchase_date" id="purchase_date" value="{{ $expense->purchase_date->format($global->date_format) }}">
                                        <label for="purchase_date" class="required">@lang('modules.expenses.purchaseDate')</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                    
                                        <input class="form-control form-control-lg" type="file" name="bill" id="bill">
                                        <label class="col-sm-3 col-form-label">@lang('app.invoice')</label>
                                        @if(!is_null($expense->bill))
                                            <a target="_blank" href="{{ asset_url('expense-invoice/'.$expense->bill) }}">@lang('app.viewInvoice')</a>
                                        @endif
                                    </div>
                                </div>                                                           

                                <!--/span-->
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">   
                                        <select class="form-control form-control-lg" placeholder="-" name="status" id="status">
                                            <option @if($expense->status == 'approved') selected @endif >approved
                                            </option>
                                            <option @if($expense->status == 'pending') selected @endif>pending</option>
                                            <option @if($expense->status == 'rejected') selected @endif>rejected
                                            </option>
                                        </select>
                                        <label for="status" class="required">@lang('app.status')</label>                                                                             
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <div class="form-actions col-md-6 offset-md-7">
                            <div class="row">
                                <div class="col-md-5 pr-0">
                                    <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline-primary gray form-control" >@lang('app.cancel')</a>
                                </div>
                                <div class="col-md-5 pr-0">
                                <button type="submit" id="save-form-2" class="btn btn-primary form-control">@lang('app.save')</button>
                                </div>
                            </div>
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
    var expense = @json($expense);
    var defaultOpt = '<option @if(is_null($expense->project_id)) selected @endif value="0">Select Project...</option>'

    var employee = employees.filter(function (item) {
        return item.id == expense.user_id
    })

    var options =  '';

    employee[0].user.projects.forEach(project => {
        options += `<option ${project.id === expense.project_id ? 'selected' : ''} value='${project.id}'>${project.project_name}</option>`
    })

    $('#project_id').html(defaultOpt+options)

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
        $('.select2#project_id').val('0').trigger('change')
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
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
            url: '{{route('admin.expenses.update', $expense->id)}}',
            container: '#updateExpense',
            type: "POST",
            redirect: true,
            file: (document.getElementById("bill").files.length == 0) ? false : true,
            data: $('#updateExpense').serialize()
        })
    });
</script>
@endpush
