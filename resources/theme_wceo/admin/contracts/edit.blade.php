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
                {!! Form::open(['id'=>'createContract','class'=>'ajax-form','method'=>'PUT']) !!} 
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('app.edit') @lang('app.menu.contract')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="card-title wceo-card-header mb-10">
                                        <div class="panel-heading">{{ $contract->subject }}
                                            <a href="{{ route('admin.contracts.show', md5($contract->id)) }}" target="_blank" class="btn btn-sm btn-outline btn-primary pull-right">View Contract</a>
                                        </div>
                                    </h4>
                                
                                    <ul class="nav nav-tabs border-tab nav-secondary nav-left contractTab" id="danger-tab" role="tablist">
                                        <li class="nav-item"><a class="nav-link active" id="summery" data-toggle="tab" href="#danger-home" role="tab" aria-controls="danger-home" aria-selected="true"><i class="fa fa-file"></i> @lang('app.menu.contract')</a></li>
                                        <li class="nav-item"><a class="nav-link" id="renewval" data-toggle="tab" href="#danger-profile" role="tab" aria-controls="danger-profile" aria-selected="false"><i class="fa fa-history"></i> @lang('modules.contracts.contractRenewalHistory')</a></li>
                                    </ul>
                                    <div class="tab-content" id="danger-tabContent">
                                        <div class="tab-pane fade show active" id="danger-home" role="tabpanel" aria-labelledby="summery">
                                            <div class="form-group">
                                                <textarea name="contract_detail" id="contract_detail">{{ $contract->contract_detail }}</textarea>
                                            </div>
                                            @if($contract->signature)
                                                <div class="text-right" id="signature-box">
                                                    <h6 class="box-title">Signature (Customer)</h6>
                                                    <img width="185" src="{{ $contract->signature->signature }}" class="img-width">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="tab-pane fade" id="danger-profile" role="tabpanel" aria-labelledby="renewval">
                                            <button type="button" class="btn btn-primary" onclick="renewContract();return false;"><i class="fa fa-refresh"></i> @lang('modules.contracts.renewContract')</button>
                                            
                                            <p>&nbsp;</p>
                                            <div class="timeline-small renewtimeLine">
                                                @foreach($contract->renew_history as $history)
                                                    <div class="media">
                                                        <div class="timeline-round m-r-30 timeline-line-1 bg-primary"></div>
                                                        <div class="media-body">
                                                        <h6>{{ $history->renewedBy->name }}: <span>@lang('modules.contracts.renewedThisContract'): ({{ $history->created_at->timezone($global->timezone)->format($global->date_format) }} {{ $history->created_at->timezone($global->timezone)->format($global->time_format) }})</span> <!-- <span class="pull-right f-14">New</span> --></h6>
                                                        <span class="sl-date">@lang('modules.contracts.newStartDate'): {{ $history->start_date->timezone($global->timezone)->format($global->date_format) }}</span><br>
                                                            <span class="sl-date">@lang('modules.contracts.newEndDate') : {{ $history->end_date->timezone($global->timezone)->format($global->date_format) }}</span><br>
                                                            <span class="sl-date">@lang('modules.contracts.newAmount') : {{ $history->amount }}</span><br>
                                                        </div>
                                                    </div>   
                                                @endforeach                                         
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
                                                    <option value="{{ $client->id }}" @if($client->id == $contract->client_id) selected @endif>{{ ucwords($client->company_name) }}</option>
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
                                                <input id="start_date" name="start_date" type="text" placeholder="-" class="form-control-lg form-control" value="{{ $contract->start_date->format($global->date_format) }}">
                                                <label for="start_date" class="control-label">@lang('modules.timeLogs.startDate')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">                                                
                                                <input id="end_date" name="end_date" type="text" placeholder="-" class="form-control-lg form-control" value="{{ $contract->end_date->format($global->date_format) }}">
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
                                            <div class="form-label-group form-group">                           
                                            <select class="form-control form-control-lg" name="status" id="status" placeholder="-">
                                            <option
                                                    @if($contract->status == 'Draft') selected @endif
                                            value="draft">@lang('modules.contracts.draft')
                                            </option>
                                            <option
                                                    @if($contract->status == 'Sent') selected @endif
                                            value="sent">@lang('modules.contracts.sent')
                                            </option>
                                            <option
                                                    @if($contract->status == 'Expired') selected @endif
                                            value="expired">@lang('modules.contracts.expired')
                                            </option>
                                            <option
                                                    @if($contract->status == 'Declined') selected @endif
                                            value="declined">@lang('modules.contracts.declined')
                                            </option>
                                            <option
                                                    @if($contract->status == 'Accepted') selected @endif
                                            value="accepted">@lang('modules.contracts.accepted')
                                            </option>
                                        </select>
                                        <label for="recurring_payment" class="control-label">@lang('app.status') </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <a href="javascript:;" id="createContractType" class="btn btn-sm btn-outline btn-primary">
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

                                        <div class="col-md-12">
                                            @if($contract->contract_file)
                                                <a href="{{$contract->contract_file}}" target="_blank" title="Download Contract"><i class="fa fa-2x fa-download"></i></a>
                                            @endif
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
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <div class="form-actions col-md-6 offset-md-7">
                            <div class="row">
                                <div class="col-md-5 pr-0">
                                    <a href="{{ route('admin.contracts.index') }}" class="btn btn-outline-primary gray form-control">@lang('app.cancel')</a>
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
        let url = location.href.replace(/\/$/, "");

        if (location.hash) {
            const hash = url.split("#");
            $('#myTab a[href="#'+hash[1]+'"]').tab("show");
            url = location.href.replace(/\/#/, "#");
            history.replaceState(null, null, url);
            setTimeout(() => {
                $(window).scrollTop(0);
            }, 400);
        }

        $('a[data-toggle="tab"]').on("click", function() {
            let newUrl;
            const hash = $(this).attr("href");
            if(hash == "#summery") {
                newUrl = url.split("#")[0];
            } else {
                newUrl = url.split("#")[0] + hash;
            }
            // newUrl += "/";
            history.replaceState(null, null, newUrl);
        });

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
            url: '{{route('admin.contracts.update', $contract->id)}}',
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

    function  renewContract() {
        var url = '{{ route('admin.contracts.renew', $contract->id)}}';
        $.ajaxModal('#taskCategoryModal',url);
    }

    function  removeHistory(id) {

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted renewal!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = '{{ route('admin.contracts.renew-remove', ':id') }}';
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token},
                    success: function (response) {
                        if(response.status == 'success') {
                            window.location.reload();
                        }
                    }
                });
            }
        });
    }
</script>
@endpush

