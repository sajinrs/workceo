@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
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


    @include('sections.account_billing_menu')
    <div class="container-fluid">
        <div class="page-header p-t-0">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col"><h5>Payment Settings</h5> </div>

                    </div>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="table-responsive">
                                <table class="table color-table inverse-table" id="cards-table">
                                    <thead>
                                        <tr>
                                            <th>Payment Mode</th>
                                            <th>Card</th>
                                            <th>Exp Date</th>
                                            <th>Action</th>
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
    <div class="container-fluid">
        <div class="page-header">
            <div class="card">

                <div class="card-body">
                    <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-toggle="tab" href="#top-home" role="tab" aria-controls="top-home" aria-selected="false" data-original-title="" title="">Billing Address</a></li>
                        <li class="nav-item"><a class="nav-link" id="profile-top-tab" data-toggle="tab" href="#top-profile" role="tab" aria-controls="top-profile" aria-selected="false" data-original-title="" title="">Shipping Address</a></li>
                    </ul>
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade show active" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">

                            {!! Form::open(['id'=>'paymentSettingsBilling','class'=>'ajax-form','method'=>'POST']) !!}
                                <div class="form-body" >
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-label-group form-group">
                                                <input type="text" name="attention" id="attention" placeholder="-"
                                                       class="form-control form-control-lg"
                                                       value="{{$customer_data['billing_address']['attention']??''}}">
                                                <label for="attention" class="col-form-label required">Full Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">
                                                <input type="text" name="street" id="street" placeholder="-"
                                                       class="form-control form-control-lg"
                                                       value="{{$customer_data['billing_address']['street']??''}}">
                                                <label for="street" class="col-form-label required">Street</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                                <input type="text" name="street2" id="street2" placeholder="-"
                                                       class="form-control form-control-lg"
                                                       value="{{$customer_data['billing_address']['street2']??''}}">
                                                <label for="street2" class="control-label">Street2</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                                <input type="text" name="city" id="city" placeholder="-"
                                                       class="form-control form-control-lg"
                                                       value="{{$customer_data['billing_address']['city']??''}}">
                                                <label for="city" class="control-label">City</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                                <input type="text" name="state" id="state" placeholder="-"
                                                       class="form-control form-control-lg"
                                                       value="{{$customer_data['billing_address']['state']??''}}">
                                                <label for="state" class="control-label">State</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                                <input type="text" name="zip" id="zip" placeholder="-"
                                                       class="form-control form-control-lg"
                                                       value="{{$customer_data['billing_address']['zip']??''}}">
                                                <label for="zip" class="control-label">Zip Code</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">
                                                <select placeholder="-" class="select2 form-control form-control-lg" data-placeholder="Country" id="country" name="country">
                                                    <option value="">--</option>
                                                    @foreach($countries as $country)
                                                        <option
                                                                @if($customer_data['billing_address']['country'] == $country->name) selected @endif
                                                        value="{{ $country->name }}">{{ ucwords($country->name) }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="country" class="col-form-label required">@lang('modules.client.country')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-md-offset-6">
                                            <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                                <button type="submit" id="save-form" class="btn btn-primary form-control" data-original-title="" title="">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="tab-pane fade" id="top-profile" role="tabpanel" aria-labelledby="profile-top-tab">
                            {!! Form::open(['id'=>'paymentSettingsShipping','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="form-body" >
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                            <input type="text" name="attention" id="attention2" placeholder="-"
                                                   class="form-control form-control-lg"
                                                   value="{{$customer_data['shipping_address']['attention']??''}}">
                                            <label for="attention2" class="control-label">Full Name</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                            <input type="text" name="street" id="sh_street" placeholder="-"
                                                   class="form-control form-control-lg"
                                                   value="{{$customer_data['shipping_address']['street']??''}}">
                                            <label for="sh_street" class="control-label">Street</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                            <input type="text" name="street2" id="sh_street2" placeholder="-"
                                                   class="form-control form-control-lg"
                                                   value="{{$customer_data['shipping_address']['street2']??''}}">
                                            <label for="sh_street2" class="control-label">Street2</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                            <input type="text" name="city" id="city2" placeholder="-"
                                                   class="form-control form-control-lg"
                                                   value="{{$customer_data['shipping_address']['city']??''}}">
                                            <label for="city2" class="control-label">City</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                            <input type="text" name="state" id="state2" placeholder="-"
                                                   class="form-control form-control-lg"
                                                   value="{{$customer_data['shipping_address']['state']??''}}">
                                            <label for="state2" class="control-label">State</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                            <input type="text" name="zip" id="zip_code2" placeholder="-"
                                                   class="form-control form-control-lg"
                                                   value="{{$customer_data['shipping_address']['zip']??''}}">
                                            <label for="zip_code2" class="control-label">Zip Code</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-label-group form-group">
                                                <select placeholder="-" class="select2 form-control form-control-lg" data-placeholder="Country" id="sh_country" name="country">
                                                    <option value="">--</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->iso_alpha2 }}">{{ ucwords($country->name) }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="sh_country" class="col-form-label required">@lang('modules.client.country')</label>
                                            </div>
                                        

                                        <!-- <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                            <input type="text" name="country" id="country"
                                                   class="form-control form-control-lg"
                                                   value="{{$customer_data['shipping_address']['country']??''}}">
                                            <label for="office_end_time" class="control-label">Country</label>
                                        </div> -->
                                    </div>
                                    <div class="col-md-4 col-md-offset-6">
                                        <div class="form-label-group form-group bootstrap-timepicker timepicker">
                                            <button type="submit" id="save-form" class="btn btn-primary form-control" data-original-title="" title="">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="zohoCardModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Card Details</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                </div>
                <div class="modal-body">
                    Loading...
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

        @if(\Session::has('message'))
        toastr.success("{{  \Session::get('message') }}");
        @endif

        showData();

        var table;
        function showData() 
        {
            table = $('#cards-table').dataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                stateSave: true,
                "ordering": false,
                ajax: '{!! route('admin.billing.cards_list') !!}',
                language: {
                    "url": "<?php echo __("app.datatable") ?>"
                },
                "fnDrawCallback": function( oSettings ) {
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                },
                columns: [
                    { data: 'payment_gateway', name: 'payment_gateway' },
                    { data: 'card_num', name: 'card_num' },
                    { data: 'exp_date', name: 'exp_date' },
                    { data: 'action', name: 'action' }
                ],
                lengthMenu: [
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
            $('.disable-search, #cards-table_wrapper select').select2({
                minimumResultsForSearch: -1
            });

            
            $('#card_number').keypress(function(e) {
                if (this.value.length == 16) {
                    e.preventDefault();
                }
                this.value = this.value.replace(/\D/g,'');
            });
        });

        $(document).on('click', '.viewZohoCard', function () {
            var id = $(this).data('card-id');
            var url = '{{ route('admin.billing.zoho-card', ':id')}}';
            url = url.replace(':id', id);
            $.ajaxModal('#zohoCardModal', url);
        });
        $(document).on('click', '.delZohoCard', function () {
            var id = $(this).data('card-id');

            swal({
                title: "Are you sure?",
                text: "Are you sure to delete this credit card!",
                icon: "warning",
                buttons: ["No, cancel please!", "Yes, delete it!"],
                dangerMode: true
            }).then((willDelete) => {
                if (willDelete) {
                    var url = '{{ route('admin.billing.del-zoho-card')}}';
                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        dataType: 'JSON',
                        data: {'_token': token, 'id': id},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                table._fnDraw();
                            }else{
                                swal("ERROR!", response.message, "error");
                            }
                        }
                    });
                }
            });

        });

        $('#paymentSettingsBilling').submit(function () {
            $.easyAjax({
                url: '{{route('admin.billing.payment_settings.save-billing')}}',
                container: '#paymentSettingsBilling',
                type: "POST",
                data: $('#paymentSettingsBilling').serialize(),
                success: function (data) {
                    if(data.status == 'success'){

                    }
                }
            })
        });

        $('#paymentSettingsShipping').submit(function () {
            $.easyAjax({
                url: '{{route('admin.billing.payment_settings.save-shipping')}}',
                container: '#paymentSettingsShipping',
                type: "POST",
                data: $('#paymentSettingsShipping').serialize(),
                success: function (data) {
                    if(data.status == 'success'){

                    }
                }
            })
        });
        
   </script>
@endpush