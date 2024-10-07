@extends('layouts.super-admin')

@push('head-script')

    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
    <style>
        #adsModal{overflow-y:scroll;}
    </style>
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
                        <h5>{{ __($pageTitle) }} List</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="faq-category-table">
                                <thead>
                                <tr>
                                    <th>@lang('app.id')</th>
                                    <th>@lang('app.menu.adspace')</th>
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
    <div class="modal fade bs-modal-md in" tabindex="-1" id="adsModal" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
        <div class="modal-dialog modal-lg" id="faq-modal-data-application">
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
        var table = $('#faq-category-table');

        $(function() {

            table.dataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: '{!! route('super-admin.ads-space.data') !!}',
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
                    { data: 'adspace', name: 'adspace' },
                    { data: 'action', name: 'action' }
                ]
            });            


            
        });

        /* function showFaqCategoryCreate() {
            var url = '{{ route('super-admin.faq-category.create')}}';

            $.ajaxModal('#faqCategoryModal', url);
        }

        function showFaqCategoryEdit(id) {
            var url = '{{ route('super-admin.faq-category.edit', ':id')}}';
            url = url.replace(':id', id);

            $.ajaxModal('#faqCategoryModal', url);
        } */

        /* function saveCategory(id) {

            if(typeof id != 'undefined'){
                var url  ="{{route('super-admin.faq-category.update',':id')}}";
                url      = url.replace(':id',id);
            }

            if (typeof id == 'undefined'){
                url = "{{ route('super-admin.faq-category.store') }}";
            }

            $.easyAjax({
                url: url,
                container: '#addEditFaqCategory',
                type: "POST",
                data: $('#addEditFaqCategory').serialize(),
                success: function (response) {
                    if(response.status == 'success'){
                        table._fnDraw();
                        $.unblockUI();
                        $('#faqCategoryModal').modal('hide');
                    }
                }
            })
        } */

        //region FAQ
        

        function editAdspace(id) 
        {        
            var url = '{{ route('super-admin.ads-space.edit', ':id')}}';
            url = url.replace(':id', id);
            $.ajaxModal('#adsModal', url);
        }

       function saveAds(id)
       {
            var url  = "{{route('super-admin.ads-space.update', ':id')}}";
            url      = url.replace(':id',id);            

            $.easyAjax({
                url: url,
                container: '#editAds',
                type: "POST",
                file: (document.getElementById("adsImage").files.length == 0) ? false : true,
                data: $('#editAds').serialize(),
                success: function (response) {
                    if(response.status == 'success'){
                        table._fnDraw();
                        $.unblockUI();
                        $('#adsModal').modal('hide');
                    }
                }
            })
        } 

    </script>
@endpush