@extends('layouts.app')
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
                            <li class="breadcrumb-item active">@lang('app.menu.contacts')</li>
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
                        <div class="google_map">
                            <img src="https://maps.googleapis.com/maps/api/staticmap?size=530x350&scale=1&maptype=roadmap
&markers=color:0x2750fe%7C{{ $client->client_details->address }}&markers=color:0x2750fe%7C{{ $client->client_details->shipping_address }}&key=AIzaSyCsLN7tz9Ww5Lt2hDS4KqaBrb8clNSwdkQ" alt="Points of Interest in Lower Manhattan" border="0" width="100%">
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
                        <li  class="nav-item"><a  class="nav-link active" href="{{ route('admin.clients.properties', $client->client_details->user_id) }}"><span>@lang('app.menu.properties')</span></a></li>
                        <li  class="nav-item"><a  class="nav-link" href="{{ route('admin.clients.projects', $client->client_details->user_id) }}"><span>@lang('app.menu.jobs')</span></a></li>
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

                                <div class="row">
                                    <div class="col-md-12">
                                        {{--class="tabbed-card"--}}
                                        <div>
                                            <ul class="nav nav-pills nav-primary" id="pills-clrtab1" role="tablist">
                                                <li class="nav-item">
                                                    <button type="button" id="show-add-form" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('modules.client.addProperty')</button>

                                            </ul>
                                        </div>

                                        {!! Form::open(['id'=>'addProperty','class'=>'ajax-form d-none','method'=>'POST']) !!}

                                        {!! Form::hidden('user_id', $client->id) !!}

                                        <div class="form-body">
                                            <div class="row m-t-30">
                                                <div class="col-md-4 ">
                                                    <div class="form-label-group form-group">
                                                        <input placeholder="-" type="text" id="street" name="street" value="{{ $leadDetail->street ?? '' }}" class="form-control form-control-lg" >
                                                        <label for="street" class="col-form-label required">@lang('modules.client.streetAddress')</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 ">
                                                    <div class="form-label-group form-group">
                                                        <input placeholder="@lang('modules.client.aptSuiteFloor')" type="tel" name="apt_floor" id="apt_floor" value="{{ $leadDetail->apt_floor ?? '' }}" class="form-control form-control-lg">
                                                        <label for="apt_floor" class="col-form-label">@lang('modules.client.aptSuiteFloor')</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 ">
                                                    <div class="form-label-group form-group">
                                                        <input placeholder="@lang('modules.client.city')" type="text" id="city" name="city" value="{{ $leadDetail->city ?? '' }}" class="form-control form-control-lg" >
                                                        <label for="city" class="col-form-label required">@lang('modules.client.city')</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 ">
                                                    <div class="form-label-group form-group">
                                                        <input placeholder="@lang('modules.client.state')" type="text" name="state" id="state" value="{{ $leadDetail->state ?? '' }}" class="form-control form-control-lg">
                                                        <label for="state" class="col-form-label required">@lang('modules.client.state')</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 ">
                                                    <div class="form-label-group form-group">
                                                        <input placeholder="@lang('modules.client.zip')" type="text" name="zip" id="zip" value="{{ $leadDetail->zip ?? '' }}" class="form-control form-control-lg">
                                                        <label for="zip" class="col-form-label required">@lang('modules.client.zip')</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 ">
                                                    <div class="form-label-group form-group">
                                                        <select placeholder="-" class="select2 form-control form-control-lg" data-placeholder="Country" id="country_id" name="country_id">
                                                            <option value="">--</option>
                                                            @foreach($countries as $country)
                                                                <option value="{{ $country->id }}">{{ ucwords($country->name) }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="country_id" class="col-form-label">@lang('modules.client.country')</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-actions">
                                                        <button type="button" id="save-form" class="btn btn-primary form-control btn-lg pull-right">@lang('app.save')</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <hr>
                                        {!! Form::close() !!}

                                    </div>
                                </div>

                                <div class="dt-ext table-responsive m-t-30">
                                    <table class="display" id="properties-table">

                                    <thead>
                                        <tr>
                                            <th>@lang('app.id')</th>
                                            <th>@lang('modules.client.streetAddress')</th>
                                            <th>@lang('modules.client.aptSuiteFloor')</th>
                                            <th>@lang('modules.client.city')</th>
                                            <th>@lang('modules.client.state')</th>
                                            <th>@lang('modules.client.zip')</th>
                                            <th>@lang('modules.client.country')</th>
                                            <th>@lang('app.action')</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="editPropertyModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                     <h5 class="modal-title" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-outline-primary gray" data-dismiss="modal">Close</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

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


<script>
    var table = $('#properties-table').dataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: '{!! route('admin.properties.data', $client->id) !!}',
        deferRender: true,
        language: {
            "url": "<?php echo __("app.datatable") ?>"
        },
        "fnDrawCallback": function( oSettings ) {
            $("body").tooltip({
                selector: '[data-toggle="tooltip"]'
            });
        },
        columns: [
            { data: 'property_id', name: 'property_id' },
            { data: 'street', name: 'street' },
            { data: 'apt_floor', name: 'apt_floor' },
            { data: 'city', name: 'city' },
            { data: 'state', name: 'state' },
            { data: 'zip', name: 'zip' },
            { data: 'country', name: 'country' },
            { data: 'action', name: 'action' }
        ]
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.properties.store')}}',
            container: '#addProperty',
            type: "POST",
            data: $('#addProperty').serialize(),
            success: function (data) {
                if(data.status == 'success'){
                    $('#addProperty')[0].reset();
                    $('#addProperty').toggleClass('hide', 'show');
                    table._fnDraw();
                }
            }
        })
    });

    $('#show-add-form').click(function () {
        $('#addProperty').toggleClass('d-none', 'd-block');
    });


    $('body').on('click', '.sa-params', function(){
        var id = $(this).data('property-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted property!",
            icon: "warning",
            buttons: ["No, cancel please!", "Yes, delete it!"],
            dangerMode: true
        }).then((willDelete) => {
                if (willDelete) {

                var url = "{{ route('admin.properties.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                            table._fnDraw();
                        }
                    }
                });
            }
        });
    });

    $('body').on('click', '.edit-property', function () {
        var id = $(this).data('property-id');

        var url = '{{ route('admin.properties.edit', ':id')}}';
        url = url.replace(':id', id);

        $('#modelHeading').html('Update Property');
        $.ajaxModal('#editPropertyModal',url);

    });


</script>
@endpush