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

                        <div class="card-body">

                            <div class="form-body">

                                @if($global->front_design == 1)
                                    @include('sections.saas.feature_page_setting_menu')
                                @else
                                    @include('sections.front_setting_menu')
                                @endif

                                <div class="tab-content">
                                    <div id="vhome3" class="tab-pane active">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="white-box">
                                                    @if($type !== 'icon')
                                                        {!! Form::open(['id'=>'editSettings','class'=>'ajax-form']) !!}
                                                        <input type="hidden" name="type" value="{{ $type }}">
                                                        <h4>{{ucwords($type)}} @lang('app.section')</h4>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label for="title">@lang('app.title')</label>
                                                                    <input type="text" class="form-control" id="title"
                                                                           name="title"
                                                                           @if($type == 'task')
                                                                           value="{{ $frontDetail->task_management_title }}"
                                                                           @elseif($type == 'bills')
                                                                           value="{{ $frontDetail->manage_bills_title }}"
                                                                           @elseif($type == 'image')
                                                                           value="{{ $frontDetail->feature_title }}"
                                                                           @elseif($type == 'team')
                                                                           value="{{ $frontDetail->teamates_title }}"
                                                                           @elseif($type == 'apps')
                                                                           value="{{ $frontDetail->favourite_apps_title }}"
                                                                            @endif
                                                                    >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12 col-xs-12">
                                                                <div class="form-group">
                                                                    <label for="address">@lang('app.description')</label>

                                                                    @if($type == 'task')
                                                                        <textarea class="form-control" id="detail"
                                                                                  rows="5"
                                                                                  name="detail">{{ $frontDetail->task_management_detail }}</textarea>
                                                                    @elseif($type == 'bills')
                                                                        <textarea class="form-control" id="detail"
                                                                                  rows="5"
                                                                                  name="detail">{{ $frontDetail->manage_bills_detail }}</textarea>
                                                                    @elseif($type == 'image')
                                                                        <textarea class="form-control" id="detail"
                                                                                  rows="5"
                                                                                  name="detail">{{ $frontDetail->feature_description }}</textarea>
                                                                    @elseif($type == 'team')
                                                                        <textarea class="form-control" id="detail"
                                                                                  rows="5"
                                                                                  name="detail">{{ $frontDetail->teamates_detail }}</textarea>
                                                                    @elseif($type == 'apps')
                                                                        <textarea class="form-control" id="detail"
                                                                                  rows="5"
                                                                                  name="detail">{{ $frontDetail->favourite_apps_detail }}</textarea>
                                                                    @endif

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
                                                    @endif
                                                    <br>
                                                    <!--   <hr> -->
                                                    <h5>@lang('modules.feature.setting')</h5>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <div class="text-right">
                                                                    <a href="javascript:;"
                                                                       class="btn btn-outline btn-primary btn-sm addFeature">@lang('modules.featureSetting.addFeature')
                                                                        <i class="fa fa-plus"
                                                                           aria-hidden="true"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>@lang('app.title')</th>
                                                                @if($type !== 'apps')
                                                                    <th>@lang('app.description')</th>
                                                                @endif
                                                                <th>{{ucwords($type)}}</th>
                                                                <th class="text-nowrap">@lang('app.action')</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @forelse($features as $feature)
                                                                <tr>
                                                                    <td>{{ ucwords($feature->title) }}</td>
                                                                    @if($type !== 'apps')
                                                                        <td>{!! $feature->description  !!}</td>
                                                                    @endif
                                                                    <td @if($feature->type != 'image' && $feature->type != 'apps') style="font-size: 27px" @endif>
                                                                        @if($feature->type == 'image' || $feature->type == 'apps')
                                                                            <img height="100" width="85"
                                                                                 src="{{ $feature->image_url }}"
                                                                                 alt=""/>
                                                                        @else
                                                                            <i class="{{ $feature->icon }}"></i>
                                                                        @endif


                                                                    </td>
                                                                    <td class="text-nowrap">
                                                                        <a href="javascript:;"
                                                                           data-feature-id="{{ $feature->id }}"
                                                                           class="btn btn-sm btn-outline-info btn-circle editFeature"
                                                                           data-toggle="tooltip"
                                                                           data-original-title="Edit"><i
                                                                                    class="fa fa-edit"
                                                                                    aria-hidden="true"></i></a>
                                                                        <a href="javascript:;"
                                                                           class="btn btn-sm btn-outline-danger btn-circle sa-params"
                                                                           data-toggle="tooltip"
                                                                           data-feature-id="{{ $feature->id }}"
                                                                           data-original-title="Delete"><i
                                                                                    class="fa fa-times"
                                                                                    aria-hidden="true"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4"
                                                                        class="text-center">@lang('messages.noRecordFound')</td>
                                                                </tr>
                                                            @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
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
            </div>
        </div>
    </div>
    </div>

    </div>
    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
    <script>
        $('.editFeature').click(function () {
            var id = $(this).data('feature-id');
            var url = '{{ route('super-admin.feature-settings.edit', ':id')}}?type={{$type}}';
            url = url.replace(':id', id);
            $('#modelHeading').html('Currency Convert Key');
            $.ajaxModal('#projectCategoryModal', url);
        })
        $('.addFeature').click(function () {
            var url = '{{ route('super-admin.feature-settings.create')}}?type={{$type}}';
            $('#modelHeading').html('Currency Convert Key');
            $.ajaxModal('#projectCategoryModal', url);
        })

        $('body').on('click', '.sa-params', function () {
            var id = $(this).data('feature-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted feature!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {

                    var url = "{{ route('super-admin.feature-settings.destroy',':id') }}?type={{$type}}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                                window.location.reload();
                            }
                        }
                    });
                }
            });
        });

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('super-admin.feature-settings.title-update')}}',
                container: '#editSettings',
                type: "POST",
                redirect: true,
                file: true,
            })
        });

    </script>
@endpush
