@forelse($employeeDocs as $key=>$employeeDoc)
    <tr>
        <td>{{ $key+1 }}</td>
        <td>{{ ucwords($employeeDoc->name) }}</td>
        <td>
            <a href="{{ route('admin.employee-docs.download', $employeeDoc->id) }}"
               data-toggle="tooltip" data-original-title="Download"
               class="badge badge-dark"><i class="fa fa-download"></i></a>
            <a target="_blank" href="{{ asset_url('employee-docs/'.$employeeDoc->user_id.'/'.$employeeDoc->hashname) }}"
               data-toggle="tooltip" data-original-title="View"
               class="badge badge-info"><i class="fa fa-search"></i></a>
            <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{ $employeeDoc->id }}"
               data-pk="list" class="badge badge-danger sa-params"><i class="fa fa-times"></i></a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3">@lang('messages.noDocsFound')</td>
    </tr>
@endforelse
