@extends('layouts.app')
@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/daterange-picker.css') }}">
@endpush

@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="{{ route('admin.all-invoices.create') }}" class="btn btn-primary btn-sm">@lang('modules.invoices.addInvoice') <i data-feather="plus"></i></a>

                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(9)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>
                    </div>
                </div>
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>

    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-md-12 col-sm-12">
                <div class="filter-section">
                    <div class="card browser-widget">
                        <div class="media card-body" style="padding: 10px">
                            <div class="media-body align-self-center count_by_status count5">
                                <div id="paidInvoicesThisMonth">
                                    <p>@lang('modules.dashboard.paidInvoicesThisMonth')</p>
                                    <h4><span class="counter">{{$paidInvoicesThisMonth}}</span></h4>
                                </div>
                                <div id="paidInvoicesThisYear">
                                    <p>@lang('modules.dashboard.paidInvoicesThisYear') </p>
                                    <h4><span class="counter">{{$paidInvoicesThisYear}}</span></h4>
                                </div>
                                <div id="unpaidInvoices">
                                    <p>@lang('modules.dashboard.unpaidInvoices') </p>
                                    <h4><span class="counter">{{$unpaidInvoices}}</span></h4>
                                </div>
                                <div id="unpaidInvoicesAmt">
                                    <p>@lang('modules.dashboard.unpaidInvoices')</p>
                                    <h4><label style="color:#B2B2B2">$</label><span class="counter">{{number_format($unpaidInvoicesAmt, 2, '.', ',')}}</span></h4>
                                </div>
                                <div id="partiallyPaidInvoices">
                                    <p>Waiting for Approval </p>
                                    <h4><span class="counter">{{$partiallyPaidInvoices}}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="product-grid">
                    <div class="feature-products">

                        <div class="row">
                            <div class="col-sm-3 p-absolute product-sidebar-col">
                                <div class="product-sidebar" style="top: 60px !important;">
                                    <div class="filter-section">
                                        <div class="card">                                            
                                            <div class="left-filter wceo-left-filter taskFilter">
                                                <div class="card-body filter-cards-view animate-chk">
                                                    <div id="ticket-filters" class="product-filter">
                                                        {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}

                                                        <div class="row"  id="ticket-filters">
                                                            <div class="product-filter col-md-12">
                                                                <div class="form-group">
                                                                    <label class="f-w-600">@lang('app.selectDateRange')</label>

                                                                    <div class="input-daterange input-group" id="date-range">
                                                                        <input type="text" class="form-control" id="start-date" placeholder="@lang('app.startDate')"
                                                                               value=""/>
                                                                        <div class="input-group-prepend input-group-append"><span class="input-group-text">@lang('app.to')</span></div>
                                                                        <input type="text" class="form-control" id="end-date" placeholder="@lang('app.endDate')"
                                                                               value=""/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="product-filter col-md-12">

                                                                <div class="form-group">
                                                                    <label  class="f-w-600">@lang('app.project')</label>

                                                                    <select class="form-control select2" name="projectID" id="projectID" data-style="form-control">
                                                                        <option value="all">@lang('app.all')</option>
                                                                        @forelse($projects as $project)
                                                                            <option value="{{$project->id}}">{{ ucfirst($project->project_name) }}</option>
                                                                        @empty
                                                                        @endforelse
                                                                    </select>

                                                                </div>
                                                            </div>
                                                            <div class="product-filter col-md-12">

                                                                <div class="form-group">
                                                                    <label  class="f-w-600">@lang('app.status')</label>

                                                                    <select class="form-control" name="status" id="status" data-style="form-control">
                                                                        <option value="all">@lang('app.all')</option>
                                                                        <option value="unpaid">@lang('app.unpaid')</option>
                                                                        <option value="paid">@lang('app.paid')</option>
                                                                        <option value="partial">@lang('app.partial')</option>
                                                                    </select>

                                                                </div>
                                                            </div>

                                                            <div class="product-filter col-md-12">

                                                                <div class="form-group">
                                                                    <label  class="f-w-600">@lang('app.client')</label>

                                                                    <select class="form-control select2" name="clientID" id="clientID" data-style="form-control">
                                                                        <option value="all">@lang('app.all')</option>
                                                                        @foreach($clients as $client)
                                                                            <option value="{{ $client->id }}">{{ ucwords($client->name) }}
                                                                                @if($client->company_name != '') {{ '('.$client->company_name.')' }} @endif</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="product-filter wceo-filter col-sm-6  pr-0">
                                                                    <button type="button" id="apply-filters" class="btn btn-primary btn-block"> @lang('app.apply')</button>
                                                                </div>
                                                                <div class="product-filter wceo-filter col-sm-6">
                                                                    <button type="button" id="reset-filters" class="btn btn-outline-secondary btn-block pull-right"> @lang('app.reset')</button>
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

                    @if($projects->isEmpty())
                        <div class="col-md-12">
                            <div class="empty-content text-center">
                                <img src="{{ asset('img/empty/invoice.jpg') }}" alt="user-img" /><br />
                                <b>No Invoices</b><br />
                                No invoices added yet.<br />
                                <a href="{{ route('admin.all-invoices.create') }}" class="btn btn-primary btn-sm m-t-20">Add Invoice</a>
                            </div>
                        </div>
                    @else  
                        <div class="product-wrapper-grid">
                            <div class="row">

                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header d-none">
                                            <span class="d-none-productlist filter-toggle">
                                                <button class="btn btn-primary toggle-data">Filters<span class="ml-2"><i class="" data-feather="chevron-down"></i></span></button>
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <div class="dt-ext table-responsive">
                                                {!! $dataTable->table(['class' => 'display']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>





    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="invoiceUploadModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">@lang('modules.invoices.uploadInvoice')</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.close')</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}
    <div class="modal fade bs-modal-md in" id="offlinePaymentDetails" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
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
<script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/daterangepicker.js')}}"></script>
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

{!! $dataTable->scripts() !!}
<script>
    
    $('body').on('click', '.reminderButton', function(){
        var id = $(this).data('invoice-id');
            swal({
                title: "Are you sure?",
                text: "Do you want to send reminder to assigned client?",
                icon: "warning",
                buttons: ["No, cancel please!", "Yes, send it!"],
                dangerMode: true
        })
        .then((willDelete) => {
                if (willDelete) {
                    var url = "{{ route('admin.all-invoices.payment-reminder',':id') }}";
                    url = url.replace(':id', id);
                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'GET',
                        url: url,
                        data: {'_token': token},
                        
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                loadTable();
                            }
                        }
                    });
                }
            });
        }); 
    

    $('body').on('click', '.verify', function() {
        var id = $(this).data('invoice-id');

        var url = '{{ route('admin.all-invoices.payment-verify', ':id') }}'
        url = url.replace(':id', id);

        $.ajaxModal('#offlinePaymentDetails', url);
    });

    var table;
    $(function() {
        loadTable();
        jQuery('#start-date, #end-date').daterangepicker({
            locale: {
                format: 'MM-DD-YYYY'
            },
            "alwaysShowCalendars": true,
            autoApply: true,
            autoUpdateInput: false,
            language: '{{ $global->locale }}',
            weekStart:'{{ $global->week_start }}',
        }, function(start, end, label) {
            var selectedStartDate = start.format('MM-DD-YYYY'); // selected start
            var selectedEndDate = end.format('MM-DD-YYYY'); // selected end

            $startDateInput = $('#start-date');
            $endDateInput = $('#end-date');

            // Updating Fields with selected dates
            $startDateInput.val(selectedStartDate);
            $endDateInput.val(selectedEndDate);
            var endDatePicker = $endDateInput.data('daterangepicker');
            endDatePicker.setStartDate(selectedStartDate);
            endDatePicker.setEndDate(selectedEndDate);
            var startDatePicker = $startDateInput.data('daterangepicker');
            startDatePicker.setStartDate(selectedStartDate);
            startDatePicker.setEndDate(selectedEndDate);

        });

        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('invoice-id');
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover the deleted invoice!",
                    icon: "{{ asset('img/warning.png')}}",
                    buttons: ["CANCEL", "DELETE"],
                    dangerMode: true
            })
            .then((willDelete) => {
                    if (willDelete) {
                        var url = "{{ route('admin.all-invoices.destroy',':id') }}";
                        url = url.replace(':id', id);
                        var token = "{{ csrf_token() }}";
                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            
                            success: function (response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    loadTable();
                                }
                            }
                        });
                    }
                });
        }); 

        

        $('body').on('click', '.unpaidAndPartialPaidCreditNote', function(){
            var id = $(this).data('invoice-id');
                swal({
                    title: "Are you sure that you want to create the credit note?",
                    text: "When creating credit note from non paid invoice, the credit note amount will get applied for this invoice.",
                    icon: "info",
                    buttons: ["No, cancel please!", "Yes, create it!"],
                    dangerMode: true
            })
            .then((willDelete) => {
                    if (willDelete) {
                        var url = "{{ route('admin.all-credit-notes.convert-invoice',':id') }}";
                        url = url.replace(':id', id);
                        location.href = url;
                    }
                });
            });
        

        $('.table-responsive').on('click', '.invoice-upload', function(){
            var invoiceId = $(this).data('invoice-id');
            $('#file-upload-dropzone').prepend('<input name="invoice_id", value="' + invoiceId + '" type="hidden">');
        });
    });

    function loadTable(){
        $('#invoices-table').on('preXhr.dt', function (e, settings, data) {
            var startDate = $('#start-date').val();

            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if (endDate == '') {
                endDate = null;
            }

            var status = $('#status').val();
            var projectID = $('#projectID').val();
            var clientID = $('#clientID').val();

            data['startDate'] = startDate;
            data['endDate'] = endDate;
            data['status'] = status;
            data['projectID'] = projectID;
            data['clientID'] = clientID;
            data['status_by'] = null;
        });


        window.LaravelDataTables["invoices-table"].search('').draw();
    }
    
    function toggleShippingAddress(invoiceId) {
        let url = "{{ route('admin.all-invoices.toggleShippingAddress', ':id') }}";
        url = url.replace(':id', invoiceId);

        $.easyAjax({
            url: url,
            type: 'GET',
            success: function (response) {
                if (response.status === 'success') {
                    loadTable();
                }
            }
        })
    }

    function addShippingAddress(invoiceId) {
        let url = "{{ route('admin.all-invoices.shippingAddressModal', ':id') }}";
        url = url.replace(':id', invoiceId);

        $.ajaxModal('#invoiceUploadModal', url);
    }    

    $('.toggle-filter').click(function () {
        $('#ticket-filters').toggle('slide');
    })

    $('#apply-filters').click(function () {
        $('.filter-section .count_by_status > div').removeClass('active');
        loadTable();
    });

    $('#reset-filters').click(function () {
        $('#storePayments')[0].reset();
        $('#projectID').val('all');
        $('#clientID').val('all');
        $('#status').trigger('change');
        $('#projectID').select2();
        $('#clientID').select2();
        $('#start-date').val('');
        $('#end-date').val('');
        $('#filter-form').find('select').select2();
        loadTable();
    })

    function exportData(){

        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = null;
        }

        var status = $('#status').val();
        var projectID = $('#projectID').val();

        var url = '{{ route('admin.all-invoices.export', [':startDate', ':endDate', ':status', ':projectID']) }}';
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':status', status);
        url = url.replace(':projectID', projectID);

        window.location.href = url;
    }


    // Change Status As cancelled
    $('body').on('click', '.sa-cancel', function(){
        var id = $(this).data('invoice-id');
            swal({
                title: "Are you sure?",
                text: "Do you want to change invoice in canceled !",
                icon: "warning",
                buttons: ["No, cancel please!", "Yes, do it!"],
                dangerMode: true
        })
        .then((willDelete) => {
                if (willDelete) {
                    var url = "{{ route('admin.all-invoices.update-status',':id') }}";
                    url = url.replace(':id', id);
                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'GET',
                        url: url,
                        data: {'_token': token},
                        
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                loadTable();
                            }
                        }
                    });
                }
            });
        });
        // Change Status As Uncancelled
        $('body').on('click', '.sa-uncancel', function(){
        var id = $(this).data('invoice-id');
            swal({
                title: "Are you sure?",
                text: "Do you want to Uncancel invoice !",
                icon: "warning",
                buttons: ["NO", "YES"],
                dangerMode: true
        })
        .then((willDelete) => {
                if (willDelete) {
                    var url = "{{ route('admin.all-invoices.update-status',':id') }}";
                    url = url.replace(':id', id);
                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'GET',
                        url: url,
                        data: {'_token': token},
                        
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                loadTable();
                            }
                        }
                    });
                }
            });
        });

    function showDataByStatus(status,status_by)
    {
        $('#status').val(status).trigger('change');
        $('#invoices-table').on('preXhr.dt', function (e, settings, data) {
            data['status_by'] = status_by;
        });
        window.LaravelDataTables["invoices-table"].search('').draw();
    }

    $('.count_by_status > div').on('click', function(event) {
        $('.count_by_status > div').removeClass('active');
        $(this).addClass('active');
        var id = $(this).attr('id');
        if(id === 'unpaidInvoices' || id == 'partiallyPaidInvoices' || id === 'unpaidInvoicesAmt'){
            if(id === 'unpaidInvoices'|| id === 'unpaidInvoicesAmt'){
                var status = 'unpaid';
                showDataByStatus('unpaid',null);
            }else{
                var status = 'partial';
                showDataByStatus('partial',null);
            }

           // $('#status').val(status).trigger('change');
           // loadTable();
        }else {
            showDataByStatus('paid',$(this).attr('id'));
        }

    });

    @if( Request::get('tab') == 'unpaid')
        $(document).ready(function(){
            $('#unpaidInvoices').click();
        });
    @endif

</script>
@endpush