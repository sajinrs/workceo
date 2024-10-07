@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dropzone.css')}}">
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
                        <a href="{{ route('admin.leads.create') }}" class="btn btn-primary btn-sm">@lang('modules.lead.addNewLead') <i data-feather="plus"></i> </a>
                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(5)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>
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
                                <div id="totalLeads">
                                    <p>@lang('modules.dashboard.totalLeads') </p>
                                    <h4><span class="counter">{{ $totalLeads }}</span></h4>
                                </div>
                                <div id="totalClientConverted">
                                    <p>@lang('modules.dashboard.totalConvertedClient') </p>
                                    <h4><span class="counter">{{ $totalClientConverted }}</span></h4>
                                </div>
                                <div id="totalPendingFollowUps">
                                    <p>@lang('modules.dashboard.totalPendingFollowUps') </p>
                                    <h4><span class="counter">{{ $pendingLeadFollowUps }}</span></h4>
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
                                                                        <label class="f-w-600">@lang('modules.lead.client')</label>
                                                                        <select class="form-control selectpicker" name="client" id="client" data-style="form-control">
                                                                            <option value="all">@lang('modules.lead.all')</option>
                                                                            <option value="lead">@lang('modules.lead.lead')</option>
                                                                            <option value="client">@lang('modules.lead.client')</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">Lead Sponsor</label>
                                                                        <select class="form-control selectpicker select2" name="sponsor" id="sponsor" data-style="form-control">
                                                                            <option value="all">@lang('modules.lead.all')</option>
                                                                            @foreach($leadAgents as $emp)
                                                                                <option value="{{ $emp->id }}">{{ ucwords($emp->user->name) }} @if($emp->user->id == $user->id)
                                                                                        (YOU) @endif</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.status')</label>
                                                                        <select class="form-control selectpicker" name="followUp" id="followUp" data-style="form-control">
                                                                            <option value="all">@lang('modules.lead.all')</option>
                                                                            <option value="pending">@lang('modules.lead.pending')</option>
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

                    @if($totalLeads == 0)
                        <div class="col-md-12">
                            <div class="empty-content text-center">
                                <img src="{{ asset('img/empty/leads.jpg') }}" alt="user-img" /><br />
                                <b>No Leads</b><br />
                                No leads added yet.<br />
                                <a href="{{ route('admin.leads.create') }}" class="btn btn-primary btn-sm m-t-20">Add Lead</a>
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
    <div class="modal fade bs-modal-md in" id="followUpModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content border-0">
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
    {{--Ajax Modal Ends--}}

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-sm in import-form" id="import-leads-form" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">Import Lead List (.csv)</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30" /></button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route' => 'admin.leads.import-template-download','method'=>'POST']) !!}
                    <div class="media">
                        <img class="rounded-circle image-radius m-r-15" src="{{ asset('img/upload.svg') }}" alt="" width="58" height="58">
                        <div class="media-body">
                            <ol>
                                <li>Download the import template (below).</li>
                                <li>Fill in template. Follow the format closely.</li>
                                <li>Upload your lead list.</li>
                            </ol>
                        </div>
                    </div>

                    <div class="notes">
                        Not sure how to format your file? <a href="http://www.workceo.com/importlist" target="_blank">Learn how</a> <br />
                        Ready to get started? <button class="download-btn" type="submit">Download Template</button>
                    </div>

                    {!! Form::close() !!}

                    <form method="POST" id="importLeadsForm" action="{{ route('admin.leads.import') }}" class="dropzone ajax-form" >
                        <div class="dz-message needsclick"><i class="icon-cloud-up"></i>
                            <h6>Drop files here or click to upload a .CSV</h6>
                        </div>
                        {{ csrf_field() }}
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                    </form> 
                    <button type="button" id='uploadfiles' class="btn btn-block btn-primary m-t-20"> Import Lead List</button>

                </div>
               
            </div>
            <!-- /.modal-content -->
        </div>
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
<script src="{{ asset('themes/wceo/assets/js/dropzone/dropzone.js')}}"></script>


