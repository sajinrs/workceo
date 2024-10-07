
@extends('layouts.auth')

@section('content')
{{--    <a href="{{ url('/login/google') }}" class="btn btn-google-plus"> Google</a>--}}
{{--    <a href="{{ url('/login/facebook') }}" class="btn btn-facebook"> Facebook</a>--}}

    <div class="form-section reg-form">
 
        <div class="row2">
            <div class="col-md-5">
                <h1>Sign Up</h1>
            </div>
            <div class="col-md-7">
                <p style="margin-top: 12px;margin-bottom: 0; text-align:right">Already have an account? <a href="{{ route('login') }}" class="text-primary m-l-5"><b>Login</b></a></p>
            </div>
        </div>
            
            {!! Form::open(['id'=>'register', 'class'=>'form-material form-horizontal', 'method'=>'POST']) !!}
            <div class="row2">

            @if(!empty($messsage))
                <div class="col-md-12 col-12 alert alert-{{$class}}">
                    {!! $messsage !!}
                </div>
            @endif

                <div id="alert" class="col-md-12 col-12"></div>
                
                <div style="clear: both;"></div>

                <div class="col-md-12">
                    <div class="form-group">
                    
                        <input type="email" name="email" id="email" placeholder="{{ __('app.email') }}" class="form-control" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" name="first_name" id="first_name" placeholder="{{ __('app.firstName') }}" class="form-control" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" name="last_name" id="last_name" placeholder="{{ __('app.lasttName') }}" class="form-control" />
                    </div>
                </div>
                <div style="clear: both;"></div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" name="phone" id="phone" placeholder="{{ __('app.phoneNumber') }}" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <input type="password" name="password" id="password" placeholder="{{ __('modules.client.password') }}" class="form-control" /> <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                    </div>
                </div>
                   
                    
                    
                   
                    @if(!is_null($global->google_recaptcha_key))
                        <div class="form-group mb-4">
                            <div class="g-recaptcha" data-sitekey="{{ $global->google_recaptcha_key }}"></div>
                        </div>
                    @endif

                    <div class="form-group text-center m-t-20">
                        <div class="col-md-12">
                            <button id="save-form" class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="button">@lang('app.signup')</button>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 5px;">
                        <div class="col-md-12">
                            <p class="line-center"><span>or</span></p>

                            <div class="social-network theme-form">
                                <a href="{{ url('/login/facebook') }}" class="m-t-5 btn btn-block social-btn btn-fb mb-2 text-center"><i class="fa fa-facebook m-r-5"></i> Sign In with Facebook</a>
                                <a href="{{ url('/login/google') }}" class="m-t-5 btn btn-block social-btn btn-google text-center"><img src="{{ asset('img/g-icon.png') }}" /> Sign In with Google</a>
                            </div>
                        </div>
                    </div>

                <!-- <div class="form-group text-center m-t-20">
                    <div class="col-xs-12">
                        <a href="{{ url('/login/facebook') }}" class="btn btn-fb btn-lg btn-block waves-effect waves-light" > <i class="fa fa-facebook m-r-5"></i>Sign In with Facebook</a>
                    </div>
                </div>
                <div class="form-group text-center m-t-10">
                    <div class="col-xs-12">
                        <a href="{{ url('/login/google') }}" class="btn btn-google btn-lg btn-block waves-effect waves-light" > <i class="fa fa-google m-r-5"></i>Sign In with Facebook</a>
                    </div>
                </div> -->

                    <div class="form-group m-b-0">
                        <div class="col-sm-12 text-center">
                            <p>Go to Website <a href="http://workceo.com/" class="text-primary m-l-5"><b>Home</b></a></p>
                            <p style="font-size: 11px;">By continuing you are agreeing to our <a href="http://workceo.com/privacy" target="
                            _blank" class="text-primary m-l-5"><b>Privacy Policy</b></a> and <a href="http://workceo.com/terms" target="
                            _blank" class="text-primary m-l-5"><b>Terms Of Service</b></a></p>

                            <h4 class="toll-free">For Questions CALL TOLL FREE: <a class="text-primary">1-888-340-WORK</a></h4>
                        </div>
                    </div>

                    
            </div>
            {!! Form::close() !!}
        </div>


@endsection
