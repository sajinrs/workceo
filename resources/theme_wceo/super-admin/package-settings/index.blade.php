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
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
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
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="panel panel-inverse">

                        <div class="card-header">
                            <h5>  @lang("app.freeTrial") @lang('app.menu.settings')</h5>

                        </div>

                        <div class="vtabs customvtab m-t-10">
                            @include('sections.super_admin_setting_menu')

                            <div class="card-header">
                                <div class="tab-content">
                                    <div id="vhome3" class="tab-pane active">

                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                                                <div class=" card-body">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">@lang('app.name')</label>
                                                                <input type="text" id="name" name="name" value="{{ $package->name ?? '' }}" class="form-control" >
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label>@lang('app.max') @lang('app.menu.employees')</label>
                                                                <input type="number" name="max_employees" id="max_employees" value="{{ $package->max_employees ?? '' }}"  class="form-control">
                                                            </div>
                                                        </div></div>
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="no_of_days">@lang('modules.packageSetting.noOfDays')</label>
                                                                <input type="number" class="form-control" id="no_of_days" name="no_of_days"
                                                                       value="{{ $packageSetting->no_of_days }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6 col-xs-12">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label for="no_of_days">@lang('modules.packageSetting.notificationBeforeDays')</label>
                                                                    <input type="number" class="form-control" id="notification_before" name="notification_before"
                                                                           value="{{ $packageSetting->notification_before }}">
                                                                </div>
                                                            </div>
                                                        </div> </div>


                                                    <div class="card-header">
                                                        <div class="tab-content">
                                                            <div id="vhome3" class="tab-pane active">
                                                                <h5>
                                                                    @lang('app.select') @lang('app.module')
                                                                </h5>
                                                            </div></div></div>
                                                    <div class="row">
                                                        <div class="col-xs-12 col-md-12">
                                                            <div class="row form-group module-in-package">
                                                                @foreach($modules as $module)
                                                                    @php
                                                                        $packageModules = (array)json_decode($packageSetting->modules);
                                                                    @endphp
                                                                    <div class="col-md-2">
                                                                        <div class="checkbox checkbox-inline checkbox-info m-b-10">
                                                                            <input id="{{ $module->module_name }}" name="module_in_package[{{ $module->id }}]" value="{{ $module->module_name }}" type="checkbox" @if(isset($packageModules) && in_array($module->module_name, $packageModules) ) checked @endif>
                                                                            <label for="{{ $module->module_name }}">{{ ucfirst($module->module_name) }}</label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label" >@lang('app.status')</label>

                                                                <div class="switchery-demo media-body text-left icon-state">
                                                                    <label class="switch">
                                                                        <input  type="checkbox" name="status" @if($packageSetting->status == 'active') checked @endif   />
                                                                        <span class="switch-state"></span>
                                                                    </label>
                                                                </div>


                                                            </div>
                                                        </div></div>

                                                    <div class="text-right">
                                                        <button type="submit" id="save-form"
                                                                class="btn btn-primary  waves-effect waves-light m-r-10">
                                                            @lang('app.update')
                                                        </button>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div> </div>
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


@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>

    <script>
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());

        });
        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('super-admin.package-settings.update', $packageSetting->id)}}',
                container: '#editSettings',
                type: "POST",
                redirect: true,
                file: true,
            })
        });

    </script>
@endpush
