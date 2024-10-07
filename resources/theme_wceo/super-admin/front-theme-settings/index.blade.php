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
    <link rel="stylesheet" href="{{ asset('plugins/image-picker/image-picker.css') }}">
    <style>
        .thumbnail {
            color: black;
            font-weight: 600;
            text-align: center;
        }

        .thumbnail.selected {
            background-color: #f8c234 !important;
        }

    </style>
@endpush

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                            <h5>{{ __($pageTitle) }} </h5>
                        </div>
                        <div class="card-body">

                            <div class="form-body">
                                @if($global->front_design == 1)
                                    @include('sections.front_setting_new_theme_menu')
                                @else
                                    @include('sections.front_setting_menu')
                                @endif

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info ">
                                            <h4 class="text-white">Favicon</h4>
                                            <i class="fa fa-info-circle"></i> @lang('messages.faviconNote')
                                        </div>

                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-body"><br>
                                            <h5>@lang('app.selectTheme') </h5>
                                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-xs-12">

                                                        <div class="form-group">
                                                            <select name="theme" id="theme"
                                                                    class="image-picker show-labels show-html"
                                                                    style="color: white">
                                                                <option
                                                                        data-img-src="{{ asset('img/old-design.jpg') }}"
                                                                        @if($global->front_design == 0) selected @endif
                                                                        value="0">
                                                                    Theme 1
                                                                </option>

                                                                <option data-img-src="{{ asset('img/new-design.jpg') }}"
                                                                        data-toggle="tooltip" data-original-title="Edit"
                                                                        @if($global->front_design == 1) selected @endif
                                                                        value="1">Theme 2
                                                                </option>

                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer  text-right">
                                                    <button type="submit" id="save-form"
                                                            class="btn btn-primary waves-effect waves-light m-r-10">
                                                        @lang('app.update')
                                                    </button>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>    <!-- .row -->
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
    <script src="{{ asset('plugins/image-picker/image-picker.min.js') }}"></script>

    <script>
        $("body").tooltip({
            selector: '[data-toggle="tooltip"]'
        });
        $(".image-picker").imagepicker({
            show_label: true
        });
        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('super-admin.theme-update')}}',
                container: '#editSettings',
                type: "POST",
                redirect: true,
                data: $('#editSettings').serialize()
            })
        });

    </script>
@endpush
