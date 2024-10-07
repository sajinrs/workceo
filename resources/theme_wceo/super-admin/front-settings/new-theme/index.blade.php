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
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('plugins/bower_components/jquery-asColorPicker-master/css/asColorPicker.css') }}">
@endpush

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                            <h5>@lang('modules.frontCms.updateTitle')</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-body">
                                @include('sections.front_setting_new_theme_menu')


                                <div class="tab-content">
                                    <div id="vhome3" class="tab-pane active">
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                <h5>@lang("modules.frontSettings.title")</h5>

                                                <div class="row">
                                                    <div class="col-sm-12 col-xs-12">
                                                        {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                                                        <span class="m-r-5">@lang('modules.frontCms.frontDetail')</span>
                                                        <hr>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label for="company_name"
                                                                               class="d-block">@lang('modules.frontCms.primaryColor')</label>
                                                                        <input type="text" name="primary_color"
                                                                               class="gradient-colorpicker form-control"
                                                                               autocomplete="off"
                                                                               value="{{ $frontDetail->primary_color }}"/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-6 col-xs-12">
                                                                    <div class="form-group">

                                                                        <label for="company_name">@lang('modules.frontCms.headerTitle')</label>
                                                                        <input type="text" class="form-control"
                                                                               id="header_title" name="header_title"
                                                                               value="{{ $frontDetail->header_title }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label for="address">@lang('modules.frontCms.headerDescription')</label>
                                                                        <textarea class="form-control"
                                                                                  id="header_description" rows="5"
                                                                                  name="header_description">{{ $frontDetail->header_description }}</textarea>
                                                                    </div>
                                                                </div>


                                                                <div class="col-sm-12 col-md-6 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label for="exampleInputPassword1">@lang('modules.frontCms.mainImage')</label>
                                                                        <div class="">
                                                                            <div class="fileinput fileinput-new"
                                                                                 data-provides="fileinput">
                                                                                <div class="fileinput-new thumbnail"
                                                                                     style="width: 200px; height: 150px;">
                                                                                    <img src="{{ $frontDetail->image_url }}"
                                                                                         alt=""/>
                                                                                </div>
                                                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                                                     style="max-width: 200px; max-height: 150px;"></div>
                                                                                <div>
                                                                        
                                <span class="btn btn-info btn-file">
                                    <span class="fileinput-new"> @lang('app.selectImage') </span>
                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                    <input type="file" name="image" id="image"> </span>
                                                                                    <a href="javascript:;"
                                                                                       class="btn btn-danger fileinput-exists"
                                                                                       data-dismiss="fileinput"> @lang('app.remove') </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="alert alert-info"><i
                                                                                class="fa fa-info-circle"></i> @lang('messages.headerImageSizeMessage')
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <div class="checkbox checkbox-info  col-md-10">
                                                                            <input id="get_started_show"
                                                                                   name="get_started_show" value="yes"
                                                                                   @if($frontDetail->get_started_show == "yes") checked
                                                                                   @endif
                                                                                   type="checkbox">
                                                                            <label for="get_started_show">@lang('modules.frontCms.getStartedButtonShow')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <div class="checkbox checkbox-info  col-md-10">
                                                                            <input id="sign_in_show" name="sign_in_show"
                                                                                   value="yes"
                                                                                   @if($frontDetail->sign_in_show == "yes") checked
                                                                                   @endif
                                                                                   type="checkbox">
                                                                            <label for="sign_in_show">@lang('modules.frontCms.singInButtonShow')</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <h4 id="social-links">@lang('modules.frontCms.socialLinks')</h4>
                                                            <hr>
                                                            <span class="text-danger">@lang('modules.frontCms.socialLinksNote')</span><br><br>
                                                            <div class="row">
                                                                @foreach(json_decode($frontDetail->social_links) as $link)

                                                                    <div class="col-sm-12 col-md-3 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label for="{{ $link->name }}">
                                                                                @lang('modules.frontCms.'.$link->name)
                                                                            </label>
                                                                            <input
                                                                                    class="form-control"
                                                                                    id="{{ $link->name }}"
                                                                                    name="social_links[{{ $link->name }}]"
                                                                                    type="url"
                                                                                    value="{{ $link->link }}"
                                                                                    placeholder="@lang('modules.frontCms.enter'.ucfirst($link->name).'Link')">
                                                                        </div>
                                                                    </div>

                                                                @endforeach
                                                            </div>
                                                            <div class="text-right">
                                                                <button type="submit" id="save-form"
                                                                        class="btn btn-primary waves-effect waves-light m-r-10">
                                                                    @lang('app.update')
                                                                </button>
                                                            </div>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- .row -->
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
            </div>

        </div>
    </div>


    </div>
    <!-- .row -->



@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asColor.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asGradient.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>
    <script>
        // Colorpicker
        $(".colorpicker").asColorPicker();
        $(".complex-colorpicker").asColorPicker({
            mode: 'complex'
        });
        $(".gradient-colorpicker").asColorPicker(
            // {
            //     mode: 'gradient'
            // }
        );
        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('super-admin.front-settings.update', $frontDetail->id)}}',
                container: '#editSettings',
                type: "POST",
                redirect: true,
                file: true,
            })
        });

    </script>
@endpush
