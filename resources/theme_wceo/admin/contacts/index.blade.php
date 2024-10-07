@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/daterange-picker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
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

                <div class="col">
                    
                    <div class="btn-group contacts bookmark pull-right" role="group">
                        <button class="btn btn-primary contactTypeadd btn-sm dropdown-toggle" id="btnGroupVerticalDrop1" type="button" data-toggle="dropdown">@lang('app.addNew') <i data-feather="plus"></i></button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                            <a class="dropdown-item" href="{{ route('admin.clients.create') }}">@lang('app.addClient')</a>
                            <a class="dropdown-item" href="{{ route('admin.leads.create') }}">@lang('app.addLead')</a>
                            <a class="dropdown-item" href="{{ route('admin.employees.create') }}">@lang('app.addEmployee')</a>
                        </div>
                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(4)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>
                      </div>
                </div>
               

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
                            <div class="media-body align-self-center count_by_status count4">
                                <div id="client">
                                    <p>@lang('modules.dashboard.totalActiveClients')</p>
                                    <h4><span class="counter">{{$totalClients}}</span></h4>
                                </div>
                                <div id="lead">
                                    <p>@lang('modules.dashboard.totalLeads') </p>
                                    <h4><span class="counter">{{$totalLeads}}</span></h4>
                                </div>
                                <div id="employee">
                                    <p>@lang('modules.dashboard.totalEmployees') </p>
                                    <h4><span class="counter">{{$totalEmployees}}</span></h4>
                                </div>
                                <div id="admin">
                                    <p>@lang('modules.dashboard.totalAdmins') </p>
                                    <h4><span class="counter">{{$totalAdmins}}</span></h4>
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
                                            
                                            <div class="left-filter wceo-left-filter">
                                                <div class="card-body filter-cards-view animate-chk">
                                                    <div class="product-filter">
                                                        <form action="" id="filter-form">
                                                            <div class="row"  id="ticket-filters">
                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.selectDateRange')</label>
                                                                        <div class="input-daterange input-group" id="date-range">
                                                                            <input type="text" class="form-control" autocomplete="off" id="start-date" placeholder="@lang('app.startDate')" value=""/>
                                                                            
                                                                            <div class="input-group-prepend input-group-append"><span class="input-group-text">@lang('app.to')</span></div>
                                                                            <input type="text" class="form-control" id="end-date"  autocomplete="off" placeholder="@lang('app.endDate')" value=""/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">User Type</label>
                                                                        <select class="hide-search form-control" name="usertype" id="usertype" data-style="form-control">
                                                                            <option value="all">@lang('modules.client.all')</option>
                                                                            <option value="client">@lang('modules.lead.client')</option>
                                                                            <option value="lead">@lang('modules.lead.lead')</option>
                                                                            <option value="employee">@lang('modules.lead.employee')</option>
                                                                            <option value="admin">@lang('modules.lead.admin')</option>
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

                    @if($totalLeads == 0 && $totalAdmins ==0 && $totalClients ==0 && $totalEmployees ==0)
                        <div class="col-md-12">
                            <div class="empty-content text-center">
                                <img src="{{ asset('img/empty/contacts.jpg') }}" alt="user-img" /><br />
                                <b>No Contacts</b><br />
                                No contacs added yet.<br />
                                <a href="{{ route('admin.clients.create') }}" class="btn btn-primary btn-sm m-t-20">Add Contact</a>
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
    <script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/daterangepicker.js')}}"></script>


    {!! $dataTable->scripts() !!}

    <script>
        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        
        function dateRangepicker()
        {
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
        }

        dateRangepicker();
        
        var table;


        $('.toggle-filter').click(function () {
            $('#ticket-filters').toggle('slide');
        })

        function showTable()
        {
            $('#employees-table').on('preXhr.dt', function (e, settings, data) {

                var startDate = $('#start-date').val();

                if (startDate == '') {
                    startDate = null;
                }

                var endDate = $('#end-date').val();

                if (endDate == '') {
                    endDate = null;
                }

                var usertype = $('#usertype').val();
                data['startDate'] = startDate;
                data['endDate'] = endDate;

                var usertype = $('#usertype').val();

                data['usertype'] = usertype;           
                data['usertype_by'] = null;
            });
            dateRangepicker();
            window.LaravelDataTables["employees-table"].search('').draw();
        }

        $('#apply-filters').click(function (event) {
            event.preventDefault();
            $('.filter-section .count_by_status > div').removeClass('active');
            showTable();
        });
       

        $('.count_by_status div').on('click', function(event) {
            $('.count_by_status > div').removeClass('active');
            $(this).addClass('active');
            $('#usertype').val($(this).attr('id')).trigger('change');
            showTable();
        });  

        

        $('#reset-filters').click(function () {
            $('#filter-form')[0].reset();
            $('#usertype').val('all');
            $('.select2').val('all');
            $('#filter-form').find('select').select2();
            $('.count_by_status > div').removeClass('active');
            showTable();
        })

        function exportData(){

            var client = $('#client').val();
            var status = $('#status').val();

            var url = '{{ route('admin.clients.export', [':status', ':client']) }}';
            url = url.replace(':client', client);
            url = url.replace(':status', status);

            window.location.href = url;
        }

        

    </script>
@endpush