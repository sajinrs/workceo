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
        @include('sections.admin_setting_menu')
       </div>
        <div class="col-md-9">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                          <h5>@lang('app.menu.projectSettings') </h5>
                           
                        </div>
                        {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">




                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">@lang('modules.accountSettings.sendReminder')
                                                <a class="mytooltip" href="javascript:void(0)"  data-trigger="hover" data-placement="top" data-content="@lang('modules.accountSettings.sendReminderInfo')">
                                                    <i class="fa fa-info-circle"></i>
                                                   
                                                </a>
                                            </label>
                                            <div class="switch-showcase icon-state">
                                                <label class="switch">
                                                    <input type="checkbox" id="send_reminder" name="send_reminder"  @if($projectSetting->send_reminder == 'yes') checked @endif ><span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row @if($projectSetting->send_reminder == 'no') d-none @endif" id="send_reminder_div">
                                    <div class="col-md-12">
                                        <label>@lang('modules.projectSettings.sendNotificationsTo')</label>
                                        <div class="form-group">
                                            <div id="remind_to">
                                                <div class="checkbox checkbox-info checkbox-inline m-r-10">
                                                    <input id="send_reminder_admin" name="remind_to[]" value="admins"
                                                           @if(in_array('admins', $projectSetting->remind_to) != false) checked @endif
                                                           type="checkbox">
                                                    <label for="send_reminder_admin">@lang('modules.messages.admins')</label>
                                                </div>
                                                <div class="checkbox checkbox-info checkbox-inline">
                                                    <input id="send_reminder_member" name="remind_to[]" value="members"
                                                           @if(in_array('members', $projectSetting->remind_to) != false) checked @endif
                                                           type="checkbox">
                                                    <label for="send_reminder_member">@lang('modules.messages.members')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-3">
                                        <div class="form-group">
                                            <label>@lang('modules.projects.remindBefore')</label>
                                            <input type="number" min="1" value="{{ $projectSetting->remind_time }}" name="remind_time" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            {{--<select name="remind_type" id="" class="form-control">--}}
                                                {{--<option value="day">@lang('app.day')</option>--}}
                                                {{--<option value="hour">@lang('app.hour')</option>--}}
                                                {{--<option value="minute">@lang('app.minute')</option>--}}
                                            {{--</select>--}}
                                            <input type="text" readonly value="{{ $projectSetting->remind_type }}" name="remind_type" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                  
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <div class="form-actions col-md-6 offset-md-7">
                <div class="row">
                    <div class="col-md-5 pr-0">
                        <button type="reset" id="reset" class="btn btn-primary form-control"> @lang('app.reset')</button>
                    </div>
                    <div class="col-md-5 pr-0">
                        <button type="submit" id="save-form" class="btn btn-primary form-control" data-original-title="" title="">@lang('app.update')</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}

    </div>
    </div>
    </div>
    </div>
    </div>
@endsection

@push('footer-script')

    <script>
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());
        });

        var changeCheckbox = document.getElementById('send_reminder');

        changeCheckbox.onchange = function () {
            if (changeCheckbox.checked) {
                $('#send_reminder_div').removeClass('d-none');
            } else {
                $('#send_reminder_div').addClass('d-none');
            }
        };

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.project-settings.update', [$projectSetting->id])}}',
                container: '#editSettings',
                type: "POST",
                redirect: true,
                data: $('#editSettings').serialize()
            })
        });

        $('.checkbox').change(function () {
            $(this).siblings('.help-block').remove();
            $(this).parents('.form-group').removeClass('has-error');
        });

        $('#reset').click(function () {
            $('#remind_time').val('{{ $projectSetting->remind_time }}').trigger('change');
        });

        $(function () {
    $('.mytooltip').popover({
        container: 'body'
    });
    var dcolor = $(".mytooltip").attr("data-theme");
    if(dcolor == "dark") {
        $(".mytooltip").addClass("bg-dark");
    }
})

        
    </script>
@endpush
