@extends('layouts.super-admin')
@section('page-title')
    <div class="col-md-12">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                            href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                                <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                            </ol>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
@push('head-script')
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/iconpicker/css/fontawesome-iconpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                            <h5>@lang('app.menu.menu') @lang('app.menu.settings')</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-body">
                                @include('sections.front_setting_new_theme_menu')

                                <div class="tab-content">
                                    <div id="vhome3" class="tab-pane active">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="white-box">
                                                    {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'PUT']) !!}

                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="title">@lang('app.home')</label>
                                                                <input type="text" class="form-control" id="home"
                                                                       name="home" value="{{ $frontMenu->home }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="title">@lang('app.price')</label>
                                                                <input type="text" class="form-control" id="price"
                                                                       name="price" value="{{ $frontMenu->price }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="title">@lang('app.contact')</label>
                                                                <input type="text" class="form-control" id="contact"
                                                                       name="contact" value="{{ $frontMenu->contact }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="title">@lang('app.feature')</label>
                                                                <input type="text" class="form-control" id="feature"
                                                                       name="feature" value="{{ $frontMenu->feature }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="title">@lang('app.get_start')</label>
                                                                <input type="text" class="form-control" id="get_start"
                                                                       name="get_start"
                                                                       value="{{ $frontMenu->get_start }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="title">@lang('app.login')</label>
                                                                <input type="text" class="form-control" id="login"
                                                                       name="login" value="{{ $frontMenu->login }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="title">@lang('app.contact_submit')</label>
                                                                <input type="text" class="form-control"
                                                                       id="contact_submit" name="contact_submit"
                                                                       value="{{ $frontMenu->contact_submit }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" id="save-form"
                                                                class="btn btn-primary waves-effect waves-light m-r-10">
                                                            @lang('app.update')
                                                        </button>
                                                    </div>
                                                    {!! Form::close() !!}
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>    <!-- .row -->

                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>

                @endsection

                @push('footer-script')
                    <script>
                        $('#save-form').click(function () {
                            $.easyAjax({
                                url: '{{route('super-admin.front-menu-settings.update', $frontMenu->id)}}',
                                container: '#editSettings',
                                type: "POST",
                                data: $('#editSettings').serialize()
                            })
                        });


                    </script>
    @endpush
