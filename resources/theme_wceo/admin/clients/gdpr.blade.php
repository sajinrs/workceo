@extends('layouts.app')

{{--@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ $pageTitle }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.clients.index') }}">{{ $pageTitle }}</a></li>
                <li class="active">@lang('app.menu.projects')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection--}}

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/mapsjs-ui.css')}}">

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
                            <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.menu.jobs')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row user-profile">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class=" ttl-info text-left ttl-border m-b-0">
                                    <h6>@lang('modules.client.companyName')</h6>
                                    <span>{{ ucwords($client->client_details->company_name) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class=" ttl-info text-left ttl-border">
                                    <h6>@lang('modules.client.clientName')</h6>
                                    <span>{{ ucwords($client->client_details->first_name) }} &nbsp;</span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class=" ttl-info text-left ttl-border">
                                    <h6>@lang('modules.client.lastName')</h6>
                                    <span>{{ $client->client_details->last_name ?? ""}} &nbsp;</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class=" ttl-info text-left ttl-border">
                                    <h6>@lang('modules.client.clientEmail')</h6>
                                    <span>{{ $clientDetail->email ?? '' }} &nbsp;</span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class=" ttl-info text-left ttl-border">
                                    <h6>@lang('app.mobile')</h6>
                                    <span>{{ $client->client_details->mobile ?? ''}}&nbsp;</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class=" ttl-info text-left ttl-border">
                                    <h6>@lang('modules.client.website')</h6>
                                    <span>{{ $client->client_details->website }}&nbsp;</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="ttl-info text-left ttl-border m-b-0 notes">
                                    <h6>@lang('app.note')</h6>
                                    <p>{{ $client->client_details->note }}&nbsp;</p>
                                </div>
                            </div>
                        </div>
                        {{--Custom fields data--}}
                        @if(isset($fields))
                            <div class="row">
                                @foreach($fields as $field)
                                    <div class=" col-sm-12 col-md-6 col-lg-6">
                                        <strong>{{ ucfirst($field->label) }}</strong> <br>
                                        <p class="text-muted">
                                            @if( $field->type == 'text')
                                                {{$clientDetail->custom_fields_data['field_'.$field->id] ?? '-'}}
                                            @elseif($field->type == 'password')
                                                {{$clientDetail->custom_fields_data['field_'.$field->id] ?? '-'}}
                                            @elseif($field->type == 'number')
                                                {{$clientDetail->custom_fields_data['field_'.$field->id] ?? '-'}}

                                            @elseif($field->type == 'textarea')
                                                {{$clientDetail->custom_fields_data['field_'.$field->id] ?? '-'}}

                                            @elseif($field->type == 'radio')
                                                {{ !is_null($clientDetail->custom_fields_data['field_'.$field->id]) ? $clientDetail->custom_fields_data['field_'.$field->id] : '-' }}
                                            @elseif($field->type == 'select')
                                                {{ (!is_null($clientDetail->custom_fields_data['field_'.$field->id]) && $clientDetail->custom_fields_data['field_'.$field->id] != '') ? $field->values[$clientDetail->custom_fields_data['field_'.$field->id]] : '-' }}
                                            @elseif($field->type == 'checkbox')
                                                {{ !is_null($clientDetail->custom_fields_data['field_'.$field->id]) ? $field->values[$clientDetail->custom_fields_data['field_'.$field->id]] : '-' }}
                                            @elseif($field->type == 'date')
                                                {{ isset($clientDetail->dob)?Carbon\Carbon::parse($clientDetail->dob)->format($global->date_format):Carbon\Carbon::now()->format($global->date_format)}}
                                            @endif
                                        </p>

                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{--custom fields data end--}}
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body" style="padding: 15px">
                        <div class="map-js-height" id="map12"></div>
                        <div class="row m-t-15">
                            <div class="col-12">
                                <div class=" ttl-info text-left ttl-border">
                                    <h6>@lang('app.address')</h6>
                                    <span>{{ $client->client_details->address }}&nbsp;</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class=" ttl-info text-left ttl-border m-b-10">
                                    <h6>@lang('app.shippingAddress')</h6>
                                    <span>{{ $client->client_details->shipping_address }}&nbsp;</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="card">

            <div class="row product-page-main">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs border-tab mb-0" id="top-tab" role="tablist">

                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.contacts.show', $client->client_details->user_id) }}"><span>@lang('app.menu.contacts')</span></a></li>
                        <li  class="nav-item"><a  class="nav-link" href="{{ route('admin.clients.properties', $client->client_details->user_id) }}"><span>@lang('app.menu.properties')</span></a></li>
                        <li  class="nav-item"><a  class="nav-link" href="{{ route('admin.clients.projects', $client->client_details->user_id) }}"><span>@lang('app.menu.jobs')</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.clients.invoices', $client->client_details->user_id) }}"><span>@lang('app.menu.invoices')</span></a></li>
                        <li  class="nav-item"><a  class="nav-link" href="{{ route('admin.clients.payments', $client->client_details->user_id) }}"><span>@lang('app.menu.payments')</span></a></li>
                        <li  class="nav-item"><a  class="nav-link" href="{{ route('admin.clients.estimates', $client->client_details->user_id) }}"><span>@lang('app.menu.estimates')</span></a></li>
                        <li  class="nav-item"><a  class="nav-link" href="{{ route('admin.clients.contracts', $client->client_details->user_id) }}"><span>@lang('app.menu.contracts')</span></a></li>
                        @if($gdpr->enable_gdpr)
                            <li class="nav-item"><a class="nav-link active" href="{{ route('admin.clients.gdpr', $client->id) }}"><span>@lang('modules.gdpr.gdpr')</span></a>
                        @endif
                    </ul>

                </div>
            </div>

            <div class="row product-page-main">
                <div class="col-sm-12">
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade active show" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                            <div class="@if($gdpr->consent_customer)col-md-8 @else col-md-12 @endif " id="follow-list-panel">

                                <div class="white-box">
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
                            @if($gdpr->consent_customer)
                                <div class="col-md-4">
                                    <div class="white-box">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="box-title">@lang('modules.gdpr.consent')</h4>
                                                <hr>
                                                <div class="panel-group" role="tablist" class="minimal-faq" aria-multiselectable="true">
                                                    @forelse($allConsents as $allConsent)
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading" role="tab" id="heading_{{ $allConsent->id }}">
                                                                <h4 class="panel-title">
                                                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{ $allConsent->id }}" aria-expanded="true" aria-controls="collapse_{{ $allConsent->id }}" class="font-bold">
                                                                        @if($allConsent->user && $allConsent->user->status == 'agree') <i class="fa fa-check text-success"></i> @else <i class="fa fa-remove fa-2x text-danger"></i> @endif {{ $allConsent->name }}
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapse_{{ $allConsent->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_{{ $allConsent->id }}">
                                                                <div class="panel-body">
                                                                    {!! Form::open(['id'=>'updateConsentLeadData_'.$allConsent->id,'class'=>'ajax-form','method'=>'POST']) !!}
                                                                    <input type="hidden" name="consent_id" value="{{ $allConsent->id }}">
                                                                    <input type="hidden" name="status" value="@if($allConsent->user && $allConsent->user->status == 'agree') disagree @else agree @endif">
                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <div class="form-group">
                                                                                <label class="control-label">@lang('modules.gdpr.additionalDescription')</label>
                                                                                <textarea name="additional_description"  rows="5" class="form-control"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    @if(($allConsent->user && $allConsent->user->status == 'disagree') || !$allConsent->user)
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
                                                                        <a href="javascript:;" onclick="saveConsentLeadData({{ $allConsent->id }})" class="btn @if($allConsent->user && $allConsent->user->status == 'agree') btn-danger @else btn-success @endif">
                                                                            @if($allConsent->user && $allConsent->user->status == 'agree')
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



@endsection

@push('footer-script')

<script src="{{ asset('themes/wceo/assets/js/map-js/mapsjs-core.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/map-js/mapsjs-service.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/map-js/mapsjs-ui.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/map-js/mapsjs-mapevents.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/map-js/custom.js')}}"></script>

<script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
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
    ajax: '{!! route('admin.clients.consent-purpose-data', $client->id) !!}',
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
        { data: 'created_at', name: 'purpose_consent_users.created_at' },
        { data: 'status', name: 'purpose_consent_users.status' },
        { data: 'ip', name: 'purpose_consent_users.ip' },
        { data: 'username', name: 'users.name' },
        { data: 'additional_description', name: 'purpose_consent_users.additional_description' }
    ]
});


function saveConsentLeadData(id) 
{
    var formId = '#updateConsentLeadData_'+id;

    $.easyAjax({
        url: '{{route('admin.clients.save-consent-purpose-data', $client->id)}}',
        container: formId,
        type: "POST",
        data: $(formId).serialize(),
        redirect: true
    })
}

</script>
@endpush