{!! $dataTable->scripts() !!}
<script>        
    var table;
    function tableLoad() {
        window.LaravelDataTables["leads-table"].search('').draw();
    }
    $(function() {
        tableLoad();
        $('#reset-filters').click(function () {
            $('#filter-form')[0].reset();
            $('#filter-form').find('select').select2();
            $('.count_by_status > div').removeClass('active');
            $('#leads-table').on('preXhr.dt', function (e, settings, data) {
                data['status_by'] = null;
            });
            $.easyBlockUI('#leads-table');
            tableLoad();
            $.easyUnblockUI('#leads-table');
        })
        var table;
        $('#apply-filters').click(function () {
            $('.count_by_status > div').removeClass('active');
            $('#leads-table').on('preXhr.dt', function (e, settings, data) {
                var client = $('#client').val();
                var followUp = $('#followUp').val();
                var sponsor = $('#sponsor').val();
                data['client'] = client;
                data['followUp'] = followUp;
                data['sponsor'] = sponsor;
                data['status_by'] = null;
            });
            $.easyBlockUI('#leads-table');
            tableLoad();
            $.easyUnblockUI('#leads-table');
        });

        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('user-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted lead!",
                icon: "{{ asset('img/warning.png')}}",
                buttons: ["CANCEL", "DELETE"],
                dangerMode: true
            }).then((willDelete) => {
                if (willDelete) {

                    var url = "{{ route('admin.leads.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.easyBlockUI('#leads-table');
                                tableLoad();
                                $.easyUnblockUI('#leads-table');
                            }
                        }
                    });
                }
            });
        });
    });

    function changeStatus(leadID, statusID){
        var url = "{{ route('admin.leads.change-status') }}";
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token,'leadID': leadID,'statusID': statusID},
            success: function (response) {
                if (response.status == "success") {
                $.easyBlockUI('#leads-table');
                tableLoad();
                $.easyUnblockUI('#leads-table');
                }
            }
        });
    }

    $('.edit-column').click(function () {
        var id = $(this).data('column-id');
        var url = '{{ route("admin.taskboard.edit", ':id') }}';
        url = url.replace(':id', id);

        $.easyAjax({
            url: url,
            type: "GET",
            success: function (response) {
                $('#edit-column-form').html(response.view);
                $(".colorpicker").asColorPicker();
                $('#edit-column-form').show();
            }
        })
    })

    function followUp (leadID) {

        var url = '{{ route('admin.leads.follow-up', ':id')}}';
        url = url.replace(':id', leadID);

        $('#modelHeading').html('Add Follow Up');
        $.ajaxModal('#followUpModal', url);
    }
    $('.toggle-filter').click(function () {
        $('#ticket-filters').toggle('slide');
    })
    function exportData(){

        var client = $('#client').val();
        var followUp = $('#followUp').val();

        var url = '{{ route('admin.leads.export', [':followUp', ':client']) }}';
        url = url.replace(':client', client);
        url = url.replace(':followUp', followUp);

        window.location.href = url;
    }


    function showDataByStatus(id) 
    {
        $('#leads-table').on('preXhr.dt', function (e, settings, data) {
            data['client'] = '';
            data['followUp'] = '';
            var status_by = id;
            data['status_by'] = status_by;
        });
        window.LaravelDataTables["leads-table"].draw();
    }        

    $('.count_by_status > div').on('click', function(event) {
        $('.count_by_status > div').removeClass('active');
        $(this).addClass('active');
        showDataByStatus($(this).attr('id'));
    });


    // Show import modal
    $('body').on('click', '.importLeadsList', function(){
        //$.ajaxModal('#import-leads-form');
        $('#import-leads-form').modal('show');
    });
    // import Data
    /* function importData(){
        var url = "{{ route('admin.leads.import') }}";
        $.easyAjax({
            type: 'POST',
            url: url,
            container: '#importLeadsForm',
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
                    $('#import-leads-form').modal('hide');
                    window.LaravelDataTables["leads-table"].draw();
                } else {
                   toastr.error(response.message,"Error");
                }
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
</script>
@endpush