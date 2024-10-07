@extends('layouts.super-admin')

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
                            <li class="breadcrumb-item active">@lang('app.addNew')</li>
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
                    {!! Form::open(['id'=>'createClient','class'=>'card ajax-form','method'=>'POST']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">Add Super Admin</h4>
                        <div class="card-options"><a class="card-options-collapse" href="#" data-toggle="card-collapse"><i
                                        class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#"
                                                                            data-toggle="card-remove"><i
                                        class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-8 ">
                                    <div class="form-group">
                                        <label class="required">@lang('app.name')</label>
                                        <input type="text" name="name" id="name"
                                               class="form-control" value="">
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="required">@lang('app.email')</label>
                                        <input type="email" name="email" id="email"
                                               class="form-control" value="">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="required">@lang('app.password')</label>
                                        <input type="password" name="password" id="password"
                                               class="form-control">
                                        <span class="help-block"> </span>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>


                            <div class="row">

                                <div class="col-md-6">
                                    <label>@lang('modules.profile.profilePicture')</label>

                                    <div class="form-group">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="https://via.placeholder.com/200x150.png?text={{ str_replace(' ', '+', __('modules.profile.uploadPicture')) }}"
                                                     alt=""/>
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                 style="max-width: 200px; max-height: 150px;"></div>
                                            <div>
                                                <span class="btn btn-success btn-sm btn-file">
                                                    <span class="fileinput-new"> @lang('app.selectImage') </span>
                                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                                    <input type="file" name="image" id="image">
                                                </span>
                                                <a href="javascript:;"
                                                   class="btn btn-danger btn-sm fileinput-exists"
                                                   data-dismiss="fileinput"> @lang('app.remove') </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!--/span-->
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-primary"><i
                                        class="fa fa-check"></i> @lang('app.save')</button>
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
                url: '{{route('super-admin.super-admin.store')}}',
                container: '#createClient',
                type: "POST",
                redirect: true,
                data: $('#createClient').serialize()
            })
        });
    </script>
@endpush

