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
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                            <h5>{{ $pageTitle }}</h5>

                        </div>

                        <div class="vtabs customvtab m-t-10">
                            @include('sections.super_admin_setting_menu')


                            <div class="card-header">
                                @include('vendor.froiden-envato.update.update_blade')
                            </div>


                            <div class="vtabs customvtab m-t-10">
                                <div class="tab-content">
                                    <div id="vhome3" class="tab-pane active">
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                <div class="col-sm-12 col-xs-12 ">
                                                    <div class="card-header">
                                                        @include('vendor.froiden-envato.update.version_info')
                                                    </div>

                                                    <!--row-->
                                                @include('vendor.froiden-envato.update.changelog')
                                                @include('vendor.froiden-envato.update.plugins')
                                                <!--/row-->
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
    </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    @include('vendor.froiden-envato.update.update_script')
@endpush
