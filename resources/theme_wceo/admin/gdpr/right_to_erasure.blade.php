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
        @include('sections.gdpr_settings_menu')
       </div>
        <div class="col-md-9">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                          <h5>Right to erasure</h5>
                           
                        </div>
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">


                                 
                                        <div class="col-md-12">
                                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}
                                            <label for="">Enable customers to request to remove data</label>
                                            <div class="form-group m-checkbox-inline m-b-30">
                                                <div class="radio radio-primary">
                                                    <input id="data_removal1" type="radio" name="data_removal" value="1"  @if($gdprSetting->data_removal) checked @endif />
                                                    <label class="mb-0" for="data_removal1"><span class="digits"> Yes</span></label>
                                                </div>
                                                <div class="radio radio-primary">
                                                    <input id="data_removal2" type="radio" name="data_removal" value="0" @if($gdprSetting->data_removal==0) checked @endif />
                                                    <label class="mb-0" for="data_removal2"><span class="digits"> No</span></label>
                                                </div>
                                            </div>
                                           
                                            <h5 >Leads to erasure</h5>
                                            <label for="" class="b-t">Enable lead to request data removal (via public form)</label>
                                            <div class="form-group m-checkbox-inline">
                                                <div class="radio radio-primary">
                                                    <input id="lead_removal_public_form1" type="radio" name="lead_removal_public_form" value="1"  @if($gdprSetting->lead_removal_public_form==1) checked @endif />
                                                    <label class="mb-0" for="lead_removal_public_form1"><span class="digits"> Yes</span></label>
                                                </div>
                                                <div class="radio radio-primary">
                                                    <input id="lead_removal_public_form2" type="radio" name="lead_removal_public_form" value="0" @if($gdprSetting->lead_removal_public_form==0) checked @endif />
                                                    <label class="mb-0" for="lead_removal_public_form2"><span class="digits"> No</span></label>
                                                </div>
                                            </div>

                                              <div class=" text-right">
                                            <button type="button" onclick="submitForm();" class="btn btn-primary">Submit</button>
                                        </div>
                                            {!! Form::close() !!}
                                        </div>
                                   

                                </div>
                            </div>
                            <!-- /.row -->

                            <div class="clearfix"></div>
                            <hr>
                            <h5 class="m-t-40">Removal Requests</h5>
                            <div class="row">
                                <div class="col-md-12">

                                <ul class="nav nav-tabs border-tab nav-primary" id="info-tab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" id="info-customers-tab" data-toggle="tab" href="#info-customers" role="tab" aria-controls="info-customers" aria-selected="true">@lang('app.customers')</a></li>
                                    <li class="nav-item"><a class="nav-link" id="leads-info-tab" data-toggle="tab" href="#info-leads" role="tab" aria-controls="info-leads" aria-selected="false">@lang('app.lead')</a></li>
                                </ul>
                                <div class="tab-content" id="info-tabContent">
                                <div class="tab-pane fade show active" id="info-customers" role="tabpanel" aria-labelledby="info-customers-tab">
                                    <div class="table-responsive m-t-20" >
                                        <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="consent-table">
                                            <thead>
                                            <tr>
                                                <th>@lang('app.id')</th>
                                                <th>@lang('app.name')</th>
                                                <th>@lang('app.description')</th>
                                                <th>@lang('app.date')</th>
                                                <th>@lang('app.status')</th>
                                                <th>@lang('app.action')</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="info-leads" role="tabpanel" aria-labelledby="leads-info-tab">
                                    <div class="table-responsive m-t-20" >
                                        <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="consent-lead-table">
                                            <thead>
                                            <tr>
                                                <th>@lang('app.id')</th>
                                                <th>@lang('modules.lead.companyName')</th>
                                                <th>@lang('app.description')</th>
                                                <th>@lang('app.date')</th>
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


                    </div>

                </div>


            </div>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>

  <!--   <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script> -->

    <script>
        function submitForm(){

            $.easyAjax({
                url: '{{route('admin.gdpr.store')}}',
                container: '#editSettings',
                type: "POST",
                data: $('#editSettings').serialize(),
            })
        }
        table = $('#consent-table').dataTable({
            responsive: true,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.gdpr.removal-data') !!}',
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
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },

                { data: 'created_at', name: 'created_at' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' }
            ]
        });

        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('user-id');
            var type = $(this).data('type');
            var text = '';
            var btnType = '';
            if(type =='approved'){
                text = 'Approve'
                btnType = 'success'
            }else{
                text = 'Reject'
                btnType = 'warning'
            }
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted user!",
                type: btnType,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, " + text + " it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.gdpr.approve-reject',[':id',':type']) }}";
                    url = url.replace(':id', id);
                    url = url.replace(':type', type);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'GET',
                        url: url,
                        data: {'_token': token},
                        success: function (response) {
                            if (response.status === "success") {
                                $.unblockUI();
                                table._fnDraw();
                            }
                        }
                    });
                }
            });
        });

        tableLead = $('#consent-lead-table').dataTable({
            responsive: true,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.gdpr.lead.removal-data') !!}',
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
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },

                { data: 'created_at', name: 'created_at' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' }
            ]
        });

        $('body').on('click', '.sa-params1', function(){
            var id = $(this).data('lead-id');
            var type = $(this).data('type');
            var text = '';
            var btnType = '';
            if(type =='approved'){
                text = 'Approve'
                btnType = 'success'
            }else{
                text = 'Reject'
                btnType = 'warning'
            }
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted lead!",
                type: btnType,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, " + text + " it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.gdpr.lead.approve-reject',[':id',':type']) }}";
                    url = url.replace(':id', id);
                    url = url.replace(':type', type);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'GET',
                        url: url,
                        data: {'_token': token},
                        success: function (response) {
                            if (response.status === "success") {
                                $.unblockUI();
                                tableLead._fnDraw();
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush

