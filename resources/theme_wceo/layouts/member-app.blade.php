<!DOCTYPE html>

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <title>@if($userRole) {{ $userRole->display_name }} @lang("app.panel")  @else @lang("app.employeePanel") @endif | {{ __($pageTitle) }}</title>

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/fontawesome_5.min.css')}}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/icofont.css')}}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/themify.css')}}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/flag-icon.css')}}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/feather-icon.css')}}">

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap.css')}}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/admin/style.css?ver=1.0.3')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dev_style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dev_style_b.css')}}">
    <link id="color" rel="stylesheet" href="{{ asset('themes/wceo/assets/css/light-1.css')}}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/floating-labels.css')}}">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/responsive.css')}}">


    <!-- Bootstrap Core CSS -->
    {{--<link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css'>
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css'>
--}}
    <!-- This is Sidebar menu CSS -->
    {{--<link href="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">--}}

    <link href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    {{--<link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet">--}}

    <!-- This is a Animation CSS -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/sweetalert2.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/select2.css')}}">



    @stack('head-script')

            <!-- This is a Custom CSS -->
    {{--<link href="{{ asset('css/style.css') }}" rel="stylesheet">--}}
    <!-- color CSS you can use different color css from css/colors folder -->
    <!-- We have chosen the skin-blue (default.css) for this starter
       page. However, you can choose any other skin from folder css / colors .
       -->
    {{--<link href="{{ asset('css/colors/default.css') }}" id="theme" rel="stylesheet">--}}
    <link href="{{ asset('plugins/froiden-helper/helper.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    {{--<link href="{{ asset('css/custom-new.css') }}" rel="stylesheet">--}}

    @if($global->rounded_theme)
    <link href="{{ asset('css/rounded.css') }}" rel="stylesheet">
    @endif

    @if(file_exists(public_path().'/css/member-custom.css'))
    <link href="{{ asset('css/member-custom.css') }}" rel="stylesheet">
    @endif


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    @if($pushSetting->status == 'active')
    <link rel="manifest" href="{{ asset('manifest.json') }}" />
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async='async'></script>
    <script>
        var OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: "{{ $pushSetting->onesignal_app_id }}",
                autoRegister: false,
                notifyButton: {
                    enable: false,
                },
                promptOptions: {
                    /* actionMessage limited to 90 characters */
                    actionMessage: "We'd like to show you notifications for the latest news and updates.",
                    /* acceptButtonText limited to 15 characters */
                    acceptButtonText: "ALLOW",
                    /* cancelButtonText limited to 15 characters */
                    cancelButtonText: "NO THANKS"
                }
            });
            OneSignal.on('subscriptionChange', function (isSubscribed) {
                console.log("The user's subscription state is now:", isSubscribed);
            });


            if (Notification.permission === "granted") {
                // Automatically subscribe user if deleted cookies and browser shows "Allow"
                OneSignal.getUserId()
                    .then(function(userId) {
                        if (!userId) {
                            OneSignal.registerForPushNotifications();
                        }
                        else{
                            let db_onesignal_id = '{{ $user->onesignal_player_id }}';

                            if(db_onesignal_id == null || db_onesignal_id !== userId){ //update onesignal ID if it is new
                                updateOnesignalPlayerId(userId);
                            }
                        }
                    })
            } else {
                OneSignal.isPushNotificationsEnabled(function(isEnabled) {
                    if (isEnabled){
                        console.log("Push notifications are enabled! - 2    ");
                        // console.log("unsubscribe");
                        // OneSignal.setSubscription(false);
                    }
                    else{
                        console.log("Push notifications are not enabled yet. - 2");
                        OneSignal.showHttpPrompt();
                        OneSignal.registerForPushNotifications({
                                modalPrompt: true
                        });
                    }

                    OneSignal.getUserId(function(userId) {
                        console.log("OneSignal User ID:", userId);
                        // (Output) OneSignal User ID: 270a35cd-4dda-4b3f-b04e-41d7463a2316
                        let db_onesignal_id = '{{ $user->onesignal_player_id }}';
                        console.log('database id : '+db_onesignal_id);

                        if(db_onesignal_id == null || db_onesignal_id !== userId){ //update onesignal ID if it is new
                            updateOnesignalPlayerId(userId);
                        }


                    });


                    OneSignal.showHttpPrompt();
                });

            }
        });
    </script>
    @endif



    <style>
        .sidebar .notify  {
        margin: 0 !important;
        }
        .sidebar .notify .heartbit {
        top: -23px !important;
        right: -15px !important;
        }
        .sidebar .notify .point {
        top: -13px !important;
        }
        .top-notifications .message-center .user-img{
            margin: 0 0 0 0 !important;
        }
    </style>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-3PGLE7QTT6"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-3PGLE7QTT6');
    </script>
