<div class="modal-header">
    <h5 class="modal-title"><i class="fa fa-clock-o"></i> Update Time Log</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

</div>
{!! Form::open(['id'=>'updateTime','class'=>'ajax-form','method'=>'PUT']) !!}

<div class="modal-body">
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="form-group form-label-group">
                                @if(isset($tasks))
                                    <select placeholder="-" class="form-control form-control-lg select2" name="task_id"
                                            id="task_id" data-style="form-control form-control-lg">
                                        @forelse($tasks as $task)
                                            <option @if($task->id == $timeLog->task_id)
                                                    selected
                                                    @endif value="{{ $task->id }}">{{ ucfirst($task->heading) }}</option>
                                        @empty
                                            <option value="">@lang('messages.noTaskAddedToProject')</option>
                                        @endforelse
                                    </select>
                                    <label for="task_id">@lang('modules.timeLogs.task')</label>

                                @else
                                    <select placeholder="-" class="form-control form-control-lg select2" name="project_id" data-placeholder="@lang('app.selectProject')"  id="project_id_edit">
                                        <option value=""></option>
                                        @foreach($timeLogProjects as $projectData)
                                            <option @if($timeLog->project->id == $projectData->id) selected @endif value="{{ $projectData->id }}">{{ ucwords($projectData->project_name) }}</option>
                                        @endforeach
                                    </select>
                                    <label for="project_id_edit">@lang('app.project')</label>

                                @endif

                            </div>
                        </div>
                        @if(isset($project))
                            <div class="col-md-6 " id="editEmployeeBox">
                                <div class="form-group form-label-group">
                                    <select placeholder="-" class="form-control form-control-lg select2" name="user_id" id="user_id_edit">
                                        @forelse($project->members as $member)
                                            <option
                                                    @if($member->user->id == $timeLog->user_id)
                                                    selected
                                                    @endif
                                                    value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                                        @empty
                                            <option value="">No member added to project</option>
                                        @endforelse
                                    </select>
                                    <label for="user_id_edit">@lang('modules.timeLogs.employeeName')</label>

                                </div>
                            </div>
                        @endif

                    </div>
                    <div class="row">
                        <div class="col-md-3 ">
                            <div class="form-group form-label-group">
                                <input placeholder="-" id="start_date" name="start_date" type="text" class="form-control form-control-lg"
                                       value="{{ $timeLog->start_time->timezone($global->timezone)->format($global->date_format) }}">
                                <label for="start_date">@lang('app.startDate')</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-label-group bootstrap-timepicker timepicker">
                                <input placeholder="-" type="text" name="start_time" id="start_time"
                                       value="{{ $timeLog->start_time->timezone($global->timezone)->format('h:i A') }}" class="form-control form-control-lg">
                                <label for="start_time">@lang('modules.timeLogs.startTime')</label>
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <div class="form-group form-label-group">
                                <input placeholder="-" id="end_date" name="end_date" type="text" class="form-control form-control-lg"
                                       @if(!is_null($timeLog->end_time)) value="{{ $timeLog->end_time->timezone($global->timezone)->format($global->date_format) }}" @else value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}" @endif>
                                <label for="end_date">@lang('app.endDate')</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-label-group bootstrap-timepicker timepicker">
                               <input placeholder="-" type="text" name="end_time" @if(!is_null($timeLog->end_time)) value="{{ $timeLog->end_time->timezone($global->timezone)->format('h:i A') }}" @endif
                                id="end_time" class="form-control form-control-lg">
                                <label for="end_time">@lang('modules.timeLogs.endTime')</label>
                            </div>
                        </div>
                        <div class="col-md-4 d-none">
                            <label for="">@lang('modules.timeLogs.totalHours')</label>

                            <p id="total_time" class="form-control-static">
                                <?php
                                $datetime1 = new DateTime($timeLog->start_time);
                                $datetime2 = new DateTime($timeLog->end_time);
                                $interval = $datetime1->diff($datetime2);
                                $hours = $interval->format('%h');
                                $days = $interval->format('%d');
                                if ($interval->format('%d') > 0) {
                                    $hours = $hours + $days * 24;
                                }
                                echo $hours . " Hours " . $interval->format('%i') . " Minutes";
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-label-group">
                                <input placeholder="-" type="text" name="memo" id="memo" class="form-control form-control-lg" value="{{ $timeLog->memo }}">
                                <label for="memo">@lang('modules.timeLogs.memo')</label>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="form-actions">
        <button type="button" id="update-form" class="btn btn-primary">@lang('app.save')
        </button>
    </div>
</div>

{!! Form::close() !!}

<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js')}}"></script>

<script>
    @if($logTimeFor->log_time_for == 'task')
    $('#editEmployeeBox').hide();
    @else
    $('#editEmployeeBox').show();
    @endif
    $("#project_id_edit, #user_id_edit").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('#project_id_edit').change(function () {
        var id = $(this).val();
        var url = '{{route('admin.all-time-logs.members', ':id')}}';
        url = url.replace(':id', id);
        $('#editEmployeeBox').show();
        $.easyAjax({
            url: url,
            type: "GET",
            redirect: true,
            success: function (data) {
                $('#user_id_edit').html(data.html);
                $('#user_id_edit').select2();
            }
        })
    });
    
    jQuery('#updateTime #start_date,#updateTime #end_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    }).on('hide', function (e) {
        calculateTime();
    });
    $('#updateTime #start_time, #updateTime #end_time').datetimepicker({
        format: 'LT',
        icons: {
            time: 'fa fa-clock-o',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-check',
            clear: 'fa fa-trash',
            close: 'fa fa-times'
        },
        //onChangeDateTime:calculateTime
    }).on('dp.change', function (event) {
        calculateTime();
    });
    function calculateTime() {
        var startDate = $('#updateTime #start_date').val();
        var endDate = $('#updateTime #end_date').val();
        var startTime = $("#updateTime #start_time").val();
        var endTime = $("#updateTime #end_time").val();

        var timeStart = new Date(startDate + " " + startTime);
        var timeEnd = new Date(endDate + " " + endTime);

        var diff = (timeEnd - timeStart) / 60000; //dividing by seconds and milliseconds

        var minutes = diff % 60;
        var hours = (diff - minutes) / 60;

        if (hours < 0 || minutes < 0) {
            var numberOfDaysToAdd = 1;
            timeEnd.setDate(timeEnd.getDate() + numberOfDaysToAdd);
            var dd = timeEnd.getDate();

            if (dd < 10) {
                dd = "0" + dd;
            }

            var mm = timeEnd.getMonth() + 1;

            if (mm < 10) {
                mm = "0" + mm;
            }

            var y = timeEnd.getFullYear();

            $('#updateTime #end_date').val(mm + '/' + dd + '/' + y);
            calculateTime();
        } else {
            $('#updateTime #total_time').html(hours + "Hrs " + minutes + "Mins");
        }

//        console.log(hours+" "+minutes);
    }

    $('#update-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.time-logs.update', $timeLog->id)}}',
            container: '#updateTime',
            type: "POST",
            data: $('#updateTime').serialize(),
            success: function (response) {
                $('#editTimeLogModal').modal('hide');
                table._fnDraw();
            }
        })
    });
</script>