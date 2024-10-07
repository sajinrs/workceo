<div class="modal-header">
    <h5 class="modal-title"><i class="fa fa-clock-o"></i> @lang('app.updateContactDetails')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

</div>
{!! Form::open(['id'=>'updateContact','class'=>'ajax-form','method'=>'PUT']) !!}

<div class="modal-body">
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-12">


                <div class="form-body">
                    <div class="row m-t-30">
                        <div class="col-md-4 ">
                            <div class="form-label-group form-group">
                                <input type="text" name="contact_name" id="contact_name" class="form-control-lg form-control" value="{{ $contact->contact_name }}">
                                <label for="contact_name">Contact Name</label>
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="form-label-group form-group">
                                <input id="phone" name="phone" type="tel" class="form-control-lg form-control" value="{{ $contact->phone }}">
                                <label for="phone">Phone</label>
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="form-label-group form-group">
                                <input id="email" name="email" type="email" class="form-control-lg form-control" value="{{ $contact->email }}" >
                                <label for="email">Email</label>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>

    </div>
</div>
{!! Form::close() !!}
<div class="modal-footer">
    <div class="form-actions  text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

        <button type="button" id="update-form" class="btn btn-primary">Save</button>
    </div>
</div>

<script>

    $('#update-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.contacts.update', $contact->id)}}',
            container: '#updateContact',
            type: "POST",
            data: $('#updateContact').serialize(),
            success: function (response) {
                $('#editContactModal').modal('hide');
                table._fnDraw();
            }
        })
    });
</script>