@extends('layouts.app')
@push('head-script')

<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
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
                        <h4 class="card-title mb-0">@lang('app.copy') @lang('app.menu.contract')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="card-title wceo-card-header mb-10">
                                        <div class="panel-heading">{{ $contract->subject }}
                                            <a href="{{ route('admin.contracts.show', md5($contract->id)) }}" target="_blank" class="btn btn-sm btn-outline btn-secondary pull-right">View Contract</a>
                                        </div>
                                    </h4>
                                
                                    <ul class="nav nav-tabs border-tab nav-secondary nav-left contractTab" id="danger-tab" role="tablist">
                                        <li class="nav-item"><a class="nav-link active" id="summery" data-toggle="tab" href="#danger-home" role="tab" aria-controls="danger-home" aria-selected="true"><i class="fa fa-file"></i> @lang('app.menu.contract')</a></li>
                                    </ul>
                                    <div class="tab-content" id="danger-tabContent">
                                        <div class="tab-pane fade show active" id="danger-home" role="tabpanel" aria-labelledby="summery">
                                            <div class="form-group">
                                                <textarea name="contract_detail" id="contract_detail">{{ $contract->contract_detail }}</textarea>
                                            </div>                                            
                                        </div>                                        
                                        
                                    </div>
                                </div>

                                <div class="col-sm-6">

                                    <div class="row">                                        
                                    
                                        <div class="col-md-12">
                                            <div class="form-label-group form-group">                              
                                                <input type="text" class="form-control form-control-lg" id="subject" name="subject" placeholder="-" value="{{ $contract->subject ?? '' }}">
                                                <label for="subject" class="control-label">@lang('app.subject')</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">                                    
                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">
                                                <select placeholder="-" class="select2 form-control-lg form-control" name="client" id="clientID">
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}" @if($client->id == $contract->client_id) selected @endif>{{ ucwords($client->name) }}</option>
                                                @endforeach
                                                </select>
                                                <label for="clientID" class="control-label">@lang('app.client')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">                                                
                                                <input type="number" class="form-control form-control-lg" placeholder="-" id="amount" name="amount" value="{{ $contract->amount ?? '' }}">
                                                <label for="amount" class="control-label">@lang('app.amount') ({{ $global->currency->currency_symbol }})</label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">
                                                <input id="start_date" name="start_date" type="text" placeholder="-" class="form-control-lg form-control" value="{{ $contract->start_date->timezone($global->timezone)->format($global->date_format) }}">
                                                <label for="start_date" class="control-label">@lang('modules.timeLogs.startDate')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">                                                
                                                <input id="end_date" name="end_date" type="text" placeholder="-" class="form-control-lg form-control" value="{{ $contract->end_date->timezone($global->timezone)->format($global->date_format) ?? '' }}">
                                                <label for="end_date" class="control-label">@lang('modules.timeLogs.endDate')</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">                           
                                                <select class="select2 form-control-lg  form-control" data-placeholder="@lang('app.client')" id="contractType" name="contract_type">
                                                    @foreach($contractType as $type)
                                                        <option value="{{ $type->id }}" @if($type->id == $contract->contract_type_id) selected @endif>{{ ucwords($type->name) }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="contractType" class="control-label">@lang('modules.contracts.contractType')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <a href="javascript:;" id="createContractType" class="btn btn-sm btn-outline btn-secondary">
                                                    <i class="fa fa-plus"></i> @lang('modules.contracts.addContractType')
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-label-group form-group">                                                
                                                <textarea placeholder="-" class="form-control-lg form-control" id="description" name="description" rows="4">{{ $contract->description ?? '' }}</textarea>
                                                <label for="description" class="control-label">@lang('modules.contracts.notes')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <div class="form-actions col-md-6 offset-md-7">
                            <div class="row">
                                <div class="col-md-5 pr-0">
                                    <a href="{{ route('admin.contracts.index') }}" class="btn btn-secondary form-control">@lang('app.back')</a>
                                </div>
                                <div class="col-md-5 pr-0">
                                <button type="submit" id="save-form" class="btn btn-primary form-control">@lang('app.copy')</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="taskCategoryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title caption-subject" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="save-category" class="btn btn-primary"> @lang('app.save')</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->.
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.custom.js')}}"></script>

<script>
    $(document).ready(() => {
        $('.slimscrolltab').slimScroll({
            height: '283px'
            , position: 'right'
            , size: "5px"
            , color: '#dcdcdc'
            , });
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('#contract_detail').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen"]]
        ]
    });
    
    jQuery('#start_date, #end_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.contracts.copy-submit')}}',
            container: '#createContract',
            type: "POST",
            redirect: true,
            data: $('#createContract').serialize()
        })
    });

    $('#createContractType').click(function(){
        var url = '{{ route('admin.contract-type.create-contract-type')}}';
        $('#modelHeading').html("@lang('modules.contracts.manageContractType')");
        $.ajaxModal('#taskCategoryModal', url);
    })

    function  renewContract() {
        var url = '{{ route('admin.contracts.renew', $contract->id)}}';
        $.ajaxModal('#taskCategoryModal',url);
    }

</script>
@endpush