</head>
<body>
<!-- Loader starts-->
<div class="loader-wrapper">
<img src="{{ asset('loader.gif') }}" />
    {{--<div class="loader bg-white">
        <div class="whirly-loader"> </div>
    </div>--}}
</div>
<!-- Loader ends-->
<div class="page-wrapper compact-wrapper wceo-wrapper">
    <!-- Page Header Start-->
    <div class="page-main-header">
        <div class="main-header-right row pl-0">
            <div class="main-header-left d-lg-none">
                <div class="logo-wrapper">

                    <a href="{{ route('member.dashboard') }}">
                        <img  src="{{ asset('front/logo.png') }}" alt="home" class="static_logo admin-logo"/>
                        <img src="{{ asset('front/logo_gif.gif') }}" alt="home" class=" admin-logo"/>
                    </a>

                </div>
            </div>
            <div class="mobile-sidebar d-block d-lg-none">
                <div class="media-body text-right switch-sm">
                    <label class="switch"><a href="#"><i id="sidebar-toggle" data-feather="align-left"></i></a></label>
                </div>
            </div>

            <div class="nav-right col p-0">
                <ul class="nav-menus">
                    <li class="pl-0">
                       <span class="pull-left top-menu-logo"><img src="{{ $global->logo_url }}" alt="home" class=" admin-logo"/>
