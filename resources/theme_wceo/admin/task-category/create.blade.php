<div class="modal-header">
    <h5 class="modal-title">@lang('modules.taskCategory.manageTaskCategory')</h5>
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
                    <th>@lang('modules.taskCategory.categoryName')</th>
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
                        <td colspan="3">@lang('messages.noTaskCategory')</td>
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
                        <input type="text" name="category_name" id="category_name" class="form-control form-control-lg" placeholder="*">
                        <label for="category_name" class="col-form-label required">@lang('modules.taskCategory.categoryName')</label>
                    </div>

                    
                </div>
            </div>
        </div>


    </div>
</div>
<div class="modal-footer">
    <div class="form-actions">
        <button type="button" id="save-category" class="btn btn-primary"> @lang('app.save')</button>
        <button type="button" class="btn btn-outline-primary gray" data-dismiss="modal">Close</button>
    </div>
</div>
{!! Form::close() !!}
<script>
    $('#createTaskCategoryForm').submit(function () {
        $.easyAjax({
            url: '{{route('admin.taskCategory.store')}}',
            container: '#createTaskCategoryForm',
            type: "POST",
            data: $('#createTaskCategoryForm').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
        return false;
    })


    $('.delete-category').click(function () {
        var id = $(this).data('cat-id');

        var buttons = {
            cancel: "CANCEL",
            confirm: {
                text: "DELETE",
                value: 'confirm',
                visible: true,
                className: "danger",
            }
        };

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted Category!",
            dangerMode: true,
            icon: "{{ asset('img/warning.png')}}",
            buttons: buttons
        }).then(function (isConfirm) {
            if (isConfirm == 'confirm') {

                var url = "{{ route('admin.taskCategory.destroy',':id') }}";
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
                        }
                    }
                });
            }
        });
    });

    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('admin.taskCategory.store')}}',
            container: '#createTaskCategoryForm',
            type: "POST",
            data: $('#createTaskCategoryForm').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>