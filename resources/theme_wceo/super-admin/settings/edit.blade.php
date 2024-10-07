@extends('layouts.super-admin')
@section('page-title')
    <div class="col-md-12">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                            href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                                <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                            </ol>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/select2.css')}}">

@endpush

@section('content')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            @if(!$global->hide_cron_message)
                <div class="col-md-12">
                    <div class="alert alert-info ">
                        <h5 class="text-white">Set following cron command on your server (Ignore if already done)</h5>
                        <code>* * * * * cd {{ base_path() }} && php artisan schedule:run >> /dev/null 2>&1</code>
                    </div>
                </div>
            @endif

            @if($global->show_public_message)
                <div class="col-md-12">
                    <div class="alert alert-success">
                        <h4>Remove public from URL</h4>
                        <h5 class="text-white">Create a file with the name <code>.htaccess</code> at the root of folder
                            (where app, bootstrap, config folder resides) and add the following content</h5>

                        <pre>
                        <code class="apache hljs">
<span class="hljs-section">&lt;IfModule mod_rewrite.c&gt;</span>

  <span class="hljs-attribute">RewriteEngine </span><span class="hljs-literal"> On</span>
  <span class="hljs-attribute"><span class="hljs-nomarkup">RewriteRule</span></span><span class="hljs-variable"> ^(.*)$ public/$1</span><span
                                    class="hljs-meta"> [L]</span>

