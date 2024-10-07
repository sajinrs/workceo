<div class="modal-header">
    <h5 class="modal-title">@lang('app.update') @lang('modules.lead.leadStatus')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

{!! Form::open(['id'=>'editLeadStatus','class'=>'ajax-form','method'=>'PUT']) !!}
<div class="modal-body">
    <div class="portlet-body">

        
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('modules.lead.leadStatus')</label>
                        <input type="text" name="type" id="type" value="{{ $status->type }}" class="form-control">
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

    $('#editLeadStatus').on('submit', function(e) {
        return false;
    })

    $('#save-group').click(function () {
        $.easyAjax({
            url: '{{route('admin.lead-status-settings.update', $status->id)}}',
            container: '#editLeadStatus',
            type: "PUT",
            data: $('#editLeadStatus').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>