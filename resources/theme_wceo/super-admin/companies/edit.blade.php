@extends('layouts.super-admin')
@push('head-script')
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/select2.css')}}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                        href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item "><a
                                        href="{{ route('super-admin.companies.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.edit')</li>
                        </ol>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="edit-profile">
            <div class="row">
                <div class="col-lg-12">

                    {!! Form::open(['id'=>'updateCompany','class'=>'card ajax-form','method'=>'PUT', 'enctype' => 'multipart/form-data']) !!}
                    <div class="card-header">
                        <h4 class="wceo-card-header card-title mb-0"> @lang('app.update') @lang('app.company')
                            [ {{$company->company_name}} ]
                            @php($class = ($company->status == 'active') ? 'badge-success' : 'badge-danger')
                            <label class="badge {{$class}} badge-pill digits">{{ucfirst($company->status)}}</label>
                        </h4>
                        <div class="card-options"><a class="card-options-collapse" href="#" data-toggle="card-collapse"><i
                                        class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#"
                                                                            data-toggle="card-remove"><i
                                        class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <h5 class="mb-4">Company Details</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_name"
                                               class="required">@lang('modules.accountSettings.companyName')</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name"
                                               value="{{ $company->company_name ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_email"
                                               class="required">@lang('modules.accountSettings.companyEmail')</label>
                                        <input type="email" class="form-control" id="company_email" name="company_email"
                                               value="{{ $company->company_email ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone"
                                               class="required">@lang('modules.accountSettings.companyPhone')</label>
                                        <input type="tel" class="form-control" id="company_phone" name="company_phone"
                                               value="{{ $company->company_phone ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">@lang('modules.accountSettings.companyWebsite')</label>
                                        <input type="text" class="form-control" id="website" name="website"
                                               value="{{ $company->website ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">@lang('modules.accountSettings.companyLogo')</label>

                                        <div class="">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail"
                                                     style="width: 250px; height: 80px;">
                                                    <img src="{{ $company->logo_url }}" alt=""/>
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                     style="max-width: 250px; max-height: 80px;"></div>
                                                <div>
                                    <span class="btn btn-success btn-sm btn-file">
                                        <span class="fileinput-new"> @lang('app.selectImage') </span>
                                        <span class="fileinput-exists"> @lang('app.change') </span>
                                        <input type="file" name="logo" id="logo"> </span>
                                                    <a href="javascript:"
                                                       class="btn btn-danger btn-sm fileinput-exists"
                                                       data-dismiss="fileinput"> @lang('app.remove') </a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address"
                                               class="required">@lang('modules.accountSettings.companyAddress')</label>
                                        <textarea class="form-control" id="address" rows="5"
                                                  name="address">{{ $company->address }}</textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">Company size</label>
                                        <select name="company_size" id="company_size" class="form-control">
                                            <option @if($company->company_size == 'Just Me') selected @endif value="Just Me">Just Me</option>
                                            <option @if($company->company_size == '2-3 People') selected @endif value="2-3 People">2-3 People</option>
                                            <option @if($company->company_size == '4-10 People') selected @endif value="4-10 People">4-10 People</option>
                                            <option @if($company->company_size == '10+ People') selected @endif value="10+ People">10+ People</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">Industry</label>
                                        <select name="industry" id="industry" class="form-control select2">
                                                <option value="">--</option>
                                            @foreach($industries as $industry)
                                                <option @if($company->industry == $industry->id) selected @endif value="{{$industry->id}}">{{$industry->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">Source</label>
                                        <select name="source" id="source" class="form-control select2">
                                            <option value="">--</option>
                                            @foreach($findus as $find)
                                                <option @if($company->source == $find->id) selected @endif value="{{$find->id}}">{{$find->source}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">Features Like</label>
                                        <?php $interest_id = explode(',',$company->interest); ?>
                                        <select name="interest" id="interest" class="form-control select2" multiple>
                                                <option value="">-</option>
                                            @foreach($interests as $key=> $interest)
                                                <option @if (in_array($interest->id, $interest_id ) ) selected @endif value="{{$interest->id}}">{{$interest->name}}</option>
                                            @endforeach
                                            
                                        </select>
                                    </div>
                                </div>
                                
                                
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">@lang('modules.accountSettings.defaultCurrency')</label>
                                        <select name="currency_id" id="currency_id" class="form-control">
                                            @foreach($currencies as $currency)
                                                <option
                                                        @if($currency->id == $company->currency_id) selected @endif
                                                value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">@lang('modules.accountSettings.defaultTimezone')</label>
                                        <select name="timezone" id="timezone" class="form-control select2">
                                            @foreach($timezones as $tz)
                                                <option @if($company->timezone == $tz) selected @endif>{{ $tz }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">@lang('modules.accountSettings.changeLanguage')</label>
                                        <select name="locale" id="locale" class="form-control select2">
                                            <option @if($company->locale == "en") selected @endif value="en">English
                                            </option>
                                            @foreach($languageSettings as $language)
                                                <option value="{{ $language->language_code }}"
                                                        @if($company->locale == $language->language_code) selected @endif >{{ $language->language_name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('app.status')</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">-</option>
                                            <option value="active"
                                                    @if($company->status == 'active') selected @endif>@lang('app.active')</option>
                                            <option value="inactive"
                                                    @if($company->status == 'inactive') selected @endif>@lang('app.inactive')</option>
                                            <option value="license_expired"
                                                    @if($company->status == 'license_expired') selected @endif>@lang('modules.dashboard.licenseExpired')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if(($companyUser))
                                <hr class="mt-4 mb-4">

                                <h5 class="mb-4">@lang('modules.company.accountSetup')</h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">@lang('modules.employees.employeeEmail')</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                   value="{{$companyUser->email}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">@lang('modules.employees.employeePassword')</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                   value="">
                                            <span class="help-block"> @lang('modules.employees.updateAdminPasswordNote')</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-primary"><i
                                        class="fa fa-check"></i> @lang('app.update')</button>
                            <a href="{{ route('super-admin.companies.index') }}"
                               class="btn btn-secondary">@lang('app.back')</a>
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid ends-->
@endsection

@push('footer-script')

    <script src="{{ asset('themes/wceo/assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/select2/select2-custom.js')}}"></script>
    <script>
        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('super-admin.companies.update', [$company->id])}}',
                container: '#updateCompany',
                type: "POST",
                redirect: true,
                file: true
            })
        });

        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

    </script>

@endpush
