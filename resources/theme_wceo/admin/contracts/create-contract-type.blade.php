<div class="modal-header">
    <h5 class="modal-title">@lang('modules.contracts.contractType')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>    
</div>

{!! Form::open(['id'=>'createTaskCategoryForm','class'=>'ajax-form','method'=>'POST']) !!}
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('app.name')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($contractType as $key=>$type)
                    <tr id="contract-type-{{ $type->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($type->name) }}</td>
                        <td><a href="javascript:;" data-cat-id="{{ $type->id }}" class="btn btn-sm btn-danger btn-rounded delete-category">@lang("app.remove")</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noContractType')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <hr>

        <div class="form-body">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('app.name')</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                </div>
            </div>
        </div>         
        
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" id="save-category" class="btn btn-primary"> @lang('app.save')</button>
</div>
{!! Form::close() !!}

<script>

    $('.delete-category').click(function () {
        var id = $(this).data('cat-id');
        var url = "{{ route('admin.contract-type.destroy',':id') }}";
        url = url.replace(':id', id);

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token, '_method': 'DELETE'},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    $('#contract-type-'+id).fadeOut();
                    var options = [];
                    var rData = [];
                    rData = response.data;
                    $.each(rData, function( index, value ) {
                        var selectData = '';
                        selectData = '<option value="'+value.id+'">'+value.name+'</option>';
                        options.push(selectData);
                    });

                    $('#contractType').html(options);
                    $('#contractType').find('select').select2();
                }
            }
        });
    });

    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('admin.contract-type.store-contract-type')}}',
            container: '#createTaskCategoryForm',
            type: "POST",
            data: $('#createTaskCategoryForm').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    if(response.status == 'success'){
                        console.log(response.data);
                        var options = [];
                        var rData = [];
                        rData = response.data;
                        $.each(rData, function( index, value ) {
                            var selectData = '';
                            selectData = '<option value="'+value.id+'">'+value.name+'</option>';
                            options.push(selectData);
                        });

                        $('#contractType').html(options);
                        $('#contractType').find('select').select2();
                        $('#taskCategoryModal').modal('hide');
                    }
                }
            }
        })
    });
</script>