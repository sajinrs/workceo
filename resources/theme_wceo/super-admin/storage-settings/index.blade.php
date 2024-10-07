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
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/select2.css')}}">
@endpush


@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                            <h5> File Storage </h5>

                        </div>

                        <div class="vtabs customvtab m-t-10">
                            @include('sections.super_admin_setting_menu')


                            <div class="vtabs customvtab m-t-10">
                                <div class="tab-content">
                                    <div id="vhome3" class="tab-pane active">
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                <div class="col-sm-12 col-xs-12 ">
                                                    {!! Form::open(['id'=>'updateSettings','class'=>'ajax-form','method'=>'POST']) !!}
                                                    <div class=" card-body">
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                                <div class="form-group">

                                                                    <label class="control-label">Select Storage</label>
                                                                    <select class="select2 form-control " id="storage"
                                                                            name="storage">
                                                                        <option value="local"
                                                                                @if(isset($localCredentials) && $localCredentials->status == 'enabled') selected @endif>
                                                                            Local (Default)
                                                                        </option>
                                                                        <option value="aws"
                                                                                @if(isset($awsCredentials) && $awsCredentials->status == 'enabled') selected @endif>
                                                                            AWS Storage (Amazon Web Services)
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!--   aws-form -->
                                                            <div class="col-sm-12 col-md-6 col-xs-12 aws-form ">
                                                                <div class="form-group">
                                                                    <label>AWS Key</label>
                                                                    <input type="text" class="form-control"
                                                                           name="aws_key"
                                                                           @if(isset($awsCredentials) && isset($awsCredentials->key)) value="{{ $awsCredentials->key }}" @endif>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-6 col-xs-12 aws-form">
                                                                <div class="form-group">
                                                                    <label>AWS Secret</label>
                                                                    <input type="text" class="form-control"
                                                                           name="aws_secret"
                                                                           @if(isset($awsCredentials) && isset($awsCredentials->secret)) value="{{ $awsCredentials->secret }}" @endif>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-6 col-xs-12 aws-form">
                                                                <div class="form-group">
                                                                    <label>AWS Region</label>
                                                                    <input type="text" class="form-control"
                                                                           id="company_name" name="aws_region"
                                                                           @if(isset($awsCredentials) && isset($awsCredentials->region)) value="{{ $awsCredentials->region }}" @endif>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-6 col-xs-12 aws-form">
                                                                <div class="form-group">
                                                                    <label>AWS Bucket</label>
                                                                    <input type="text" class="form-control"
                                                                           id="company_name" name="aws_bucket"
                                                                           @if(isset($awsCredentials) && isset($awsCredentials->bucket)) value="{{ $awsCredentials->bucket }}" @endif>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!--/row-->

                                                    </div>
                                                    <div class="card-footer  text-right">
                                                        <div class="form-actions m-t-20">
                                                            <button type="submit" id="save-form-2"
                                                                    class="btn btn-primary waves-effect waves-light m-r-10">
                                                                <i class="fa fa-check"></i>
                                                                @lang('app.save')
                                                            </button>

                                                        </div>
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
    </div>
    </div>
    <!-- .row -->



@endsection

@push('footer-script')
    <script src="{{ asset('themes/wceo/assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/select2/select2-custom.js')}}"></script>
    <script>
        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        $(function () {
            var type = $('#storage').val();
            if (type == 'aws') {
                $('.aws-form').css('display', 'block');
            } else if (type == 'local') {
                $('.aws-form').css('display', 'none');
            }
        });

        $('#storage').on('change', function (event) {
            event.preventDefault();
            var type = $(this).val();
            if (type == 'aws') {
                $('.aws-form').css('display', 'block');
            } else if (type == 'local') {
                $('.aws-form').css('display', 'none');
            }
        });

        $('#save-form-2').click(function () {
            $.easyAjax({
                url: '{{ route('super-admin.storage-settings.store')}}',
                container: '#updateSettings',
                type: "POST",
                redirect: true,
                data: $('#updateSettings').serialize()
            })
        });
    </script>
@endpush
