<div class="modal-header">
   
    <h5 class="modal-title">@lang('modules.tasks.uplodedFiles')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="" id="list">
            <ul class="list-group" id="files-list">
                @forelse($taskFiles as $file)
                <li class="list-group-item">


                            {{ $file->filename }}

                    <span class="pull-right">

                        <small class="text-muted m-r-10">{{ $file->created_at->diffForHumans() }}</small>




                                <a target="_blank" href="{{ $file->file_url }}"
                                   data-toggle="tooltip" data-original-title="View"
                                   class="badge badge-info"><i
                                            class="fa fa-search"></i></a>


                            @if(is_null($file->external_link))
                            <a href="{{ route('admin.task-files.download', $file->id) }}"
                               data-toggle="tooltip" data-original-title="Download"
                               class="badge badge-dark"><i
                                        class="fa fa-download"></i></a>
                            @endif

                            <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{ $file->id }}"
                               data-pk="list" class="badge badge-danger sa-delete"><i class="fa fa-times"></i></a>
                            {{--<span class="clearfix m-l-10">{{ $file->created_at->diffForHumans() }}</span>--}}
                        </span>

                </li>
                @empty
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-12">
                                @lang('messages.noFileUploaded')
                            </div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<script>
    $('body').on('click', '.sa-delete', function () {
        var id = $(this).data('file-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted file!",
            dangerMode: true,
            icon: 'warning',
            buttons: {
                cancel: "No, cancel please!",
                confirm: {
                    text: "Yes, delete it!",
                    value: true,
                    visible: true,
                    className: "danger",
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('member.task-files.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $('#totalUploadedFiles').html(response.totalFiles);
                            $('#list ul.list-group').html(response.html);
                        }
                    }
                });
            }
        });
    });



    </script>
