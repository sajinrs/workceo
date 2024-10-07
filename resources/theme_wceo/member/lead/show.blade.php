@extends('layouts.member-app')


@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('member.leads.index') }}">@lang('app.menu.lead')</a></li>
                            <li class="breadcrumb-item active">@lang('modules.lead.profile')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="card">
            <div class="row product-page-main">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs border-tab mb-0" id="top-tab" role="tablist">
                        <li  class="nav-item"><a class="nav-link active" href="{{ route('member.leads.show', $lead->id) }}"><span>@lang('modules.lead.profile')</span></a>
                        </li>
                        @if($user->can('edit_lead'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('member.proposals.show', $lead->id) }}"><span>@lang('modules.lead.proposal')</span></a></li>
                        <li  class="nav-item"><a class="nav-link" href="{{ route('member.lead-files.show', $lead->id) }}"><span>@lang('modules.lead.file')</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('member.leads.followup', $lead->id) }}"><span>@lang('modules.lead.followUp')</span></a></li>
                        @endif

                    </ul>

                </div>
            </div>
        </div>

                <div class="col-sm-12 p-0 user-profile">
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade active show" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h5>@lang('modules.lead.leadInfo')</h5>

                                </div>
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class=" ttl-info text-left ttl-border">
                                                <h6>@lang('modules.lead.companyName')</h6>
                                                <span>{{ ucwords($lead->company_name) }}&nbsp</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class=" ttl-info text-left ttl-border">
                                                <h6>@lang('modules.lead.clientEmail')</h6>
                                                <span>{{ $lead->client_email ?? 'NA'}}&nbsp</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 col-md-6" >
                                            <div class=" ttl-info text-left ttl-border">
                                                <h6>@lang('modules.lead.firstName')</h6>
                                                <span>{{ $lead->client_first_name ?? 'NA'}}&nbsp</span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-6" >
                                            <div class=" ttl-info text-left ttl-border">
                                                <h6>@lang('modules.lead.lastName')</h6>
                                                <span>{{ $lead->client_last_name ?? 'NA'}}&nbsp</span>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class=" ttl-info text-left ttl-border">
                                                <h6>@lang('modules.lead.mobile')</h6>
                                                <span>{{ $lead->mobile ?? 'NA'}}&nbsp</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class=" ttl-info text-left ttl-border">
                                                <h6>@lang('modules.lead.website')</h6>
                                                <span>{{ $lead->website ?? 'NA'}}&nbsp</span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-6">
                                            <div class=" ttl-info text-left ttl-border">
                                                <h6>@lang('modules.lead.address')</h6>
                                                <span>{{ $lead->address ?? 'NA'}}&nbsp</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5>@lang('modules.lead.leadDetails')</h5>

                                </div>

                                <div class="card-body">
                                    <div class="row">


                                        <div class="col-md-6">
                                            @if($lead->agent_id != null)
                                                <div class="col-12 p-0">
                                                    <div class=" ttl-info text-left ttl-border">
                                                        <h6>@lang('app.leadAgent')</h6>
                                                        <span>--&nbsp</span>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($lead->source_id != null)
                                                <div class="col-12 p-0">
                                                    <div class=" ttl-info text-left ttl-border">
                                                        <h6>@lang('modules.lead.source')</h6>
                                                        <span>{{ $lead->lead_source->type ?? 'NA'}}&nbsp</span>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($lead->status_id != null)
                                                <div class="col-12 p-0">
                                                    <div class=" ttl-info text-left ttl-border">
                                                        <h6>@lang('modules.lead.status')</h6>
                                                        <span>{{ $lead->lead_status->type ?? 'NA'}}&nbsp</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <div class=" ttl-info text-left ttl-border">
                                                <h6>@lang('app.note')</h6>
                                                <p>{{ $lead->note ?? 'NA'}}&nbsp</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


        </div>
   
    

@endsection

