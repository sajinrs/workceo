@extends('layouts.app')

@section('page-title')
  <div class="col-md-12">
        <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a  href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>
@endsection

@section('content')

 <div class="container-fluid">
   <div class="row">
    <div class="col-md-3">
    @include('sections.notification_settings_menu')
       </div>
        <div class="col-md-9">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                          <h5>@lang('modules.slackSettings.updateTitle') </h5>
                           
                        </div>
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">
 
              

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-md-6">
                                  <h5>@lang("modules.slackSettings.notificationTitle")</h5>
                                    <p class="text-muted m-b-10 font-13">
                                        @lang("modules.slackSettings.notificationSubtitle")
                                    </p>

                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12 b-t p-t-20">
                                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form form-horizontal','method'=>'PUT']) !!}

                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="control-label col-md-8 pt-2 pr-0 text-right">@lang("modules.emailSettings.userRegistration")</label>

                                                    <div class="col-md-4">
                                                        <div class="switch-showcase icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[4]->id }}" @if($emailSettings[4]->send_slack == 'yes') checked @endif ><span class="switch-state"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="control-label col-md-8 pt-2 pr-0 text-right">@lang("modules.emailSettings.employeeAssign")</label>

                                                    <div class="col-md-4">
                                                        <div class="switch-showcase icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[5]->id }}" @if($emailSettings[5]->send_slack == 'yes') checked @endif ><span class="switch-state"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="control-label col-md-8 pt-2 pr-0 text-right">@lang("modules.emailSettings.newNotice")</label>

                                                    <div class="col-md-4">
                                                        <div class="switch-showcase icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[6]->id }}" @if($emailSettings[6]->send_slack == 'yes') checked @endif ><span class="switch-state"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="control-label col-md-8 pt-2 pr-0 text-right">@lang("modules.emailSettings.taskAssign")</label>

                                                    <div class="col-md-4">
                                                        <div class="switch-showcase icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[7]->id }}" @if($emailSettings[7]->send_slack == 'yes') checked @endif ><span class="switch-state"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="control-label col-md-8 pt-2 pr-0 text-right">@lang("modules.emailSettings.expenseAdded")</label>

                                                    <div class="col-md-4">
                                                        <div class="switch-showcase icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[0]->id }}" @if($emailSettings[0]->send_slack == 'yes') checked @endif ><span class="switch-state"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="control-label col-md-8 pt-2 pr-0 text-right">@lang("modules.emailSettings.expenseMember")</label>

                                                    <div class="col-md-4">
                                                        <div class="switch-showcase icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[1]->id }}" @if($emailSettings[1]->send_slack == 'yes') checked @endif ><span class="switch-state"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="control-label col-md-8 pt-2 pr-0 text-right">@lang("modules.emailSettings.expenseStatus")</label>

                                                    <div class="col-md-4">
                                                        <div class="switch-showcase icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[2]->id }}" @if($emailSettings[2]->send_slack == 'yes') checked @endif ><span class="switch-state"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="control-label col-md-8 pt-2 pr-0 text-right">@lang("modules.emailSettings.ticketRequest")</label>

                                                    <div class="col-md-4">
                                                        <div class="switch-showcase icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[3]->id }}" @if($emailSettings[3]->send_slack == 'yes') checked @endif ><span class="switch-state"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="control-label col-md-8 pt-2 pr-0 text-right">@lang("modules.emailSettings.leaveRequest")</label>

                                                    <div class="col-md-4">
                                                        <div class="switch-showcase icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[8]->id }}" @if($emailSettings[8]->send_slack == 'yes') checked @endif ><span class="switch-state"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="control-label col-md-8 pt-2 pr-0 text-right">@lang("modules.emailSettings.taskComplete")</label>

                                                    <div class="col-md-4">
                                                        <div class="switch-showcase icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[9]->id }}" @if($emailSettings[9]->send_slack == 'yes') checked @endif ><span class="switch-state"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="control-label col-md-8 pt-2 pr-0 text-right">@lang("modules.emailSettings.invoiceNotification")</label>

                                                    <div class="col-md-4">
                                                        <div class="switch-showcase icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[10]->id }}" @if($emailSettings[10]->send_slack == 'yes') checked @endif ><span class="switch-state"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    {!! Form::open(['id'=>'editSlackSettings','class'=>'ajax-form','method'=>'PUT']) !!}

                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="company_name">@lang('modules.slackSettings.slackWebhook')</label>
                                            <input type="text" class="form-control" id="slack_webhook"
                                                   name="slack_webhook" value="{{ $slackSettings->slack_webhook }}" style="width: 360px;">
                                        </div>


                                        <div class="form-group">
                                        <div class="col-md-8 pl-0">
                                            <label for="exampleInputPassword1" class="d-block">@lang('modules.slackSettings.slackNotificationLogo')</label>

                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail">
                                                    @if(is_null($slackSettings->slack_logo))
                                                        <img src="https://via.placeholder.com/200x150.png?text={{ str_replace(' ', '+', __('modules.slackSettings.uploadSlackLog')) }}"
                                                             alt="" class="fileinput-preview-set"/>

                                                           <input type="hidden" id="logo_url" value="https://via.placeholder.com/200x150.png?text={{ str_replace(' ', '+', __('modules.slackSettings.uploadSlackLog')) }}">        
                                                    @else
                                                        <img src="{{ $slackSettings->slack_logo_url }}"
                                                             alt="" class="fileinput-preview-set"/>
                                    <input type="hidden" id="logo_url" value="{{ $slackSettings->slack_logo_url }}">
                                                    @endif
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail thumbnailset"
                                                     style="max-width: 200px; max-height: 150px;"></div>
                                                <div>
                                                        <span class="btn btn-primary btn-block btn-file">
                                                            <span class="fileinput-new selectImages"> @lang('app.selectImage') </span>
                                                            <span class="fileinput-exists"> @lang('app.change') </span>
                                                            <input type="file" name="slack_logo" id="logo">
                                                        </span>
                                                    <a href="javascript:;"  class="btn btn-danger removeall fileinput-exists"
                                                       data-dismiss="fileinput"> @lang('app.remove') </a>
                                                </div>
                                            </div>

                                            @if(!is_null($slackSettings->slack_logo))
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


                                    <div class="form-actions m-t-20">
                                        <button type="submit" id="save-form"
                                                class="btn btn-primary waves-effect waves-light m-r-10">
                                            @lang('app.update')
                                        </button>
                                        <button type="button" id="send-test-notification"
                                                class="btn btn-primary waves-effect waves-light">@lang('modules.slackSettings.sendTestNotification')</button>

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
    <!-- .row -->