<span class="hljs-section">&lt;/IfModule&gt;</span>
</code></pre>
                    </div>

                </div>
            @endif
            <div class="col-md-12">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                            <h5>@lang('modules.accountSettings.updateTitle') </h5>
                            <span>
                             @if($cachedFile)
                                    <span class="m-r-5">@lang('messages.cacheEnabled')</span>

                                    <a href="javascript:;" id="clear-cache"
                                       class="btn btn-xs btn-danger m-l-5 text-white"><i
                                                class="fa fa-times"></i> @lang('app.disableCache')</a>
                                @else

                                    <span class="m-r-5">@lang('messages.cacheDisabled')</span>

                                    <a href="javascript:;" id="refresh-cache" class="btn btn-xs btn-success text-white">
                                    <i
                                            class="fa fa-check"></i> @lang('app.enableCache')</a>
                                @endif
                            </span>
                        </div>

                        <div class="vtabs customvtab m-t-10">
                            @include('sections.super_admin_setting_menu')
                            <div class="tab-content">
                                <div id="vhome3" class="tab-pane active">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                                            <div class=" card-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   for="company_name">@lang('modules.accountSettings.companyName')</label>
                                                            <input type="text" class="form-control" id="company_name"
                                                                   name="company_name"
                                                                   value="{{ $global->company_name }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   for="company_email">@lang('modules.accountSettings.companyEmail')</label>
                                                            <input type="email" class="form-control" id="company_email"
                                                                   name="company_email"
                                                                   value="{{ $global->company_email }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   for="company_phone">@lang('modules.accountSettings.companyPhone')</label>
                                                            <input type="tel" class="form-control" id="company_phone"
                                                                   name="company_phone"
                                                                   value="{{ $global->company_phone }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   for="exampleInputPassword1">@lang('modules.accountSettings.companyWebsite')</label>
                                                            <input type="text" class="form-control" id="website"
                                                                   name="website"
                                                                   value="{{ $global->website }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-4 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   for="company_phone">@lang('modules.invoices.currency')</label>
                                                            <select class="form-control select2" id="currency_id"
                                                                    name="currency_id">
                                                                @forelse($currencies as $currency)
                                                                    <option @if($currency->id == $global->currency_id) selected
                                                                            @endif value="{{ $currency->id }}">
                                                                        {{ $currency->currency_name }} -
                                                                        ({{ $currency->currency_symbol }})
                                                                    </option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-4 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   for="address">@lang('modules.accountSettings.defaultTimezone')</label>
                                                            <select name="timezone" id="timezone"
                                                                    class="form-control select2">
                                                                @foreach($timezones as $tz)
                                                                    <option @if($global->timezone == $tz) selected @endif>{{ $tz }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-4 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   for="address">@lang('modules.accountSettings.changeLanguage')</label>
                                                            <select name="locale" id="locale"
                                                                    class="form-control select2">
                                                                <option @if($global->locale == "en") selected
                                                                        @endif value="en">English
                                                                </option>
                                                                @foreach($languageSettings as $language)
                                                                    <option value="{{ $language->language_code }}"
                                                                            @if($global->locale == $language->language_code) selected @endif >{{ $language->language_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   for="google_recaptcha_key">@lang('modules.accountSettings.google_recaptcha_key')</label>
                                                            <input type="text" class="form-control"
                                                                   id="google_recaptcha_key" name="google_recaptcha_key"
                                                                   value="{{ $global->google_recaptcha_key }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   for="google_recaptcha_secret">@lang('modules.accountSettings.google_recaptcha_secret')</label>
                                                            <input type="text" class="form-control"
                                                                   id="google_recaptcha_secret"
                                                                   name="google_recaptcha_secret"
                                                                   value="{{ $global->google_recaptcha_secret }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{--<div class="row">
                                                    <div class="col-sm-12 col-md-4 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   for="exampleInputPassword1">@lang('modules.accountSettings.companyLogo')</label>

                                                            <div class="col-md-12">
                                                                <div class="fileinput fileinput-new"
                                                                     data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail"
                                                                         style="width: 200px; height: 150px;">
                                                                        <img src="{{ $global->logo_url }}" alt=""/>
                                                                    </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                                                         style="max-width: 200px; max-height: 150px;"></div>
                                                                    <div>
                                                                <span class="btn btn-success btn-sm btn-file">
                                                                    <span class="fileinput-new"> @lang('app.selectImage') </span>
                                                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                                                    <input type="file" name="logo" id="logo"> </span>
                                                                        <a href="javascript:;"
                                                                           class="btn btn-danger btn-sm fileinput-exists"
                                                                           data-dismiss="fileinput"> @lang('app.remove') </a>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                   

                                                    <div class="col-sm-12 col-md-4 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   for="exampleInputPassword1">GIF @lang('modules.accountSettings.companyLogo')</label>

                                                            <div class="col-md-12">
                                                                <div class="fileinput fileinput-new"
                                                                     data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail"
                                                                         style="width: 200px; height: 150px;">
                                                                        <img src="{{ $global->logo_gif_front_url }}"
                                                                             alt=""/>
                                                                    </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                                                         style="max-width: 200px; max-height: 150px;"></div>
                                                                    <div>
                                                                <span class="btn btn-success btn-sm btn-file">
                                                                    <span class="fileinput-new"> @lang('app.selectImage') </span>
                                                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                                                    <input type="file" name="logo_gif_front"
                                                                           id="logo_gif_front"> </span>
                                                                        <a href="javascript:;"
                                                                           class="btn btn-danger btn-sm fileinput-exists"
                                                                           data-dismiss="fileinput"> @lang('app.remove') </a>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>


                                                    
                                                </div>--}}
                                                <div class="row">

                                                <div class="col-sm-12 col-md-4 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   for="exampleInputPassword1">@lang('modules.accountSettings.frontLogo')</label>

                                                            <div class="col-md-12">
                                                                <div class="fileinput fileinput-new"
                                                                     data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail"
                                                                         style="width: 200px; height: 150px;">
                                                                        <img src="{{ $global->logo_front_url }}"
                                                                             alt=""/>
                                                                    </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                                                         style="max-width: 200px; max-height: 150px;"></div>
                                                                    <div>
                                                                <span class="btn btn-success btn-sm btn-file">
                                                                    <span class="fileinput-new"> @lang('app.selectImage') </span>
                                                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                                                    <input type="file" name="logo_front"
                                                                           id="logo_front"> </span>
                                                                        <a href="javascript:;"
                                                                           class="btn btn-danger btn-sm fileinput-exists"
                                                                           data-dismiss="fileinput"> @lang('app.remove') </a>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                <div class="col-sm-12 col-md-4 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label">@lang('modules.themeSettings.loginScreenBackground')</label>

                                                            <div class="col-md-12 m-b-20">
                                                                <div class="fileinput fileinput-new"
                                                                     data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail"
                                                                         style="width: 200px; height: 150px;">
                                                                        <img src="{{ $global->login_background_url }}"
                                                                             alt=""/>
                                                                    </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                                                         style="max-width: 200px; max-height: 150px;"></div>
                                                                    <div>
                                        <span class="btn btn-success btn-sm btn-file">
                                        <span class="fileinput-new"> @lang('app.selectImage') </span>
                                        <span class="fileinput-exists"> @lang('app.change') </span>
                                        <input type="file" name="login_background" id="login_background"> </span>
                                                                        <a href="javascript:;"
                                                                           class="btn btn-danger btn-sm fileinput-exists"
                                                                           data-dismiss="fileinput"> @lang('app.remove') </a>
                                                                    </div>
                                                                </div>
                                                                <span class="help-block">Recommended size: 1500 X 1056 (Pixels)</span>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12 col-md-4 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Signup Screen Background</label>

                                                            <div class="col-md-12 m-b-20">
                                                                <div class="fileinput fileinput-new"
                                                                     data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail"
                                                                         style="width: 200px; height: 150px;">
                                                                        <img src="{{ $global->signup_background_url }}"
                                                                             alt=""/>
                                                                    </div>
                                                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                                                         style="max-width: 200px; max-height: 150px;"></div>
                                                                    <div>
                                        <span class="btn btn-success btn-sm btn-file">
                                        <span class="fileinput-new"> @lang('app.selectImage') </span>
                                        <span class="fileinput-exists"> @lang('app.change') </span>
                                        <input type="file" name="signup_background" id="signup_background"> </span>
                                                                        <a href="javascript:;"
                                                                           class="btn btn-danger btn-sm fileinput-exists"
                                                                           data-dismiss="fileinput"> @lang('app.remove') </a>
                                                                    </div>
                                                                </div>
                                                                <span class="help-block">Recommended size: 1500 X 1056 (Pixels)</span>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   for="address">@lang('modules.accountSettings.companyAddress')</label>
                                                            <textarea class="form-control" id="address" rows="8"
                                                                      name="address">{{ $global->address }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   for="address">@lang('modules.accountSettings.weekStartFrom')</label>
                                                            <select name="week_start" id="week_start"
                                                                    class="form-control select2">
                                                                <option value="0"
                                                                        @if($global->week_start == '0') selected @endif >
                                                                    Sunday
                                                                </option>
                                                                <option value="1"
                                                                        @if($global->week_start == '1') selected @endif>
                                                                    Monday
                                                                </option>
                                                                <option value="2"
                                                                        @if($global->week_start == '2') selected @endif>
                                                                    Tuesday
                                                                </option>
                                                                <option value="3"
                                                                        @if($global->week_start == '3') selected @endif>
                                                                    Wednesday
                                                                </option>
                                                                <option value="4"
                                                                        @if($global->week_start == '4') selected @endif>
                                                                    Thursday
                                                                </option>
                                                                <option value="5"
                                                                        @if($global->week_start == '5') selected @endif>
                                                                    Friday
                                                                </option>
                                                                <option value="6"
                                                                        @if($global->week_start == '6') selected @endif>
                                                                    Saturday
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label"
                                                                   class="control-label">@lang('modules.accountSettings.updateEnableDisable')
                                                                <a href="javascript:void(0)"
                                                                   class="example-popover"
                                                                   data-trigger="hover"
                                                                   data-container="body"
                                                                   data-toggle="popover"
                                                                   data-placement="top"
                                                                   data-offset="-20px -20px"
                                                                   data-content="@lang('modules.accountSettings.updateEnableDisableTest')">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </a>


                                                            </label>
                                                            <div class="media-body text-left icon-state">
                                                                <label class="switch">
                                                                    <input id="system_update" name="system_update"
                                                                           @if($global->system_update == true) checked
                                                                           @endif  type="checkbox"><span
                                                                            class="switch-state"></span>
                                                                </label>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-xs-12">
                                                        <div class="form-group">

                                                            <label class="col-form-label control-label">@lang('modules.accountSettings.emailVerification')
                                                                <a href="javascript:void(0)"
                                                                   class="example-popover"
                                                                   data-trigger="hover"
                                                                   data-container="body"
                                                                   data-toggle="popover"
                                                                   data-placement="top"
                                                                   data-offset="-20px -20px"
                                                                   data-content="@lang('modules.accountSettings.emailVerificationEnableDisable')">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </a>


                                                            </label>
                                                            <div class="media-body text-left icon-state">
                                                                <label class="switch">
                                                                    <input id="email_verification"
                                                                           name="email_verification"
                                                                           @if($global->email_verification == true) checked
                                                                           @endif  type="checkbox"><span
                                                                            class="switch-state"></span>
                                                                </label>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer  text-right">

                                                <button type="submit" id="save-form"
                                                        class="btn btn-primary waves-effect waves-light m-r-10 ">
                                                    @lang('app.update')
                                                </button>

                                            </div>

                                            {!! Form::close() !!}
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


        </div>
        <!-- .row -->
    </div>




@endsection

@push('footer-script')

    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset('themes/wceo/assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/select2/select2-custom.js')}}"></script>

    <script src="{{ asset('themes/wceo/assets/js/popover-custom.js')}}"></script>
    <script>
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());

        });

        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        $('#refresh-cache').click(function () {
            $.easyAjax({
                url: '{{url("refresh-cache")}}',
                type: "GET",
                success: function () {
                    window.location.reload();
                }
            })
        });

        $('#clear-cache').click(function () {
            $.easyAjax({
                url: '{{url("clear-cache")}}',
                type: "GET",
                success: function () {
                    window.location.reload();
                }
            })
        });

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('super-admin.settings.update', $global->id)}}',
                container: '#editSettings',
                type: "POST",
                redirect: true,
                file: true,
            })
        });

    </script>
@endpush

