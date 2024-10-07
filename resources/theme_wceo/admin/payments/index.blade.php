@extends('layouts.app')

@push('head-script')
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
                        <a href="{{ route('admin.payments.create') }}" class="btn btn-primary btn-sm">@lang('modules.payments.addPayment') <i data-feather="plus"></i> </a>

                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(12)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>
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
                                            <div class="left-filter wceo-left-filter" style="top: 68px;">
                                                <div class="card-body filter-cards-view animate-chk">
                                                    <div class="product-filter">
                                                        <form action="" id="filter-form">
                                                            <div class="row"  id="ticket-filters">
                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.selectDateRange')</label>
                                                                        <div class="input-daterange input-group" id="date-range">
                                                                            <input type="text" class="form-control"  autocomplete="off" id="start-date" placeholder="@lang('app.startDate')" value=""/>
                                                                            <div class="input-group-prepend input-group-append"><span class="input-group-text">@lang('app.to')</span></div>
                                                                            <input type="text" class="form-control"  autocomplete="off" id="end-date" placeholder="@lang('app.endDate')" value=""/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.status')</label>     
                                                                        <select class="form-control" name="status" id="status" data-style="form-control">
                                                                            <option value="all">@lang('app.all')</option>
                                                                            <option value="complete">@lang('app.completed')</option>
                                                                            <option value="pending">@lang('app.pending')</option>
                                                                        </select>                                                          
                                                                    </div>
                                                                </div>

                                                                <div class="product-filter col-md-12">                                                                    
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.job')</label>
                                                                        <select class="form-control" name="project" id="project" data-style="form-control">
                                                                            <option value="all">@lang('modules.client.all')</option>
                                                                            @forelse($projects as $project)
                                                                                <option value="{{$project->id}}">{{ $project->project_name }}</option>
                                                                            @empty
                                                                            @endforelse
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

                    @if($paymentCount == 0)
                        <div class="col-md-12 m-t-20">
                            <div class="empty-content text-center">
                                <img src="{{ asset('img/empty/payment.jpg') }}" alt="user-img" /><br />
                                <b>No Payments</b><br />
                                No payments added yet.<br />
                                <a href="{{ route('admin.payments.create') }}" class="btn btn-primary btn-sm m-t-20">Add Payment</a>
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
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

<script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/daterangepicker.js')}}"></script>

{!! $dataTable->scripts() !!}

<script>   

    $('#payments-table').on('preXhr.dt', function (e, settings, data) {
        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = null;
        }

        var status = $('#status').val();
        var project = $('#project').val();
        var client = $('#client').val();

        data['startDate'] = startDate;
        data['endDate'] = endDate;
        data['status'] = status;
        data['project'] = project;
        data['client'] = client;
    });
    
    var table;
    function tableLoad() {
            window.LaravelDataTables["payments-table"].draw();
        }
    $(function() {
       
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
        loadTable();

        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('payment-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted payment record!",
                icon: "{{ asset('img/warning.png')}}",
                buttons: ["CANCEL", "DELETE"],
                dangerMode: true
            }).then((willDelete) => {
                if (willDelete) {

                    var url = "{{ route('admin.payments.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.easyBlockUI('#payments-table');
                                tableLoad();
                                $.easyUnblockUI('#payments-table');
                            }
                        }
                    });
                }
            });
        });
        

        $('#import-excel').click(function () {
            $.easyAjax({
                url: '{{route('admin.payments.importExcel')}}',
                container: '#importExcel',
                type: "POST",
                redirect: true,
                file: (document.getElementById("import_file").files.length == 0) ? false : true
            })
        });


    });

    function loadTable(){
        window.LaravelDataTables["payments-table"].search('').draw();
    }

    $('.toggle-filter').click(function () {
        $('#ticket-filters').toggle('slide');
    })

    $('#apply-filters').click(function () {
        loadTable();
    });

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
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

        var project = $('#project').val();

        var url = '{{ route('admin.payments.export', [':startDate', ':endDate', ':status', ':project']) }}';
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':status', status);
        url = url.replace(':project', project);

        window.location.href = url;
    }


</script>
@endpush
