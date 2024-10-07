@extends('layouts.app')
@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
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
                          <h5>Consent</h5>
                           
                        </div>
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab">
                    

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12">
                            
                                    <div class="row b-t m-t-20 ">
                                        <div class="col-md-12">
                                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}
                                            <label for="">Enable consent for customers</label>
                                            <div class="form-group m-checkbox-inline">
                                                <div class="radio radio-primary">
                                                    <input id="consent_customer1" type="radio" name="consent_customer" value="1"  @if($gdprSetting->consent_customer) checked @endif />
                                                    <label class="mb-0" for="consent_customer1"><span class="digits"> Yes</span></label>
                                                </div>
                                                <div class="radio radio-primary">
                                                    <input id="consent_customer2" type="radio" name="consent_customer" value="0" @if($gdprSetting->consent_customer==0) checked @endif />
                                                    <label class="mb-0" for="consent_customer2"><span class="digits"> No</span></label>
                                                </div>
                                            </div>
                                           
                                            <hr>
                                            <label for="">Enable consent for Leads</label>
                                            <div class="form-group m-checkbox-inline">
                                                <div class="radio radio-primary">
                                                    <input id="consent_leads1" type="radio" name="consent_leads" value="1"  @if($gdprSetting->consent_leads) checked @endif />
                                                    <label class="mb-0" for="consent_leads1"><span class="digits"> Yes</span></label>
                                                </div>
                                                <div class="radio radio-primary">
                                                    <input id="consent_leads2" type="radio" name="consent_leads" value="0" @if($gdprSetting->consent_leads==0) checked @endif />
                                                    <label class="mb-0" for="consent_leads2"><span class="digits"> No</span></label>
                                                </div>
                                            </div>

                                            <hr>
                                            <label for="">Public page consent information block</label>
                                            <div class="form-group">
                                                <textarea name="consent_block" id="consent_block" cols="30" rows="10">
                                                    {{$gdprSetting->consent_block}}
                                                </textarea>

                                            </div> 
                                            <div class=" text-right">
                                            <button type="button" onclick="submitForm();" class="btn btn-primary">
                                                Submit
                                            </button>
                                        </div>
                                            {!! Form::close() !!}

                                            <hr>
                                            <h5 class="m-t-30">Purpose of consent</h5>
                                            <p>
                                                <button type="button" id="addConsent" class="btn btn-primary"><i
                                                            class="icon-plus"></i> Add new consent
                                                </button>
                                            </p>

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="consent-table">
                                                    <thead>
                                                    <tr>
                                                        <th>@lang('app.id')</th>
                                                        <th>@lang('modules.notices.notice')</th>
                                                        <th>@lang('app.description')</th>
                                                        <th>@lang('app.date')</th>
                                                        <th>@lang('app.action')</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- /.row -->

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{--Ajax Modal--}}
        <div class="modal fade bs-modal-md in" id="consentModal" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-md" id="modal-data-application">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modelHeading"></h5>
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
            <!-- /.modal-dialog -->
        </div>
        {{--Ajax Modal Ends--}}

    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.custom.js')}}"></script>
    <script>
        $('#consent_block').summernote({
            height: 200,                 // set editor height
            minHeight: null,             // set minimum height of editor
            maxHeight: null,             // set maximum height of editor
            focus: false,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['fontsize', ['fontsize']],
                ['para', ['ul', 'ol', 'paragraph']],
                ["view", ["fullscreen"]]
            ]
        }); 

        function submitForm() {

            $.easyAjax({
                url: '{{route('admin.gdpr.store')}}',
                container: '#editSettings',
                type: "POST",
                data: $('#editSettings').serialize(),
            })
        }

        $('#addConsent').on('click', function () {
            var url = '{{ route('admin.gdpr.add-consent')}}';
            $.ajaxModal('#consentModal', url);
        });

        $('body').on('click','.sa-params-edit', function () {
            var id = $(this).data('user-id');
            var url = "{{ route('admin.gdpr.edit-consent',':id') }}";
            url = url.replace(':id', id);
            $.ajaxModal('#consentModal', url);
        });

        $('body').on('click', '.sa-params', function () {
            var id = $(this).data('user-id');
            swal({

                title: "Are you sure?",
                text: "You will not be able to recover the deleted notice!",
                icon: "{{ asset('img/warning.png')}}",
                buttons: ["CANCEL", "DELETE"],
                dangerMode: true
        })
        .then((willDelete) => {
                if (willDelete) {

                    var url = "{{ route('admin.gdpr.purpose-delete',':id') }}";
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

        

        table = $('#consent-table').dataTable({
            responsive: true,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.gdpr.purpose-data') !!}',
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
                { data: 'action', name: 'action' }
            ]
        });
    </script>
@endpush

