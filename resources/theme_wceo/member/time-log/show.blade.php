
<div class="modal-header">
    <h5 class="modal-title"><i class="icofont icofont-clock-time"></i> @lang('modules.timeLogs.stopTimer')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

<div class="modal-body">
<div class="form-body">
            {!! Form::open(['id'=>'stopTimer','class'=>'ajax-form','method'=>'PUT']) !!}

            {!! Form::hidden('timeId', $timeLog->id) !!}
            @if(!is_null($timeLog->task_id))
            <h5>@lang('app.task')# {{ $timeLog->task_id }} - {{ ucwords($timeLog->task->heading) }}</h5>
            @else
                <h5>@lang('app.project')# {{ $timeLog->project_id }} - {{ ucwords($timeLog->project->project_name) }}</h5>
            @endif
            <p>
                <strong>@lang('modules.timeLogs.memo') :-</strong> {!!  ucfirst($timeLog->memo) !!}
            </p>
            <hr>
            
                <div class="row">
                    <div class="col-md-12 text-center">
                        <span id="active-timer-modal">{{ $timeLog->timer }}</span>
                    </div>
                    <!--/span-->

                </div>
                <!--/row-->

            <hr>
            <div class="form-actions">
                <button type="button" id="stop-timer-btn" class="btn btn-block btn-danger"><i class="fa fa-times"></i> @lang('modules.timeLogs.stopTimer')</button>
            </div>
            {!! Form::close() !!}

            <p>&nbsp;</p>
        
</div>
</div>

<script>

    $(document).ready(function(e) {
        var $worked = $("#active-timer-modal");
        function updateTimer() {
            var myTime = $worked.html();
            var ss = myTime.split(":");
//            console.log(ss);

            var hours = ss[0];
            var mins = ss[1];
            var secs = ss[2];
            secs = parseInt(secs)+1;

            if(secs > 59){
                secs = '00';
                mins = parseInt(mins)+1;
            }

            if(mins > 59){
                secs = '00';
                mins = '00';
                hours = parseInt(hours)+1;
            }

            if(hours.toString().length < 2) {
                hours = '0'+hours;
            }
            if(mins.toString().length < 2) {
                mins = '0'+mins;
            }
            if(secs.toString().length < 2) {
                secs = '0'+secs;
            }
            var ts = hours+':'+mins+':'+secs;

//            var dt = new Date();
//            dt.setHours(ss[0]);
//            dt.setMinutes(ss[1]);
//            dt.setSeconds(ss[2]);
//            var dt2 = new Date(dt.valueOf() + 1000);
//            var ts = dt2.toTimeString().split(" ")[0];
            $worked.html(ts);
            setTimeout(updateTimer, 1000);
        }

        setTimeout(updateTimer, 1000);
    });

    //    save new task
    $('#stop-timer-btn').click(function () {
        $.easyAjax({
            url: '{{route('member.time-log.update', $timeLog->id)}}',
            container: '#stopTimer',
            type: "POST",
            data: $('#stopTimer').serialize(),
            success: function (data) {
                $('#timer-section').html(data.html);
                $('#projectTimerModal').modal('hide');
                $('#projectTimerModal .modal-body').html('Loading...');
            }
        })
    });

</script>
