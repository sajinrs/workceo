@extends('layouts.super-admin')

@push('head-script')
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/select2.css')}}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                        href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a
                                        href="{{ route('super-admin.super-admin.index') }}">{{ __($pageTitle) }}</a>
                            </li>
                            <li class="breadcrumb-item active">@lang('app.edit')</li>
                        </ol>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="edit-profile">
            <div class="row">
                <div class="col-lg-12">
                    {!! Form::open(['id'=>'updateClient','class'=>'card ajax-form','method'=>'PUT']) !!}
                    <div class="card-header">
                        <h4 class="wceo-card-header card-title mb-0">@lang('app.update') @lang('app.superAdmin')
                            [ {{ $userDetail->name }} ]
                            @php($class = ($userDetail->status == 'active') ? 'badge-success' : 'badge-danger')
                            <label class="badge {{$class}}">{{ucfirst($userDetail->status)}}</label>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-8 ">
                                    <div class="form-group">
                                        <label>@lang('app.name')</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                               value="{{ $userDetail->name }}" autocomplete="nope">
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>@lang('app.email')</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                               value="{{ $userDetail->email }}" autocomplete="nope">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>@lang('modules.employees.employeePassword')</label>
                                        <input type="password" style="display: none">
                                        <input type="password" name="password" id="password" class="form-control"
                                               autocomplete="nope">
                                        <span class="help-block"> @lang('modules.profile.passwordNote')</span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    @if($user->id != $userDetail->id)
                                        <div class="form-group">
                                            <label>@lang('app.status')</label>
                                            <select name="status" id="status" class="form-control">
                                                <option @if($userDetail->status == 'active') selected
                                                        @endif value="active">@lang('app.active')</option>
                                                <option @if($userDetail->status == 'deactive') selected
                                                        @endif value="deactive">@lang('app.deactive')</option>
                                            </select>
                                        </div>
                                    @endif
                                </div>
                                <!--/span-->
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('modules.profile.profilePicture')</label>
                                    <div class="form-group">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="{{ $userDetail->image_url }}" alt=""/>
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                 style="width: 200px; height: 150px;"></div>
                                            <div>
                                                <span class="btn btn-success btn-sm btn-small btn-file">
                                                    <span class="fileinput-new"> @lang('app.selectImage') </span>
                                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                                    <input type="file" name="image" id="image">
                                                </span>
                                                <a href="javascript:;" class="btn btn-danger btn-sm fileinput-exists"
                                                   data-dismiss="fileinput"> @lang('app.remove') </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-primary"><i
                                        class="fa fa-check"></i> @lang('app.update')</button>
                            <a href="{{ route('super-admin.super-admin.index') }}"
                               class="btn btn-secondary">@lang('app.back')</a>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script src="{{ asset('themes/wceo/assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/select2/select2-custom.js')}}"></script>

    <script>
        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('super-admin.super-admin.update', [$userDetail->id])}}',
                container: '#updateClient',
                type: "POST",
                redirect: true,
                file: true
            })
        });
    </script>
@endpush
