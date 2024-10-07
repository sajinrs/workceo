@extends('layouts.auth')

@section('content')

    <div class="row" style="margin-left: -7.5px;margin-right: -7.5px;">
        <div class="col-md-6">
            <h1>Login</h1>
        </div>
        <div class="col-md-6">
            <p style="margin-top: 12px;margin-bottom: 0; text-align:right">New to WorkCEO? <a href="{{ route('front.signup.index') }}" class="text-primary m-l-5"><b>Sign Up</b></a></p>
        </div>
        @if (Session::has('error'))
            <div class="col-md-12">
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            </div>
            <?php Session::forget('error');?>
        @endif
    </div>

    <form class="form-horizontal form-material" id="loginform" action="{{ route('login') }}" method="POST">
        {{ csrf_field() }}


        @if (session('message'))
            <div class="alert alert-danger m-t-10">
                {{ session('message') }}
            </div>
        @endif

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <div class="col-xs-12">
                <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" autofocus required="" placeholder="@lang('app.email')">
                @if ($errors->has('email'))
                    <div class="help-block with-errors">{{ $errors->first('email') }}</div>
                @endif

            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <input class="form-control" id="password" type="password" name="password" required="" placeholder="@lang('modules.client.password')"> <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                @if ($errors->has('password'))
                    <div class="help-block with-errors">{{ $errors->first('password') }}</div>
                @endif
            </div>
        </div>
        @if($setting->google_recaptcha_key)
        <div class="form-group {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
            <div class="col-xs-12">
                <div class="g-recaptcha"
                     data-sitekey="{{ $setting->google_recaptcha_key }}">
                </div>
                @if ($errors->has('g-recaptcha-response'))
                    <div class="help-block with-errors">{{ $errors->first('g-recaptcha-response') }}</div>
                @endif
            </div>
        </div>
        @endif

        <div class="form-group">
            <div class="col-md-12">
                <div class="checkbox checkbox-primary pull-left p-t-0">
                    <input id="checkbox-signup" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                </div>
                <label for="checkbox-signup" class="remember text-dark"> @lang('app.rememberMe') </label>
                <a href="{{ route('password.request') }}"  class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> @lang('app.forgotPassword')?</a> </div>
        </div>
        <div class="form-group text-center" style="margin-bottom: 15px;">
            <div class="col-xs-12">
                <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit">@lang('app.login')</button>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-xs-12">
                <p class="line-center"><span>or</span></p>

                <div class="social-network theme-form">
                    <a href="{{ url('/login/facebook') }}" class="m-t-5 btn btn-block social-btn btn-fb mb-2 text-center"><i class="fa fa-facebook m-r-5"></i> Sign In with Facebook</a>
                    <a href="{{ url('/login/google') }}" class="m-t-5 btn btn-block social-btn btn-google text-center"><img src="{{ asset('img/g-icon.png') }}" /> Sign In with Google</a>
                </div>
            </div>
        </div>

        <div class="form-group m-b-0">
            <div class="col-sm-12 text-center">
                <p>Go to Website <a href="http://workceo.com/" class="text-primary m-l-5"><b>Home</b></a></p>
                <p style="font-size: 11px;">By continuing you are agreeing to our <a href="http://workceo.com/privacy" target="_blank" class="text-primary m-l-5"><b>Privacy Policy</b></a> and <a href="http://workceo.com/terms" target="_blank" class="text-primary m-l-5"><b>Terms Of Service</b></a></p>
            </div>
        </div>
        <div class="form-group m-b-0">
            <div class="col-sm-12 text-center">
               
            </div>
        </div>
    </form>
@endsection
