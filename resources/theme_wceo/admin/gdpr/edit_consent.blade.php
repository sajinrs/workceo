<div class="modal-header">
<h5 class="modal-title">Edit consent</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

{!! Form::open(['id'=>'editConsent','class'=>'ajax-form','method'=>'PUT']) !!}
<div class="modal-body">
    <div class="portlet-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control form-control-lg" value="{{$consent->name}}">
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control form-control-lg"
                                  placeholder="Briefly describe the purpose on consent">{{$consent->description}}</textarea>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<div class="modal-footer">

    <div class="form-actions">
        <div class=" text-right">
        <button type="button" id="save-consent" class="btn btn-primary"> @lang('app.save')</button>
        </div>
    </div>
</div>
{!! Form::close() !!}

<script>

    
    $('#save-consent').click(function () {
        $.easyAjax({
            url: '{{route('admin.gdpr.update-consent', $consent->id)}}',
            container: '#editConsent',
            type: "POST",
            data: $('#editConsent').serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload();
                }
            }
        })
    });
</script>