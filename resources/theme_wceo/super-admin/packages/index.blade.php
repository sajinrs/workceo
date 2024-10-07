@extends('layouts.super-admin')
@push('head-script')
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/sweetalert2.css')}}">
    <!-- Plugins css Ends-->
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                        href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="#packageTopImage" data-toggle="modal"
                           class="btn btn-secondary btn-sm">Billing Images</a>

                        <a href="{{ route('super-admin.packages.create') }}"
                           class="btn btn-primary btn-sm">@lang('app.add') @lang('app.template') <i class="fa fa-plus"
                                                                                                    aria-hidden="true"></i></a>

                    </div>
                </div>
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Templates List</h5>
                        <span>{{ $totalPackages }} @lang('app.total') @lang('app.menu.packages')</span>
                    </div>
                    <div class="card-body">


                        <div class="table-responsive">
                            <table class="display" id="users-table">
                                <thead>
                                <tr>
                                    <th>@lang('app.id')</th>
                                    <th>@lang('app.name')</th>
                                    <th>@lang('app.annual') @lang('app.price') ({{$global->currency->currency_symbol}}
                                        )
                                    </th>
                                    <th>@lang('app.monthly') @lang('app.price') ({{$global->currency->currency_symbol}}
                                        )
                                    </th>
                                    <th>@lang('app.menu.employees')</th>
                                    <th>@lang('app.module')</th>
                                    <th>@lang('app.action')</th>
                                    <th>@lang('app.status')</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Zero Configuration  Ends-->
        </div>
    </div>

    <div class="modal fade bs-modal-md in" id="packageTopImage" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
        <div class="modal-dialog modal-md" id="faq-modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Billing Image</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                {!! Form::open(['id'=>'packTopImageForm','class'=>'ajax-form', 'method'=>'PUT']) !!}
<div class="modal-body">
    <div class="portlet-body">

       
        <div class="form-body">
            <div class="row">
                

                <div class="col-md-6">
                    <label>Package Top Monthly Image (680x270) </label>
                    <div class="form-group">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 100px;">
                                <img src="{{$global->package_banner_monthly_url}}" alt=""/>
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail"
                                    style="max-width: 200px; max-height: 100px;"></div>
                            <div>
                        <span class="btn btn-sm btn-success btn-file">
                        <span class="fileinput-new"> @lang('app.selectImage') </span>
                        <span class="fileinput-exists"> @lang('app.change') </span>
                        <input type="file" name="package_banner_monthly" /> </span>
                                <a href="javascript:;" class="btn btn-sm btn-danger fileinput-exists"
                                    data-dismiss="fileinput"> @lang('app.remove') </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label>Billing Footer Image (680x130) </label>
                    <div class="form-group">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 100px;">
                                <img src="{{$global->billing_footer_image_url}}" alt=""/>
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail"
                                    style="max-width: 200px; max-height: 100px;"></div>
                            <div>
                        <span class="btn btn-sm btn-success btn-file">
                        <span class="fileinput-new"> @lang('app.selectImage') </span>
                        <span class="fileinput-exists"> @lang('app.change') </span>
                        <input type="file" name="billing_footer_image" /> </span>
                                <a href="javascript:;" class="btn btn-sm btn-danger fileinput-exists"
                                    data-dismiss="fileinput"> @lang('app.remove') </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label>Package Top Annually Image (680x270)</label>
                    <div class="form-group">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 100px;">
                                <img src="{{$global->package_banner_annually_url}}" alt=""/>
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail"
                                    style="max-width: 200px; max-height: 100px;"></div>
                            <div>
                        <span class="btn btn-sm btn-success btn-file">
                        <span class="fileinput-new"> @lang('app.selectImage') </span>
                        <span class="fileinput-exists"> @lang('app.change') </span>
                        <input type="file" name="package_banner_annually" /> </span>
                                <a href="javascript:;" class="btn btn-sm btn-danger fileinput-exists"
                                    data-dismiss="fileinput"> @lang('app.remove') </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label>Checkout Left Image (680x130) </label>
                    <div class="form-group">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 100px;">
                                <img src="{{$global->checkout_left_image_url}}" alt=""/>
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail"
                                    style="max-width: 200px; max-height: 100px;"></div>
                            <div>
                        <span class="btn btn-sm btn-success btn-file">
                        <span class="fileinput-new"> @lang('app.selectImage') </span>
                        <span class="fileinput-exists"> @lang('app.change') </span>
                        <input type="file" name="checkout_left_image" /> </span>
                                <a href="javascript:;" class="btn btn-sm btn-danger fileinput-exists"
                                    data-dismiss="fileinput"> @lang('app.remove') </a>
                            </div>
                        </div>
                    </div>
                </div>

                          
            </div>
            
        </div>
        
    </div>
