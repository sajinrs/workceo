@extends('layouts.super-admin')

@section('page-title')

@endsection

@push('head-script')
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/sweetalert2.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/select2.css')}}">


@endpush

@section('content')

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
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="{{ route('super-admin.companies.create') }}"
                           class="btn btn-primary btn-sm">@lang('app.add') @lang('app.company') <i class="fa fa-plus"
                                                                                                   aria-hidden="true"></i></a>

                    </div>
                </div>
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="product-grid">
                    <div class="feature-products">

                        <div class="row">
                            <div class="col-sm-3 p-absolute">
                                <div class="product-sidebar">
                                    <div class="filter-section">
                                        <div class="card">

                                            <div class="left-filter">
                                                <div class="card-body filter-cards-view animate-chk">
                                                    <div class="product-filter">
                                                        <form action="" id="filter-form">
                                                            <div class="row" id="ticket-filters">
                                                                <div class="product-filter col-sm-12">
                                                                    <label class="f-w-600">@lang('app.package')</label>
                                                                    <select class="form-control js-example-basic-single"
                                                                            name="package" id="package"
                                                                            data-style="form-control">
                                                                        <option value="all">@lang('app.all')</option>
                                                                        @foreach( $packages as $package)
                                                                            <option value="{{ $package->id }}">{{ ucwords($package->name) }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="product-filter col-sm-12 m-t-15 m-b-15">
                                                                    <label class="f-w-600">@lang('app.package') @lang('modules.invoices.type')</label>
                                                                    <select class="form-control js-example-basic-single"
                                                                            name="type" id="type"
                                                                            data-style="form-control">
                                                                        <option value="all">@lang('app.all')</option>
                                                                        <option value="monthly">@lang('app.monthly')</option>
                                                                        <option value="annual">@lang('app.annual')</option>
                                                                    </select>
                                                                </div>

                                                                <div class="product-filter wceo-filter col-sm-6  pr-0">
                                                                    <button type="button" id="apply-filters" class="btn btn-primary btn-block"> @lang('app.apply')</button>
                                                                </div>
                                                                <div class="product-filter wceo-filter col-sm-6">
                                                                    <button type="button" id="reset-filters" class="btn btn-outline-secondary btn-block pull-right"> @lang('app.reset')</button>
                                                                </div>


                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="product-wrapper-grid">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header d-none">
                                        <span class="d-none-productlist filter-toggle">
                                            <button class="btn btn-primary toggle-data">Filters<span class="ml-2"><i
                                                            class="" data-feather="chevron-down"></i></span></button>
                                        </span>
                                    </div>

                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="display" id="users-table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('app.name')</th>
                                                    <th>@lang('app.email')</th>
                                                    <th>@lang('app.package')</th>
                                                    <th>@lang('app.status')</th>
                                                    <th>@lang('app.details')</th>
                                                    <th>@lang('app.lastActivity')</th>
                                                    <th>@lang('app.action')</th>

                                                </tr>
                                                </thead>
                                            </table>
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

    <!-- Container-fluid Ends-->
    {{--Ajax Modal--}}

    <div class="modal fade" id="packageUpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="ajax-form" id="update-company-form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Change Package</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @method('PUT')

                        <div class="wceo-modal-body">
                            Loading...
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary"><i
                                        class="fa fa-check"></i> @lang('app.update')</button>

                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">@lang('app.back')</button>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="offlineMethod" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
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
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>



    <!-- Plugins JS start-->
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatables/datatable.custom.js')}}"></script>

    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/custom.js')}}"></script>



    <script src="{{ asset('themes/wceo/assets/js/sweet-alert/sweetalert.min.js')}}"></script>

    <script src="{{ asset('themes/wceo/assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/select2/select2-custom.js')}}"></script>

    <script>
        $(function () {
            var modal = $('#packageUpdateModal');
            tableLoad();
            $('#reset-filters').click(function () {
                $('#filter-form')[0].reset();
                $(".js-example-basic-single").val('all').trigger('change');
                tableLoad();
            });

            var table;
            $('#apply-filters').click(function () {
                tableLoad();
            });


            $('.toggle-filter').click(function () {
                $('#ticket-filters').toggle('slide');
            })

            $('body').on('click', '.package-update-button', function () {
                modal.find('.wceo-modal-body').html('Loading...');
                const url = '{{ route('super-admin.companies.edit-package.get', ':companyId') }}'.replace(':companyId', $(this).data(
                    'company-id'
                ));
                $.easyAjax({
                    type: 'GET',
                    url: url,
                    blockUI: false,
                    messagePosition: "inline",
                    success: function (response) {
                        if (response.status === "success" && response.data) {
                            modal.find('.wceo-modal-body').html(response.data).closest('#packageUpdateModal').modal('show');
                            tableLoad();
                        } else {
                            modal.find('.wceo-modal-body').html('Loading...').closest('#packageUpdateModal').modal('show');
                        }
                    }
                });
            });

            $('#offlineMethod').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            })
        });


        tableLoad = () => {
            var packageName = $('#package').val();
            var packageType = $('#type').val();

            table = $('#users-table').DataTable({
                dom: "<'row'<'col-md-6'Bl><'col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                responsive: true,
                processing: true,
                serverSide: true,
                stateSave: true,
                destroy: true,

                buttons: [
                    {
                        text: 'Filters<span class="ml-2"><i class="fa fa-angle-down"></i></span>',
                        className: 'filterBtn',
                        action: function (e, dt, node, config) {
                            $('.toggle-data').trigger('click');
                        }
                    },

                ],
                ajax: '{!! route('super-admin.companies.data') !!}?package=' + packageName + '&type=' + packageType,
                language: {
                    "url": "<?php echo __("app.datatable") ?>"
                },
                "fnDrawCallback": function (oSettings) {
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'company_name', name: 'company_name'},
                    {data: 'company_email', name: 'company_email'},
                    {data: 'package', name: 'package.name', 'sortable': false},
                    {data: 'status', name: 'status'},
                    {data: 'details', name: 'details', 'sortable': false},
                    {data: 'last_login', name: 'last_login'},
                    {data: 'action', name: 'action'}
                ]
            });


        }
        $(function () {

            $('body').on('click', '.sa-params', function () {
                var id = $(this).data('user-id');
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover the deleted company!",
                    icon: "warning",
                    buttons: ["No, cancel please!", "Yes, delete it!"],
                    dangerMode: true
                })
                    .then((willDelete) => {
                        if (willDelete) {

                            var url = "{{ route('super-admin.companies.destroy',':id') }}";
                            url = url.replace(':id', id);

                            var token = "{{ csrf_token() }}";

                            $.easyAjax({
                                type: 'POST',
                                url: url,
                                data: {'_token': token, '_method': 'DELETE'},
                                success: function (response) {
                                    if (response.status == "success") {
                                        $.unblockUI();
                                        var total = $('#totalCompanies').text();
                                        $('#totalCompanies').text(parseInt(total) - parseInt(1));
                                        //table._fnDraw();
                                        tableLoad();
                                    }
                                }
                            });
                        }
                    });
            });
        });


    </script>
@endpush
