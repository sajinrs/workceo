<div class="modal-header">
    
    <h5 class="modal-title">@lang('app.update') @lang('modules.tickets.template')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'editTicketTemplate','class'=>'ajax-form','method'=>'PUT']) !!}
         <div class="form-body">

            <div class="form-group">
                 <label>@lang('modules.tickets.templateHeading')</label>
                    <input type="text" name="reply_heading" id="reply_heading" value="{{ $template->reply_heading }}" class="form-control">
            </div>

             <div class="form-group">
                 <label>@lang('modules.tickets.templateText')</label>
                  <textarea name="reply_text" id="reply_text" class="form-control" rows="10">{{ $template->reply_text }}</textarea>
            </div>

            
                </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="modal-footer">
          <button type="button" id="update-template" class="btn btn-primary"> @lang('app.save')</button>
</div>


<script>

    $('#editTicketTemplate').on('submit', function(e) {
        return false;
    })

    $('#update-template').click(function () {
        $.easyAjax({
            url: '{{route('admin.replyTemplates.update', $template->id)}}',
            container: '#editTicketTemplate',
            type: "PUT",
            data: $('#editTicketTemplate').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>