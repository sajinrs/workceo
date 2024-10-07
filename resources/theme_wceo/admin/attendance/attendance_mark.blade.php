<div class="modal-header" id="attendDetail">
    <h5 class="modal-title">
     <i class="fa fa-clock"></i> @lang('app.menu.attendance')
        @if($type == 'edit')
            @lang('app.details')
        @else
            @lang('app.mark')
        @endif

        @if($clock_in > 0)
            <label class="badge badge-success"><i class="fa fa-check"></i> @lang('modules.attendance.present')</label>
            <button type="button" title="Attendance Detail" id="viewAttendance" class="btn btn-info btn-sm btn-rounded view-attendance" data-attendance-id="{{$row->id}}">
                <i class="fa fa-search"></i> Detail
            </button>
        @else
            <label class="badge badge-danger"><i class="fa fa-exclamation-circle"></i> @lang('modules.attendance.absent')</label>
        @endif
    </h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

</div>

<div class="modal-body">
    <div class="portlet-body">

        @if($total_clock_in < $maxAttandenceInDay)
            {!! Form::open(['id'=>'attendance-container','class'=>'ajax-form','method'=>'POST']) !!}
            {{ csrf_field() }}
            <input type="hidden" name="attendance_date" value="{{ Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($global->date_format) }}">
            <input type="hidden" name="user_id" value="{{ $userid }}">
            @if($type == 'edit')
                <input type="hidden" name="_method" value="PUT">
            @endif
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="form-group">
                        <label>@lang('modules.attendance.clock_in')</label>
                        <input type="text" name="clock_in_time" class="form-control a-timepicker"   autocomplete="off"   id="clock-in-time" @if(!is_null($row->clock_in_time)) value="{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->clock_in_time)->timezone($global->timezone)->format($global->time_format) }}" @endif>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">@lang('modules.attendance.clock_in') IP</label>
                        <input type="text" name="clock_in_ip" id="clock-in-ip" class="form-control" value="{{ $row->clock_in_ip ?? request()->ip() }}">
                    </div>
                </div>

                @if($row->total_clock_in == 0)
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" >@lang('modules.attendance.late')</label><br />
                            <label class="switch">
                                <input type="checkbox" name="late" id="late" @if($row->late == "yes") checked @endif><span class="switch-state"></span>
                            </label> 
                        </div>
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>@lang('modules.attendance.clock_out')</label>
                        <input type="text" name="clock_out_time" id="clock-out" class="form-control b-timepicker"   autocomplete="off"
                                @if(!is_null($row->clock_out_time)) value="{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->clock_out_time)->timezone($global->timezone)->format($global->time_format) }}" @endif>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">@lang('modules.attendance.clock_out') IP</label>
                        <input type="text" name="clock_out_ip" id="clock-out-ip-{{ $row->id }}" class="form-control" value="{{ $row->clock_out_ip ?? request()->ip() }}">
                    </div>
                </div>

                @if($row->total_clock_in == 0)
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" >@lang('modules.attendance.halfDay')</label>
                            <label class="switch">
                                <input type="checkbox" name="halfday" id="halfday" @if($row->half_day == "yes") checked @endif><span class="switch-state"></span>
                            </label>                      
                        </div>
                    </div>
                @endif

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">@lang('modules.attendance.working_from')</label>
                        <input type="text" name="working_from" id="working-from" class="form-control" value="{{ $row->working_from ?? 'office' }}">
                    </div>
                </div>
            </div>


        </div>  
        
        <div class="modal-footer">
            <button type="button" id="save-category" class="btn btn-primary save-attendance"> @lang('app.save')</button>
        </div>
        
        {!! Form::close() !!}
        @else
            <div class="col-xs-12">
                <div class="alert alert-info">@lang('modules.attendance.maxColckIn')</div>
            </div>
        @endif
        
    </div>
</div>



<script>
    $('.a-timepicker, .b-timepicker').datetimepicker({
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
    
    $('#attendance-container').on('click', '.save-attendance', function () {
        @if($type == 'edit')
            var url = '{{route('admin.attendances.update', $row->id)}}';
            var modalElement = $('#attendanceModal');
        @else
            var url = '{{route('admin.attendances.storeMark')}}';
            var modalElement = $('#projectTimerModal');
        @endif
        $.easyAjax({
            url: url,
            type: "POST",
            container: '#attendance-container',
            data: $('#attendance-container').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    showTable();
                    modalElement.modal('hide');
                }
            }
        })
    });
    $('#viewAttendance').on('click',function () {
        $('#attendanceModal').modal('hide');
        var attendanceID = $(this).data('attendance-id');
        var url = '{!! route('admin.attendances.info', ':attendanceID') !!}';
        url = url.replace(':attendanceID', attendanceID);

        $('#modelHeading').html('{{__("app.menu.attendance") }}');
        $.ajaxModal('#projectTimerModal', url);
    });

</script>