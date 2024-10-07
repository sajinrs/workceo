@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap-datetimepicker.min.css') }}">
@endpush

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
                          <h5>@lang('app.update') @lang('app.menu.attendanceSettings')</h5>
                           
                        </div>
                    {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            
                            <div class="row">
                              
                                    <div class="col-md-4">
                                        <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                                                
                                            <input type="text" name="office_start_time" id="office_start_time"
                                                        class="form-control form-control-lg"
                                                        value="{{ \Carbon\Carbon::createFromFormat('H:i:s', $attendanceSetting->office_start_time)->format($global->time_format) }}">
                                                            <label for="office_start_time" class="control-label">@lang('modules.attendance.officeStartTime')</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                            <input type="text" name="office_end_time" id="office_end_time"
                                                    class="form-control form-control-lg"
                                                    value="{{ \Carbon\Carbon::createFromFormat('H:i:s', $attendanceSetting->office_end_time)->format($global->time_format) }}">
                                                    <label for="office_end_time" class="control-label">@lang('modules.attendance.officeEndTime')</label>
                                          
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                                <input type="text" name="halfday_mark_time" id="halfday_mark_time"
                                                       class="form-control form-control-lg" placeholder="*"
                                                       value="@if($attendanceSetting->halfday_mark_time){{ \Carbon\Carbon::createFromFormat('H:i:s', $attendanceSetting->halfday_mark_time)->format($global->time_format) }}@else 01:00 @endif">
                                            <label for="office_end_time" class="control-label">@lang('modules.attendance.halfDayMarkTime')</label>
                                        </div>
                                    </div>

                                    
                             </div>    
                             <div class="row"> 

                                    <div class="col-md-4">
                                        <div class="form-label-group form-group">
                                           
                                            <input type="number" class="form-control form-control-lg" id="late_mark_duration"
                                                   name="late_mark_duration"
                                                   value="{{ $attendanceSetting->late_mark_duration }}" placeholder="*">
                                                    <label for="late_mark_duration" class="control-label required">@lang('modules.attendance.lateMark')</label>
                                        </div>
                                    </div>                        
                                    
                                    <div class="col-md-8">
                                        <div class="form-label-group form-group">
                                           
                                            <input type="number" class="form-control form-control-lg" id="clockin_in_day"
                                                   name="clockin_in_day"
                                                   value="{{ $attendanceSetting->clockin_in_day }}" placeholder="*">
                                                    <label for="late_mark_duration" class="control-label">@lang('modules.attendance.checkininday')</label>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row"> 
                                    <div class="col-md-7">
                                        <div class="checkbox checkbox-primary">
                                            <input id="employee_clock_in_out" type="checkbox" name="employee_clock_in_out" value="yes"  @if($attendanceSetting->employee_clock_in_out == "yes") checked  @endif>
                                            <label for="employee_clock_in_out"> @lang('modules.attendance.allowSelfClock')</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="checkbox checkbox-primary">
                                            <input id="radius_check" name="radius_check" type="checkbox" value="yes"  @if($attendanceSetting->radius_check == "yes") checked  @endif>
                                            <label for="radius_check"> @lang('modules.attendance.checkForRadius')</label>
                                        </div>                                     
                                    </div>

                                    <div class="col-md-6 @if($attendanceSetting->radius_check == "no") d-none @endif" id="radiusBox">
                                        <div class="form-label-group form-group">
                                            <input type="number" class="form-control form-control-lg" id="radius"
                                                   name="radius"
                                                   value="{{ $attendanceSetting->radius }}" placeholder="*">
                                            <label for="radius" class="control-label">@lang('modules.attendance.radius')</label>
                                        </div>
                                    </div>
                                </div>

                               </div>    

                               <div class="row">     
                                    <div class="col-md-5">
                                        <div class="checkbox checkbox-primary">
                                            <input id="ip_check" name="ip_check" type="checkbox" value="yes"  @if($attendanceSetting->ip_check == "yes") checked @endif>
                                            <label for="ip_check"> @lang('modules.attendance.checkForIp')</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 @if($attendanceSetting->ip_check == "no") d-none @endif" id="ipBox">
                                        <div id="addMoreBox1" class="clearfix">
                                            @forelse($ipAddresses as $index => $ipAddress)
                                                <div class="form-label-group form-label-group form-group" id="occasionBox">
                                                    <input class="form-control"  type="text" value="{{ $ipAddress }}" name="ip[{{ $index }}]" placeholder="@lang('modules.attendance.ipAddress')"/>
                                                    <div id="errorOccasion"></div>
                                                </div>
                                            @empty
                                                <div class="form-label-group form-group" id="occasionBox">
                                                    <input class="form-control form-control-lg"  type="text" id="ip[0]" name="ip[0]" placeholder="*"/>
                                                    <label for="ip[0]" class="control-label"> @lang('modules.attendance.ipAddress')</label>
                                                    <div id="errorOccasion"></div>
                                                </div>
                                            @endforelse
                                            <div class="col-md-1">
                                                {{--<button type="button"  onclick="removeBox(1)"  class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>--}}
                                            </div>
                                        </div>
                                        <div id="insertBefore"></div>
                                        <div class="clearfix">

                                        </div>
                                        <button type="button" id="plusButton" class="btn btn-sm btn-info" style="margin-bottom: 20px;">
                                            Add More <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                           </div>      
                           <div class="row">     
                                    <div class="col-md-12">
                                        <hr>
                                        <label class="control-label col-md-12 p-l-0"><b>@lang('modules.attendance.officeOpenDays')</b></label>
                                        <div class="row">
                                        
                                            <div class="checkbox checkbox-primary col-md-2 m-b-10">
                                                <input id="open_mon" name="office_open_days[]" value="1"
                                                       @foreach($openDays as $day)
                                                           @if($day == 1) checked @endif
                                                       @endforeach
                                                       type="checkbox">
                                                <label for="open_mon">@lang('app.monday')</label>
                                            </div>
                                            <div class="checkbox checkbox-primary col-md-2 m-b-10">
                                                <input id="open_tues" name="office_open_days[]" value="2"
                                                       @foreach($openDays as $day)
                                                       @if($day == 2) checked @endif
                                                       @endforeach
                                                       type="checkbox">
                                                <label for="open_tues">@lang('app.tuesday')</label>
                                            </div>
                                            <div class="checkbox checkbox-primary col-md-2 m-b-10">
                                                <input id="open_wed" name="office_open_days[]" value="3"
                                                       @foreach($openDays as $day)
                                                       @if($day == 3) checked @endif
                                                       @endforeach
                                                       type="checkbox">
                                                <label for="open_wed">@lang('app.wednesday')</label>
                                            </div>
                                            <div class="checkbox checkbox-primary col-md-2 m-b-10">
                                                <input id="open_thurs" name="office_open_days[]" value="4"
                                                       @foreach($openDays as $day)
                                                       @if($day == 4) checked @endif
                                                       @endforeach
                                                       type="checkbox">
                                                <label for="open_thurs">@lang('app.thursday')</label>
                                            </div>
                                            <div class="checkbox checkbox-primary col-md-2 m-b-10">
                                                <input id="open_fri" name="office_open_days[]" value="5"
                                                       @foreach($openDays as $day)
                                                       @if($day == 5) checked @endif
                                                       @endforeach
                                                       type="checkbox">
                                                <label for="open_fri">@lang('app.friday')</label>
                                            </div>
                                            <div class="checkbox checkbox-primary col-md-2 m-b-10">
                                                <input id="open_sat" name="office_open_days[]" value="6"
                                                       @foreach($openDays as $day)
                                                       @if($day == 6) checked @endif
                                                       @endforeach
                                                       type="checkbox">
                                                <label for="open_sat">@lang('app.saturday')</label>
                                            </div>
                                            <div class="checkbox checkbox-primary col-md-2 m-b-10">
                                                <input id="open_sun" name="office_open_days[]" value="0"
                                                       @foreach($openDays as $day)
                                                       @if($day == 0) checked @endif
                                                       @endforeach
                                                       type="checkbox">
                                                <label for="open_sun">@lang('app.sunday')</label>
                                            </div>
                                        </div>
                                    </div>
                             </div>
                             
                                </div>



                            </div>
                            

                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <div class="form-actions col-md-3  offset-md-9 ">
                            <button type="submit" id="save-form" class="btn btn-primary form-control"> @lang('app.update')</button>
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>

            </div>
        </div>


    </div>
    <!-- .row -->
    </div>

@endsection

@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js') }}"></script>


<script>
    var $insertBefore = $('#insertBefore');
    var $i = {{ count($ipAddresses) }};
    /* $('#office_end_time, #office_start_time, #halfday_mark_time').timepicker({
        @if($global->time_format == 'H:i')
        showMeridian: false
        @endif
    }); */

    $('#office_end_time, #office_start_time, #halfday_mark_time').datetimepicker({
                    format: 'LT',
                    icons: {
                        time: 'fa fa-clock',
                        date: 'fa fa-calendar',
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down',
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-check',
                        clear: 'fa fa-trash',
                        close: 'fa fa-times'
                    },
                });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.attendance-settings.update', ['1'])}}',
            container: '#editSettings',
            type: "POST",
            redirect: true,
            data: $('#editSettings').serialize()
        })
    });

    $('#radius_check').click(function(){
        if($(this).prop("checked") == true){
            $('#radiusBox').attr("style", "display: block !important");
        }
        else if($(this).prop("checked") == false){
            $('#radiusBox').attr("style", "display: none !important");
        }
    });
    $('#ip_check').click(function(){
        if($(this).prop("checked") == true){
            $('#ipBox').attr("style", "display: block !important");
        }
        else if($(this).prop("checked") == false){
            $('#ipBox').attr("style", "display: none !important");
        }
    });
    // Add More Inputs
    $('#plusButton').click(function(){

        $i = $i+1;
        var indexs = $i+1;
        $(' <div id="addMoreBox'+indexs+'" class="clearfix"> ' +
            '<div class="row">'+
            '<div class="col-md-9 pr-0"> <div class="form-label-group form-group">'+
                '<input class="form-control form-control-lg" name="ip['+$i+']" id="ip['+$i+']" type="text" value="" placeholder="*"/> <label for="ip['+$i+']" class="control-label"> @lang('modules.attendance.ipAddress')</label></div>'+
            '</div>' +
            '<div class="col-md-3"><a href="javascript:;" onclick="removeBox('+indexs+')" class="btn btn-outline-danger  btn-circle sa-params"><span class="icon-trash" aria-hidden="true"></span></a></div>' +
            '</div></div>').insertBefore($insertBefore);

    });
    // Remove fields
    function removeBox(index){
        $('#addMoreBox'+index).remove();
    }

</script>

@endpush

