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
                            <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">{{ __($pageTitle) }}</a></li>
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

                    {!! Form::open(['id'=>'createClient','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.client.createTitle')</h4>
                    </div>

                    @if(isset($leadDetail->id))
                        <input type="hidden" name="lead" value="{{ $leadDetail->id }}">
                    @endif
                    <div class="card-body">
                        <div class="form-body">
                            <h5 class="mb-4">@lang('modules.client.clientInfo')</h5>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <input placeholder="@lang('modules.client.companyName')" type="text" id="company_name" name="company_name" value="{{ $leadDetail->company_name ?? '' }}" class="form-control form-control-lg" >
                                    <label for="company_name" class="col-form-label required">@lang('modules.client.companyName')</label>
                                </div>
                            </div>


                            <div class="col-md-6">

                                <div class="form-label-group  form-group">
                                    <input placeholder="@lang('modules.client.clientEmail')" type="email" name="email" id="email" value="{{ $leadDetail->client_email ?? '' }}"  class="form-control form-control-lg">
                                    <label for="email" class="col-form-label required">@lang('modules.client.clientEmail')</label>
                                    <span class="help-block">@lang('modules.client.emailNote')</span>
                                </div>
                            </div>
                            <!--/span-->
                        </div>

                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-label-group form-group">
                                    <input placeholder="@lang('modules.client.clientName')" type="text" name="name" id="name"  value="{{ $leadDetail->client_first_name ?? '' }}"   class="form-control form-control-lg">
                                    <label for="name" class="col-form-label required">@lang('modules.client.clientName')</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <input placeholder="@lang('modules.client.lastName')" type="text" id="last_name" name="last_name" value="{{ $leadDetail->client_last_name ?? '' }}" class="form-control form-control-lg" >
                                    <label for="last_name" class="col-form-label required">@lang('modules.client.lastName')</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <input placeholder="@lang('modules.client.website')" type="text" id="website" name="website" value="{{ $leadDetail->website ?? '' }}" class="form-control form-control-lg" >
                                    <label for="website" class="col-form-label">@lang('modules.client.website')</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <input placeholder="@lang('modules.client.mobile')" type="tel" name="mobile" id="mobile" value="{{ $leadDetail->mobile ?? '' }}" class="form-control form-control-lg">
                                    <label for="mobile" class="col-form-label">@lang('modules.client.mobile')</label>
                                </div>
                            </div>
                        </div>


                        <hr class="mt-4 mb-4">

                        <h5 class="mb-4">@lang('modules.client.propertyDetails')</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <input placeholder="-" type="text" id="street" name="street" value="{{ $leadDetail->street ?? '' }}" class="form-control form-control-lg" >
                                    <label for="street" class="col-form-label required">@lang('modules.client.streetAddress')</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <input placeholder="@lang('modules.client.aptSuiteFloor')" type="tel" name="apt_floor" id="apt_floor" value="{{ $leadDetail->apt_floor ?? '' }}" class="form-control form-control-lg">
                                    <label for="apt_floor" class="col-form-label">@lang('modules.client.aptSuiteFloor')</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <input placeholder="@lang('modules.client.city')" type="text" id="city" name="city" value="{{ $leadDetail->city ?? '' }}" class="form-control form-control-lg" >
                                    <label for="city" class="col-form-label required">@lang('modules.client.city')</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <input placeholder="@lang('modules.client.state')" type="text" name="state" id="state" value="{{ $leadDetail->state ?? '' }}" class="form-control form-control-lg">
                                    <label for="state" class="col-form-label required">@lang('modules.client.state')</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <input placeholder="@lang('modules.client.zip')" type="text" name="zip" id="zip" value="{{ $leadDetail->zip ?? '' }}" class="form-control form-control-lg">
                                    <label for="zip" class="col-form-label required">@lang('modules.client.zip')</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    {{--<input placeholder="@lang('modules.client.country')" type="text" name="country_id" id="country_id" value="{{ $leadDetail->country_id ?? '' }}" class="form-control form-control-lg">--}}

                                    <select placeholder="-" class="select2 form-control form-control-lg" data-placeholder="Country" id="country_id" name="country_id">
                                        <option value="">--</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ ucwords($country->name) }}</option>
                                        @endforeach
                                    </select>
                                    <label for="country_id" class="col-form-label required">@lang('modules.client.country')</label>
                                </div>
                            </div>
                        </div>


                        <!--/row-->
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="checkbox checkbox-primary">
                                    <input id="is_same_address" type="checkbox" data-original-title="" title="">
                                    <label for="is_same_address"> Billing Address is Same as Property Address </label>
                                </div>
                            </div>
                        </div> <!--/row-->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-label-group form-group">
                                    <textarea placeholder="@lang('app.shippingAddress')" name="shipping_address" id="shipping_address" class="form-control" rows="1"></textarea>
                                    <label for="shipping_address" class="col-form-label">@lang('app.shippingAddress')</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-label-group form-group">
                                    <textarea placeholder="@lang('app.note')" name="note" id="note" class="form-control" rows="5"></textarea>
                                    <label for="note" class="col-form-label">@lang('app.note')</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div style="margin-bottom: 10px;">
                                        <label class="control-label">@lang('modules.client.sendCredentials')</label>
                                        <span class="help-block">( @lang('modules.client.sendCredentialsMessage'))</span>
                                    </div>
                                    <div class="radio radio-inline col-md-4">
                                        <input type="radio" name="sendMail" id="sendMail1"
                                                value="yes">
                                        <label for="sendMail1" class="">
                                            @lang('app.yes') </label>
                                    </div>
                                    <div class="radio radio-inline col-md-4">
                                        <input type="radio" name="sendMail"
                                                id="sendMail2" checked value="no">
                                        <label for="sendMail2" class="">
                                            @lang('app.no') </label>
                                    </div>
                                </div>
                            </div>
                        </div>              

                        <div class="row">
                            @if(isset($fields))
                                @foreach($fields as $field)
                                    <div class="col-md-6">
                                        <label>{{ ucfirst($field->label) }}</label>
                                        <div class="form-group">
                                            @if( $field->type == 'text')
                                                <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                            @elseif($field->type == 'password')
                                                <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                            @elseif($field->type == 'number')
                                                <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                            @elseif($field->type == 'textarea')
                                                <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" id="{{$field->name}}" cols="3">{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>
                                            @elseif($field->type == 'radio')
                                                <div class="radio-list">
                                                    @foreach($field->values as $key=>$value)
                                                        <label class="radio-inline @if($key == 0) p-0 @endif">
                                                            <div class="radio radio-info">
                                                                <input type="radio" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" id="optionsRadios{{$key.$field->id}}" value="{{$value}}" @if(isset($clientDetail) && $clientDetail->custom_fields_data['field_'.$field->id] == $value) checked @elseif($key==0) checked @endif>>
                                                                <label for="optionsRadios{{$key.$field->id}}">{{$value}}</label>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @elseif($field->type == 'select')
                                                {!! Form::select('custom_fields_data['.$field->name.'_'.$field->id.']',
                                                        $field->values,
                                                         isset($editUser)?$editUser->custom_fields_data['field_'.$field->id]:'',['class' => 'form-control gender'])
                                                 !!}

                                            @elseif($field->type == 'checkbox')
                                                <div class="mt-checkbox-inline">
                                                    @foreach($field->values as $key => $value)
                                                        <label class="mt-checkbox mt-checkbox-outline">
                                                            <input name="custom_fields_data[{{$field->name.'_'.$field->id}}][]" type="checkbox" value="{{$key}}"> {{$value}}
                                                            <span></span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @elseif($field->type == 'date')
                                                <input type="text" class="form-control form-control-inline date-picker" size="16" name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                       value="{{ isset($editUser->dob)?Carbon\Carbon::parse($editUser->dob)->format('Y-m-d'):Carbon\Carbon::now()->format($global->date_format)}}">
                                            @endif
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block"></span>

                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>


                    </div>
                </div>

                <div class="card-footer text-right">
                    <div class="form-actions col-md-3  offset-md-9 ">
                        <button type="submit" id="save-form" class="btn btn-primary form-control">@lang('app.save')</button>
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
$('#save-form').click(function () {
    $.easyAjax({
        url: '{{route('admin.clients.store')}}',
        container: '#createClient',
        type: "POST",
        redirect: true,
        data: $('#createClient').serialize()
    })
});

$(document).ready(function () {
    $('#is_same_address').change(function () {
        if ($(this).is(":checked")) {
            var country = $("#country_id option:selected");
            var address = [$('#street').val(),
                $('#apt_floor').val(),
                $('#city').val(),
                $('#state').val(),
                $('#zip').val(),
                country.text()
            ];
            var shipping_address = address.join(', ');
            $('#shipping_address').val(shipping_address);
        } else {
            $('#shipping_address').val('');
        }
    });

})

</script>
@endpush

