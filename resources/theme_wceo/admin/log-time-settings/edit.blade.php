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
                          <h5>{{ __($pageTitle) }} </h5>
                           
                        </div>
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">




                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            
                        <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">

                <div class="vtabs customvtab m-t-10">

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="white-box">

                                        <div class="alert alert-info ">
                                            <i class="fa fa-info-circle"></i> @lang('messages.logTimeNote')
                                        </div>
                                        {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="radio-list">
                                                    <label class="radio-inline p-r-30">
                                                        <div class="radio radio-info">
                                                            <input type="radio" name="log_time_for" @if($logTime->log_time_for == 'project') checked @endif id="for_project" value="project">
                                                            <label for="for_project">@lang('modules.logTimeSetting.project')</label>
                                                        </div>
                                                    </label>
                                                    <label class="radio-inline">
                                                        <div class="radio radio-info">
                                                            <input type="radio" name="log_time_for" id="for_task" @if($logTime->log_time_for == 'task') checked @endif value="task">
                                                            <label for="for_task">@lang('modules.logTimeSetting.task')</label>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="checkbox checkbox-info  col-md-10">
                                                <input id="auto_timer_stop" name="auto_timer_stop" value="yes"
                                                       @if($logTime->auto_timer_stop == "yes") checked
                                                       @endif
                                                       type="checkbox">
                                                <label for="auto_timer_stop">@lang('modules.logTimeSetting.autoStopTimerAfterOfficeTime')</label>
                                            </div>
                                        </div>

                                        {{--<div class="row">--}}
                                            {{--<div class="col-md-12 m-t-30">--}}
                                                {{--<button class="btn btn-success" id="save-form" type="button"><i class="fa fa-check"></i> @lang('app.save')</button>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {!! Form::close() !!}

                                    </div>
                                </div>
                            </div>
                            <!-- /.row -->

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

       
   

    </div>
    </div>
    </div>
    </div>
    </div>
@endsection




@push('footer-script')

<script>

    // change Log Time For Setting
    $('input[name=log_time_for]').click(function () {
        var timeFor = $('input[name=log_time_for]:checked').val();

        $.easyAjax({
            url: '{{route('admin.log-time-settings.store')}}',
            type: "POST",
            data: {'_token': '{{ csrf_token() }}', 'log_time_for': timeFor}
        })
    });

    $('#auto_timer_stop').click(function(){
        var auto_timer_stop = 'no';
        if($(this).prop("checked") == true){
             auto_timer_stop = 'yes';
        }
        $.easyAjax({
            url: '{{route('admin.log-time-settings.store')}}',
            type: "POST",
            data: {'_token': '{{ csrf_token() }}', 'auto_timer_stop': auto_timer_stop}
        })
    });
</script>
@endpush

