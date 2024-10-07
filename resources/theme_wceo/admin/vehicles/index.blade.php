@extends('layouts.app')

@push('head-script')
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dropzone.css')}}">
    <style>
        .left-filter {width: 100% !important;}
        .feature-products form .form-group input{height:auto;}
    </style>
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
                        <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary btn-sm">@lang('modules.vehicles.addNewVehicle') <i data-feather="plus"></i> </a>

                        <a href="javascript:;" style="width: 203px;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(13)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>
                    </div>
                </div>
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>

    <div class="container-fluid product-wrapper">
            <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="filter-section">
                    <div class="card browser-widget bw-employee">
                        <div class="media card-body" style="padding: 10px">
                            <div class="media-body align-self-center count_by_status">
                                <div id="allVehicles">
                                    <p>Total Vehicles </p>
                                    <h4><span class="counter">{{ $vechicleCount }}</span></h4>
                                </div>
                                <div id="notAssigned">
                                    <p>Operators Not Assigned To Vehicle </p>
                                    <h4><span class="counter">{{ $vehicleNotAssigned }}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <!-- Zero Configuration  Starts-->
                <div class="col-sm-12">
                    <div class="product-grid">
                        <div class="feature-products">

                            <div class="row">
                                <div class="col-sm-3 p-absolute product-sidebar-col">
                                    <div class="product-sidebar" style="top: 60px !important;">
                                        <div class="filter-section">
                                            <div class="card">

                                                <div class="left-filter wceo-left-filter taskFilter" style=";width:100%; top: 68px;">
                                                    <div class="card-body filter-cards-view animate-chk">
                                                        <div class="product-filter">
                                                            <form action="" id="filter-form">
                                                                <div class="row"  id="ticket-filters">
                                                                    <div class="product-filter col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="f-w-600">@lang('app.status')</label>
                                                                            <select class="form-control" name="status" id="status" data-style="form-control">
                                                                                <option value="all">@lang('modules.client.all')</option>
                                                                                <option value="active">Active</option>
                                                                                <option value="in_shop">In Shop</option>
                                                                                <option value="out_of_service">Out of Service</option>
                                                                                <option value="inactive">Inactive</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    {{--<div class="product-filter col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="f-w-600">@lang('modules.employees.title')</label>
                                                                            <select class="form-control select2" name="employee" id="employee" data-style="form-control">
                                                                                <option value="all">@lang('modules.client.all')</option>
                                                                                @forelse($employees as $employee)
                                                                                    <option value="{{$employee->id}}">{{ ucfirst($employee->name) }}</option>
                                                                                @empty
                                                                                @endforelse
                                                                            </select>
                                                                        </div>
                                                                    </div>--}}

                                                                    {{--<div class="product-filter col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="f-w-600">@lang('app.skills')</label>
                                                                            <select class="select2 select2-multiple " multiple="multiple"
                                                                                    data-placeholder="Choose Skills" name="skill[]" id="skill" data-style="form-control">
                                                                                <option value="all">@lang('modules.client.all')</option>
                                                                                @forelse($skills as $skill)
                                                                                    <option value="{{$skill->id}}">{{ ucfirst($skill->name) }}</option>
                                                                                @empty
                                                                                @endforelse
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="product-filter col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="f-w-600">@lang('modules.employees.role')</label>
                                                                            <select class="form-control select2" name="role" id="role" data-style="form-control">
                                                                                <option value="all">@lang('modules.client.all')</option>
                                                                                @forelse($roles as $role)
                                                                                    @if ($role->id <= 3)
                                                                                        <option value="{{$role->id}}">{{ __('app.' . $role->name) }}</option>
                                                                                    @else
                                                                                        <option value="{{$role->id}}">{{ ucfirst($role->name )}}</option>
                                                                                    @endif
                                                                                @empty
                                                                                @endforelse
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="product-filter col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="f-w-600">@lang('app.designation')</label>
                                                                            <select class="form-control select2" name="designation" id="designation" data-style="form-control">
                                                                                <option value="all">@lang('modules.client.all')</option>
                                                                                @forelse($designations as $designation)
                                                                                    <option value="{{$designation->id}}">{{ ucfirst($designation->name) }}</option>
                                                                                @empty
                                                                                @endforelse
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="product-filter col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="f-w-600">Department</label>
                                                                            <select class="form-control select2" name="department" id="department" data-style="form-control">
                                                                                <option value="all">@lang('modules.client.all')</option>
                                                                                @forelse($departments as $department)
                                                                                    <option value="{{$department->id}}">{{ ucfirst($department->team_name) }}</option>
                                                                                @empty
                                                                                @endforelse
                                                                            </select>
                                                                        </div>
                                                                    </div>--}}

                                                                    <div class="product-filter wceo-filter col-sm-6">
                                                                        <button type="button" id="apply-filters" class="btn btn-primary btn-block">@lang('app.apply')</button>
                                                                    </div>
                                                                    <div class="product-filter wceo-filter col-sm-6">
                                                                        <button type="button" id="reset-filters" class="btn btn-outline-secondary btn-block pull-right">@lang('app.reset')</button>
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

                        @if($vechicleCount == 0)
                            <div class="col-md-12">
                                <div class="empty-content text-center">
                                    <img src="{{ asset('img/empty/employees.jpg') }}" alt="user-img" /><br />
                                    <b>No Vehicles</b><br />
                                    No vehicles added yet.<br />
                                    <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary btn-sm m-t-20">Add Vehicle</a>
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
    <div class="modal fade bs-modal-sm in import-form" id="import-employees-form" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">Import Employee List (.csv)</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30" /></button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route' => 'admin.employees.import-template-download','method'=>'POST']) !!}
                        <div class="media">
                            <img class="rounded-circle image-radius m-r-15" src="{{ asset('img/upload.svg') }}" alt="" width="58" height="58">
                            <div class="media-body">
                                <ol>
                                    <li>Download the import template (below).</li>
                                    <li>Fill in template. Follow the format closely.</li>
                                    <li>Upload your employee list.</li>
                                </ol>
                            </div>
                        </div>

                        <div class="notes">
                            Not sure how to format your file? <a href="http://www.workceo.com/importlist" target="_blank">Learn how</a> <br />
                            Ready to get started? <button class="download-btn" type="submit">Download Template</button>
                        </div>
                    {!! Form::close() !!}

                    <form method="POST" id="importLeadsForm" action="{{ route('admin.employees.import') }}" class="dropzone ajax-form" >
                        <div class="dz-message needsclick"><i class="icon-cloud-up"></i>
                            <h6>Drop files here or click to upload a .CSV</h6>
                        </div>
                        {{ csrf_field() }}
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                    </form> 
                    <button type="button" id='uploadfiles' class="btn btn-block btn-primary m-t-20"> Import Employee List</button>

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
<style>
    .select2-container-multi .select2-choices .select2-search-choice {
        background: #ffffff !important;
    }
