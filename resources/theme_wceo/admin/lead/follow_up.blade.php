

<!--/span-->

<div class="modal-header">
     <h5 class="modal-title"><i class="ti-plus"></i> @lang("modules.lead.leadFollowUp")</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

</div>


        {!! Form::open(['id'=>'followUpForm','class'=>'ajax-form','method'=>'POST']) !!}


        <div class="modal-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">@lang("modules.lead.leadFollowUp")</label>
                    <input type="text" autocomplete="off" name="next_follow_up_date" id="next_follow_up_date" placeholder="Select Date" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label class="control-label">@lang("modules.lead.remark")</label>
                    <textarea id="followRemark" name="remark" class="form-control"></textarea>
                </div>
            </div>
        </div>
        <!--/span-->
        <div class="modal-footer">
            <div class="col-md-12">
                <div class="form-group">
                    <button class="btn btn-primary" id="postFollowUpForm"  type="button"> @lang('app.save')</button>

                    <button class="btn btn-secondary" data-dismiss="modal" type="button"> @lang('app.close')</button>
                </div>
            </div>
        </div>

    {!! Form::hidden('lead_id', $leadID) !!}
        {!! Form::close() !!}
        <!--/row-->


<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script>
    $('#next_follow_up_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });
    
    //    update task
    $('#postFollowUpForm').click(function () {
        $.easyAjax({
            url: '{{route('admin.leads.follow-up-store')}}',
            container: '#followUpForm',
            type: "POST",
            data: $('#followUpForm').serialize(),
            success: function (response) {
                $('#followUpModal').modal('hide');
                window.location.reload();
            }
        });

        return false;
    });
</script>