</span>
                        <h3 class="pull-left"> {{ $global->company_name }}</h3>
                    </li>



                <!-- Full screen-->
                    <li><a class="text-dark" href="#!" title="View Full Screen" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>


                    <!-- Notification-->
                    <li class="onhover-dropdown">
                        <i data-feather="bell"></i>
                        @if(count($user->unreadNotifications) > 0)
                            <span class="dot"></span>
                        @endif

                        <ul class="notification-dropdown member-notifications onhover-show-div pl-0">
                            <li>@lang('app.newNotifications') <span class="badge badge-pill badge-primary pull-right top-notification-count">{{ count($user->unreadNotifications) }}</span></li>
                            <div class="notificationList">
                                @foreach ($user->unreadNotifications->slice(0, 6) as $notification)
                                    @if(view()->exists('notifications.member.'.\Illuminate\Support\Str::snake(class_basename($notification->type))))
                                        @include('notifications.member.'.\Illuminate\Support\Str::snake(class_basename($notification->type)))
                                    @endif
                                @endforeach

                                @if(count($user->unreadNotifications) > 0)
                                <li class="bg-light txt-dark">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                {{--<a class="notification_btn text-center mark-notification-read btn btn-outline-secondary btn-block pull-right" href="javascript:;"> @lang('app.markRead') </a>--}}
                                            </div>
                                            <div class="col-sm-6">
                                                <a class="notification_btn  mark-notification-read pull-right show-all-notifications2 btn btn-primary btn-block" href="javascript:;"> View All</a>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            </div>


                        </ul>
                    </li>


               <!-- User-->
                    <li class="onhover-dropdown">
                        <div class="media align-items-center">
                            @if(is_null($user->image))
                                <img class="align-self-center pull-right img-50 rounded-circle" src="{{ asset('img/default-profile-3.png') }}" alt="{{ ucwords($user->name) }}">

                            @else
                                <img class="align-self-center pull-right img-50 rounded-circle" src="{{ asset_url('avatar/'.$user->image) }}" alt="{{ ucwords($user->name) }}">

                            @endif
                            <div class="dotted-animation"><span class="animate-circle"></span><span class="main-circle"></span></div>
                        </div>
                        <ul class="profile-dropdown wceo-admin onhover-show-div p-20">
                            <li><b>{{ ucwords($user->name) }}</b></li>
                            @if($user->hasRole('admin'))
                                <li>
                                    <a href="{{ route('admin.dashboard') }}">
                                        <i class="fa fa-sign-in-alt"></i>  @lang("app.loginAsAdmin")
                                    </a>
                                </li>
                            @endif

                            <li><a href="{{ route('member.profile.index') }}"><i class="fa fa-cog"></i> @lang("app.menu.profileSettings")</a></li>                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();"
                                ><i class="fa fa-power-off"></i> @lang('app.logout')</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>

                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="d-lg-none mobile-toggle pull-right"><i data-feather="more-horizontal"></i></div>
            </div>
            <script id="result-template" type="text/x-handlebars-template">
                <div class="ProfileCard u-cf">
                    <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
                    <div class="ProfileCard-details">
                        <div class="ProfileCard-realName">NAME.....</div>
                    </div>
                </div>
            </script>
            <script id="empty-template" type="text/x-handlebars-template">
                <div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div>

            </script>
        </div>
    </div>
    <!-- Page Header Ends-->
    <!-- Page Body Start-->
    <div class="page-body-wrapper sidebar-icon">
        <!-- Page Sidebar Start-->
        <div class="page-sidebar">
            <div class="main-header-left d-none d-lg-block">
                <div class="logo-wrapper">
                    <a href="{{ route('member.dashboard') }}">
                        <img width="70px" src="{{ asset('front/logo.png') }}" alt="home" class="static_logo admin-logo"/>
                        <img width="70px" src="{{ asset('front/logo_gif.gif') }}" alt="home" class=" admin-logo"/>
                    </a>
                </div>
            </div>
            <!-- Left navbar-header -->
        @include('sections.member_left_sidebar')
        <!-- Left navbar-header end -->
        </div>
        <!-- Right sidebar Start-->
        @include('sections.right_sidebar')
        <!-- Page Sidebar Ends-->
        <div class="page-body">

            @if (!empty($__env->yieldContent('filter-section')))
                <div class="col-md-3 filter-section">
                    <h5><i class="fa fa-sliders"></i> @lang('app.filterResults')</h5>
                    @yield('filter-section')
                </div>
            @endif

            @if (!empty($__env->yieldContent('other-section')))
                <div class="col-md-3 filter-section">
                    @yield('other-section')
                </div>
            @endif

            @if (\Request::is('member/dashboard')) 
                <div class="welcome_head_emp">
                    Hello {{ ($user->name) }} &nbsp; |  &nbsp;Welcome to your Employee Dashboard  &nbsp;|  &nbsp;{{ now()->format('F d, Y') }}
                </div>
            @endif
            <div class="
            @if (!empty($__env->yieldContent('filter-section')) || !empty($__env->yieldContent('other-section')))
                    col-md-9
@else
                    col-md-12
@endif
                    data-section">

                @if (!empty($__env->yieldContent('filter-section')) || !empty($__env->yieldContent('other-section')))
                    <div class="row hidden-md hidden-lg">
                        <div class="col-xs-12 p-l-25 m-t-10">
                            <button class="btn btn-inverse btn-outline" id="mobile-filter-toggle"><i class="fa fa-sliders"></i></button>
                        </div>
                    </div>
                @endif

                @yield('page-title')

            <!-- .row -->
                @yield('content')



            </div>
        </div>
    </div>
    <!-- Page Body Ends-->
    <!-- Left navbar-header -->
    {{--@include('sections.member_left_sidebar')--}}
            <!-- Left navbar-header end -->
    <!-- Page Content -->


        <!-- /.container-fluid -->

    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->


{{--Timer Modal--}}
<div class="modal fade bs-modal-md in" id="projectTimerModal" role="dialog" aria-labelledby="myModalLabel"
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
{{--Timer Modal Ends--}}



{{--Timer Modal--}}
<div class="modal fade bs-modal-md in" id="projectTimerModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
{{--Timer Modal Ends--}}

