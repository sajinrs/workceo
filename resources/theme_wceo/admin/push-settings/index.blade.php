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
                          <h5>@lang('modules.pushSettings.updateTitle') </h5>
                           
                        </div>
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">
                     

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-md-12">

                                   <h5>@lang("modules.slackSettings.notificationTitle")</h5>

                                    <p class="text-muted m-b-10 font-13">
                                        @lang("modules.slackSettings.notificationSubtitle")
                                    </p>

                                    <div class="row">
                                        <div class="col-sm-6 col-xs-6 b-t p-t-20">
                                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form form-horizontal','method'=>'PUT']) !!}

                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="control-label col-md-8 pt-2 pr-0 text-right">@lang("modules.emailSettings.employeeAssign")</label>

                                                    <div class="col-md-4">
                                                        <div class="switch-showcase icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[5]->id }}" @if($emailSettings[5]->send_push == 'yes') checked @endif ><span class="switch-state"></span>
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
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[7]->id }}" @if($emailSettings[7]->send_push == 'yes') checked @endif ><span class="switch-state"></span>
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
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[0]->id }}" @if($emailSettings[0]->send_push == 'yes') checked @endif ><span class="switch-state"></span>
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
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[1]->id }}" @if($emailSettings[1]->send_push == 'yes') checked @endif ><span class="switch-state"></span>
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
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[2]->id }}" @if($emailSettings[2]->send_push == 'yes') checked @endif ><span class="switch-state"></span>
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
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[3]->id }}" @if($emailSettings[3]->send_push == 'yes') checked @endif ><span class="switch-state"></span>
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
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[8]->id }}" @if($emailSettings[8]->send_push == 'yes') checked @endif ><span class="switch-state"></span>
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
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[9]->id }}" @if($emailSettings[9]->send_push == 'yes') checked @endif ><span class="switch-state"></span>
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
                                                                <input type="checkbox" class="change-email-setting" data-setting-id="{{ $emailSettings[10]->id }}" @if($emailSettings[10]->send_push == 'yes') checked @endif ><span class="switch-state"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            {!! Form::close() !!}
                                        </div>
                                    </div>
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

        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());

        });

        $('.change-email-setting').change(function () {
            var id = $(this).data('setting-id');

            if ($(this).is(':checked'))
                var sendSlack = 'yes';
            else
                var sendSlack = 'no';

            var url = '{{route('admin.push-notification-settings.updatePushNotification', ':id')}}';
            url = url.replace(':id', id);
            $.easyAjax({
                url: url,
                type: "POST",
                data: {'id': id, 'send_push': sendSlack, '_method': 'POST', '_token': '{{ csrf_token() }}'}
            })
        });

        $('#send-test-notification').click(function () {

            var url = '{{route('admin.push-notification-settings.sendTestNotification')}}';

            $.easyAjax({
                url: url,
                type: "GET",
                success: function (response) {

                }
            })
        });



    </script>
@endpush

