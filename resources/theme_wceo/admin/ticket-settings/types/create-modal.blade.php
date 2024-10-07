<div class="modal-header">
   
   
    <h5 class="modal-title">@lang('app.addNew') @lang('modules.tickets.ticketType')</h5>
     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'addTicketType','class'=>'ajax-form','method'=>'POST']) !!}
          <div class="form-body">

            <div class="form-group">
                 <label>@lang('modules.tickets.ticketType')</label>
                 <input type="text" name="type" id="type" class="form-control">
            </div>
                </div>
      

    </div>
</div>
<div class="modal-footer">
          <button type="button" id="save-group" class="btn btn-primary"> @lang('app.save')</button>
</div>
  {!! Form::close() !!}


<script>

    $('#addTicketType').on('submit', function(e) {
        return false;
    })

    $('#save-group').click(function () {
        $.easyAjax({
            url: '{{route('admin.ticketTypes.store')}}',
            container: '#addTicketType',
            type: "POST",
            data: $('#addTicketType').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    setValueInForm('type_id', response.optionData);
                    $.unblockUI();
                    $('#ticketModal').modal('hide');
                }
            }
        })
    });
</script>