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
        @include('sections.payment_setting_menu')
       </div>
        <div class="col-md-9">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                          <h5>@lang('app.menu.onlinePayment')</h5>
                           
                        </div>
                        {!! Form::open(['id'=>'updateSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                     <div  class="card-body">
             
                 <div class="form-body" >
                    <h5>Paypal</h5>
                        <div class="vtabs customvtab m-t-10">

                   


                      
                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                           
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="white-box">
                                  
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12 ">
                                                

                                                <div class="form-body">
                                                    <div class="row">
                                                  

                                                        <div class="col-md-12">
                                                            <div class="form-label-group form-group">
                                                            
                                                                <input type="password" name="paypal_client_id" id="paypal_client_id"
                                                                       class="form-control form-control-lg" value="{{ $credentials->paypal_client_id }}" placeholder="*">
                                                                <span class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                                                    <label for="paypal_client_id" class="fomr-control required">Paypal Client Id</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-label-group form-group">
                                                              
                                                                <input type="password" name="paypal_secret" id="paypal_secret"
                                                                       class="form-control form-control-lg" value="{{ $credentials->paypal_secret }}" placeholder="*">
                                                                <span class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                                                  <label for="paypal_secret" class="fomr-control required">Paypal Secret</label>
                                                            </div>

                                                            <div class="form-label-group form-group">
                                                               
                                                                <select class="form-control form-control-lg" name="paypal_mode" id="paypal_mode" data-style="form-control form-control-lg">
                                                                    <option value="sandbox" @if($credentials->paypal_mode == 'sandbox') selected @endif>Sandbox</option>
                                                                    <option value="live" @if($credentials->paypal_mode == 'live') selected @endif>Live</option>
                                                                </select>
                                                                 <label>Select environment</label>
                                                            </div>
                                                          <label for="mail_from_name">@lang('app.webhook')</label>
                                                            <div class="form-label-group form-group">
                                                              
                                                                <p class="text-bold">{{ route('verify-ipn') }}</p>
                                                                <p class="text-info">(@lang('messages.addPaypalWebhookUrl'))</p>
                                                            </div>
                                                        </div>
                                                        <!--/span-->

                                                        <div class="col-md-12">
                                                            <div class="form-label-group form-group">

                                                            <label class="control-label  pl-0" >@lang('modules.payments.paypalStatus')</label><br /><br />
                                                            <div class="switch-showcase icon-state">
                                                                <label class="switch">
                                                                    <input type="checkbox" name="paypal_status" @if($credentials->paypal_status == 'active') checked @endif ><span class="switch-state"></span>
                                                                </label>
                                                            </div>
                                                               
                                                               
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 m-t-20">
                                                            <h5>Stripe</h5>
                                                            <hr class="m-t-0 m-b-20">
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-label-group form-group">
                                                              
                                                                <input type="text" name="stripe_client_id" id="stripe_client_id"
                                                                       class="form-control form-control-lg" value="{{ $credentials->stripe_client_id }}" placeholder="*">
                                                               <label for="stripe_client_id" class="fomr-control required">Stripe Client Id</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-label-group form-group">
                                                           
                                                                <input type="text" name="stripe_secret" id="stripe_secret"
                                                                       class="form-control form-control-lg" value="{{ $credentials->stripe_secret }}" placeholder="*">
                                                                <i class="fa fa-fw fa-eye-slash field-icon toggle-password"></i>
                                                                     <label for="stripe_secret" class="fomr-control required">Stripe Secret</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-label-group form-group">
                                                              
                                                                <input type="text" name="stripe_webhook_secret" id="stripe_webhook_secret"
                                                                       class="form-control form-control-lg" value="{{ $credentials->stripe_webhook_secret }}" placeholder="*">
                                                                <span class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                                                  <label for="stripe_webhook_secret" class="fomr-control required">Stripe Webhook Secret</label>
                                                            </div>
                                                           <label for="mail_from_name">@lang('app.webhook')</label>
                                                            <div class="form-label-group form-group">
                                                            
                                                                <p class="text-bold">{{ route('verify-webhook') }}</p>
                                                                <p class="text-info">(@lang('messages.addStripeWebhookUrl'))</p>
                                                                   
                                                            </div>
                                                        </div>
                                                        <!--/span-->

                                                        <div class="col-md-12">
                                                            <div class="form-label-group form-group">

                                                            <label class="control-label  pl-0" >@lang('modules.payments.stripeStatus')</label><br /><br />
                                                            <div class="switch-showcase icon-state">
                                                                <label class="switch">
                                                                <input type="checkbox" name="stripe_status" @if($credentials->stripe_status == 'active') checked @endif ><span class="switch-state"></span>
                                                                </label>
                                                            </div>                                                              
                                                                
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 m-t-20">
                                                            <h5>@lang('modules.paymentSetting.razorpay')</h5>
                                                            <hr class="m-t-0 m-b-20">
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-label-group form-group">
                                                               
                                                                <input type="text" name="razorpay_key" id="razorpay_key"
                                                                       class="form-control form-control-lg" value="{{ $credentials->razorpay_key }}" placeholder="*">
                                                                  <label for="razorpay_key" class="fomr-control required">Razorpay Key</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-label-group form-group">
                                                             
                                                                <input type="text" name="razorpay_secret" id="razorpay_secret"
                                                                       class="form-control form-control-lg" value="{{ $credentials->razorpay_secret }}" placeholder="*">
                                                                <span class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                                                   <label for="razorpay_secret" class="fomr-control required">Razorpay Secret Key</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-label-group form-group">
                                                               
                                                                <input type="text" name="razorpay_webhook_secret" id="razorpay_webhook_secret"
                                                                       class="form-control form-control-lg" value="{{ $credentials->razorpay_webhook_secret }}" placeholder="*">
                                                                <span class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                                                 <label for="razorpay_webhook_secret" class="fomr-control">Razorpay Webhook Secret Key</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-label-group form-group">

                                                                <label class="control-label  pl-0" >@lang('modules.payments.razorpayStatus')</label><br /><br />
                                                                <div class="switch-showcase icon-state">
                                                                    <label class="switch">
                                                                    <input type="checkbox" name="razorpay_status" @if($credentials->razorpay_status == 'active') checked @endif ><span class="switch-state"></span>
                                                                    </label>
                                                                </div>                                                              
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <!--/row-->

                                                </div>
                                                  
                                            </div>
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

        <div class="card-footer text-right">
            <div class="form-actions col-md-3  offset-md-9 ">
                <button type="submit" id="save-form-2" class="btn btn-primary form-control"> @lang('app.save')</button>
            </div>
        </div>
        {!! Form::close() !!}


    </div>
    <!-- .row -->


    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="leadStatusModal" role="dialog" aria-labelledby="myModalLabel"
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
        
        $('#save-form-2').click(function () {
            $.easyAjax({
                url: '{{ route('admin.payment-gateway-credential.update', [$credentials->id])}}',
                container: '#updateSettings',
                type: "POST",
                redirect: true,
                data: $('#updateSettings').serialize()
            })
        });
    </script>
@endpush

