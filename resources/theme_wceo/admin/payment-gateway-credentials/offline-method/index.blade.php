@extends('layouts.app')
@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
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
   @include('sections.payment_setting_menu')
       </div>
        <div class="col-md-9">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                          <h5>@lang('app.menu.offlinePaymentMethod') </h5>
                           
                        </div>
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">

                    
                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="white-box">
                                        @if(!$offlineMethods->isEmpty())
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <a href="javascript:;" id="addMethod" class="btn btn-outline btn-primary btn-sm addMethod">@lang('modules.offlinePayment.addMethod') <i class="fa fa-plus" aria-hidden="true"></i></a>
                                                </div>
                                            </div>

                                        </div>
                                        @endif

                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('app.menu.method')</th>
                                                    <th>@lang('app.description')</th>
                                                    <th>@lang('app.status')</th>
                                                    <th width="20%">@lang('app.action')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($offlineMethods as $key=>$method)
                                                    <tr>
                                                        <td>{{ ($key+1) }}</td>
                                                        <td>{{ ucwords($method->name) }}</td>
                                                        <td>{!! ucwords($method->description) !!} </td>
                                                        <td>@if($method->status == 'yes') <label class="badge badge-success">@lang('modules.offlinePayment.active')</label> @else <label class="badge badge-danger">@lang('modules.offlinePayment.inActive')</label> @endif </td>
                                                        <td>
                                                            <a href="javascript:;" data-type-id="{{ $method->id }}"
                                                               class="btn btn-outline-info btn-circle edit-type m-t-5">
                                                              <span class="icon-pencil"></span></a>
                                                            <a href="javascript:;" data-type-id="{{ $method->id }}"
                                                               class="btn btn-outline-danger btn-circle delete-type m-t-5"> <span class="icon-trash"></span> </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <td colspan="5" class="text-center">
                                                        <div class="empty-space" style="height: 200px;">
                                                            <div class="empty-space-inner">
                                                                <div class="icon" style="font-size:30px"><i
                                                                            class="icon-layers"></i>
                                                                </div>
                                                                <div class="title m-b-15">
                                                                    @lang('messages.noMethodsAdded')
                                                                </div>
                                                                <div class="subtitle">
                                                                    <a href="javascript:;" id="addMethod" class="btn btn-outline btn-success btn-sm addMethod">@lang('modules.offlinePayment.addMethod') <i class="fa fa-plus" aria-hidden="true"></i></a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>

                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

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
    <div class="modal fade bs-modal-md in" id="leadStatusModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('app.addNew') @lang('app.menu.offlinePaymentMethod')</h5>
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

@endsection

@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.custom.js')}}"></script>
    <script>

    //    save project members
    $('#save-type').click(function () {
        $.easyAjax({
            url: '{{route('admin.offline-payment-setting.store')}}',
            container: '#createMethods',
            type: "POST",
            data: $('#createMethods').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    window.location.reload();
                }
            }
        })
    });


    $('body').on('click', '.delete-type', function () {
            var id = $(this).data('type-id');
                swal({
                    title: "Are you sure?",
                    text: "This will remove the method from the list.",
                    icon: "warning",
                    buttons: ["No, cancel please!", "Yes, delete it!"],
                    dangerMode: true
            })
            .then((willDelete) => {
                    if (willDelete) {
                        var url = "{{ route('admin.offline-payment-setting.destroy',':id') }}";
                        url = url.replace(':id', id);
                        var token = "{{ csrf_token() }}";
                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            
                            success: function (response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    window.location.reload();
                                }
                            }
                        });
                    }
                });
            }); 


    $('.edit-type').click(function () {
        var typeId = $(this).data('type-id');
        var url = '{{ route("admin.offline-payment-setting.edit", ":id")}}';
        url = url.replace(':id', typeId);

        $('#modelHeading').html("{{  __('app.edit')." ".__('modules.offlinePayment.title') }}");
        $.ajaxModal('#leadStatusModal', url);
    })
    $('.addMethod').click(function () {
        var url = '{{ route("admin.offline-payment-setting.create")}}';
        $('#modelHeading').html("{{  __('app.edit')." ".__('modules.offlinePayment.title') }}");
        $.ajaxModal('#leadStatusModal', url);
    })


</script>


@endpush

