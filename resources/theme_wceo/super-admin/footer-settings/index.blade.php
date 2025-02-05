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
                            <h5>@lang('modules.footer.setting')</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-body">
                                @if($global->front_design == 1)
                                    @include('sections.saas.footer_page_setting_menu')
                                @else
                                    @include('sections.front_setting_menu')
                                @endif


                                <div class="tab-content">
                                    <div id="vhome3" class="tab-pane active">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="white-box">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <div class="text-right">
                                                                    <a href="javascript:;"
                                                                       class="btn btn-outline btn-primary btn-sm addFeature">@lang('modules.footer.addFooter')
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
                                                                <th>@lang('app.description')</th>
                                                                <th class="text-nowrap">@lang('app.action')</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @forelse($footer as $footerMenu)
                                                                <tr>
                                                                    <td>{{ ucwords($footerMenu->name) }}</td>
                                                                    <td>{!! $footerMenu->description  !!}</td>
                                                                    <td class="text-nowrap">
                                                                        <a href="javascript:;"
                                                                           data-feature-id="{{ $footerMenu->id }}"
                                                                           class="btn btn-info btn-circle editFeature"
                                                                           data-toggle="tooltip"
                                                                           data-original-title="Edit"><i
                                                                                    class="fa fa-pencil"
                                                                                    aria-hidden="true"></i></a>
                                                                        <a href="javascript:;"
                                                                           class="btn btn-danger btn-circle sa-params"
                                                                           data-toggle="tooltip"
                                                                           data-feature-id="{{ $footerMenu->id }}"
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
                <!-- .row -->
                {{--Ajax Modal--}}
                <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog"
                     aria-labelledby="myModalLabel"
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
                            var url = '{{ route('super-admin.footer-settings.edit', ':id')}}';
                            url = url.replace(':id', id);
                            $('#modelHeading').html('edit Feature');
                            $.ajaxModal('#projectCategoryModal', url);
                        })
                        $('.addFeature').click(function () {
                            var url = '{{ route('super-admin.footer-settings.create')}}';
                            $('#modelHeading').html('Add Feature');
                            $.ajaxModal('#projectCategoryModal', url);
                        })

                        $('body').on('click', '.sa-params', function () {
                            var id = $(this).data('feature-id');
                            swal({
                                title: "Are you sure?",
                                text: "You will not be able to recover the deleted footer menu!",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Yes, delete it!",
                                cancelButtonText: "No, cancel please!",
                                closeOnConfirm: true,
                                closeOnCancel: true
                            }, function (isConfirm) {
                                if (isConfirm) {

                                    var url = "{{ route('super-admin.footer-settings.destroy',':id') }}";
                                    url = url.replace(':id', id);

                                    var token = "{{ csrf_token() }}";

                                    $.easyAjax({
                                        type: 'POST',
                                        url: url,
                                        data: {'_token': token, '_method': 'DELETE'},
                                        success: function (response) {
                                            if (response.status == "success") {
                                                $.unblockUI();
                                                window.location.reload();
                                            }
                                        }
                                    });
                                }
                            });
                        });

                    </script>
    @endpush