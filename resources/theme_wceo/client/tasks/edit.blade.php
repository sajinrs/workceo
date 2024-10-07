@extends('layouts.client-app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} #{{ $project->id }} - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('client.dashboard.index') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('client.projects.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.menu.invoices')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid product-wrapper">
        <div class="row">
            <div class="col-md-12">
                <section>
                    <div class="sttabs tabs-style-line"> @include('client.projects.show_project_menu')  </div>
                </section>
            </div>
        </div>
    </div>
    <!-- .row -->

    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->            

            <div class="col-sm-12">
                <div class="product-grid">

                    <div class="product-wrapper-grid">
                        <div class="row">
                            
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>@lang('app.menu.tasks')</h5>
                                    </div>
                                    
                                    <div class="card-body">
                                        <ul class="list-group" id="invoices-list">
                                            <li class="list-group-item">
                                                <div class="row font-bold">
                                                    <div class="col-md-10">
                                                        <b>@lang('app.task')</b>
                                                    </div>
                                                    <div class="col-md-2 text-right">
                                                    <b>@lang('app.dueDate')</b>
                                                    </div>
                                                </div>
                                            </li>
                                            @forelse($tasks as $task)
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <a href="javascript:;" class="show-task-detail"
                                                            data-task-id="{{ $task->id }}">{{ ucfirst($task->heading) }}</a>
                                                        </div>
                                                        <div class="col-md-3 text-right">
                                                            <span class="@if($task->due_date->isPast()) text-danger @else text-success @endif">{{ $task->due_date->format($global->date_format) }}</span>

                                                            @foreach ($task->users as $member)
                                                                <img data-toggle="tooltip" data-original-title="{{ ucwords($member->name) }}" src="{{ $member->image_url }}"
                                        alt="user" class="img-circle" width="25" height="25">
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            @lang('messages.noRecordFound')
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.custom.js')}}"></script>

<script>

$('#invoices-list').on('click', '.show-task-detail', function () {
        $(".right-sidebar").addClass("right-sidebar-width-auto");
        //$('.right_side_toggle').trigger('click');
        $(".right-sidebar").slideDown(50).addClass("show shw-rside");
        var id = $(this).data('task-id');
        var url = "{{ route('client.tasks.show',':id') }}";
        url = url.replace(':id', id);

        $.easyAjax({
            type: 'GET',
            url: url,
            success: function (response) {
                if (response.status == "success") {
                    $('#right-sidebar-content').html(response.view);
                }
            }
        });
    })

    $('ul.showProjectTabs .projectTasks .nav-link').addClass('active');
</script>
@endpush

