@extends('layouts.super-admin')
@push('head-script')
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
    <!-- Plugins css Ends-->
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
            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>@lang('app.menu.invoices') List</h5>
                        <span>{{ $totalInvoices }} @lang('app.total') @lang('app.menu.invoices')</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="users-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('app.company')</th>
                                    <th>@lang('app.package')</th>
                                    <th>@lang('modules.payments.transactionId')</th>
                                    <th>@lang('app.amount')</th>
                                    <th>@lang('app.date')</th>
                                    <th>@lang('modules.billing.nextPaymentDate')</th>
                                    <th>@lang('modules.payments.paymentGateway')</th>
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
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">

            </div>
        </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatables/datatable.custom.js')}}"></script>

    <script>
        $(function () {
            var table = $('#users-table').dataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: '{!! route('super-admin.invoices.data') !!}',
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
                    {data: 'company', name: 'company'},
                    {data: 'package', name: 'package'},
                    {data: 'transaction_id', name: 'transaction_id'},
                    {data: 'amount', name: 'amount'},
                    {data: 'paid_on', name: 'paid_on'},
                    {data: 'next_pay_date', name: 'next_pay_date'},
                    {data: 'method', name: 'method'},
                    {data: 'action', name: 'action'}
                ]
            });
        });

        function changePackage(id) {

        }
    </script>
@endpush