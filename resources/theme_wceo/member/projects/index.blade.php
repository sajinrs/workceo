@extends('layouts.member-app')

@push('head-script')
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
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                @if($user->can('add_projects'))
                <div class="col">
                    <div class="bookmark pull-right">
                        {{--<a href="{{ route('member.project-template.index') }}"  class="btn btn-outline btn-secondary btn-sm">@lang('app.menu.addProjectTemplate') <i class="fa fa-plus" aria-hidden="true"></i></a>--}}
                        <a href="{{ route('member.projects.create') }}" class="btn btn-outline btn-primary btn-sm">@lang('modules.projects.addNewProject') <i class="fa fa-plus" aria-hidden="true"></i></a>
                    </div>
                </div>
                @endif
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>


    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->

            <div class="col-md-12 col-sm-12 d-none">
                <div class="filter-section">
                    <div class="card browser-widget bw-project">
                        <div class="media card-body" style="padding: 10px">
                            <div class="media-body align-self-center count_by_status">

                                <div id="total">
                                    <p>@lang('modules.dashboard.totalProjects')</p>
                                    <h4><span class="counter" id="totalWorkingDays">{{ $totalProjects }}</span></h4>
                                </div>
                                <div id="overdue">
                                    <p>@lang('modules.tickets.overDueProjects')</p>
                                    <h4><span class="counter" id="daysPresent">{{ $overdueProjects }}</span></h4>
                                </div>
                                <div id="notStarted">
                                    <p>@lang('app.notStarted') @lang('app.menu.jobs')</p>
                                    <h4><span class="counter" id="daysLate">{{ $notStartedProjects }}</span></h4>
                                </div>
                                <div id="finished">
                                    <p>@lang('modules.tickets.completedProjects')</p>
                                    <h4><span class="counter" id="halfDays">{{ $finishedProjects }}</span></h4>
                                </div>
                                <div id="inProcess">
                                    <p>@lang('app.inProgress') @lang('app.menu.jobs')</p>
                                    <h4><span class="counter" id="absentDays">{{ $inProcessProjects }}</span> </h4>
                                </div>
                                <div id="canceled">
                                    <p>@lang('app.canceled') @lang('app.menu.jobs')</p>
                                    <h4><span class="counter" id="holidayDays">{{ $canceledProjects }}</span></h4>
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
                                                                        <label class="f-w-600">@lang('app.menu.projects') @lang('app.status')</label>
                                                                        <select class="select2 form-control" data-placeholder="@lang('app.menu.jobs') @lang('app.status')" id="status">
                                                                            <option selected value="all">@lang('app.all')</option>
                                                                            <option
                                                                                    value="not started">@lang('app.notStarted')
                                                                            </option>
                                                                            <option
                                                                                    value="in progress">@lang('app.inProgress')
                                                                            </option>
                                                                            <option
                                                                                    value="on hold">@lang('app.onHold')
                                                                            </option>
                                                                            <option
                                                                                    value="canceled">@lang('app.canceled')
                                                                            </option>
                                                                            <option
                                                                                    value="finished">@lang('app.finished')
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="product-filter col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="f-w-600">@lang('app.clientName')</label>
                                                                        <select class="select2 form-control" data-placeholder="@lang('app.clientName')" id="client_id">
                                                                            <option selected value="all">@lang('app.all')</option>
                                                                            @foreach($clients as $client)
                                                                                <option value="{{ $client->id }}">{{ $client->name }}</option>
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
                                            <button class="btn btn-primary toggle-data">Filters<span class="ml-2"><i class="" data-feather="chevron-down"></i></span></button>
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="dt-ext table-responsive">
                                        {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
                                        
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



    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
        <!-- /.modal-dialog -->.
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

<script src="{{ asset('plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
<script src="{{ asset('plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

{!! $dataTable->scripts() !!}
<script>
    var table;
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    $('.select2').val('all');
    $(function() {
        showData();
        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('user-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted project!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('member.projects.destroy',':id') }}";
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
                                table._fnDraw();
                            }
                        }
                    });
                }
            });
        });

        $('#createProject').click(function(){
            var url = '{{ route('admin.projectCategory.create')}}';
            $('#modelHeading').html('Manage Project Category');
            $.ajaxModal('#projectCategoryModal',url);
        })

    });

    function showData() 
    {
        $('#projects-table').on('preXhr.dt', function (e, settings, data) {
            var status = $('#status').val();
            var clientID = $('#client_id').val();

            data['status'] = status;
            data['client_id'] = clientID;
            data['status_by'] = null;
        });
        window.LaravelDataTables["projects-table"].search('').draw();
    }

    $('#apply-filters').click(function (event) {
        event.preventDefault();
        $('.filter-section .count_by_status > div').removeClass('active');
        showData();
    });

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
        $('.filter-section .count_by_status > div').removeClass('active');
        $('#projects-table').on('preXhr.dt', function (e, settings, data) {
            data['status_by'] = null;
        });
        showData();
    }); 

    $('.count_by_status div').on('click', function(event) {
        $('.count_by_status > div').removeClass('active');
        $(this).addClass('active');
        showDataByStatus($(this).attr('id'));
    }); 

    function showDataByStatus(id) 
    {
        $('#projects-table').on('preXhr.dt', function (e, settings, data) {
            data['status'] = '';
            data['client_id'] = '';            
            var status_by = id;
            data['status_by'] = status_by;
        });
        window.LaravelDataTables["projects-table"].search('').draw();
    }

</script>
@endpush