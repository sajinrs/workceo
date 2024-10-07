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
                        <a href="{{ route('admin.contracts.create') }}" class="btn btn-primary btn-sm">@lang('modules.contracts.createContract') <i data-feather="plus"></i> </a>

                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(10)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>
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
            <div class="col-md-12 col-sm-12">
                <div class="filter-section">
                    <div class="card browser-widget">
                        <div class="media card-body" style="padding: 10px">
                            <div class="media-body align-self-center count_by_status">
                                <div id="contractCounts">
                                    <p>@lang('modules.contracts.totalContracts') </p>
                                    <h4><span class="counter">{{ $contractCounts }}</span></h4>
                                </div>
                                <div id="aboutToExpire"> 
                                    <p>@lang('modules.contracts.aboutToExpire') </p>
                                    <h4><span class="counter">{{ $aboutToExpireCounts }}</span></h4>
                                </div>
                                <div id="expiredCounts">
                                    <p>@lang('modules.contracts.expired') </p>
                                    <h4><span class="counter">{{ $expiredCounts }}</span></h4>
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
                                                                            <input type="text" class="form-control"  autocomplete="off" id="start-date" placeholder="@lang('app.startDate')"
                                                                                value=""/>
                                                                                <div class="input-group-prepend input-group-append"><span class="input-group-text">@lang('app.to')</span></div>
                                                                            <input type="text" class="form-control"  autocomplete="off" id="end-date" placeholder="@lang('app.endDate')"
                                                                                value=""/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.client')</label>
                                                                        <select class="form-control" data-placeholder="@lang('app.client')" name="client" id="clientID" data-style="form-control">
                                                                            <option value="all">@lang('app.all')</option>
                                                                            @foreach($clients as $client)
                                                                                <option value="{{ $client->id }}">{{ ucwords($client->company_name) }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>                                                                    
                                                                </div>

                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('modules.contracts.contractType')</label>
                                                                        <select class="form-control" data-placeholder="@lang('modules.contracts.contractType')" name="contractType" id="contractType" data-style="form-control">
                                                                            <option value="all">@lang('app.all')</option>
                                                                            @foreach($contractType as $type)
                                                                                <option value="{{ $type->id }}">{{ ucwords($type->name) }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>                                                                    
                                                                </div>

                                                                <div class="product-filter wceo-filter col-sm-6 pr-0">
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

                    @if($contractCounts == 0)
                        <div class="col-md-12">
                            <div class="empty-content text-center">
                                <img src="{{ asset('img/empty/contracts.jpg') }}" alt="user-img" /><br />
                                <b>No Contracts</b><br />
                                No contracts added yet.<br />
                                <a href="{{ route('admin.contracts.create') }}" class="btn btn-primary btn-sm m-t-20">Add Contract</a>
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


@section('content')
    
    <!-- .row -->

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
   
    var table;
    function tableLoad() {
            window.LaravelDataTables["leads-table"].draw();
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

        $('body').on('click', '.sa-params', function () {
            var id = $(this).data('contract-id');
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover the deleted contract!",
                    icon: "{{ asset('img/warning.png')}}",
                    buttons: ["CANCEL", "DELETE"],
                    dangerMode: true
            })
            .then((willDelete) => {
                    if (willDelete) {
                        var url = "{{ route('admin.contracts.destroy',':id') }}";
                        url = url.replace(':id', id);
                        var token = "{{ csrf_token() }}";
                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            
                            success: function (response) {
                                if (response.status == "success") {
                                    $.easyBlockUI('#contracts-table');
                                    window.LaravelDataTables["contracts-table"].draw();
                                    $.easyUnblockUI('#contracts-table');
                                }
                            }
                        });
                    }
                });
            });       

    });


    $('.toggle-filter').click(function () {
        $('#ticket-filters').toggle('slide');
    })
    $('#contracts-table').on('preXhr.dt', function (e, settings, data) {
        var startDate = $('#start-date').val();
        if (startDate == '') {
            startDate = null;
        }
        var endDate = $('#end-date').val();
        if (endDate == '') {
            endDate = null;
        }
        var clientID = $('#clientID').val();
        var contractType = $('#contractType').val();
        var status = $('#status').val();
        data['startDate'] = startDate;
        data['endDate'] = endDate;
        data['status'] = status;
        data['clientID'] = clientID;
        data['contractType'] = contractType;
    });

    $('#apply-filters').click(function () {
        loadTable();
    });

    function loadTable(){
        window.LaravelDataTables["contracts-table"].search('').draw();
    }

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
        $('#filter-form').find('select').select2();
        loadTable();
    });

    

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

        var url = '{{ route('admin.estimates.export', [':startDate', ':endDate', ':status']) }}';
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':status', status);

        window.location.href = url;
    }

    function showDataByStatus(id) 
    {
        $('#contracts-table').on('preXhr.dt', function (e, settings, data) {
            var status_by = id;
            data['status_by'] = status_by;
        });
        window.LaravelDataTables["contracts-table"].draw();
    }        

    $('.count_by_status > div').on('click', function(event) {
        $('.count_by_status > div').removeClass('active');
        $(this).addClass('active');
        showDataByStatus($(this).attr('id'));
    });

</script>
@endpush