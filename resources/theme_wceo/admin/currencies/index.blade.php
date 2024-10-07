@extends('layouts.app')

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
                          <h5>@lang('modules.currencySettings.currencies')</h5>
                           
                        </div>
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">

                    
                    

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="white-box">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <a href="{{ route('admin.currency.create') }}" class="btn btn-outline btn-primary btn-sm">@lang('modules.currencySettings.addNewCurrency') <i class="fa fa-plus" aria-hidden="true"></i></a>
                                                    <a href="javascript:;" id="update-exchange-rates" class="btn btn-primary btn-outline btn-sm">@lang('app.update') @lang('modules.currencySettings.exchangeRate') <i class="fa fa-refresh" aria-hidden="true"></i></a>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="alert alert-info"><i class="fa fa-info-circle"></i> @lang('messages.exchangeRateNote')</div>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>@lang('modules.currencySettings.currencyName')</th>
                                                    <th>@lang('modules.currencySettings.currencySymbol')</th>
                                                    <th>@lang('modules.currencySettings.currencyCode')</th>
                                                    <th>@lang('modules.currencySettings.exchangeRate')</th>
                                                    <th class="text-nowrap">@lang('app.action')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($currencies as $currency)
                                                    <tr>
                                                        <td>{{ ucwords($currency->currency_name) }} {!! ($global->currency_id == $currency->id) ? "<span class='badge badge-pill badge-primary'>DEFAULT</span>" : "" !!}</td>
                                                        <td>{{ $currency->currency_symbol }}</td>
                                                        <td>{{ $currency->currency_code }}</td>
                                                        <td>{{ $currency->exchange_rate }}</td>
                                                        <td class="text-nowrap">
                                                            <a href="{{ route('admin.currency.edit', [$currency->id]) }}" class="btn btn-outline-info btn-circle"
                                                               data-toggle="tooltip" data-original-title="Edit"><span class="icon-pencil"></span></a>

                                                            {{--<a href="javascript:;" class="btn btn-danger btn-circle sa-params"--}}
                                                               {{--data-toggle="tooltip" data-currency-id="{{ $currency->id }}" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>--}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>    <!-- .row -->

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
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
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
<script>
    $('body').on('click', '.sa-params', function(){
        var id = $(this).data('currency-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted currency!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "DELETE",
            cancelButtonText: "CANCEL",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.currency.destroy',':id') }}";
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
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });

    $('#update-exchange-rates').click(function () {
        var url = '{{route('admin.currency.update-exchange-rates')}}';
        $.easyAjax({
            url: url,
            type: "GET",
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                    window.location.reload();
                }
            }
        })
    });

</script>
@endpush