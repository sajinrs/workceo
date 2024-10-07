@extends('layouts.super-admin')

@push('head-script')

    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
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
                            <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>  
                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                           onclick="showOnboardingCreate()">@lang('app.add') @lang('app.menu.onBoarding') <i class="fa fa-plus"></i> </a>
                    </div>
                </div>              
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
                        <h5>{{ __($pageTitle) }} Task</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="boarding-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('app.boarding.taskTitle')</th>
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
    <div class="modal fade bs-modal-md in" id="boardingModal" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
        <div class="modal-dialog modal-md" id="faq-modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
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
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}
    
@endsection

@push('footer-script')
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatables/datatable.custom.js')}}"></script>
    <script>
        var table = $('#boarding-table');

        $(function() {

            table.dataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: '{!! route('super-admin.onboarding.data') !!}',
                language: {
                    "url": "<?php echo __("app.datatable") ?>"
                },
                "fnDrawCallback": function( oSettings ) {
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'id' },
                    { data: 'adspace', name: 'adspace' },
                    { data: 'action', name: 'action' }
                ]
            });            


            
        });        

        function showOnboardingCreate() {
            var url = '{{ route('super-admin.onboarding.create')}}';

            $.ajaxModal('#boardingModal', url);
        }

        function saveBoarding()
        {
            var url  = "{{route('super-admin.onboarding.store')}}";
            
            $.easyAjax({
                url: url,
                container: '#boardingForm',
                type: "POST",
                file: (document.getElementById("boardImage").files.length == 0) ? false : true,
                data: $('#boardingForm').serialize(),
                success: function (response) {
                    if(response.status == 'success'){
                        table._fnDraw();
                        $.unblockUI();
                        $('#boardingModal').modal('hide');
                    }
                }
            })
        } 

        function editBoarding(id) 
        {        
            var url = '{{ route('super-admin.onboarding.edit', ':id')}}';
            url = url.replace(':id', id);
            $.ajaxModal('#boardingModal', url);
        }

        function updateBoarding(id)
        {
            var url  = "{{route('super-admin.onboarding.update', ':id')}}";
            url      = url.replace(':id',id);            

            $.easyAjax({
                url: url,
                container: '#boardingForm',
                type: "POST",
                file: (document.getElementById("boardImage").files.length == 0) ? false : true,
                data: $('#boardingForm').serialize(),
                success: function (response) {
                    if(response.status == 'success'){
                        table._fnDraw();
                        $.unblockUI();
                        $('#boardingModal').modal('hide');
                    }
                }
            })
        } 

        function deleteBoarding(id) 
        {
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted item!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {

                    var url = "{{ route('super-admin.onboarding.destroy', ':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                $('#faqModal').modal('hide');
                                table._fnDraw();
                            }
                        }
                    });
                }
            });
        }      

    </script>
@endpush