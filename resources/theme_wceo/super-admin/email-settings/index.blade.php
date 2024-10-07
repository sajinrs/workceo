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
@endpush

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                            <h5>{{ __($pageTitle) }} </h5>

                        </div>

                        <div class="vtabs customvtab m-t-10">
                            @include('sections.super_admin_setting_menu')
                            <div class="tab-content">
                                <div id="vhome3" class="tab-pane active">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            {!! Form::open(['id'=>'updateSettings','class'=>'ajax-form']) !!}
                                            {!! Form::hidden('_token', csrf_token()) !!}
                                            @method('PUT')
                                            <div class=" card-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-xs-12">
                                                        <div id="alert">
                                                            @if($smtpSetting->mail_driver =='smtp')
                                                                @if($smtpSetting->verified)
                                                                    <div class="alert alert-success">{{__('messages.smtpSuccess')}}</div>
                                                                @else
                                                                    <div class="alert alert-danger">{{__('messages.smtpError')}}</div>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="mt-4"></div>


                                                        <div class="col-sm-12 col-md-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="col-form-label"
                                                                       for="mailDriver">@lang("modules.emailSettings.mailDriver")</label>
                                                                <div class="form-group m-t-15 m-checkbox-inline mb-0 custom-radio-ml">
                                                                    <div class="radio radio-primary">
                                                                        <input id="radioinline1" type="radio"
                                                                               onchange="getDriverValue(this);"
                                                                               value="mail"
                                                                               @if($smtpSetting->mail_driver == 'mail') checked
                                                                               @endif name="mail_driver">
                                                                        <label class="mb-0"
                                                                               for="radioinline1">Mail</label>
                                                                    </div>
                                                                    <div class="radio radio-primary">
                                                                        <input id="radioinline2" type="radio"
                                                                               value="smtp"
                                                                               onchange="getDriverValue(this);"
                                                                               @if($smtpSetting->mail_driver == 'smtp') checked
                                                                               @endif name="mail_driver">
                                                                        <label class="mb-0"
                                                                               for="radioinline2">SMTP</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-6 col-xs-12">
                                                            <div class="form-group smtp_div">
                                                                <label>@lang("modules.emailSettings.mailHost")</label>
                                                                <input type="text" name="mail_host" id="mail_host"
                                                                       class="form-control"
                                                                       value="{{ $smtpSetting->mail_host }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6 col-xs-12 smtp_div">
                                                            <div class="form-group">
                                                                <label class="col-form-label"
                                                                       for="mailPort">@lang("modules.emailSettings.mailPort")</label>
                                                                <input type="text" name="mail_port" id="mail_port"
                                                                       class="form-control"
                                                                       value="{{ $smtpSetting->mail_port }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6 col-xs-12">
                                                            <div class="form-group smtp_div">
                                                                <label class="col-form-label"
                                                                       for="mail_username">@lang("modules.emailSettings.mailUsername")</label>
                                                                <input type="text" name="mail_username"
                                                                       id="mail_username"
                                                                       class="form-control"
                                                                       value="{{ $smtpSetting->mail_username }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6 col-xs-12">
                                                            <div class="form-group smtp_div">
                                                                <label class="control-label"
                                                                       for="mailPassword">@lang("modules.emailSettings.mailPassword")</label>
                                                                <input type="password" name="mail_password"
                                                                       id="mail_password"
                                                                       class="form-control"
                                                                       value="{{ $smtpSetting->mail_password }}">

                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-6 col-xs-12">
                                                            <div class="form-group smtp_div">
                                                                <label class="control-label">@lang("modules.emailSettings.mailEncryption")</label>
                                                                <select class="form-control" name="mail_encryption"
                                                                        id="mail_encryption">
                                                                    <option @if($smtpSetting->mail_encryption == 'tls') selected @endif>
                                                                        tls
                                                                    </option>
                                                                    <option @if($smtpSetting->mail_encryption == 'ssl') selected @endif>
                                                                        ssl
                                                                    </option>

                                                                    <option value="null"
                                                                            @if($smtpSetting->mail_encryption == null) selected @endif>
                                                                        none
                                                                    </option>
                                                                </select>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">@lang("modules.emailSettings.mailFrom")</label>
                                                                <input type="text" name="mail_from_name"
                                                                       id="mail_from_name"
                                                                       class="form-control"
                                                                       value="{{ $smtpSetting->mail_from_name }}">

                                                            </div>
                                                        </div>


                                                        <div class="col-sm-12 col-md-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">@lang("modules.emailSettings.mailFromEmail")</label>
                                                                <input type="text" name="mail_from_email"
                                                                       id="mail_from_email"
                                                                       class="form-control"
                                                                       value="{{ $smtpSetting->mail_from_email }}">
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                                <div class="card-footer  text-right">

                                                    <button type="submit" id="save-form"
                                                            class="btn btn-primary waves-effect waves-light m-r-10 ">
                                                        @lang('app.update')
                                                    </button>
                                                <!--   <button type="button" id="send-test-email"
                                                class="btn btn-success">@lang('modules.emailSettings.sendTestEmail')</button> -->
                                                    <button class="btn btn-secondary" type="button" data-toggle="modal"
                                                            data-original-title="test"
                                                            data-target="#send-test-email">@lang('modules.emailSettings.sendTestEmail')</button>
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

        </div>
    </div>

    </div>
    <!---- end-->


    {{--Ajax Modal--}}

    <div class="modal fade" id="send-test-email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Test Email</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">Ã—</span></button>
                </div>
                <div class="modal-body">  {!! Form::open(['id'=>'testEmail','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Enter email address where test mail needs to be sent</label>
                                    <input type="text" name="test_email" id="test_email"
                                           class="form-control"
                                           value="{{ $user->email }}">
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="send-test-email-submit">submit</button>
                    </div>
                    {!! Form::close() !!}</div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-dismiss="modal">Close</button>
                    <!--  <button class="btn btn-secondary" type="button">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
    <script>
        $('#save-form').click(function () {

            var url = '{{route('super-admin.email-settings.update', $smtpSetting->id)}}';

            $.easyAjax({
                url: url,
                type: "POST",
                container: '#updateSettings',
                data: $('#updateSettings').serialize(),
                messagePosition: "inline",
                success: function (response) {
                    if (response.status == 'error') {
                        $('#alert').prepend('<div class="alert alert-danger">{{__('messages.smtpError')}}</div>')
                    } else {
                        $('#alert').show();
                    }
                }
            })
        });

        $('#send-test-email').click(function () {
            $('#testMailModal').modal('show')
        });

        $('#send-test-email-submit').click(function () {
            $.easyAjax({
                url: '{{route('super-admin.email-settings.sendTestEmail')}}',
                type: "GET",
                messagePosition: "inline",
                container: "#testEmail",
                data: $('#testEmail').serialize()

            })
        });


        function getDriverValue(sel) {
            if (sel.value == 'mail') {
                $('.smtp_div').hide();
                $('#alert').hide();
            } else {
                $('.smtp_div').show();
                $('#alert').show();
            }
        }

        @if ($smtpSetting->mail_driver == 'mail')
        $('.smtp_div').hide();
        $('#alert').hide();
        @endif
    </script>
@endpush