@endsection

@push('footer-script')

<script>

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.slack-settings.update', ['1'])}}',
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
            img = '<img src="https://via.placeholder.com/200x150.png?text={{ str_replace(' ', '+', __('modules.slackSettings.uploadSlackLog')) }}" alt=""/>';
        }
        else{
            img = '<img src="{{ asset_url('slack-logo/'.$slackSettings->slack_logo) }}" alt=""/>'
        }
        $('.thumbnail').html(img);

    });
</script>
<script>
    
    $('.change-email-setting').change(function () {
        var id = $(this).data('setting-id');

        if ($(this).is(':checked'))
            var sendSlack = 'yes';
        else
            var sendSlack = 'no';

        var url = '{{route('admin.slack-settings.updateSlackNotification', ':id')}}';
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            type: "POST",
            data: {'id': id, 'send_slack': sendSlack, '_method': 'POST', '_token': '{{ csrf_token() }}'}
        })
    });

    $('#send-test-notification').click(function () {

        var url = '{{route('admin.slack-settings.sendTestNotification')}}';

        $.easyAjax({
            url: url,
            type: "GET",
            success: function (response) {

            }
        })
    });


function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('.fileinput-preview-set').attr('src', e.target.result);
        $('.thumbnailset').attr('style','display:none !important');
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}
$(".removeall").click(function() {
     $('.btn-file').addClass('btn-block');
     $('.selectImages').show();
     $('.fileinput-preview-set').attr('src', $('#logo_url').val());
     $('.fileinput-exists').hide();
 });
$("#logo").change(function() {
    $('.btn-file').removeClass('btn-block');
    $('.selectImages').hide();

    $('.fileinput-exists').css('display', 'inline-block');
  readURL(this);
});
</script>
@endpush

