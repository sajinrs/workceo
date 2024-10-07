<div class="modal-header">
    <h5  class="modal-title">@lang('app.category')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
{!! Form::open(['id'=>'createProjectCategory','class'=>'ajax-form','method'=>'POST']) !!}
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('modules.projectCategory.categoryName')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($categories as $key=>$category)
                    <tr id="cat-{{ $category->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($category->category_name) }}</td>
                        <td><a href="javascript:;" data-cat-id="{{ $category->id }}" class="btn btn-sm btn-danger btn-rounded delete-category">@lang("app.remove")</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noProductCategory')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <hr>
        
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-label-group form-group">                                   
                        <input placeholder="-" type="text" id="category_name" name="category_name" class="form-control-lg form-control">
                        <label for="category_name" class="required">@lang('modules.projectCategory.categoryName')</label>      
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>
</div>

<div class="modal-footer">
    <div class="form-actions">
        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('app.cancel')</button>
        <button type="button" id="save-category" class="btn btn-primary"> @lang('app.save')</button>
    </div>
</div>
{!! Form::close() !!}

<script>

$('body').on('click', '.delete-category', function () {
    var id = $(this).data('cat-id');
    swal({

        title: "Are you sure?",
        text: "You will not be able to recover the deleted category!",
        icon: "{{ asset('img/warning.png')}}",
        buttons: ["CANCEL", "DELETE"],
        dangerMode: true
    })
    .then((willDelete) => {
        if (willDelete) {

            var url = "{{ route('admin.productCategory.destroy',':id') }}";
            url = url.replace(':id', id);

            var token = "{{ csrf_token() }}";

            $.easyAjax({
                type: 'POST',
                url: url,
                data: {'_token': token, '_method': 'DELETE'},
                success: function (response) {
                    if (response.status == "success") {
                        $.unblockUI();
                        $('#cat-'+id).fadeOut();
                        var options = [];
                        var rData = [];
                        rData = response.data;

                        $.each(rData, function( index, value ) {
                            var selectData = '';
                            selectData = '<option value="'+value.id+'">'+value.category_name+'</option>';
                            options.push(selectData);
                        });

                        options.splice(0, 0, '<option value="">Select Category...</option>');
                        $('#category_id').html(options);
                    }
                }
            });
        }
    });
});    
    
    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('admin.productCategory.store')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: $('#createProjectCategory').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    var options = [];
                    var rData = [];
                    rData = response.data;

                    $.each(rData, function( index, value ) {
                        var selectData = '';
                        selectData = '<option value="'+value.id+'">'+value.category_name+'</option>';
                        options.push(selectData);
                    });

                    options.splice(0, 0, '<option value="">Select Category...</option>');
                    $('#category_id').html(options);
                    $('#taxModal').modal('hide');
                }
            }
        })
    });
</script>