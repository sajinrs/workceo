@extends('layouts.app')


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
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitleArchive) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-primary btn-sm">@lang('modules.projects.allProject') <i class="fa fa-search" aria-hidden="true"></i></a>
                    </div>
                </div>
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>


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
                                                                        <label class="f-w-600">@lang('app.menu.jobs') @lang('app.status')</label>
                                                                        <select class="select2 form-control" data-placeholder="@lang('app.menu.jobs') @lang('app.status')" id="status">
                                                                            <option selected value="all">@lang('app.all')</option>
                                                                            <option value="complete">@lang('app.complete')</option>
                                                                            <option value="incomplete">@lang('app.incomplete')</option>
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
                                            <table class="display" id="project-table">
                                                <thead>
                                                <tr>
                                                    <th>@lang('app.id')</th>
                                                    <th>@lang('modules.projects.projectName')</th>
                                                    <th>@lang('modules.projects.projectMembers')</th>
                                                    <th>@lang('app.deadline')</th>
                                                    <th>@lang('app.client')</th>
                                                    <th>@lang('app.completion')</th>
                                                    <th width="150px;">@lang('app.action')</th>
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



    <!-- .row -->

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

<script>
    var table;
    

    $(function() {
        showData();

        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('user-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted job!",
                icon: "{{ asset('img/warning.png')}}",
                buttons: ["CANCEL", "DELETE"],
                dangerMode: true
            }).then((willDelete) => {
                if (willDelete) {

                    var url = "{{ route('admin.projects.destroy',':id') }}";
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

        $('body').on('click', '.revert', function(){
            var id = $(this).data('user-id');
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.unArchiveMessage')",
                icon: "{{ asset('img/warning.png')}}",
                buttons: ["@lang('messages.confirmNoArchive')", "@lang('messages.confirmRevert')"],
                dangerMode: true
            }).then((willDelete) => {
                if (willDelete) {


                    var url = "{{ route('admin.projects.archive-restore',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'GET',
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
            var url = "{{ route('admin.projectCategory.create')}}";
            $('#modelHeading').html('Manage Project Category');
            $.ajaxModal('#projectCategoryModal',url);
        })

    });

    function initCounter() {
        $(".counter").counterUp({
            delay: 100,
            time: 1200
        });
    }

    function showData() {
        var status = $('#status').val();
        var clientID = $('#client_id').val();

        var searchQuery = "?status="+status+"&client_id="+clientID;
       table = $('#project-table').dataTable({
            dom:"<'row'<'col-md-6'Bl><'col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            processing: true,
            serverSide: true,
            destroy: true,
           buttons: [
               {
                   text: 'Filters<span class="ml-2"><i class="fa fa-angle-down"></i></span>',
                   className:'filterBtn',
                   action: function ( e, dt, node, config ) {
                       $('.toggle-data').trigger('click');
                   }
               },
               
           ],
           
            ajax: "{!! route('admin.projects.archive-data') !!}"+searchQuery,
            "order": [[ 0, "desc" ]],
            deferRender: true,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function( oSettings ) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            
            columns: [
                { data: 'id', name: 'id' },
                { data: 'project_name', name: 'project_name'},
                { data: 'members', name: 'members' },
                { data: 'deadline', name: 'deadline' },
                { data: 'client_id', name: 'client_id' },
                { data: 'completion_percent', name: 'completion_percent' },
                { data: 'action', name: 'action' }
            ],

                'lengthMenu': [
                    [ 10, 25, 50, 100 ],
                    [ 'Show 10', 'Show 25', 'Show 50', 'Show 100' ]
                ],

                language: {
                    searchPlaceholder: "Search...",
                    sSearch:  '<i class="fa fa-search"></i> _INPUT_',
                    lengthMenu: "_MENU_"
                }  
        });
    }

    $(document).ready(function(){
        $('#project-table_wrapper select').select2({minimumResultsForSearch: -1});
    });

    $('#status').on('change', function(event) {
        event.preventDefault();
        showData();
    });

    $('#client_id').on('change', function(event) {
        event.preventDefault();
        showData();
    });

    initCounter();
</script>
@endpush