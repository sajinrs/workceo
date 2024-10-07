@extends('layouts.app')
@push('head-script')
    <!-- Plugins css start-->
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
                    <!-- Bookmark Start-->
                        <div class="col">
                            <div class="bookmark pull-right">
                                <a href="{{ route('admin.clients.edit', $client->client_details->id) }}" class="btn btn-primary btn-sm"> <i class="fa fa-edit"></i> @lang('modules.client.editClient')</a>
                            </div>
                        </div>
                    <!-- Bookmark Ends-->

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
                            <div class="row m-t-20">
                                @foreach($fields as $field)
                                    <div class="col-sm-12 col-md-6">
                                        <div class=" ttl-info text-left ttl-border">
                                        <h6>{{ ucfirst($field->label) }}</h6>
                                        <span>
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
                                        </span>

                                    </div>
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
                        <div class="google_map">
                                <img src="https://maps.googleapis.com/maps/api/staticmap?size=530x350&scale=1&maptype=roadmap
&markers=icon:https://mysmartassistants.com/works/workceo/public/img/blue_mark.png%7Clabel:%7C{{ $client->client_details->address }}&markers=color:0x2750fe%7C{{ $client->client_details->shipping_address }}&key=AIzaSyCsLN7tz9Ww5Lt2hDS4KqaBrb8clNSwdkQ" alt="Points of Interest in Lower Manhattan" border="0" width="100%">
                            <!--<img src="https://maps.googleapis.com/maps/api/staticmap?size=530x350&scale=1&maptype=roadmap
&markers=color:0x2750fe%7C{{ $client->client_details->address }}&markers=color:0x2750fe%7C{{ $client->client_details->shipping_address }}&key=AIzaSyCsLN7tz9Ww5Lt2hDS4KqaBrb8clNSwdkQ" alt="Points of Interest in Lower Manhattan" border="0" width="100%">-->
                        </div>
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
                    <ul class="showProjectTabs nav nav-tabs border-tab mb-0" id="top-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.contacts.show', $client->client_details->user_id) }}"><span>@lang('app.menu.contacts')</span></a></li>
                        <li  class="nav-item"><a  class="nav-link" href="{{ route('admin.clients.properties', $client->client_details->user_id) }}"><span>@lang('app.menu.properties')</span></a></li>
                        <li  class="nav-item"><a  class="nav-link active" href="{{ route('admin.clients.projects', $client->client_details->user_id) }}"><span>@lang('app.menu.jobs')</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.clients.invoices', $client->client_details->user_id) }}"><span>@lang('app.menu.invoices')</span></a></li>
                        <li  class="nav-item"><a  class="nav-link" href="{{ route('admin.clients.payments', $client->client_details->user_id) }}"><span>@lang('app.menu.payments')</span></a></li>
                        <li  class="nav-item"><a  class="nav-link" href="{{ route('admin.clients.estimates', $client->client_details->user_id) }}"><span>@lang('app.menu.estimates')</span></a></li>
                        <li  class="nav-item"><a  class="nav-link" href="{{ route('admin.clients.contracts', $client->client_details->user_id) }}"><span>@lang('app.menu.contracts')</span></a></li>
                        @if($gdpr->enable_gdpr)
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.clients.gdpr', $client->id) }}"><span>@lang('modules.gdpr.gdpr')</span></a></li>
                        @endif
                    </ul>
                </div>

                <div class="col-sm-12">
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade active show" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('modules.client.projectName')</th>
                                            <th>@lang('modules.client.startedOn')</th>
                                            <th>@lang('modules.client.deadline')</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody id="timer-list">
                                        @forelse($client->projects as $key=>$project)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ ucwords($project->project_name) }}</td>
                                                <td>{{ $project->start_date->format($global->date_format) }}</td>
                                                <td>@if($project->deadline){{ $project->deadline->format($global->date_format) }}@else - @endif</td>
                                                <td><a href="{{ route('admin.projects.show', $project->id) }}" class="badge badge-info">@lang('modules.client.viewDetails')</a></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">@lang('messages.noProjectFound')</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- .row -->

@endsection

@push('footer-script')

<script src="{{ asset('themes/wceo/assets/js/map-js/mapsjs-core.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/map-js/mapsjs-service.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/map-js/mapsjs-ui.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/map-js/mapsjs-mapevents.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/map-js/custom.js')}}"></script>

@endpush