{{--Ajax Modal--}}
<div class="modal fade bs-modal-md in"  id="subTaskModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" id="modal-data-application">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <span class="caption-subject font-red-sunglo bold uppercase" id="subTaskModelHeading">Sub Task e</span>
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
    <!-- /.modal-dialog -->.
</div>
{{--Ajax Modal Ends--}}

{{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="noticeDetailModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
{{--Ajax Modal Ends--}}

<script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
<!-- Bootstrap js-->
<script src="{{ asset('themes/wceo/assets/js/bootstrap/popper.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/bootstrap/bootstrap.js')}}"></script>
<!-- feather icon js-->
<script src="{{ asset('themes/wceo/assets/js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/icons/feather-icon/feather-icon.js')}}"></script>
<!-- Sidebar jquery-->
<script src="{{ asset('themes/wceo/assets/js/sidebar-menu.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/config.js')}}"></script>
<!-- Plugins JS start-->
{{--<script src="{{ asset('themes/wceo/assets/js/chart/chartist/chartist.js')}}"></script>--}}
<script src="{{ asset('themes/wceo/assets/js/chart/knob/knob.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/knob/knob-chart.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/prism/prism.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/clipboard/clipboard.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/counter/jquery.waypoints.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/counter/jquery.counterup.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/counter/counter-custom.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/custom-card/custom-card.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/notify/bootstrap-notify.min.js')}}"></script>
{{--<script src="{{ asset('themes/wceo/assets/js/dashboard/default.js')}}"></script>--}}
<script src="{{ asset('themes/wceo/assets/js/notify/index.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/typeahead/handlebars.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/typeahead/typeahead.bundle.js')}}"></script>
{{--<script src="{{ asset('themes/wceo/assets/js/typeahead/typeahead.custom.js')}}"></script>--}}
<script src="{{ asset('themes/wceo/assets/js/chat-menu.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/height-equal.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/tooltip-init.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/typeahead-search/handlebars.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/typeahead-search/typeahead-custom.js')}}"></script>
<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{ asset('themes/wceo/assets/js/script.js')}}"></script>


<!-- jQuery -->
{{--<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>--}}
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>--}}

<!-- Bootstrap Core JavaScript -->
{{--<script src="{{ asset('bootstrap/dist/js/bootstrap.min.js') }}"></script>--}}
{{--<script src='//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js'></script>--}}

