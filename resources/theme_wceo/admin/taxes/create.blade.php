<div class="modal-header">
    <h5  class="modal-title">@lang('modules.invoices.tax')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

</div>
{!! Form::open(['id'=>'createTax','class'=>'ajax-form','method'=>'POST']) !!}
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('modules.invoices.taxName')</th>
                    <th>@lang('modules.invoices.rate') %</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($taxes as $key=>$tax)
                    <tr id="tax-{{ $tax->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($tax->tax_name) }}</td>
                        <td>{{ $tax->rate_percent }}</td>
                        <td><a href="javascript:;" data-tax-id="{{ $tax->id }}" class="btn btn-sm btn-danger btn-rounded delete-tax">@lang("app.remove")</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noRecordFound')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <hr>
        <div class="form-body">
            <div class="row">
                <div class="col-xs-6 col-md-6">
                    <div class="form-label-group form-group">                                   
                        <input placeholder="-" type="text" id="tax_name" name="tax_name" class="form-control-lg form-control">
                        <label for="tax_name" class="required">@lang('modules.invoices.taxName')</label>      
                    </div>
                </div>
                <div class="col-xs-6 col-md-6">
                    <div class="form-label-group form-group">                                   
                        <input placeholder="-" type="text" id="rate_percent" name="rate_percent" class="form-control-lg form-control">
                        <label for="rate_percent" class="required">@lang('modules.invoices.rate') %</label>      
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<div class="modal-footer">
    <div class="form-actions">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.cancel')</button>
        <button type="button" id="save-tax" class="btn btn-primary"> @lang('app.save')</button>
    </div>
</div>
{!! Form::close() !!}
<script>
    $('#createTax').submit(function () {
        $.easyAjax({
            url: '{{route('admin.taxes.store')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: $('#createTax').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
        return false;
    })

    $('body').on('click', '.delete-tax', function () {
        var id = $(this).data('tax-id');
        swal({

            title: "Are you sure?",
            text: "You will not be able to recover the deleted tax!",
            icon: "warning",
            buttons: ["No, cancel please!", "Yes, delete it!"],
            dangerMode: true
        })
        .then((willDelete) => {
            if (willDelete) {

                var url = "{{ route('admin.taxes.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            $('#tax-'+id).fadeOut();
                        }
                    }
                });
            }
        });
    });    

    
    $('#save-tax').click(function () {
        $.easyAjax({
            url: '{{route('admin.taxes.store')}}',
            container: '#createTax',
            type: "POST",
            data: $('#createTax').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>