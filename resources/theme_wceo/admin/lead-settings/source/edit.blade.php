<div class="modal-header">
    <h5 class="modal-title">@lang('app.update') @lang('modules.lead.leadSource')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    
</div>

{!! Form::open(['id'=>'editLeadSource','class'=>'ajax-form','method'=>'PUT']) !!}
<div class="modal-body">
    <div class="portlet-body">

        
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('modules.lead.leadSource')</label>
                        <input type="text" name="type" id="type" value="{{ $source->type }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal-footer">
        <button type="button" id="save-group" class="btn btn-primary"> @lang('app.save')</button>
    </div>

    {!! Form::close() !!}

<script>

    $('#editLeadSource').on('submit', function(e) {
        return false;
    })

    $('#save-group').click(function () {
        $.easyAjax({
            url: '{{route('admin.lead-source-settings.update', $source->id)}}',
            container: '#editLeadSource',
            type: "PUT",
            data: $('#editLeadSource').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>