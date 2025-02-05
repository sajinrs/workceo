<div class="modal-header">
    <h5 class="modal-title">@lang('modules.projects.projectCategory')</h5>
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
                        <td colspan="3">@lang('messages.noProjectCategory')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <hr>
         <div class="form-body">
            <div class="row">
                <div class="col-xs-12 col-12">
                    <div class="form-label-group form-group">
                        <input type="text" name="category_name" id="category_name" class="form-control form-control-lg" placeholder="*">
                        <label for="category_name" class="col-form-label required">@lang('modules.projectCategory.categoryName')</label>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<div class="modal-footer">
    <div class="form-actions">
        <button type="button" id="save-category" class="btn btn-primary"> @lang('app.save')</button>
    </div>
</div>
{!! Form::close() !!}
<script>

    
    $('.delete-category').click(function () {
        var id = $(this).data('cat-id');

        var buttons = {
            cancel: "No, cancel please!",
            confirm: {
                text: "Yes, delete it!",
                value: 'confirm',
                visible: true,
                className: "danger",
            }
        };

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted Category!",
            dangerMode: true,
            icon: 'warning',
            buttons: buttons
        }).then(function (isConfirm) {
            if (isConfirm == 'confirm') {

                var url = "{{ route('admin.projectCategory.destroy',':id') }}";
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

                            $('#category_id').html(options);
                            $('#category_id').select2();
                        }
                    }
                });
            }
        });
    });

    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('admin.projectCategory.store-cat')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: $('#createProjectCategory').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    if(response.status == 'success'){
                       // console.log(response.data);
                        var options = [];
                        var rData = [];
                        rData = response.data;
                        $.each(rData, function( index, value ) {
                            var selectData = '';
                            selectData = '<option value="'+value.id+'">'+value.category_name+'</option>';
                            options.push(selectData);
                        });

                        $('#category_id').html(options);
                        $('#category_id').select2();
                        $('#projectCategoryModal').modal('hide');
                    }
                }
            }
        })
    });
</script>