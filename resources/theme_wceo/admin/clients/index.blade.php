@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/daterange-picker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dropzone.css')}}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> 
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
                        <a href="javascript:;" onclick="addClient()" class="btn btn-primary btn-sm">@lang('modules.client.addClient') <i data-feather="plus"></i></a>
                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(3)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>

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
                    <div class="card browser-widget bw-project">
                        <div class="media card-body" style="padding: 10px">
                            <div class="media-body align-self-center count5 count_by_status">
                                <div id="active">
                                    <p>@lang('app.active') @lang('app.menu.clients')</p>
                                    <h4><span class="counter">{{$activeClients}}</span></h4>
                                </div>
                                <div id="deactive">
                                    <p>@lang('app.inactive') @lang('app.menu.clients') </p>
                                    <h4><span class="counter">{{$deactiveClients}}</span></h4>
                                </div>
                                
                                <div id="thisweek">
                                    <p>@lang('app.created') @lang('app.menu.thisWeek') </p>
                                    <h4><span class="counter">{{$thisWeekClient}}</span></h4>
                                </div> 
                                <div id="thismonth">
                                    <p>@lang('app.created') @lang('app.menu.thisMonth') </p>
                                    <h4><span class="counter">{{$thisMonthClient}}</span></h4>
                                </div> 
                                <div id="thisyear">
                                    <p>@lang('app.created') @lang('app.menu.thisYear') </p>
                                   <h4><span class="counter">{{$thisYearClient}}</span></h4>
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
                                                                        <label class="f-w-600">@lang('app.status')</label>
                                                                        <select class="form-control" name="status" id="status">
                                                                            <option value="all">@lang('modules.client.all')</option>
                                                                            <option value="active">@lang('app.active')</option>
                                                                            <option value="deactive">@lang('app.inactive')</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.client')</label>
                                                                        <select class="form-control select2" name="client" id="client" data-style="form-control">
                                                                            <option value="all">@lang('modules.client.all')</option>
                                                                            @forelse($clients as $client)
                                                                                <option value="{{$client->id}}">{{ $client->company_name }}</option>
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

                    @if($totalClients == 0)
                        <div class="col-md-12">
                            <div class="empty-content text-center">
                                <img src="{{ asset('img/empty/client.jpg') }}" alt="user-img" /><br />
                                <b>No Clients</b><br />
                                No clients added yet.<br />
                                <a href="{{ route('admin.clients.create') }}" class="btn btn-primary btn-sm m-t-20">Add Client</a>
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

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-sm in import-form" id="import-client-form" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">Import Client List (.csv)</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30" /></button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route' => 'admin.clients.import-template-download','method'=>'POST']) !!}

                    <div class="media">
                        <img class="rounded-circle image-radius m-r-15" src="{{ asset('img/upload.svg') }}" alt="" width="58" height="58">
                        <div class="media-body">
                            <ol>
                                <li>Download the import template (below).</li>
                                <li>Fill in template. Follow the format closely.</li>
                                <li>Upload your client list.</li>
                            </ol>
                        </div>
                    </div>

                    <div class="notes">
                        Not sure how to format your file? <a href="http://www.workceo.com/importlist" target="_blank">Learn how</a> <br />
                        Ready to get started? <button class="download-btn" type="submit">Download Template</button>
                    </div>

                    
                    {!! Form::close() !!}

                    <form method="POST" id="importClientsForm2" action="{{ route('admin.clients.import') }}" class="dropzone ajax-form" >
                        <div class="dz-message needsclick"><i class="icon-cloud-up"></i>
                            <h6>Drop files here or click to upload a .CSV</h6>
                        </div>
                        {{ csrf_field() }}
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                    </form>
                    <button type="button" id='uploadfiles' class="btn btn-block btn-primary m-t-20"> Import Client List</button>

                </div>
                
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    {{--Ajax Modal Ends--}}

    {{--Client Add Modal--}}
    <div class="modal fade bs-modal-md in event-content" id="clientModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                @include('admin.event-calendar.client-add')
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

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
    <script src="{{ asset('themes/wceo/assets/js/dropzone/dropzone.js')}}"></script>

    {!! $dataTable->scripts() !!}

    <script>
        /* $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        }); */
        

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
        var table;
        $(function () {
            $('body').on('click', '.sa-params', function () {
                var id = $(this).data('user-id');
                swal({
                    
                    title: "Are you sure?",
                    text: "You will not be able to recover the deleted user!",
                    icon: "{{ asset('img/warning.png')}}",
                    buttons: ["CANCEL", "DELETE"],
                    dangerMode: true,
                    
            })
            .then((willDelete) => {
                    if (willDelete) {

                        var url = "{{ route('admin.clients.destroy',':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            success: function (response) {
                                if (response.status == "success") {
                                    $.easyBlockUI('#clients-table');
                                    window.LaravelDataTables["clients-table"].search('').draw();
                                    $.easyUnblockUI('#clients-table');
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

        $('#apply-filters').click(function () {
            $('#clients-table').on('preXhr.dt', function (e, settings, data) {
                var startDate = $('#start-date').val();

                if (startDate == '') {
                    startDate = null;
                }

                var endDate = $('#end-date').val();

                if (endDate == '') {
                    endDate = null;
                }

                var status = $('#status').val();
                var client = $('#client').val();
                data['startDate'] = startDate;
                data['endDate'] = endDate;
                data['status'] = status;
                data['client'] = client;
            });
            $.easyBlockUI('#clients-table');
            window.LaravelDataTables["clients-table"].search('').draw();
            $.easyUnblockUI('#clients-table');
        });

        $('#reset-filters').click(function () {
            $('#filter-form')[0].reset();
            $('#status').val('all');
            $('.select2').val('all');
            $('#filter-form').find('select').select2();
            $('.count_by_status > div').removeClass('active');
            $('#clients-table').on('preXhr.dt', function (e, settings, data) {
                data['status'] = null;
            });

            $.easyBlockUI('#clients-table');
            window.LaravelDataTables["clients-table"].search('').draw();
            $.easyUnblockUI('#clients-table');
        })

        function showDataByStatus(id) 
        {
            $('#clients-table').on('preXhr.dt', function (e, settings, data) {
                data['startDate'] = '';
                data['endDate']   = '';                
                data['client']    = '';
                var status_by = id;
                data['status'] = status_by;
            });
            window.LaravelDataTables["clients-table"].search('').draw();
        }        

        $('.count_by_status > div').on('click', function(event) {
            $('.count_by_status > div').removeClass('active');
            $(this).addClass('active');
            showDataByStatus($(this).attr('id'));
        });

        function exportData(){

            var client = $('#client').val();
            var status = $('#status').val();

            var url = "{{ route('admin.clients.export', [':status', ':client']) }}";
            url = url.replace(':client', client);
            url = url.replace(':status', status);

            window.location.href = url;
        }

        // Show import modal
        $('body').on('click', '.importClientList', function(){
            //var url = '{{ route('admin.contract-type.create-contract-type')}}';
            $('#import-client-form').modal('show');
        });

        // import Data
        /* function importData(){
            var url = "{{ route('admin.clients.import') }}";
            $.easyAjax({
                type: 'POST',
                url: url,
                container: '#importClientsForm',
                file: true,
                success: function (response) {
                    if(response.status == 'success'){
                        window.location.reload();
                    }
                },
                error: function (response) {
                    swal({
                            title: "Error",
                            text: "import failed!",
                            icon: "warning"
                        })

                }
            });
        } */


Dropzone.autoDiscover = false;       

var myDropzone = new Dropzone(".dropzone", { 
    paramName: "file",
    maxFiles: 1,
    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },
    uploadMultiple:false,
    autoProcessQueue: false,
    acceptedFiles: ".csv",
    parallelUploads: 1, 
    init: function () {
        
            this.on("success", function (file, response) {
                console.log(response);
                console.log(file);
                if(response.status == 'success'){
                    toastr.success(response.message,"Success");
                    $('#import-client-form').modal('hide');
                    window.LaravelDataTables["clients-table"].search('').draw();
                } else {
                   toastr.error(response.message,"Error");
                }
                /* if (myDropzone.files[0].status == Dropzone.SUCCESS ) {
                    //window.location.reload();
                } else {
                    swal({
                            title: "Error",
                            text: "import failed!",
                            icon: "warning"
                        })                    
                } */
                this.removeAllFiles(true); 
                $.unblockUI();
            })
        }
});

$('#uploadfiles').click(function(){
    $.easyBlockUI();
    myDropzone.processQueue();
}); 

$('body').addClass('custom-toaster');

function addClient()
{
    $('#clientModal').modal({
        show:true,
        backdrop: 'static',
        keyboard: false,
    });
}

$('#saveClient').click(function () {
    $.easyAjax({
        url: '{{route('admin.clients.store')}}',
        container: '#createClient',
        type: "POST",
        redirect: true,
        data: $('#createClient').serialize(),
        success: function (response) {
            if(response.status == 'success'){
                $('#client_id').html(response.teamData);
                $("#client_id").select2();
                $('#clientModal').modal('hide');
                $("#newClient").prop("checked", false);
                $('#createClient')[0].reset();
                $('#clients-table').DataTable().ajax.reload();
            }
        }
    })
});
</script>
@endpush