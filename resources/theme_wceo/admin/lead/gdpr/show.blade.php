@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">

@endpush

@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                    <h4 class="m-b-0" style="color: #1d61d2;"><i class="{{ $pageIcon }}"></i> <span class="upper"> {{ __($pageTitle) }} </span> - <span class="font-bold">{{  ucwords($lead->company_name) }}</span></h4>
                        <!--<h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} #{{ $lead->id }} - <span class="font-bold">{{ ucwords($lead->company_name) }}</span></h3>-->
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.leads.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">GDPR</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid">
        <div class="card">
            <div class="row product-page-main">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs border-tab mb-0" id="top-tab" role="tablist">
                        <li  class="nav-item"><a class="nav-link" href="{{ route('admin.leads.show', $lead->id) }}"><span>@lang('modules.lead.profile')</span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.proposals.show', $lead->id) }}"><span>@lang('modules.lead.proposal')</span></a></li>
                        <li  class="nav-item"><a class="nav-link" href="{{ route('admin.lead-files.show', $lead->id) }}"><span>@lang('modules.lead.file')</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.leads.followup', $lead->id) }}"><span>@lang('modules.lead.followUp')</span></a></li>
                        @if($gdpr->enable_gdpr)
                            <li class="nav-item"><a class="nav-link active" href="{{ route('admin.leads.gdpr', $lead->id) }}"><span>GDPR</span></a></li>
                        @endif
                    </ul>
                </div>
                <div class="col-sm-12" id="follow-list-panel">
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade active show" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                            <div class="card-header">
                                <h5>GDPR</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                <div class="@if($gdpr->consent_leads)col-md-8 @else col-md-12 @endif " id="follow-list-panel">
                                    <div class="white-box">

                                        @if($gdpr->public_lead_edit)
                                            <div class="row  m-b-10">
                                                <div class="col-md-12">
                                                    <a href="{{ route('front.gdpr.lead', md5($lead->id)) }}" target="_blank"
                                                       class="btn btn-success btn-outline"><i class="fa fa-eye"></i> View public Form</a>
                                                </div>
                                            </div>
                                        @endif
                                        <hr>

                                        <div class="row m-b-10">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="consent-table">
                                                    <thead>
                                                    <tr>
                                                        <th>@lang('modules.gdpr.purpose')</th>
                                                        <th>@lang('app.date')</th>
                                                        <th>@lang('app.action')</th>
                                                        <th>@lang('modules.gdpr.ipAddress')</th>
                                                        <th>@lang('modules.gdpr.staffMember')</th>
                                                        <th>@lang('modules.gdpr.additionalDescription')</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($gdpr->consent_leads)
                                    <div class="col-md-4">
                                        <div class="white-box">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4 class="box-title">@lang('modules.gdpr.consent')</h4>
                                                    <small><a href="{{ route('front.gdpr.consent', md5($lead->id)) }}">@lang('app.view') @lang('modules.gdpr.consent')</a></small>
                                                    <hr>
                                                    <div class="panel-group" role="tablist" class="minimal-faq" aria-multiselectable="true">
                                                        @forelse($allConsents as $allConsent)
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading" role="tab" id="heading_{{ $allConsent->id }}">
                                                                    <h4 class="panel-title">
                                                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{ $allConsent->id }}" aria-expanded="true" aria-controls="collapse_{{ $allConsent->id }}" class="font-bold">
                                                                            @if($allConsent->lead && $allConsent->lead->status == 'agree') <i class="fa fa-check text-success"></i> @else <i class="fa fa-remove fa-2x text-danger"></i> @endif {{ $allConsent->name }}
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse_{{ $allConsent->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_{{ $allConsent->id }}">
                                                                    <div class="panel-body">
                                                                        {!! Form::open(['id'=>'updateConsentLeadData_'.$allConsent->id,'class'=>'ajax-form','method'=>'POST']) !!}
                                                                        <input type="hidden" name="consent_id" value="{{ $allConsent->id }}">
                                                                        <input type="hidden" name="status" value="@if($allConsent->lead && $allConsent->lead->status == 'agree') disagree @else agree @endif">
                                                                        <div class="row">
                                                                            <div class="col-xs-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">@lang('modules.gdpr.additionalDescription')</label>
                                                                                    <textarea name="additional_description"  rows="5" class="form-control"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        @if(($allConsent->lead && $allConsent->lead->status == 'disagree') || !$allConsent->lead)
                                                                            <div class="row">
                                                                                <div class="col-xs-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label">@lang('modules.gdpr.purposeDescription')</label>
                                                                                        <textarea name="consent_description" rows="5" class="form-control">{{ $allConsent->description }}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif

                                                                        <div class="form-actions">
                                                                            <a href="javascript:;" onclick="saveConsentLeadData({{ $allConsent->id }})" class="btn @if($allConsent->lead && $allConsent->lead->status == 'agree') btn-danger @else btn-success @endif">
                                                                                @if($allConsent->lead && $allConsent->lead->status == 'agree')
                                                                                    @lang('modules.gdpr.optOut')
                                                                                @else
                                                                                    @lang('modules.gdpr.optIn')
                                                                                @endif
                                                                            </a>
                                                                        </div>
                                                                        {!! Form::close() !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <p class="text-center">No Consent available.</p>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>


@endsection

@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/custom.js')}}"></script>
<script type="text/javascript">

    table = $('#consent-table').dataTable({
        responsive: true,
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: '{!! route('admin.leads.consent-purpose-data', $lead->id) !!}',
        language: {
            "url": "<?php echo __("app.datatable") ?>"
        },
        "fnDrawCallback": function( oSettings ) {
            $("body").tooltip({
                selector: '[data-toggle="tooltip"]'
            });
        },
        columns: [
            { data: 'name', name: 'purpose_consent.name' },
            { data: 'created_at', name: 'purpose_consent_leads.created_at' },
            { data: 'status', name: 'purpose_consent_leads.status' },
            { data: 'ip', name: 'purpose_consent_leads.ip' },
            { data: 'username', name: 'users.name' },
            { data: 'additional_description', name: 'purpose_consent_leads.additional_description' }
        ]
    });


    function saveConsentLeadData(id) {
        var formId = '#updateConsentLeadData_'+id;

        $.easyAjax({
            url: '{{route('admin.leads.save-consent-purpose-data', $lead->id)}}',
            container: formId,
            type: "POST",
            data: $(formId).serialize(),
            redirect: true
        })
    }

</script>
@endpush