@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
@endpush

@section('page-title')
  <div class="col-md-12">
        <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a  href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>
@endsection



@section('content')
 <div class="container-fluid">
   <div class="row">
        <div class="col-md-3">
        @include('sections.admin_setting_menu')
       </div>
        <div class="col-md-9">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                          <h5>@lang('app.menu.customFields') </h5>
                           
                        </div>
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">



                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="btn-group">
                                            <button id="add-field" class="btn btn-primary btn-outline btn-sm"><i class="fa fa-plus"></i> @lang('modules.customFields.addField')
                                            </button>
                                        </div>
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <hr>
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="custom_fields">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Module</th>
                                            <th>@lang('modules.customFields.label')</th>
                                            <th>@lang('app.name')</th>
                                            <th>@lang('modules.invoices.type')</th>
                                            <th>@lang('app.value')</th>
                                            <th>@lang('app.required')</th>
                                            <th>@lang('app.action')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            <div class="clearfix"></div>
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
                    <h5 class="modal-title" id="modelHeading">@lang('modules.customFields.addField')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->.
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')

    <!-- Plugins JS start-->
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
    
<!-- <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>

<script src="{{ asset('plugins/bower_components/jquery.repeater/jquery.repeater.js') }}"></script> -->
<script>
    $(function() {
        var table = $('#custom_fields').dataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.custom-fields.data') !!}',
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
                {data: 'id', name: 'id', orderable: false, searchable: false, visible:false},
                {data: 'module', name: 'custom_field_groups.name', orderable: true, searchable: true},
                {data: 'label', name: 'label', orderable: true, searchable: true},
                {data: 'name', name: 'name', orderable: true, searchable: true},
                {data: 'type', name: 'type', orderable: true, searchable: true},
                {data: 'values', name: 'values', orderable: true, searchable: true},
                {data: 'required', name: 'required', orderable: true, searchable: true},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('body').on('click', '.sa-params', function () {
            var id = $(this).data('user-id');
            swal({

                title: "Are you sure?",
                text: "You will not be able to recover the deleted field!",
                icon: "{{ asset('img/warning.png')}}",
                buttons: ["CANCEL", "DELETE"],
                dangerMode: true
        })
        .then((willDelete) => {
                if (willDelete) {

                    var url = "{{ route('admin.custom-fields.destroy',':id') }}";
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
        });

        $('body').on('click', '#edit-field', function () {
            var id = $(this).data('user-id');
            var url = "{{ route('admin.custom-fields.edit',':id') }}";
            url = url.replace(':id', id);

            $('#modelHeading').html('Edit Field');
            $.ajaxModal('#projectCategoryModal',url);
        });
        $('#add-field').click(function(){
            var url = '{{ route('admin.custom-fields.create')}}';
            $('#modelHeading').html('Add Field');
            $.ajaxModal('#projectCategoryModal',url);
        })

    });
</script>

@endpush

