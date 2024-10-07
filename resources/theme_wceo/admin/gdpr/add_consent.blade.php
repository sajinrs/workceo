<div class="modal-header">
 
    <h5 class="modal-title">Add consent</h5>
       <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
{!! Form::open(['id'=>'createConsent','class'=>'ajax-form','method'=>'POST']) !!}
<div class="modal-body">
    <div class="portlet-body">


        
        <div class="form-body">
       <div class="form-group"> 
                        <label>Name</label>
                        <input type="text" name="name" class="form-control form-control-lg">
                    </div>
            <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control form-control-lg"
                                  placeholder="Briefly describe the purpose on consent"></textarea>
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
            url: '{{route('admin.gdpr.store-consent')}}',
            container: '#createConsent',
            type: "POST",
            data: $('#createConsent').serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload();
                }
            }
        })
    });
</script>