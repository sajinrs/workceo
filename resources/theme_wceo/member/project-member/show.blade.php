@extends('layouts.member-app')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} #{{ $project->id }} - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('member.projects.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('modules.projects.members')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @include('member.projects.show_project_menu')

        <div class="col-sm-12 p-0 user-profile">
            <div class="tab-content" id="top-tabContent">
                <div class="tab-pane fade active show" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                @if($project->isProjectAdmin || $user->can('add_projects'))
                    <div class="row">
                        <div  class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>@lang('modules.projects.addMemberTitle')</h5>
                                </div>
                                {!! Form::open(['id'=>'createMembers','class'=>'ajax-form','method'=>'POST']) !!}

                                <div class="card-body">
                                    <div class="form-body row">
                                        {!! Form::hidden('project_id', $project->id) !!}
                                        <div  class="col-md-9">
                                            <div class="form-group" id="user_id">
                                                <select class="select2 m-b-10 select2-multiple form-control " multiple="multiple"
                                                        data-placeholder="Choose Members" name="user_id[]">
                                                        @foreach($employees as $emp)
                                                        <option value="{{ $emp->id }}">{{ ucwords($emp->name). ' ['.$emp->email.']' }} @if($emp->id == $user->id)
                                                                (YOU) @endif</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div  class="col-md-3">
                                            <button type="submit" id="save-members" class="btn btn-secondary form-control"> @lang('app.save')
                                            </button>
                                        </div>
                                    </div>

                                </div>
                                {!! Form::close() !!}

                            </div>
                        </div>
                        
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h5>@lang('modules.projects.members')</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>@lang('app.name')</th>
                                    <th>@lang('app.action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($project->members as $member)
                                    <tr>
                                        <td>
                                            {!!  '<img src="'.$member->user->image_url.'"
                                        alt="user" class="img-circle" width="40" height="40">' !!}
                                            {{ ucwords($member->user->name) }}
                                        </td>
                                        <td>
                                            @if($project->isProjectAdmin || $user->can('add_projects'))
                                                <a href="javascript:;" data-member-id="{{ $member->id }}" class="btn btn-sm btn-danger btn-rounded delete-members"><i class="fa fa-times"></i> @lang('app.remove')</a>
                                            @endif
                                        </td>
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
            </div>
        </div>
    </div>


@endsection

@push('footer-script')
<script type="text/javascript">
    
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    //    save project members
    $('#save-members').click(function () {
        $.easyAjax({
            url: '{{route('member.project-members.store')}}',
            container: '#createMembers',
            type: "POST",
            data: $('#createMembers').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                    window.location.reload();
                }
            }
        })
    });


    $('body').on('click', '.delete-members', function(){
    var id = $(this).data('member-id');
    swal({
        title: "Are you sure?",
        text: "This will remove the member from the project.",
        icon: "warning",
        buttons: ["No, cancel please!", "Yes!"],
        dangerMode: true
    }).then((willDelete) => {
        if (willDelete) {
            var url = "{{ route('member.project-members.destroy',':id') }}";
            url = url.replace(':id', id);

            var token = "{{ csrf_token() }}";

            $.easyAjax({
                type: 'POST',
                url: url,
                data: {'_token': token, '_method': 'DELETE'},
                success: function (response) {
                    if (response.status == "success") {
                        $.unblockUI();
                        window.location.reload();
                    }
                }
            });
        }
    });
});

    
$('ul.showProjectTabs .projectMembers .nav-link').addClass('active');

</script>
@endpush
