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
                            <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
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
                <div  class="card-body">
             
                     <div class="form-body" >
                             
                                   <h6 class="mb-4">@lang("modules.slackSettings.notificationTitle")</h6>

                                 <h6 class="mb-4">
                                        @lang("modules.slackSettings.notificationSubtitle")
                                    </h6>
                                <span class="m-r-5">
                                        Signup on <a href="https://onesignal.com/" target="_blank">onesignal.com</a>
                                    </span>
                      

              <div class="vtabs customvtab m-t-10">
                   @include('sections.super_admin_setting_menu')
                                 
                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                           <div class="col-sm-12 col-xs-12">
                                    {!! Form::open(['id'=>'editSlackSettings','class'=>'ajax-form','method'=>'PUT']) !!}

                                   
                                    <div class="card-body">
                                        <div class="row">
                                         <div class="col-sm-12 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label  for="company_name">@lang('modules.pushSettings.oneSignalAppId')</label>
                                            <input type="text" class="form-control" id="onesignal_app_id"
                                                   name="onesignal_app_id" value="{{ $pushSettings->onesignal_app_id }}">
                                        </div>
                                         </div> 
                                         <div class="col-sm-12 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="company_name">@lang('modules.pushSettings.oneSignalRestApiKey')</label>
                                            <input type="password" class="form-control" id="onesignal_rest_api_key"
                                                   name="onesignal_rest_api_key" value="{{ $pushSettings->onesignal_rest_api_key }}">
                                            <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        </div>

                                        </div>
                                     </div>
                                      <div class="row">
                                         <div class="col-sm-12 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="company_name">@lang('app.status')</label>
                                            <select name="status" class="form-control" id="">
                                                <option
                                                        @if($pushSettings->status == 'inactive') selected @endif
                                                value="inactive">@lang('app.inactive')</option>
                                                <option
                                                        @if($pushSettings->status == 'active') selected @endif
                                                value="active">@lang('app.active')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                  <div class="row">
                                         <div class="col-sm-12 col-md-6 col-xs-12">
                                        <div class="form-group" style="display: none">
                                            <label for="exampleInputPassword1" class="d-block">@lang('modules.slackSettings.slackNotificationLogo')</label>

                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail"
                                                     style="width: 200px; height: 150px;">
                                                    @if(is_null($pushSettings->notification_logo))
                                                        <img src="https://via.placeholder.com/200x150.png?text={{ str_replace(' ', '+', __('modules.slackSettings.uploadSlackLogo')) }}"
                                                             alt=""/>
                                                    @else
                                                        <img src="{{ asset_url('notification-logo/'.$pushSettings->notification_logo) }}"
                                                             alt=""/>
                                                    @endif
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                     style="max-width: 200px; max-height: 150px;"></div>
                                                <div>
                                                        <span class="btn btn-info btn-file">
                                                            <span class="fileinput-new"> @lang('app.selectImage') </span>
                                                            <span class="fileinput-exists"> @lang('app.change') </span>
                                                            <input type="file" name="notification_logo" id="notification_logo">
                                                        </span>
                                                    <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                       data-dismiss="fileinput"> @lang('app.remove') </a>
                                                </div>
                                            </div>

                                            @if(!is_null($pushSettings->notification_logo))
                                                <div class="form-group">
                                                    <label for="removeImage">@lang("modules.emailSettings.removeImage")</label>
                                                    <div class="switchery-demo">
                                                        <input type="checkbox" name="removeImage" id="removeImageButton" class="js-switch removeImage"
                                                               data-color="#99d683" />
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="clearfix"></div>
                                        </div>

                                    </div>
                                    </div> 
                                 </div>
                             <div class="card-footer  text-right">
                                    
                                        <button type="submit" id="save-form"
                                                class="btn btn-primary waves-effect waves-light m-r-10">
                                            @lang('app.update')
                                        </button>
                                        <button type="button" id="send-test-notification"
                                                class="btn btn-secondary waves-effect waves-light">@lang('modules.slackSettings.sendTestNotification')</button>

                                    </div>

                                    {!! Form::close() !!}

                                </div>
                                <!-- .row -->

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
 </div>

        <!-- .row -->


        @endsection

        @push('footer-script')
        <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
        <script>
            $('#save-form').click(function () {
                $.easyAjax({
                    url: '{{route('super-admin.push-notification-settings.update', ['1'])}}',
                    container: '#editSlackSettings',
                    type: "POST",
                    redirect: true,
                    file: true
                })
            });
            $('#removeImageButton').change(function () {
                var removeButton;
                if ($(this).is(':checked'))
                    removeButton = 'yes';
                else
                    removeButton = 'no';

                var img;
                if(removeButton == 'yes'){
                    img = '<img src="https://via.placeholder.com/200x150.png?text={{ str_replace(' ', '+', __('modules.slackSettings.uploadSlackLogo')) }}" alt=""/>';
                }
                else{
                    img = '<img src="{{ asset_url('notification-logo/'.$pushSettings->notification_logo) }}" alt=""/>'
                }
                $('.thumbnail').html(img);

            });

            $('#send-test-notification').click(function () {

                var url = '{{route('super-admin.push-notification-settings.sendTestNotification')}}';

                $.easyAjax({
                    url: url,
                    type: "GET",
                    success: function (response) {

                    }
                })
            });
        </script>
    @endpush
