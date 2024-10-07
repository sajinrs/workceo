@extends('layouts.super-admin')
@section('page-title')
    <div class="col-md-12">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3><i class="{{ $pageIcon }}"></i> {{ ($pageTitle) }}</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                            href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                                <li class="breadcrumb-item active"><a
                                            href="{{ route('super-admin.language-settings.index') }}">{{ __($pageTitle) }}</a>
                                </li>
                                <li class="breadcrumb-item active">@lang('app.addNew')</li>
                            </ol>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="panel panel-inverse">

                        <div class="card-header">
                            <h5>  @lang('app.language') @lang('app.menu.settings')</h5>


                        </div>

                        <div class="vtabs customvtab m-t-10">
                            <div class="tab-content">
                                <div id="vhome3" class="tab-pane active">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            {!! Form::open(['id'=>'createCurrency','class'=>'ajax-form','method'=>'POST']) !!}
                                            <div class=" card-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="language_name">@lang('app.language') @lang('app.name')</label>
                                                            <input type="text" class="form-control" id="language_name"
                                                                   name="language_name"
                                                                   placeholder="Enter Language Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="language_code">@lang('app.language_code')</label>
                                                            <input type="text" class="form-control" id="language_code"
                                                                   name="language_code"
                                                                   placeholder="Enter Language Code">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-xs-12">

                                                        <div class="form-group ">
                                                            <label for="usd_price">@lang('app.status') </label>
                                                            <select class="form-control" name="status">
                                                                <option value="enabled">@lang('app.enable')</option>
                                                                <option value="disabled">@lang('app.disable')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer  text-right">
                                                    <button type="submit" id="save-form"
                                                            class="btn btn-primary  waves-effect waves-light m-r-10">
                                                        @lang('app.save')
                                                    </button>
                                                    <button type="reset"
                                                            class="btn btn-secondary  waves-effect waves-light">@lang('app.reset')</button>
                                                </div>
                                                {!! Form::close() !!}
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
    </div>

    <!-- .row -->

@endsection

@push('footer-script')
    <script>

        // store language
        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('super-admin.language-settings.store')}}',
                container: '#createCurrency',
                type: "POST",
                redirect: true,
                data: $('#createCurrency').serialize()
            })
        });
    </script>
@endpush

