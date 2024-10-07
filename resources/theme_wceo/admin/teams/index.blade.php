@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        @if(!$groups->isEmpty())
                        <a href="{{ route('admin.teams.create') }}" class="btn btn-primary btn-sm">@lang('app.add') @lang('app.department') <i data-feather="plus"></i> </a>
                        @endif

                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(14)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>
                    </div>
                </div>
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>

<div class="container-fluid product-wrapper">
    <div class="row">
        @if($groups->isEmpty())
            <div class="col-md-12 m-t-20">
                <div class="empty-content text-center">
                    <img src="{{ asset('img/empty/department.jpg') }}" alt="user-img" /><br />
                    <b>No Departments</b><br />
                    No departments added yet.<br />
                    <a href="{{ route('admin.teams.create') }}" class="btn btn-primary btn-sm m-t-20">Add Department</a>
                </div>
            </div>
        @else  
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                <div class="table-responsive">
                    <table class="department-table table table-bordered table-hover toggle-circle default footable-loaded footable" id="users-table">
                        <thead>
                        <tr>
                            <th>@lang('app.id')</th>
                            <th>@lang('app.department')</th>
                            <th>@lang('modules.projects.members')</th>
                            <th>@lang('app.action')</th>
                        </tr>
                        </thead>
                        <tbody>


                        @forelse($groups as $group)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $group->team_name }} </td>
                                <td><label class="btn-sm badge-success">{{ sizeof($group->member) }} @lang('modules.projects.members')</label></td>
                                <td>

                                <div class="dropdown-basic">
                                    <div class="dropdown">
                                        <div class="btn-group mb-0">
                                            <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                                            <div class="dropdown-content">
                                            <div class="dep-table">
                                                <a href="{{ route('admin.teams.edit', [$group->id]) }}"><i class="feather-16" data-feather="settings"></i> @lang('app.manage')</a>
                                                <a href="javascript:;"  data-group-id="{{ $group->id }}" class="sa-params"><i class="feather-16" data-feather="x"></i> @lang('app.delete') </a>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                    
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    <div class="empty-space" style="height: 200px;">
                                        <div class="empty-space-inner">
                                            <div class="icon" style="font-size:30px"><i
                                                        class="icon-layers"></i>
                                            </div>
                                            <div class="title m-b-15">@lang('messages.noDepartment')
                                            </div>
                                            <div class="subtitle">
                                                <a href="{{ route('admin.teams.create') }}" class="btn btn-outline btn-success btn-sm">@lang('app.add') @lang('app.team') <i class="fa fa-plus" aria-hidden="true"></i></a>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
    <!-- .row -->

@endsection

@push('footer-script')
<script>
    $(function() {

        $('body').on('click', '.sa-params', function () {
            var id = $(this).data('group-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted team!",
                icon: "{{ asset('img/warning.png')}}",
                buttons: ["CANCEL", "DELETE"],
                dangerMode: true
        })
        .then((willDelete) => {
                if (willDelete) {
                    var url = "{{ route('admin.teams.destroy',':id') }}";
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
    });
</script>
@endpush
