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
                        <h4 class="m-b-0" style="color: #1d61d2;"><i class="{{ $pageIcon }}"></i> <span class="upper"> {{ __($pageTitle) }} </span> #{{ $project->id }} - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('modules.projects.milestones')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid product-wrapper">
        @include('admin.projects.show_project_menu')
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="pull-left">@lang('modules.projects.milestones')</h5>
                        <div class="pull-right">
                            <a href="javascript:;" id="show-add-form" class="btn btn-primary btn-sm"><i  class="fa fa-plus"></i> @lang('modules.projects.createMilestone')</a>
                        </div>
                    </div>
                    <div class="card-body">
                        

                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::open(['id'=>'logTime','class'=>'ajax-form d-none','method'=>'POST']) !!}

                                {!! Form::hidden('project_id', $project->id) !!}

                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group form-label-group">
                                                        <input placeholder="-" id="milestone_title" name="milestone_title" type="text"  class="form-control form-control-lg" >
                                                        <label for="milestone_title" class="required">@lang('modules.projects.milestoneTitle')</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-label-group">
                                                        <select placeholder="-" name="status" id="status" class="form-control form-control-lg hide-search">
                                                            <option value="incomplete">@lang('app.incomplete')</option>
                                                            <option value="complete">@lang('app.complete')</option>
                                                        </select>
                                                        <label for="status">@lang('app.status')</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-label-group">
                                                        <select placeholder="-" name="currency_id" id="currency_id" class="form-control form-control-lg hide-search">
                                                            <option value="">--</option>
                                                            @foreach ($currencies as $item)
                                                                <option value="{{ $item->id }}">{{ $item->currency_code.' ('.$item->currency_symbol.')' }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="currency_id">@lang('modules.invoices.currency')</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-label-group">
                                                        <input placeholder="-" id="cost" name="cost" type="number"
                                                               class="form-control form-control-lg" value="0" min="0" step=".01">
                                                        <label for="cost">@lang('modules.projects.milestoneCost')</label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group form-label-group">
                                                <textarea placeholder="-" name="summary" id="summary" rows="4" class="form-control form-control-lg"></textarea>
                                                <label for="summary" class="required">@lang('modules.projects.milestoneSummary')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions m-t-20 m-b-30 text-right row">
                                    <div class="col-md-3 offset-md-6">
                                        <button type="button" id="save-form" class="btn btn-primary form-control">@lang('app.save')</button>

                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" id="close-form" class="btn btn-outline-primary gray form-control">@lang('app.close')</button>

                                    </div>
                                 </div>
                                {!! Form::close() !!}


                            </div>
                        </div>

                        <div class="table-responsive m-t-30">
                            <table class="display" id="timelog-table">
                                <thead>
                                <tr>
                                    <th>@lang('app.id')</th>
                                    <th>@lang('modules.projects.milestoneTitle')</th>
                                    <th>@lang('modules.projects.milestoneCost')</th>
                                    <th>@lang('app.status')</th>
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



    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="editTimeLogModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
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
    {{--Ajax Modal Ends--}}

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="detailTimeLogModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailmodelHeading"></h5>
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

<script>
    var table = $('#timelog-table').dataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: '{!! route('admin.milestones.data', $project->id) !!}',
        deferRender: true,
        language: {
            "url": "<?php echo __("app.datatable") ?>"
        },
        "fnDrawCallback": function (oSettings) {
            $("body").tooltip({
                selector: '[data-toggle="tooltip"]'
            });
        },
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {data: 'milestone_title', name: 'milestone_title'},
            {data: 'cost', name: 'cost'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'}
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

    $(document).ready(function(){
        $('#timelog-table_wrapper select').select2({minimumResultsForSearch: -1});
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.milestones.store')}}',
            container: '#logTime',
            type: "POST",
            data: $('#logTime').serialize(),
            success: function (data) {
                if (data.status == 'success') {
                    $('#logTime').trigger("reset");
                    $('#logTime').toggleClass('d-none', 'd-block');
                    table._fnDraw();
                }
            }
        })
    });

    $('#show-add-form, #close-form').click(function () {
        $('#logTime').toggleClass('d-none', 'd-block');
    });

    $('body').on('click', '.sa-params', function(){
            var id = $(this).data('milestone-id');
            swal({

                title: "Are you sure?",
                text: "You will not be able to recover the deleted milestone!",
                icon: "{{ asset('img/warning.png')}}",
                buttons: ["CANCEL", "DELETE"],
                dangerMode: true
            })
            .then((willDelete) => {
                    if (willDelete) {

                        var url = "{{ route('admin.milestones.destroy',':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            success: function (response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    table._fnDraw();
                                }
                            }
                        });
                    }
                });
            })

    

    $('body').on('click', '.edit-milestone', function () {
        $('#modelHeading').html('@lang('app.update') @lang('modules.projects.milestones')');
        var id = $(this).data('milestone-id');

        var url = '{{ route('admin.milestones.edit', ':id')}}';
        url = url.replace(':id', id);

        $.ajaxModal('#editTimeLogModal', url);

    });

    $('body').on('click', '.milestone-detail', function () {
        $('#detailmodelHeading').html('@lang('modules.projects.milestones') @lang('app.details')');

        var id = $(this).data('milestone-id');
        var url = '{{ route('admin.milestones.detail', ":id")}}';
        url = url.replace(':id', id);
        $.ajaxModal('#detailTimeLogModal',url);
    })
    $('ul.showProjectTabs .projectMilestones .nav-link').addClass('active');
</script>
@endpush
