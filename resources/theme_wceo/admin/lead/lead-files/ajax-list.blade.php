@forelse($lead->files as $file)
    <li class="list-group-item justify-content-between align-items-center">
        {{ $file->filename }}
        <span class="pull-right">
   <small class="text-muted m-r-10">{{ $file->created_at->diffForHumans() }}</small>

        <a target="_blank" href="{{ $file->file_url }}" data-toggle="tooltip" data-original-title="View" class="btn btn-small badge-info"><i class="fa fa-search m-r-0"></i></a>
        &nbsp;&nbsp;
        <a href="{{ route('admin.lead-files.download', $file->id) }}" data-toggle="tooltip" data-original-title="Download" class="btn btn-small badge-dark"><i class="fa fa-download"></i></a>
        &nbsp;&nbsp;
        <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{ $file->id }}" class="btn btn-small badge-danger sa-params" data-pk="list"><i class="fa fa-times"></i></a>
            </span>
    </li>
@empty
    <li class="list-group-item d-flex justify-content-between align-items-center">
        @lang('messages.noFileUploaded')</li>
@endforelse