</div>

<div class="modal-footer">
    <div class="form-actions">
        <button type="button" onclick="updateTopImage({{$global->id}});" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>       
        <button type="button" class="btn btn-danger" data-dismiss="modal"> @lang('app.cancel')</button> 
    </div>
</div>
{!! Form::close() !!}
               
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@push('footer-script')

    <!-- Plugins JS start-->
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatables/datatable.custom.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script>
        $(function () {
            var table = $('#users-table').dataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: '{!! route('super-admin.packages.data') !!}',
                language: {
                    "url": "<?php echo __("app.datatable") ?>"
                },
                "fnDrawCallback": function (oSettings) {
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'annual_price', name: 'annual_price'},
                    {data: 'monthly_price', name: 'monthly_price'},
                    {data: 'max_employees', name: 'max_employees'},
                    {data: 'module_in_package', name: 'module_in_package'},
                    {data: 'action', name: 'action'},
                    {data: 'status', name: 'status'}
                ]
            });


            $('body').on('click', '.sa-params', function () {
                var id = $(this).data('user-id');
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover the deleted template!",
                    icon: "warning",
                    buttons: ["No, cancel please!", "Yes, delete it!"],
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            var url = "{{ route('super-admin.packages.destroy',':id') }}";
                            url = url.replace(':id', id);

                            var token = "{{ csrf_token() }}";

                            $.easyAjax({
                                type: 'POST',
                                url: url,
                                data: {'_token': token, '_method': 'DELETE'},
                                success: function (response) {
                                    if (response.status == "success") {
                                        $.unblockUI();
                                        var total = $('#totalPackages').text();
                                        $('#totalPackages').text(parseInt(total) - parseInt(1));
                                        table._fnDraw();
                                    }
                                }
                            });
                        }
                    });


            });


        });
        $(document).on('change','.package_status',function (){
            if($(this).is(":checked")){
                var id = $(this).attr('data-id');

                swal({
                    title: "Are you sure to activate template?",
                    text: "Activated template cannot be reverted!",
                    icon: "warning",
                    buttons: ["No, cancel please!", "Yes, activate it!"],
                    dangerMode: true
                }).then((willDelete) => {
                    if (willDelete) {
                        var url = "{{ route('super-admin.packages.activate',':package_id') }}";
                        url = url.replace(':package_id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token},
                            success: function (response) {
                                if (response.status == "success") {
                                    window.location.reload();
                                }
                            }
                        });

                    }else{
                        $(this).prop('checked', false);
                    }
                });

            }

        });

        function updateTopImage(id)
        {
            var url  = "{{route('super-admin.packages.update-pack-banner', ':id')}}";
            url      = url.replace(':id',id);    


            $.easyAjax({
                url: url,
                container: '#packTopImageForm',
                type: "POST",
                file: true,
                data: $('#packTopImageForm').serialize(),
                success: function (response) {
                    
                        $('#packageTopImage').modal('hide');
                }
            })
            
        } 

    </script>
@endpush