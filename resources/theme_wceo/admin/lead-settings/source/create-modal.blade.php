<div class="modal-header">
    <h5 class="modal-title">@lang('app.addNew') @lang('modules.lead.leadSource')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

</div>   {!! Form::open(['id'=>'addLeadSource','class'=>'ajax-form','method'=>'POST']) !!}

<div class="modal-body">
    <div class="portlet-body">

        <div class="form-body">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('modules.lead.leadSource')</label>
                        <input type="text" name="type" id="type" class="form-control">
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" id="save-group" class="btn btn-primary"> @lang('app.save')</button>

</div>
{!! Form::close() !!}
<script>

    // Store lead source
    $('#save-group').click(function () {
        $.easyAjax({
            url: '{{route('admin.lead-source-settings.store')}}',
            container: '#addLeadSource',
            type: "POST",
            data: $('#addLeadSource').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    var options = [];
                    var rData = [];
                    rData = response.optionData;
                    $('#source_id').html(rData);
                    $("#source_id").select2();
                    $('#projectCategoryModal').modal('hide');
                }
            }
        })
    });
</script>