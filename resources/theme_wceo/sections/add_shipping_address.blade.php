<div class="modal-header">
    <h5 class="modal-title"> @lang('modules.clients.addShippingAddress')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> 
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'addShippingAddress','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row ">
                <div class="col-sm-12 m-b-10">
                    <div class="form-group">
                        <label for="shipping_address">@lang('modules.clients.shippingAddress')</label>
                        <textarea class="form-control" name="shipping_address" id="shipping_address" rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<div class="modal-footer">
    <div class="form-actions">
        <button type="button" id="save-shipping-address" class="btn btn-primary"> @lang('app.save')</button>
    </div>
</div>

<script>
    $(function () {
        $('#save-shipping-address').click(function() {
            @php
                $url = auth()->user()->hasRole('admin') ? 'admin.all-invoices.addShippingAddress' : 'member.all-invoices.addShippingAddress'
            @endphp

            let url = "{{ route($url, $clientId) }}"
    
            $.easyAjax({
                url: url,
                type: 'POST',
                data: $('#addShippingAddress').serialize(),
                success: function (response) {
                    $('#invoiceUploadModal').modal('hide');
                    loadTable();
                }
            })
        })
    });
</script>