<!-- Sidebar menu plugin JavaScript -->
{{--<script src="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>--}}
<!--Slimscroll JavaScript For custom scroll-->
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<!--Wave Effects -->
{{--<script src="{{ asset('js/waves.js') }}"></script>--}}
<!-- Custom Theme JavaScript -->
{{--<script src="{{ asset('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>--}}
{{--<script src="{{ asset('js/custom.js') }}"></script>--}}
{{--<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>--}}
<script src="{{ asset('plugins/froiden-helper/helper.js') }}"></script>
<script src="{{ asset('plugins/bower_components/toast-master/js/jquery.toast.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/select2/select2-custom.js')}}"></script>
{{--sticky note script--}}
{{--<script src="{{ asset('js/cbpFWTabs.js') }}"></script>--}}
{{--<script src="{{ asset('plugins/bower_components/icheck/icheck.min.js') }}"></script>--}}
{{--<script src="{{ asset('plugins/bower_components/icheck/icheck.init.js') }}"></script>--}}
<script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup-init.js') }}"></script>
<script src="https://js.pusher.com/5.0/pusher.min.js"></script>

<script>
    $('.notificationSlimScroll').slimScroll({
        height: '250',
        position: 'right',
        color: '#dcdcdc'
    });

    $('body').on('click', '.active-timer-modal', function(){
        var url = '{{ route('member.all-time-logs.show-active-timer')}}';
        $('#modelHeading').html('Active Timer');
        $.ajaxModal('#projectTimerModal',url);
    });

    $('.datepicker, #start-date, #end-date').on('click', function(e) {
        e.preventDefault();
        $(this).attr("autocomplete", "off");  
    });

    function updateOnesignalPlayerId(userId) {
        $.easyAjax({
            url: '{{ route("member.profile.updateOneSignalId") }}',
            type: 'POST',
            data:{'userId':userId, '_token':'{{ csrf_token() }}'},
            success: function (response) {
            }
        })
    }

    function addOrEditStickyNote(id)
    {
        var url = '';
        var method = 'POST';
        if(id === undefined || id == "" || id == null) {
            url =  '{{ route('member.sticky-note.store') }}'
        } else{

            url = "{{ route('member.sticky-note.update',':id') }}";
            url = url.replace(':id', id);
            var stickyID = $('#stickyID').val();
            method = 'PUT'
        }

        var noteText = $('#notetext').val();
        var stickyColor = $('#stickyColor').val();
        $.easyAjax({
            url: url,
            container: '#responsive-modal',
            type: method,
            data:{'notetext':noteText,'stickyColor':stickyColor,'_token':'{{ csrf_token() }}'},
            success: function (response) {
                $("#responsive-modal").modal('hide');
                getNoteData();
            }
        })
    }

    // FOR SHOWING FEEDBACK DETAIL IN MODEL
    function showCreateNoteModal(){
        var url = '{{ route('member.sticky-note.create') }}';

        $("#responsive-modal").removeData('bs.modal').modal({
            remote: url,
            show: true
        });

        $('#responsive-modal').on('hidden.bs.modal', function () {
            $(this).find('.modal-body').html('Loading...');
            $(this).data('bs.modal', null);
        });

        return false;
    }

    // FOR SHOWING FEEDBACK DETAIL IN MODEL
    function showEditNoteModal(id){
        var url = '{{ route('member.sticky-note.edit',':id') }}';
        url  = url.replace(':id',id);

        $("#responsive-modal").removeData('bs.modal').modal({
            remote: url,
            show: true
        });

        $('#responsive-modal').on('hidden.bs.modal', function () {
            $(this).find('.modal-body').html('Loading...');
            $(this).data('bs.modal', null);
        });

        return false;
    }

    function selectColor(id){
        $('.icolors li.active ').removeClass('active');
        $('#'+id).addClass('active');
        $('#stickyColor').val(id);

    }


    function deleteSticky(id){

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted Sticky Note!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('member.sticky-note.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        $('#stickyBox_'+id).hide('slow');
                        $("#responsive-modal").modal('hide');
                        getNoteData();
                    }
                });
            }
        });
    }


    //getting all chat data according to user
    function getNoteData(){

        var url = "{{ route('member.sticky-note.index') }}";

        $.easyAjax({
            type: 'GET',
            url: url,
            messagePosition: '',
            data:  {},
            container: ".noteBox",
            error: function (response) {

                //set notes in box
                $('#sticky-note-list').html(response.responseText);
            }
        });
    }

    //    sticky notes script
    var stickyNoteOpen = $('#open-sticky-bar');
    var stickyNoteClose = $('#close-sticky-bar');
    var stickyNotes = $('#footer-sticky-notes');
    var viewportHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
    var stickyNoteHeaderHeight = stickyNotes.height();

    $('#sticky-note-list').css('max-height', viewportHeight-150);

    stickyNoteOpen.click(function () {
        $('#sticky-note-list').toggle(function () {
            $(this).animate({
                height: (viewportHeight-150)
            })
        });
        stickyNoteClose.toggle();
        stickyNoteOpen.toggle();
    })

    stickyNoteClose.click(function () {
        $('#sticky-note-list').toggle(function () {
            $(this).animate({
                height: 0
            })
        });
        stickyNoteOpen.toggle();
        stickyNoteClose.toggle();
    })

    $(function(){
        var $slimScrolls = $('.slimscroll');
        if($slimScrolls.length > 0) {
            $slimScrolls.slimScroll({
                height: 'auto',
                width: '100%',
                position: 'right',
                size: '7px',
                color: '#ccc',
                wheelStep: 10,
                touchScrollStep: 100
            });
            var wHeight = $(window).height() - 60;
            $slimScrolls.height(wHeight);
            $('.sidebar .slimScrollDiv').height(wHeight);
            $(window).resize(function() {
                var rHeight = $(window).height() - 60;
                $slimScrolls.height(rHeight);
                $('.sidebar .slimScrollDiv').height(rHeight);
            });
            $(".slimScrollDiv").css("overflow", "visible");

        }


    });