</style>
<script>    
    var table;

    $(function() {
        loadTable();

        $('body').on('click', '.sa-params', function () {
                var id = $(this).data('vehicle-id');
                swal({

                    title: "Are you sure?",
                    text: "You will not be able to recover the deleted vehicle!",
                    icon: "{{ asset('img/warning.png')}}",
                    buttons: ["CANCEL", "DELETE"],
                    dangerMode: true
            })
            .then((willDelete) => {
                    if (willDelete) {

                        var url = "{{ route('admin.vehicles.destroy',':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            success: function (response) {
                                if (response.status == "success") {
                                    $.easyBlockUI('#vehicles-table');
                                    loadTable();
                                    $.easyUnblockUI('#vehicles-table');
                                }
                            }
                        });
                    }
                });
            });    

        
    });
    function loadTable(){
        $('#contracts-table').on('preXhr.dt', function (e, settings, data) {
            var employee = $('#employee').val();
            var status   = $('#status').val();
            var role     = $('#role').val();
            var skill     = $('#skill').val();
            var designation     = $('#designation').val();
            var department     = $('#department').val();
            data['employee'] = employee;
            data['status'] = status;
            data['role'] = role;
            data['skill'] = skill;
            data['designation'] = designation;
            data['department'] = department;
            data['status_by'] = null;
        });

        window.LaravelDataTables["vehicles-table"].search('').draw();
    }

    $('.toggle-filter').click(function () {
        $('#ticket-filters').toggle('slide');
    })

    $('#apply-filters').click(function () {
        $('#vehicles-table').on('preXhr.dt', function (e, settings, data) {
            var employee = $('#employee').val();
            var status   = $('#status').val();
            var role     = $('#role').val();
            var skill     = $('#skill').val();
            var designation     = $('#designation').val();
            var department     = $('#department').val();
            data['employee'] = employee;
            data['status'] = status;
            data['role'] = role;
            data['skill'] = skill;
            data['designation'] = designation;
            data['department'] = department;
        });
        $('.filter-section .count_by_status > div').removeClass('active');
        loadTable();
    });

    $('#reset-filters').click(function () {
        $('.filter-section .count_by_status > div').removeClass('active');
        $('#filter-form')[0].reset();
        $('#status').val('all');
        $('.select2').val('all');
        $('#filter-form').find('select').select2();
        $('#vehicles-table').on('preXhr.dt', function (e, settings, data) {
            data['status_by'] = null;
        });
        loadTable();
    })

    function exportData(){

        var employee = $('#employee').val();
        var status   = $('#status').val();
        var role     = $('#role').val();

        var url = '{{ route('admin.employees.export', [':status' ,':employee', ':role']) }}';
        url = url.replace(':role', role);
        url = url.replace(':status', status);
        url = url.replace(':employee', employee);

        window.location.href = url;
    }

    $(".filter-toggle").click(function(){
        if($(".product-sidebar").hasClass("open")){
            $('.product-sidebar-col').show();
            $('.taskFilter').slimScroll({
                height: '520px'
            });
        }else{
            $('.product-sidebar-col').hide();
        }
    });

    function showDataByStatus(id) 
    {
        $('#vehicles-table').on('preXhr.dt', function (e, settings, data) {
            var status_by = id;
            data['status_by'] = status_by;
        });
        window.LaravelDataTables["vehicles-table"].search('').draw();
    }        

    $('.filter-section .count_by_status > div').on('click', function(event) {
        $('.filter-section .count_by_status > div').removeClass('active');
        $(this).addClass('active');
        showDataByStatus($(this).attr('id'));
    });

    // Show import modal
    $('body').on('click', '.importEmployeesList', function(){
        //$.ajaxModal('#import-employees-form');
        $('#import-employees-form').modal('show');
    });
    // import Data
    /* function importData(){
        var url = "{{ route('admin.employees.import') }}";
        $.easyAjax({
            type: 'POST',
            url: url,
            container: '#importEmployeesForm',
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
                    $('#import-employees-form').modal('hide');
                    window.LaravelDataTables["vehicles-table"].draw();
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
</script>
@endpush