<div class="modal-header">
    <h5 class="modal-title">@lang('modules.attendance.attendanceDetail')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> 
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" >@lang('modules.attendance.late')</label>
                    @if(count($attendances) > 0 && $attendances[0]->late == "yes") <span class="badge badge-success"><i class="fa fa-check"></i> @lang('app.yes')</span>
                    @else
                        <span class="badge badge-danger"><i class="fa fa-times"></i> @lang('app.no')</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" >@lang('modules.attendance.halfDay')</label>
                    @if(count($attendances) > 0 && $attendances[0]->half_day == "yes") <span class="badge badge-success"><i class="fa fa-check"></i> @lang('app.yes')</span>
                    @else
                        <span class="badge badge-danger"><i class="fa fa-times"></i> @lang('app.no')</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>@lang('modules.attendance.clock_in')</th>
                    <th>@lang('modules.attendance.clock_out')</th>
                    <th>@lang('app.others')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($attendances as $attendance)
                    <tr>
                        <td width="25%" class="al-center bt-border">
                            {{ $attendance->clock_in_time->timezone($global->timezone)->format($global->time_format) }}
                        </td>
                        <td width="25%" class="al-center bt-border">
                            @if(!is_null($attendance->clock_out_time)) {{ $attendance->clock_out_time->timezone($global->timezone)->format($global->time_format) }} @else - @endif
                        </td>
                        <td class="bt-border" style="padding-bottom: 5px;">
                            <strong>@lang('modules.attendance.clock_in') IP: </strong> {{ $attendance->clock_in_ip }}<br>
                            <strong>@lang('modules.attendance.clock_out') IP: </strong> {{ $attendance->clock_out_ip }}<br>
                            <strong>@lang('modules.attendance.working_from'): </strong> {{ $attendance->working_from }}<br>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noAttendanceDetail')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
