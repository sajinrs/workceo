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
                            <li class="breadcrumb-item"><a href="{{ route('admin.contracts.index') }}">{{ __($pageTitle) }}</a></li>
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
                {!! Form::open(['id'=>'createContract','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('app.add') @lang('app.menu.contract')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <select placeholder="-" class="select2 form-control-lg form-control" name="client" id="clientID">
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ ucwords($client->company_name) }}</option>
                                        @endforeach
                                        </select>
                                        <label for="clientID" class="control-label">@lang('app.client')</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                              
                                        <input type="text" class="form-control form-control-lg" id="subject" name="subject" placeholder="-">
                                        <label for="subject" class="control-label">@lang('app.subject')</label>
                                    </div>
                                </div>
                            </div>
                            <!--/row-->                           

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                        
                                        <input type="number" class="form-control form-control-lg" id="amount" name="amount" placeholder="-">       
                                        <label for="amount" class="control-label">@lang('app.amount') ({{ $global->currency->currency_symbol }})</label>                               
                                    </div>
                                </div>
                               
                            </div>                          

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                        
                                        <input id="start_date" name="start_date" type="text" class="form-control form-control-lg" value="{{ \Carbon\Carbon::today()->format($global->date_format) }}">
                                        <label for="start_date" class="control-label">@lang('modules.timeLogs.startDate')</label>
                                    </div>  
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                     
                                        <input id="end_date" name="end_date" type="text" placeholder="-" class="form-control form-control-lg" value="{{ \Carbon\Carbon::today()->format($global->date_format) }}">
                                        <label for="end_date" class="control-label">@lang('modules.timeLogs.endDate')</label>
                                    </div>  
                                </div>                             
                            </div>

                            <div class="row">
                                <div class="col-md-6">                                 
                                    <div class="form-label-group form-group">
                                        <select class="select2 form-control-lg form-control" placeholder="-" id="contractType" name="contract_type">
                                            @foreach($contractType as $type)
                                                <option value="{{ $type->id }}">{{ ucwords($type->name) }}</option>
                                            @endforeach
                                        </select>
                                        <label for="contractType" class="control-label">@lang('modules.contracts.contractType')</label>
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                            <div class="form-label-group form-group">                           
                                            <select class="form-control form-control-lg" name="status" id="status" placeholder="-">
                                            <option
                                                    @if($client->status == 'Draft') selected @endif
                                            value="draft">@lang('modules.contracts.draft')
                                            </option>
                                            <option
                                                    @if($client->status == 'Sent') selected @endif
                                            value="sent">@lang('modules.contracts.sent')
                                            </option>
                                            <option
                                                    @if($client->status == 'Expired') selected @endif
                                            value="expired">@lang('modules.contracts.expired')
                                            </option>
                                            <option
                                                    @if($client->status == 'Declined') selected @endif
                                            value="declined">@lang('modules.contracts.declined')
                                            </option>
                                            <option
                                                    @if($client->status == 'Accepted') selected @endif
                                            value="accepted">@lang('modules.contracts.accepted')
                                            </option>
                                        </select>
                                        <label for="recurring_payment" class="control-label">@lang('app.status') </label>
                                            </div>
                                        </div>   
                                <div class="col-md-6">                                    
                                    <a href="javascript:;" id="createContractType" class="btn btn-sm btn-outline btn-primary">
                                        <i class="fa fa-plus"></i> @lang('modules.contracts.addContractType')
                                    </a>                                    
                                </div>                            
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group">           
                                        <textarea class="form-control form-control-lg" id="description" name="description" rows="4" placeholder="-"></textarea>
                                        <label for="description" class="control-label">@lang('modules.contracts.notes')</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Upload Contract</label>
                                        <div class="col-sm-9">
                                            <input name="contract_file" id="contractFile" class="form-control" type="file">
                                            <small>Accept only PDF, DOC, DOCX</small>
                                        </div>
                                    </div>
                                </div>
                            </div>                    
                            
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions col-md-3 offset-md-9">
                            <button type="submit" id="save-form" class="btn btn-primary form-control">@lang('app.save')</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

     <!-- .row -->
     {{--Ajax Modal--}}
     <div class="modal fade bs-modal-md in" id="taskCategoryModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">Modal title</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>    
    {{--Ajax Modal Ends--}}


@endsection



@push('footer-script')

<script>
    jQuery('#start_date, #end_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });
    
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.contracts.store')}}',
            container: '#createContract',
            type: "POST",
            redirect: true,
            file: (document.getElementById("contractFile").files.length == 0) ? false : true,
            data: $('#createContract').serialize()
        })
    });
    $('#createContractType').click(function(){
        var url = '{{ route('admin.contract-type.create-contract-type')}}';
        $('#modelHeading').html("@lang('modules.contracts.manageContractType')");
        $.ajaxModal('#taskCategoryModal', url);
    })
</script>
@endpush

