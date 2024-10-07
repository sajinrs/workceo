<div class="modal-header">
    <h5 class="modal-title"><i class="fa fa-clock"></i> @lang('app.menu.attendance') @lang('app.details')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> 
</div>
<div class="modal-body">
<div class="portlet-body">
    <div class="row">
        <div class="col-md-6">
                    <h4>@lang('app.menu.attendance') <small class="text-muted">{{ $startTime->format($global->date_format) }}</small></h4>
                    <div class="punch-det">
                        <h6>@lang('modules.attendance.clock_in')</h6>
                        <p>{{ $startTime->format($global->time_format) }}</p>
                    </div>
                    <div class="punch-info">
                        <div class="punch-hours">
                            <span>{{ $totalTime }} hrs</span>
                        </div>
                    </div>
                    <div class="punch-det">
                        <h6>@lang('modules.attendance.clock_out')</h6>
                        <p>{{ $endTime->format($global->time_format) }}
                            @if (isset($notClockedOut))
                                (@lang('modules.attendance.notClockOut'))
                            @endif
                        </p>
                    </div>

        </div>
        <div class="col-md-6">
                    <h5 class="card-title">@lang('modules.employees.activity')</h5>

                    @foreach ($attendanceActivity->reverse() as $item)
                        <div class="row res-activity-box" id="timelogBox{{ $item->aId }}">

                        <div class="col-md-5 timeline-small attendance_timeline">
                            <div class="media">
                                <div class="timeline-round m-r-20 timeline-line-1 bg-primary"></div>
                                <div class="media-body">
                                    <p class="mb-0">@lang('modules.attendance.clock_in')</p>
                                    <p class="res-activity-time">
                                        <i class="fa fa-clock"></i> {{ $item->clock_in_time->timezone($global->timezone)->format($global->time_format) }}.
                                    </p>
                                </div>
                            </div>
                            <div class="media">
                                <div class="timeline-round m-r-20 timeline-line-1 bg-primary"></div>
                                <div class="media-body">
                                    <p class="mb-0">@lang('modules.attendance.clock_out')</p>
                                    <p class="res-activity-time">
                                        <i class="fa fa-clock"></i>
                                        @if (!is_null($item->clock_out_time))
                                            {{ $item->clock_out_time->timezone($global->timezone)->format($global->time_format) }}.
                                        @else
                                            @lang('modules.attendance.notClockOut')
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                            

                            <div class="col-md-3">
                                <label class="badge badge-info"><a href="javascript:;" onclick="editAttendance({{ $item->aId }})" style="display: inline-block;" id="attendance-edit" data-attendance-id="{{ $item->aId }}" ><i class="fa fa-edit"></i></a> </label>
                                <label class="badge badge-danger"><a href="javascript:;" onclick="deleteAttendance({{ $item->aId }})" style="display: inline-block;" id="attendance-edit" data-attendance-id="{{ $item->aId }}" ><i class="fa fa-times"></i></a></label>
                            </div>
                        </div>
                    @endforeach

        </div>
    </div>
</div>

</div>
<script>
    function deleteAttendance(id){
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted user!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.attendances.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();

                            $('#timelogBox'+id).remove();
                            showTable();
                            $('#projectTimerModal').modal('hide');
                        }
                    }
                });
            }
        });
    }

</script>