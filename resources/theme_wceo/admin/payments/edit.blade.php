@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap-datetimepicker.min.css') }}">
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">{{ __($pageTitle) }}</a></li>
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
                    {!! Form::open(['id'=>'updatePayment','class'=>'ajax-form','method'=>'PUT']) !!}    
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.payments.updatePayment')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                @if(in_array('projects', $modules))
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                        
                                        <select class="select2 form-control-lg form-control" data-placeholder="@lang('app.selectProject') (@lang('app.optional'))" placeholder="-" name="project_id" id="project_id">
                                            <option value=""></option>
                                            @foreach($projects as $project)
                                                <option
                                                @if($project->id == $payment->project_id) selected @endif
                                                value="{{ $project->id }}">{{ $project->project_name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="project_id" class="control-label">@lang('app.selectProject')</label>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                     
                                        <select class="form-control form-control-lg" placeholder="-" name="currency_id" id="currency_id">
                                            @foreach($currencies as $currency)
                                                <option
                                                    @if($currency->id == $payment->currency_id) selected @endif
                                                    value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                                            @endforeach
                                        </select>
                                        <label for="currency_id" class="control-label">@lang('modules.invoices.currency')</label>                                                              
                                    </div>
                                </div>
                            </div>
                            <!--/row-->

                            <div class="row">                                
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                           
                                        <input type="text" placeholder="-" name="amount" id="amount" class="form-control form-control-lg" value="{{ number_format((float)$payment->amount, 2, '.', '') }}">    
                                        <label for="amount" class="required">@lang('modules.invoices.amount')</label>                                                                             
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                         
                                        <input type="text" name="gateway" id="gateway" class="form-control form-control-lg" placeholder="-" value="{{ $payment->gateway }}">
                                        <label for="gateway" class="control-label">@lang('modules.payments.paymentGateway')</label>
                                        <span  class="help-block"> Paypal, Authorize.net, Stripe, Bank Transfer, Cash or others.</span>                         
                                    </div>  
                                </div>

                            </div>

                            <div class="row">                                
                                <div class="col-md-4">
                                    <div class="form-label-group form-group">                                    
                                        <input type="text" name="transaction_id" id="transaction_id" class="form-control-lg form-control" placeholder="-" value="{{ $payment->transaction_id }}">
                                        <label for="transaction_id" class="control-label">@lang('modules.payments.transactionId')</label>                               
                                    </div>  
                                </div>

                                <div class="col-md-4">
                                    <div class="form-label-group form-group">                                    
                                        <input type="text" class="form-control-lg form-control" name="paid_on"  id="paid_on" @if(is_null($payment->paid_on)) value="{{ Carbon\Carbon::today()->format('d/m/Y H:i') }}" @else value="{{ $payment->paid_on->format('d/m/Y H:i') }}" @endif>
                                        <label for="transaction_id" class="control-label">@lang('modules.payments.paidOn')</label>                               
                                    </div>  
                                </div>

                                <div class="col-md-4">
                                    <div class="form-label-group form-group">                                    
                                        <select name="status" id="status" class="form-control form-control-lg" placeholder="-">
                                            <option @if($payment->status == 'complete') selected @endif value="complete">@lang('app.completed')</option>
                                            <option @if($payment->status == 'pending') selected @endif value="pending">@lang('app.pending')</option>
                                        </select>
                                        <label for="status" class="control-label">@lang('app.status')</label>                               
                                    </div>  
                                </div>
                                
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group">                                      
                                        <textarea id="remarks" name="remarks" class="form-control-lg form-control" placeholder="-" >{{ $payment->remarks }}</textarea>     
                                        <label for="remarks" class="control-label">@lang('app.remark')</label>
                                    </div>
                                </div>
                            </div>
                            
                            

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions col-md-6 offset-md-7">
                            <div class="row">
                                <div class="col-md-5 pr-0">
                                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-primary gray form-control" >@lang('app.cancel')</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js') }}"></script>
<script>    

    jQuery('#paid_on').datetimepicker({
        format: 'D/M/Y HH:mm',
        icons: {
            time: 'fa fa-clock',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-check',
            clear: 'fa fa-trash',
            close: 'fa fa-times'
        },
    });
   
    $('#save-form-2').click(function () {
        $.easyAjax({
            url: '{{route('admin.payments.update', $payment->id)}}',
            container: '#updatePayment',
            type: "POST",
            data: $('#updatePayment').serialize()
        })
    });
</script>
@endpush
