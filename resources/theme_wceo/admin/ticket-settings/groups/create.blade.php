<div class="modal-header">
    <h5 class="modal-title">@lang('modules.tickets.manageGroups')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('modules.tickets.group')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($groups as $key=>$group)
                    <tr id="group-{{ $group->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($group->group_name) }}</td>
                        <td><a href="javascript:;" data-group-id="{{ $group->id }}" class="btn btn-sm btn-danger btn-outline btn-rounded delete-group"><i class="fa fa-times"></i> @lang("app.remove")</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noGroupAdded')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <hr>
        {!! Form::open(['id'=>'createTicketGroup','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('modules.tickets.groupName')</label>
                        <input type="text" name="group_name" id="group_name" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-group" class="btn btn-primary"> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $('#createTicketGroup').on('submit', function(e) {
        return false;
    })

    $('.delete-group').click(function () {
        var id = $(this).data('group-id');
            swal({
                title: "Are you sure?",
                text: "This will delete the group from the list.",
                icon: "warning",
                buttons: ["No, cancel please!", "Yes, delete it!"],
                dangerMode: true
        })
        .then((willDelete) => {
                if (willDelete) {
                    var url = "{{ route('admin.ticket-groups.destroy',':id') }}";
                    url = url.replace(':id', id);
                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        
                        success: function (response) {
                            if (response.status == "success") 
                            {
                                $.unblockUI();
                                window.location.reload();
                            }
                        }
                    });
                }
            });
        }); 

    $('#save-group').click(function () {
        $.easyAjax({
            url: '{{route('admin.ticket-groups.store')}}',
            container: '#createTicketGroup',
            type: "POST",
            data: $('#createTicketGroup').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>