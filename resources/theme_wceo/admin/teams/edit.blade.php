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
                            <li class="breadcrumb-item"><a href="{{ route('admin.teams.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.update')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                {!! Form::open(['id'=>'createCurrency','class'=>'ajax-form','method'=>'PUT']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('app.update') @lang('app.department')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group">
                                        <input type="text" class="form-control form-control-lg" id="team_name" name="team_name" placeholder="-" value="{{ $group->team_name }}"> 
                                        <label for="team_name" class="required">@lang('app.department')</label>    
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <h5>@lang('modules.projects.members')</h5>
                                </div>

                                <div class="col-xl-6">                                    
                                        <div class="new-users">
                                        @forelse($group->member as $member)
                                            <div class="media"><img class="rounded-circle image-radius m-r-15" src="{{$member->user->image_url}}" alt="">
                                                <div class="media-body">
                                                <h6 class="mb-0 f-w-700">{{ ucwords($member->user->name) }} <a  data-toggle="tooltip" data-original-title="Edit" href="{{ route('admin.employees.edit', $member->user->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a></h6>
                                                <p>{{ $member->user->email }}</p>
                                                </div>
                                            </div>
                                            @empty
                                            @lang('messages.noRecordFound')
                                        @endforelse
                                        </div>
                                </div>

                                

                            </div>                   
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <div class="form-actions col-md-6 offset-md-7">
                            <div class="row">
                                <div class="col-md-5 pr-0">
                                    <a href="{{route('admin.teams.index')}}" class="btn btn-outline-primary gray form-control">@lang('app.cancel')</a>
                                </div>
                                <div class="col-md-5 pr-0">
                                <button type="submit" id="save-form" class="btn btn-primary form-control">@lang('app.save')</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection



@push('footer-script')
<script>
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.teams.update', [$group->id])}}',
            container: '#createCurrency',
            type: "POST",
            redirect: true,
            data: $('#createCurrency').serialize()
        })
    });

</script>
@endpush

