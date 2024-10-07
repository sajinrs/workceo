<div class="modal-header">
   
    <h5 class="modal-title">@lang('app.update') @lang('modules.tickets.ticketType')</h5>
     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="portlet-body">

 {!! Form::open(['id'=>'editTicketType','class'=>'ajax-form','method'=>'POST']) !!}
          <div class="form-body">

            <div class="form-group">
                 <label>@lang('modules.tickets.ticketType')</label>
                  <input type="text" name="type" id="type" value="{{ $type->type }}" class="form-control">
            </div>
                </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="modal-footer">
          <button type="button" id="save-group" class="btn btn-primary"> @lang('app.save')</button>
</div>



<script>

    $('#editTicketType').on('submit', function(e) {
        return false;
    })

    $('#save-group').click(function () {
        $.easyAjax({
            url: '{{route('admin.ticketTypes.update', $type->id)}}',
            container: '#editTicketType',
            type: "PUT",
            data: $('#editTicketType').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>