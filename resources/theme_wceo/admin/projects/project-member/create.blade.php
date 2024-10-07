@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col">
                <div class="page-header-left">
                    <h4 class="m-b-0" style="color: #1d61d2;"><i class="{{ $pageIcon }}"></i> <span class="upper"> {{ __($pageTitle) }} </span> #{{ $project->id }}
                        - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('admin.projects.index') }}">{{ __($pageTitle) }}</a></li>
                        <li class="breadcrumb-item active">@lang('modules.projects.members')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid product-wrapper">
    @include('admin.projects.show_project_menu')
    <div class="col-sm-12 p-0 user-profile">
        <div class="tab-content" id="top-tabContent">
            <div class="tab-pane fade active show" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>@lang('modules.projects.members')</h5>
                            </div>
                            <div class="card-body">
                                <button style="margin-bottom: 10px" class="btn btn-sm btn-primary delete_all">Delete All
                                    Selected</button>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="50px"><input type="checkbox" id="master"></th>
                                            <th>@lang('app.name')</th>
                                            <th>@lang('app.role')</th>
                                            <th>@lang('app.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($project->members as $member)
                                        <tr>
                                            <td><input type="checkbox" class="sub_chk" data-id="{{$member->id}}"></td>
                                            <td>
                                                {!! '<img src="'.$member->user->image_url.'" alt="user"
                                                    class="img-circle rounded-circle" width="40" height="40">' !!}
                                                {{ ucwords($member->user->name) }}
                                            </td>
                                            <td>
                                                <div class="radio radio-info">
                                                    <input type="radio" name="project_admin" class="assign_role"
                                                        id="project_admin_{{ $member->user->id }}"
                                                        value="{{ $member->user->id }}" @if($member->user->id ==
                                                    $project->project_admin) checked @endif
                                                    >
                                                    <label for="project_admin_{{ $member->user->id }}"> Project Admin
                                                    </label>
                                                </div>
                                            </td>
                                            <td><a href="javascript:;" data-member-id="{{ $member->id }}"
                                                    class="btn btn-sm btn-danger btn-rounded delete-members"><i
                                                        class="fa fa-times"></i> @lang('app.remove')</a></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td>
                                                @lang('messages.noMemberAddedToProject')
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>@lang('modules.projects.addMemberTitle')</h5>
                                    </div>
                                    {!! Form::open(['id'=>'createMembers','class'=>'ajax-form','method'=>'POST']) !!}

                                    <div class="card-body">
                                        <div class="form-body row">
                                            {!! Form::hidden('project_id', $project->id) !!}
                                            <div class="col-md-9">
                                                <div class="form-group" id="user_id">
                                                    <select class="select2 m-b-10 select2-multiple form-control "
                                                        multiple="multiple" data-placeholder="Choose Members"
                                                        name="user_id[]">
                                                        @foreach($employees as $emp)
                                                        <option value="{{ $emp->id }}">
                                                            {{ ucwords($emp->name). ' ['.$emp->email.']' }} @if($emp->id
                                                            == $user->id)
                                                            (YOU) @endif</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" id="save-members"
                                                    class="btn btn-sm btn-primary form-control"> @lang('app.save')
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                    {!! Form::close() !!}

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>@lang('app.add') @lang('app.department')</h5>
                                    </div>
                                    <div class="card-body">
                                        {!! Form::open(['id'=>'saveGroup','class'=>'ajax-form','method'=>'POST']) !!}

                                        <div class="form-body row">

                                            {!! Form::hidden('project_id', $project->id) !!}
                                            <div class="col-md-9">
                                                <div class="form-group" id="user_id">
                                                    <select class="select2 m-b-10 select2-multiple  form-control"
                                                        multiple="multiple"
                                                        data-placeholder="Choose @lang('app.department')"
                                                        name="group_id[]">
                                                        @foreach($groups as $group)
                                                        <option value="{{ $group->id }}">
                                                            {{ ucwords($group->team_name) }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">

                                                <button type="submit" id="save-group"
                                                    class="btn btn-sm btn-primary form-control">@lang('app.save')
                                                </button>
                                            </div>
                                        </div>

                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                </div>
                <div class="row">

                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('footer-script')
<script type="text/javascript">
//save project members
$('#save-members').click(function() {
    $.easyAjax({
        url: "{{route('admin.project-members.store')}}",
        container: '#createMembers',
        type: "POST",
        data: $('#createMembers').serialize(),
        success: function(response) {
            if (response.status == "success") {
                $.unblockUI();

                window.location.reload();
            }
        }
    })
});

//add group members
$('#save-group').click(function() {
    $.easyAjax({
        url: "{{route('admin.project-members.storeGroup')}}",
        container: '#saveGroup',
        type: "POST",
        data: $('#saveGroup').serialize(),
        success: function(response) {
            if (response.status == "success") {
                $.unblockUI();
                window.location.reload();
            }
        }
    })
});


$('body').on('click', '.delete-members', function() {
    var id = $(this).data('member-id');
    swal({
        title: "Are you sure?",
        text: "This will remove the member from the project.",
        icon: "{{ asset('img/warning.png')}}",
        buttons: ["CANCEL", "DELETE"],
        dangerMode: true
    }).then((willDelete) => {
        if (willDelete) {
            var url = "{{ route('admin.project-members.destroy',':id') }}";
            url = url.replace(':id', id);

            var token = "{{ csrf_token() }}";

            $.easyAjax({
                type: 'POST',
                url: url,
                data: {
                    '_token': token,
                    '_method': 'DELETE'
                },
                success: function(response) {
                    if (response.status == "success") {
                        $.unblockUI();
                        window.location.reload();
                    }
                }
            });
        }
    });
});

$('body').on('click', '.assign_role', function() {
    var userId = $(this).val();
    var projectId = '{{ $project->id }}';
    var token = "{{ csrf_token() }}";

    $.easyAjax({
        url: "{{route('admin.employees.assignProjectAdmin')}}",
        type: "POST",
        data: {
            userId: userId,
            projectId: projectId,
            _token: token
        },
        success: function(response) {
            if (response.status == "success") {
                $.unblockUI();
            }
        }
    })

});

$('ul.showProjectTabs .projectMembers .nav-link').addClass('active');

$(document).ready(function() {
    $('#master').on('click', function(e) {
        if ($(this).is(':checked', true)) {
            $(".sub_chk").prop('checked', true);
        } else {
            $(".sub_chk").prop('checked', false);
        }
    });

    $('.delete_all').on('click', function(e) {
        var ids = [];

        $(".sub_chk:checked").each(function() {
            ids.push($(this).attr('data-id'));
        });

        if (ids.length <= 0) {
            alert("Please select row.");
        } else {
            swal({
                title: "Are you sure?",
                text: "This will remove the member from the project.",
                icon: "{{ asset('img/warning.png')}}",
                buttons: ["CANCEL", "DELETE"],
                dangerMode: true
            }).then((willDelete) => {
                if (willDelete) {
                    var join_selected_values = ids.join(",");
                    var url = "{{ route('admin.project-members.delete-all',':id') }}";
                    url = url.replace(':id', ids);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {
                            '_token': token,
                            '_method': 'DELETE'
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                window.location.reload();
                            }
                        }
                    });

                }
            });
        }
    });
});
</script>
@endpush