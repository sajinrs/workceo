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
                            <li class="breadcrumb-item active">@lang('app.edit')</li>
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

                    {!! Form::open(['id'=>'updateClient','class'=>'ajax-form','method'=>'PUT']) !!}
                    <div class="card-header">
                        <h4 class="card-title wceo-card-header  mb-0"><div class="panel-heading"> @lang('modules.client.updateTitle')
                                {{--[ {{ $userDetail->name }} ]--}}
                                @php($class = ($userDetail->status == 'active') ? 'badge-success' : 'badge-danger')
                                <span class="badge {{$class}}">{{ucfirst($userDetail->status)}}</span>
                            </div>
                        </h4>
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
                                        <input placeholder="@lang('modules.client.companyName')" type="text" id="company_name" name="company_name" class="form-control form-control-lg"  value="{{ $clientDetail->company_name ?? '' }}">
                                        <label for="company_name" class="control-label required">@lang('modules.client.companyName')</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="@lang('modules.client.clientEmail')" type="email" name="email" id="email" class="form-control form-control-lg" value="{{ $userDetail->email }}">
                                        <label class="col-form-label required" for="email">@lang('modules.client.clientEmail')</label>
                                        <span class="help-block">@lang('modules.client.emailNote')</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 ">
                                    <div class="form-label-group form-group">
                                        <input placeholder="@lang('modules.client.clientName')" type="text" name="name" id="name" class="form-control form-control-lg" value="{{ $clientDetail->first_name }}">
                                        <label class="col-form-label required" for="name">@lang('modules.client.clientName')</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="@lang('modules.client.lastName')" type="text" id="last_name" name="last_name" value="{{ $clientDetail->last_name ?? '' }}" class="form-control form-control-lg" >
                                        <label for="last_name" class="col-form-label required">@lang('modules.client.lastName')</label>
                                    </div>
                                </div>

                                <!--/span-->
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="@lang('modules.client.website')" type="text" id="website" name="website" class="form-control form-control-lg" value="{{ $clientDetail->website ?? '' }}" >
                                        <label for="website" class="control-label">@lang('modules.client.website')</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="@lang('modules.client.mobile')" type="tel" name="mobile" id="mobile" class="form-control form-control-lg" value="{{ $userDetail->mobile }}">
                                        <label for="mobile">@lang('modules.client.mobile')</label>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <select name="status" id="status" class="form-control form-control-lg hide-search">
                                            <option @if($userDetail->status == 'active') selected
                                                    @endif value="active">@lang('app.active')</option>
                                            <option @if($userDetail->status == 'deactive') selected
                                                    @endif value="deactive">@lang('app.deactive')</option>
                                        </select>
                                        <label for="status">@lang('app.status')</label>

                                    </div>
                                </div>
                            </div>

                            <hr class="mt-4 mb-4">

                            <h5 class="mb-4">@lang('modules.client.propertyDetails')</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="@lang('modules.client.streetAddress') " type="text" id="street" name="street" value="{{ $clientDetail->street ?? '' }}" class="form-control form-control-lg" >
                                        <label for="street" class="col-form-label required">@lang('modules.client.streetAddress')</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="@lang('modules.client.aptSuiteFloor')" type="tel" name="apt_floor" id="apt_floor" value="{{ $clientDetail->apt_floor ?? '' }}" class="form-control form-control-lg">
                                        <label for="apt_floor" class="col-form-label">@lang('modules.client.aptSuiteFloor')</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="@lang('modules.client.city')" type="text" id="city" name="city" value="{{ $clientDetail->city ?? '' }}" class="form-control form-control-lg" >
                                        <label for="city" class="col-form-label required">@lang('modules.client.city')</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="@lang('modules.client.state')" type="text" name="state" id="state" value="{{ $clientDetail->state ?? '' }}" class="form-control form-control-lg">
                                        <label for="state" class="col-form-label required">@lang('modules.client.state')</label>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="@lang('modules.client.zip')" type="text" name="zip" id="zip" value="{{ $clientDetail->zip ?? '' }}" class="form-control form-control-lg">
                                        <label for="zip" class="col-form-label required">@lang('modules.client.zip')</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        {{--<input placeholder="@lang('modules.client.country')" type="text" name="country" id="country" value="{{ $clientDetail->country ?? '' }}" class="form-control form-control-lg">--}}
                                        <select placeholder="-" class="select2 form-control form-control-lg" data-placeholder="Country" id="country_id" name="country_id">
                                            <option value="">--</option>
                                            @foreach($countries as $country)
                                                <option @if($clientDetail->country_id == $country->id) selected @endif value="{{ $country->id }}">{{ ucwords($country->name) }}</option>
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
                                        <input  id="is_same_address" type="checkbox" {{$clientDetail->shipping_address ? 'checked' : ''}} data-original-title="" title="" value="1">
                                        <label for="is_same_address"> Billing Address is Same as Property Address </label>
                                    </div>
                                </div>
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group">
                                        <textarea placeholder="@lang('app.shippingAddress')" name="shipping_address" id="shipping_address" class="form-control form-control-lg" rows="4">{{$clientDetail->shipping_address ?? ''}}</textarea>
                                        <label for="shipping_address">@lang('app.shippingAddress')</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group">
                                        <textarea placeholder="@lang('app.note')" name="note" id="note" class="form-control form-control-lg" rows="5">{{ $clientDetail->note ?? '' }}</textarea>
                                        <label for="note">@lang('app.note')</label>
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
                                                    <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control form-control-lg" placeholder="{{$field->label}}" value="{{$clientDetail->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                @elseif($field->type == 'password')
                                                    <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control form-control-lg" placeholder="{{$field->label}}" value="{{$clientDetail->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                @elseif($field->type == 'number')
                                                    <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control form-control-lg" placeholder="{{$field->label}}" value="{{$clientDetail->custom_fields_data['field_'.$field->id] ?? ''}}">

                                                @elseif($field->type == 'textarea')
                                                    <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control form-control-lg" id="{{$field->name}}" cols="3">{{$clientDetail->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>

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
                                                             isset($clientDetail)?$clientDetail->custom_fields_data['field_'.$field->id]:'',['class' => 'form-control form-control-lg gender'])
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
                                                    <input type="text" class="form-control form-control-lg date-picker" size="16" name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                           value="{{ isset($employeeDetail->custom_fields_data['field_'.$field->id])?Carbon\Carbon::createFromFormat('m/d/Y', $employeeDetail->custom_fields_data['field_'.$field->id])->format('m/d/Y'):Carbon\Carbon::now()->format('m/d/Y')}}">
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
                        <div class="form-actions row">
                            <div class=" col-md-3 offset-md-6">
                                <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-primary gray  form-control">@lang('app.cancel')</a>                               
                            </div>
                            <div class=" col-md-3 ">
                                <button type="submit" id="save-form" class="btn btn-primary  form-control"> @lang('app.save')</button>
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
<script>  
$('#save-form').click(function () {
    $.easyAjax({
        url: '{{route('admin.clients.update', [$clientDetail->id])}}',
        container: '#updateClient',
        type: "POST",
        redirect: true,
        data: $('#updateClient').serialize()
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
