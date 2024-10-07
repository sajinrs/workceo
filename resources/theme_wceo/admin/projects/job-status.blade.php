<div class="modal-header">
    <h5  class="modal-title">Update Status</h5>
    <button class="close btn-close-outside close-btn-sm-box" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30" /></button>

</div>
{!! Form::open(['id'=>'updateStatus','class'=>'ajax-form','method'=>'POST']) !!}
<div class="modal-body">
    <div class="portlet-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Job Status</label>
                        <select class="form-control select2" name="job_status" data-style="form-control">
                            <option data-percentage="0" data-projstatus="not started" @if($project[0]->job_status == 'schedule') selected @endif value="schedule">Schedule</option>
                            <option data-percentage="17" data-projstatus="not started" @if($project[0]->job_status == 'omw') selected @endif value="omw">En Route</option>
                            <option data-percentage="33" data-projstatus="in progress" @if($project[0]->job_status == 'start') selected @endif value="start">Start</option>
                            <option data-percentage="50" data-projstatus="in progress" @if($project[0]->job_status == 'finish') selected @endif value="finish">Finish</option>
                            <option data-percentage="66" data-projstatus="awaiting invoice" @if($project[0]->job_status == 'invoice') selected @endif value="invoice">Invoice</option>
                            <option data-percentage="83" data-projstatus="awaiting pay" @if($project[0]->job_status == 'paid') selected @endif value="paid">Paid</option>
                            <option data-percentage="100" data-projstatus="finished" @if($project[0]->job_status == 'closed') selected @endif value="closed">Closed</option>
                        </select>
                    </div>
                </div>
                
            </div>
        </div>


    </div>
</div>
<div class="modal-footer">
<div class="form-actions">
    <button type="button" id="save-status" class="btn btn-primary"> @lang('app.save')</button>
</div>
</div>
{!! Form::close() !!}
<script> 

    $('#save-status').click(function () {
        var status     = $('#updateStatus option:selected').val();
        var projstatus = $('#updateStatus option:selected').data('projstatus');
        var percentage = $('#updateStatus option:selected').data('percentage');
        var token = "{{ csrf_token() }}";
        var id = '{{$project[0]->id}}';
        var url = '{{ route('admin.projects.update-job-status', ":id")}}';
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            container: '#updateStatus',
            type: "POST",
            data: {'_token': token, 'percentage':percentage, 'status':status, 'projstatus':projstatus},
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });

    $('select').select2({minimumResultsForSearch: -1});
</script>