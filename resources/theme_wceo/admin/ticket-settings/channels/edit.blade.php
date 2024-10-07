<div class="modal-header">
  
    <h5 class="modal-title">@lang('app.update') @lang('app.menu.ticketChannel')</h5>
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'editTicketType','class'=>'ajax-form','method'=>'PUT']) !!}
          <div class="form-body">

            <div class="form-group">
                 <label>@lang('modules.tickets.channelName')</label>
                    <input type="text" name="channel_name" id="channel_name" value="{{ $channel->channel_name }}" class="form-control">
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
            url: '{{route('admin.ticketChannels.update', $channel->id)}}',
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