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
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
    <style>
        .panel-black .panel-heading a, .panel-inverse .panel-heading a {
            color: unset !important;
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
                            <h5>@lang('app.menu.offlinePaymentMethod')</h5>
                        </div>
                        <div class="vtabs customvtab m-t-10">

                            @include('sections.super_admin_payment_setting_menu')

                            <div class="tab-content">
                                <div id="vhome3" class="tab-pane active">

                                    <div class="card-header">

                                        <div class="row">
                                            @if(!$offlineMethods->isEmpty())
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-xs-12">
                                                        <div class="form-group">
                                                            <a href="javascript:;" id="addMethod"
                                                               class="btn btn-outline btn-success btn-sm addMethod">@lang('modules.offlinePayment.addMethod')
                                                                <i class="fa fa-plus" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif

                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>@lang('app.menu.method')</th>
                                                        <th>@lang('app.description')</th>
                                                        <th>@lang('app.status')</th>
                                                        <th width="20%">@lang('app.action')</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse($offlineMethods as $key=>$method)
                                                        <tr>
                                                            <td>{{ ($key+1) }}</td>
                                                            <td>{{ ucwords($method->name) }}</td>
                                                            <td>{!! ucwords($method->description) !!} </td>
                                                            <td>@if($method->status == 'yes') <span
                                                                        class="badge badge-pill badge-primary">@lang('modules.offlinePayment.active')</span> @else
                                                                    <span class="badge badge-pill badge-primaryer">@lang('modules.offlinePayment.inActive')</span> @endif
                                                            </td>


                                                            <td>
                                                                <a href="javascript:;" data-type-id="{{ $method->id }}"
                                                                   class="edit-type m-t-5"><span
                                                                            class="icon-pencil"></span> @lang('app.edit')
                                                                </a>
                                                                <a href="javascript:;" data-type-id="{{ $method->id }}"
                                                                   class="delete-type m-t-5"><span
                                                                            class="fa fa-trash-o"></span> @lang('app.remove')
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center">
                                                                <div class="empty-space" style="height: 200px;">
                                                                    <div class="empty-space-inner">
                                                                        <div class="icon" style="font-size:30px"><i
                                                                                    class="fa fa-key"></i>
                                                                        </div>
                                                                        <div class="title m-b-15">@lang('messages.noMethodsAdded')
                                                                        </div>
                                                                        <div class="subtitle">
                                                                            <a href="javascript:;"
                                                                               class="btn btn-outline btn-success btn-sm addMethod">@lang('modules.offlinePayment.addMethod')
                                                                                <i class="fa fa-plus"
                                                                                   aria-hidden="true"></i></a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <!-- .row -->


            {{--Ajax Modal--}}
            <div class="modal fade bs-modal-md in" id="leadStatusModal" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-md" id="modal-data-application">
                    <div class="modal-content">

                        <div class="modal-body" id="modelHeadings">
                            Loading...
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

                    //    save project members
                    $('#save-type').click(function () {
                        $.easyAjax({
                            url: '{{route('super-admin.offline-payment-setting.store')}}',
                            container: '#createMethods',
                            type: "POST",
                            data: $('#createMethods').serialize(),
                            success: function (response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    window.location.reload();
                                }
                            }
                        })
                    });


                    $('body').on('click', '.delete-type', function () {
                        var id = $(this).data('type-id');
                        swal({
                            title: "Are you sure?",
                            text: "This will remove the method from the list.",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes!",
                            cancelButtonText: "No, cancel please!",
                            closeOnConfirm: true,
                            closeOnCancel: true
                        }, function (isConfirm) {
                            if (isConfirm) {

                                var url = "{{ route('super-admin.offline-payment-setting.destroy',':id') }}";
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


                    $('.edit-type').click(function () {
                        var typeId = $(this).data('type-id');
                        var url = '{{ route("super-admin.offline-payment-setting.edit", ":id")}}';
                        url = url.replace(':id', typeId);

                        $('#modelHeading').html("{{  __('app.edit')." ".__('modules.offlinePayment.title') }}");
                        // $.ajaxModal('#leadStatusModal', url);

                        $('#leadStatusModal').modal('show');
                        $.easyAjax({
                            url: url,
                            type: "GET",
                            dataType: 'html',
                            success: function (response) {
                                $('#modelHeadings').html(response);
                            }
                        })
                    })
                    $('.addMethod').click(function () {
                        var url = '{{ route("super-admin.offline-payment-setting.create")}}';
                        $('#modelHeading').html("{{  __('app.edit')." ".__('modules.offlinePayment.title') }}");

                        $('#leadStatusModal').modal('show');
                        $.easyAjax({
                            url: url,
                            type: "GET",
                            dataType: 'html',
                            success: function (response) {
                                $('#modelHeadings').html(response);
                            }
                        })

                        // $.ajaxModal('#leadStatusModal', url);
                    })


                </script>


    @endpush

