@extends('layouts.super-admin')

@section('page-title')

@endsection

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
                            <li class="breadcrumb-item active">@lang('app.addNew')</li>
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
                    {!! Form::open(['id'=>'createCompany','class'=>'card ajax-form','method'=>'POST', 'enctype' => 'multipart/form-data']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('app.add') @lang('app.company')</h4>
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
                                               value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_email"
                                               class="required">@lang('modules.accountSettings.companyEmail')</label>
                                        <input type="email" class="form-control" id="company_email" name="company_email"
                                               value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone"
                                               class="required">@lang('modules.accountSettings.companyPhone')</label>
                                        <input type="tel" class="form-control" id="company_phone" name="company_phone"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">@lang('modules.accountSettings.companyWebsite')</label>
                                        <input type="text" class="form-control" id="website" name="website"
                                               value="">
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

                                                    <img src="{{ $global->logo_url }}" alt=""/>
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                     style="width: 250px; height: 80px;"></div>
                                                <div>
                                    <span class="btn btn-sm btn-success btn-file">
                                        <span class="fileinput-new"> @lang('app.selectImage') </span>
                                        <span class="fileinput-exists"> @lang('app.change') </span>
                                        <input type="file" name="logo" id="logo"> </span>
                                                    <a href="javascript:;"
                                                       class="btn btn-sm btn-danger fileinput-exists"
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
                                                  name="address"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address">Company size</label>
                                        <select name="company_size" id="company_size" class="form-control">
                                            <option value="Just Me">Just Me</option>
                                            <option value="2-3 People">2-3 People</option>
                                            <option value="4-10 People">4-10 People</option>
                                            <option value="10+ People">10+ People</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address">Industry</label>
                                        <select name="industry" id="industry" class="form-control select2">
                                                <option value="">--</option>
                                            @foreach($industries as $industry)
                                                <option value="{{$industry->id}}">{{$industry->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address">Source</label>
                                        <select name="source" id="source" class="form-control select2">
                                            <option value="">--</option>
                                            @foreach($findus as $find)
                                                <option value="{{$find->id}}">{{$find->source}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">@lang('modules.accountSettings.defaultCurrency')</label>
                                        <select name="currency_id" id="currency_id" class="form-control select2">
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">@lang('modules.accountSettings.defaultTimezone')</label>
                                        <select name="timezone" id="timezone" class="form-control select2">
                                            @foreach($timezones as $tz)
                                                <option @if($tz == 'America/New_York') selected @endif>{{ $tz }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">@lang('modules.accountSettings.changeLanguage')</label>
                                        <select name="locale" id="locale" class="form-control select2">
                                            <option value="en">English
                                            </option>
                                            @foreach($languageSettings as $language)
                                                <option value="{{ $language->language_code }}">{{ $language->language_name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('app.status')</label>
                                        <select name="status" id="status" class="form-control select2">
                                            <option value="active">@lang('app.active')</option>
                                            <option value="inactive">@lang('app.inactive')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-4 mb-4">

                            <h5 class="mb-4">@lang('modules.company.accountSetup')</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="required">@lang('app.email')</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email"
                                               class="required">@lang('modules.employees.employeePassword')</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                               value="">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-primary"><i
                                        class="fa fa-check"></i> @lang('app.save')</button>

                        </div>
                    </div>
                    {!! Form::close() !!}


                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script src="{{ asset('themes/wceo/assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/select2/select2-custom.js')}}"></script>

    <script>
        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        $('#save-form').click(function () {

            $.easyAjax({
                url: '{{route('super-admin.companies.store')}}',
                container: '#createCompany',
                type: "POST",
                redirect: true,
                file: true,
            });
        });
    </script>

@endpush