</script>

<script>
    $('body').on('click', '.timer-modal', function(){
        var url = '{{ route('member.time-log.create')}}';
        $('#modelHeading').html('Start Timer For a Project');
        $.ajaxModal('#projectTimerModal',url);
    });

    $('body').on('click', '.stop-timer-modal', function(){
        var url = '{{ route('member.time-log.show', ':id')}}';
        url = url.replace(':id', $(this).data('timer-id'));

        $('#modelHeading').html('Stop Timer');
        $.ajaxModal('#projectTimerModal',url);
    });

    $('.mark-notification-read').click(function () {
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            type: 'POST',
            url: '{{ route("mark-notification-read") }}',
            data: {'_token': token},
            success: function (data) {
                if(data.status == 'success'){
                    $('.top-notifications').remove();
                    $('.top-notification-count').html('0');
                    $('#top-notification-dropdown .notify').remove();
                    var notifications = '{{ route('member.notices.index') }}';
                    window.location.href = notifications;
                }
            }
        });

    });

    $('.show-all-notifications').click(function () {
        var url = '{{ route('show-all-member-notifications')}}';
        $('#modelHeading').html('View Unread Notifications');
        $.ajaxModal('#projectTimerModal',url);
    });

    $('#sticky-note-toggle').click(function () {
        $('#footer-sticky-notes').toggle();
        $('#sticky-note-toggle').hide();
    })

    $('body').on('click', '.right-side-toggle', function () {
        $(".right-sidebar").slideDown(50).removeClass("shw-rside");
    })

    $(document).on('click',".right_side_toggle", function(){
        $(".right-sidebar").removeClass("right-sidebar-width-auto");
        $(".right-sidebar").slideUp(50).removeClass("show shw-rside");
        // $('#right_side_bar').toggleClass('show shw-rside');
    });
    
    $('body').on('click', '.stop_btn', function () {
       $('.stop-timer-modal').click();
    });
</script>

<script>
$('body').on('click', '.noticeShow', function () {
    var noticeId = $(this).data('notice-id');
    var url = "{{ route('member.notices.show',':id') }}";
    url = url.replace(':id', noticeId);
    $('#modelHeading').html('Notice');
    $.ajaxModal('#noticeDetailModal', url);
});
</script>

@if(!is_null($timer))
    <script>

        $(document).ready(function(e) {
            var $worked = $("#active-timer");
            if($worked.length){
                function updateTimer() {
                    var myTime = $worked.html();
                    var ss = myTime.split(":");
//            console.log(ss);

                    var hours = ss[0];
                    var mins = ss[1];
                    var secs = ss[2];
                    secs = parseInt(secs)+1;

                    if(secs > 59){
                        secs = '00';
                        mins = parseInt(mins)+1;
                    }

                    if(mins > 59){
                        secs = '00';
                        mins = '00';
                        hours = parseInt(hours)+1;
                    }

                    if(hours.toString().length < 2) {
                        hours = '0'+hours;
                    }
                    if(mins.toString().length < 2) {
                        mins = '0'+mins;
                    }
                    if(secs.toString().length < 2) {
                        secs = '0'+secs;
                    }
                    var ts = hours+':'+mins+':'+secs;

//            var dt = new Date();
//            dt.setHours(ss[0]);
//            dt.setMinutes(ss[1]);
//            dt.setSeconds(ss[2]);
//            var dt2 = new Date(dt.valueOf() + 1000);
//            var ts = dt2.toTimeString().split(" ")[0];
                    $worked.html(ts);
                    setTimeout(updateTimer, 1000);
                }
                setTimeout(updateTimer, 1000);
            }

        });

    
    </script>




@endif

<script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
   
   var pusher = new Pusher('13e1002107b1ad495f88', {
     cluster: 'us2',
     forceTLS: true
   });
   
   
</script>

@stack('footer-script')

</body>
</html>
