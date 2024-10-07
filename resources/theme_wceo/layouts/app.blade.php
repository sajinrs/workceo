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

    <title>@lang('app.adminPanel') | {{ __($pageTitle) }}</title>
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
    <!-- Plugins css start-->
    {{--<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/chartist.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/prism.css')}}">--}}

    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap.css')}}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/admin/style.css?ver=1.0.3')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dev_style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dev_style_b.css')}}">
    <link id="color" rel="stylesheet" href="{{ asset('themes/wceo/assets/css/light-1.css')}}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/floating-labels.css')}}">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/responsive.css?ver=1.0.1')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/admin/popup-style.css')}}">



    <!-- Bootstrap Core CSS -->
    {{--<link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">--}}
    {{--<link rel='stylesheet prefetch'--}}
          {{--href='https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css'>--}}
    {{--<link rel='stylesheet prefetch'--}}
          {{--href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css'>--}}

    <!-- This is Sidebar menu CSS -->
    {{--<link href="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">--}}

    <link href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    {{--<link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet">--}}

    <!-- This is a Animation CSS -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">


    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/sweetalert2.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/select2.css')}}">

    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/date-picker.css') }}">

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

    @if(file_exists(public_path().'/css/admin-custom.css'))
        <link href="{{ asset('css/admin-custom.css') }}" rel="stylesheet">
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
                            //OneSignal.setSubscription(false);
                        }
                        else{
                            console.log("Push notifications are not enabled yet. - 2");
                            //OneSignal.showHttpPrompt();
                            //OneSignal.registerForPushNotifications({
                                    //modalPrompt: true
                             //});
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
        /* .content-wrapper .sidebar #side-menu>li>.active {
            background: transparent;
        }
        .content-wrapper .sidebar #side-menu>li>.active:hover {
            background: #272d36;
        } */
    </style>

    {{--<script src='https://kit.fontawesome.com/a076d05399.js'></script>--}}

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
<!-- page-wrapper Start-->
@php 
    $topAds     = topAds(); 
    $headerAds  = headerAds();
    $popupAds   = popupAds();
    $toasterAds = toasterAds();
    $footerAds  = footerAds();
    $userChecklistCount = userChecklistCount();
@endphp

@if($topAds->status == 1)
    <div class="topAds-new">
        {{$topAds->content}} 
        @if($topAds->button_text) <a href="{{ route('admin.billing') }}">{{$topAds->button_text}}</a> @endif
    </div>
@endif

@if($popupAds->status == 1)
    <div id="adsModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <a href="{{ route('admin.billing') }}"><img src="{{ $popupAds->image_url ?? '' }}"  class="img-responsive" alt=""/></a>
                </div>
            </div>
        </div>
    </div>
@endif




<div class="page-wrapper compact-wrapper wceo-wrapper">
    <!-- Page Header Start-->
    <div class="page-main-header">
        <div class="main-header-right row pl-0">
            <div class="main-header-left d-lg-none">
                <div class="logo-wrapper">

                    <a href="{{ route('admin.dashboard') }}">
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
                    <li class="pl-0 @if(isset($headerAds->status) && $headerAds->status == 1) headads-exit @endif">
                       <span class="pull-left top-menu-logo"><img src="{{ $global->logo_url }}" alt="home" class=" admin-logo"/>
</span>
                        <h3 class="pull-left d-none d-sm-block"> {{ $global->company_name }}</h3>
                    </li>

                    @if($headerAds->status == 1)
                        <li class="headerAds"> <a href="{{ route('admin.billing') }}"><img src="{{ $headerAds->image_url ?? '' }}" alt=""/></a> </li>
                    @endif                    

                    <!-- Full screen-->
                    <li class="d-none d-sm-block"><a data-toggle="tooltip" title="View Full Screen" class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
                    <!-- Language -->
                    {{--<li class="onhover-dropdown"><a class="txt-dark" href="#">--}}
                            {{--<h6>EN</h6></a>--}}
                        {{--<ul class="language-dropdown onhover-show-div p-20">--}}
                            {{--<li><a href="#" data-lng="en"><i class="flag-icon flag-icon-is"></i> English</a></li>--}}
                            {{--<li><a href="#" data-lng="es"><i class="flag-icon flag-icon-um"></i> Spanish</a></li>--}}
                            {{--<li><a href="#" data-lng="pt"><i class="flag-icon flag-icon-uy"></i> Portuguese</a></li>--}}
                            {{--<li><a href="#" data-lng="fr"><i class="flag-icon flag-icon-nz"></i> French</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    <!-- Menu-->
                    <li class="onhover-dropdown btn-dropdoen-add">
                        <i data-feather="plus"></i>
                        <ul class="wceo-menu-dropdown onhover-show-div p-20">
                            @if(in_array('projects',$modules))
                                <li><a href="{{ route('admin.projects.create') }}"><i class="fa fa-th-list"></i>@lang('app.addJob')</a></li>
                            @endif

                            @if(in_array('tasks',$modules))
                                <li><a href="{{ route('admin.all-tasks.create') }}"><i class="fa fa-tasks"></i>@lang('app.add') @lang('app.task')</a></li>
                            @endif

                            @if(in_array('clients',$modules))
                                <li><a href="{{ route('admin.clients.create') }}"><i class="icofont icofont-users-alt-2"></i>@lang('app.add') @lang('app.client')</a></li>
                            @endif

                            @if(in_array('employees',$modules))
                                <li><a href="{{ route('admin.employees.create') }}"><i class="icofont icofont-users"></i>@lang('app.add') @lang('app.employee')</a></li>
                            @endif

                            @if(in_array('payments',$modules))
                                <li><a href="{{ route('admin.payments.create') }}"><i class="icofont icofont-coins"></i>@lang('modules.payments.addPayment')</a></li>
                            @endif

                            @if(in_array('tickets',$modules))
                                <li><a href="{{ route('admin.tickets.create') }}"><i class="icofont icofont-ticket"></i>@lang('app.add') @lang('modules.tickets.ticket')</a></li>
                            @endif
                        </ul>
                    </li>
                    <!-- Notification-->
                    <li class="onhover-dropdown">
                        <i data-feather="bell"></i>
                        @if(count($user->unreadNotifications) > 0)
                            <span class="dot"></span>
                        @endif
            
                        <ul class="notification-dropdown onhover-show-div pl-0">
                            <li>@lang('app.newNotifications') <span class="badge badge-pill badge-primary pull-right top-notification-count">{{ count($user->unreadNotifications) }}</span></li>
                            <div class="notificationList">
                                @foreach ($user->unreadNotifications->slice(0, 6) as $notification)
                                    @if(view()->exists('notifications.member.'.\Illuminate\Support\Str::snake(class_basename($notification->type))))
                                        @include('notifications.member.'.\Illuminate\Support\Str::snake(class_basename($notification->type)))
                                    @endif
                                @endforeach

                                
                                    <li class="bg-light txt-dark">
                                        <div class="row">
                                        <div class="col-sm-6">
                                        <a class="notification_btn text-center mark-notification-read2 btn btn-outline-secondary btn-block pull-right" href="{{ route('admin.notices.create') }}"> Create New </a>
    </div>
    <div class="col-sm-6">
                                        <a class="notification_btn mark-notification-read pull-right show-all-notifications2 btn btn-primary btn-block" href="javascript:;"> View All</a>
    </div>
    </div>
                                    </li>
                               
                            </div>

                            
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);" id="job-activity-top" data-toggle="tooltip" title="Job Activity" ><i data-feather="list"></i></a></li>

                    <li><a href="javascript:void(0);" id="sticky-note-top" data-toggle="tooltip" title="Notes"><i data-feather="edit"></i></a></li>


                    {{--<li><a  class="text-dark" href="javascript:;" ><i class="icon-note"></i></a></li>--}}
                    <!-- User-->
                    <li class="onhover-dropdown userProfile">
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
                            
                            <li>
                                <!--<a href="{{ route('member.dashboard') }}">
                                    <i class="fa fa-sign-in-alt"></i> @lang('app.loginAsEmployee')
                                </a>-->
                                <a href="{{ route('member.dashboard') }}"><i data-feather="log-in"></i> @lang('app.loginAsEmployee')</a>
                            </li>
                            @role('admin')
                            <li><a href="{{ route('admin.settings.index') }}"><!--<i class="fa fa-cog">--></i><i data-feather="settings"></i> @lang('app.menu.settings')</a></li>
                            <li><a href="{{ route('admin.billing') }}"><!--<i class="fa fa-money-bill"></i>--><i data-feather="bookmark"></i> @lang('app.menu.account') & @lang('app.menu.billing')</a> </li>

                            @endrole
                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();"
                                ><!--<i class="fa fa-power-off"></i>--><i data-feather="power"></i> @lang('app.logout')</a>
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
        <div class="page-sidebar @if($topAds->status == 1) leftBar @endif">
            <div class="main-header-left d-none d-lg-block">
                <div class="logo-wrapper">
                    <a href="{{ route('admin.dashboard') }}">
                        <img width="70px" src="{{ asset('front/logo.png') }}" alt="home" class="static_logo admin-logo"/>
                        <img width="70px" src="{{ asset('front/logo_gif.gif') }}" alt="home" class=" admin-logo"/>
                    </a>
                </div>
            </div>
            <!-- Left navbar-header -->
            @include('sections.left_sidebar')
            <!-- Left navbar-header end -->
        </div>
        <!-- Right sidebar Start-->
        {{--<div class="right-sidebar" id="right_side_bar">
        </div>--}}
        @include('sections.right_sidebar')
        <!-- Page Sidebar Ends-->
        <div class="page-body">

              @if (!empty($__env->yieldContent('other-section')))
                <div class="row">

                    @endif


            @yield('page-title')
            
                @if (!empty($__env->yieldContent('filter-section')))
                    <div class="col-md-3 filter-section">
                        <h5 class="pull-left"><i class="fa fa-sliders"></i> @lang('app.filterResults')</h5>
                        <h5 class="pull-right hidden-sm hidden-md hidden-xs">
                            <button class="btn btn-default btn-xs btn-circle btn-outline filter-section-close" ><i class="fa fa-chevron-left"></i></button>
                        </h5>

                        @yield('filter-section')
                    </div>
                @endif

                @if (!empty($__env->yieldContent('other-section')))
                    <div class="col-md-3 filter-section">
                        @yield('other-section')
                    </div>
                @endif
                
                @if (\Request::is('admin/dashboard')) 
                <div class="welcome_head">
                    Hello {{ ($user->name) }} &nbsp; |  &nbsp;Welcome to your Admin Dashboard  &nbsp;|  &nbsp;{{ now()->format('F d, Y') }}

                    <div id="step1" data-highlightClass="testsa" class="pull-right m-r-30">   
                        <span id="checklist-percentage">                     
                            @if($userChecklistCount['totalChecked'] < $userChecklistCount['totalCheckList'])
                                
                                <span class="checkedCount">{{$userChecklistCount['totalChecked']}}</span>/{{$userChecklistCount['totalCheckList']}} 
                            @endif
                        </span>
                        <a class="btn-checklist" href="javascript:;" onclick="setupChecklist()">Setup Checklist</a>
                        <a class="btn-tour" title="Dashboard Tour" href="javascript:;" onclick="showTour()"><i class="icofont icofont-search"></i></a>
                        <a class="btn-page-tips" href="javascript:;" onclick="getPageTips(1)"><i data-feather="alert-circle"></i> @lang('app.menu.pageTips')</a>
                    </div>
                </div>
                @endif

                <div class="
            @if (!empty($__env->yieldContent('filter-section')) || !empty($__env->yieldContent('other-section')))
                        col-md-9
@else
                        col-md-12
@endif
                        data-section">
                    <button class="btn btn-default btn-xs btn-outline btn-circle m-t-5 filter-section-show hidden-sm hidden-md" style="display:none"><i class="fa fa-chevron-right"></i></button>
                    @if (!empty($__env->yieldContent('filter-section')) || !empty($__env->yieldContent('other-section')))
                        <div class="row hidden-md hidden-lg">
                            <div class="col-xs-12 p-l-25 m-t-10">
                                <button class="btn btn-inverse btn-outline" id="mobile-filter-toggle"><i class="fa fa-sliders"></i></button>
                            </div>
                        </div>
                    @endif

                   <!--  @yield('page-title') -->

                <!-- .row -->
                    @yield('content')

                    {{--@include('sections.right_sidebar')--}}

                </div>

                @if($toasterAds->status == 1)
                    <div class="toasterAds">
                        <div class="alert dark alert-dismissible fade show" role="alert">
                            <a href="{{ route('admin.billing') }}"><img src="{{ $toasterAds->image_url ?? '' }}" alt=""/></a>
                            <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                    </div>
                @endif
        </div>
       
    </div>
    <!-- Page Body Ends-->
</div>
<!-- page-wrapper Ends-->
  </div>  </div>  </div>  </div>

<!-- footer start-->
<footer class="footer-new">
<div class="container">

@if($footerAds->status == 1)
<?php $footerData = json_decode($footerAds->content);?>
<?php //$count=count(collect($footerData)); echo $count;?>
    <div class="row">
        <div class="col-lg-4">
            {!! $footerData->col1  !!}
        </div>
    
        <div class="col-lg-2">
         <?php //$count=count(collect($footerData->col2)); echo $count;?>
            {!! $footerData->col2  !!}
        </div>
        <div class="col-lg-2">
            {!! $footerData->col3  !!}
        </div>
        <div class="col-lg-2">
            {!! $footerData->col4  !!}
        </div>
        <div class="col-lg-2">
           {!! $footerData->col5  !!}
            <!--<a href="#"><img src="{{ asset('img/appstore.png') }}" alt=""></a>
            <a href="#"><img src="{{ asset('img/playsore.png') }}" alt=""></a>-->
        </div>
    </div>
    @endif
            <div class="row">
                <div class="col-md-6 footer-copyright">
                    <p class="mb-0 ml-10"></p>
                </div>
                <div class="col-md-6">
                    <p class="pull-right mb-0">Copyright 2021 © WORKCEO All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>


<!-- /#wrapper -->


{{--Footer sticky notes--}}
<div id="footer-sticky-notes" class="row hidden-xs hidden-sm" style="display: none">
    <div  id="sticky-note-header">
        <div class="row">
            <div class="col-10">
                <h5 class="modal-title font-weight-bold">NOTES <span class="text-gray-dark">{{count($stickyNotes)}}</span></h5>
            </div>
            <div class="col-2 text-right"><a class="right_side_toggle" href="javascript:void(0);"><i class="fa fa-times fa-lg"></i></a></div>
            <div class="col-12 m-t-10">
                <a href="javascript:;" onclick="showCreateNoteModal()" class="btn btn-outline-primary btn-block">@lang("modules.sticky.addNote")</a>
            </div>
        </div>
    </div>
    <div id="sticky-note-list">

        @foreach($stickyNotes as $note)
            <div class="row sticky-note" id="stickyBox_{{$note->id}}">
                <div class="col-9 sticky-date">
                    <span class="badge badge-{{$note->colour}} b-none"> {{ $note->updated_at->diffForHumans() }}</span>
                </div>
                <div class="col-3">    <a href="javascript:;"  onclick="showEditNoteModal({{$note->id}})"><i class="fas fa-edit text-gray-dark"></i></a>
                    <a href="javascript:;" class="m-l-5" onclick="deleteSticky({{$note->id}})" ><i class="fa fa-times text-gray-dark"></i></a>
                </div>
                <div class="col-12 m-t-10"><div class="sticky-note-desc">{!! nl2br($note->note_text)  !!}</div></div>

            </div>
        @endforeach

    </div>
</div>
<a href="javascript:;" id="sticky-note-toggle"><i class="icon-note"></i></a>

{{--sticky note end--}}

{{--Timer Modal--}}
<div class="modal fade bs-modal-md in" id="projectTimerModal" role="dialog" aria-labelledby="myModalLabel"
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>           
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
{{--Timer Modal Ends--}}

{{--sticky note modal--}}
<div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noteModelHeading"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{--sticky note modal ends--}}
{{--Timer Modal--}}
<div class="modal fade bs-modal-md in" id="projectTimerModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    <!-- /.modal-dialog -->
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





<!-- jQuery -->
{{--<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>--}}


<!-- Bootstrap Core JavaScript -->
{{--<script src="{{ asset('bootstrap/dist/js/bootstrap.min.js') }}"></script>--}}
{{--<script src='//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js'></script>--}}

<!-- Sidebar menu plugin JavaScript -->
{{--<script src="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>--}}

<!-- latest jquery-->
{{--<script src="{{ asset('themes/wceo/assets/js/jquery-3.2.1.min.js')}}"></script>--}}
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
{{--<script src="{{ asset('themes/wceo/assets/js/theme-customizer/customizer.js')}}"></script>--}}

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
{{--<script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>--}}
{{--<script src="{{ asset('js/jquery.magnific-popup-init.js') }}"></script>--}}

<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>


<script>
$(document).ready(function(){
  $('[data-toggle="tooltip').on('click', function () {
    $('[data-toggle="tooltip').tooltip('hide');
  });
});
</script>

<script>
    $(document).ready(function(){
        //$.fn.datepicker.defaults.language = 'it';
        $("select").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        $('.hide-search').select2({
            minimumResultsForSearch: -1
        });

        $(".db_date_range_dd").select2('destroy');

        
    });

    $('.notificationSlimScroll').slimScroll({
        height: '250',
        position: 'right',
        color: '#dcdcdc'
    });
    $('#right_side_bar').slimScroll({
        height: 'calc(100% - 65px)'
    });


    $('body').on('click', '.timer-modal', function(){
        var url = '{{ route('admin.all-time-logs.show-active-timer')}}';
        $('#modelHeading').html('Active Timer');
        $.ajaxModal('#projectTimerModal',url);
    });

    $('.datepicker, #start-date, #end-date').on('click', function(e) {
        e.preventDefault();
        $(this).attr("autocomplete", "off");
    });

    function addOrEditStickyNote(id)
    {
        var url = '';
        var method = 'POST';
        if(id === undefined || id == "" || id == null) {
            url =  '{{ route('admin.sticky-note.store') }}'
        } else{

            url = "{{ route('admin.sticky-note.update',':id') }}";
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
        var url = '{{ route('admin.sticky-note.create') }}';
        $('#noteModelHeading').text('Add New Note');
        $("#responsive-modal").modal();
        $("#responsive-modal").on('shown.bs.modal', function () {
            $(this).find(".modal-content").load(url);
            $(this).off('shown.bs.modal');
        });
        // Trigger to do stuff with form loaded in modal
        $(document).trigger("ajaxPageLoad");

        // Call onload method if it was passed in function call
        if (typeof onLoad != "undefined") {
            onLoad();
        }

        $('#responsive-modal').on('hidden.bs.modal', function () {
            $(this).find('.modal-body').html('Loading...');
            $(this).data('bs.modal', null);
        });

        return false;
    }


    $('body').on('click', '[data-toggle="modal"]', function(){
        $($(this).data("target")+' .modal-body').load($(this).data("remote"));
    });



    // FOR SHOWING FEEDBACK DETAIL IN MODEL
    function showEditNoteModal(id){
        var url = '{{ route('admin.sticky-note.edit',':id') }}';
        url  = url.replace(':id',id);
        $('#noteModelHeading').text('Edit Note');
        /*$("#responsive-modal").removeData('bs.modal').modal({
            remote: url,
            show: true
        });*/

        $("#responsive-modal").modal();
        $("#responsive-modal").on('shown.bs.modal', function () {
            $(this).find(".modal-content").load(url);
            $(this).off('shown.bs.modal');
        });
        // Trigger to do stuff with form loaded in modal
        $(document).trigger("ajaxPageLoad");

        // Call onload method if it was passed in function call
        if (typeof onLoad != "undefined") {
            onLoad();
        }

        $('#responsive-modal').on('hidden.bs.modal', function () {
            $(this).find('.modal-body').html('Loading...');
            $(this).data('bs.modal', null);
        });

        return false;
    }

    function selectColor(id){
        //$('.icolors li.active ').removeClass('active');
        $('.icolors li').html('');
        $('#'+id).html('<i class="fas fa-check"></i>');
        $('#stickyColor').val(id);

    }

    
    function deleteSticky(id){

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted Sticky Note!",

            icon: "warning",
            buttons: ["No, cancel please!", "Yes, delete it!"],
            dangerMode: true
        })
            .then((willDelete) => {
                if (willDelete) {

                var url = "{{ route('admin.sticky-note.destroy',':id') }}";
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

        var url = "{{ route('admin.sticky-note.index') }}";

        $.easyAjax({
            type: 'GET',
            url: url,
            messagePosition: '',
            data:  {},
            container: ".noteBox",
            error: function (response) {

                //set notes in box
                $('#right-sidebar-content').html(response.responseText);
            }
        });
    }

    function getJobActivityData(){
        var url = "{{ route('admin.projects.activities') }}";

        $.easyAjax({
            type: 'GET',
            url: url,
            messagePosition: '',
            data:  {},
            container: ".noteBox",
            error: function (response) {

                //set notes in box
                $('#right-sidebar-content').html(response.responseText);
            }
        });
    }

    function getPageTipArticles(id){
        var url = "{{ route('admin.pagetip.show',':id') }}";
            url = url.replace(':id', id);

        $.easyAjax({
            type: 'GET',
            url: url,
            messagePosition: '',
            data:  {},
            container: ".noteBox",
            error: function (response) {

                //set notes in box
                $('#right-sidebar-content').html(response.responseText);
            }
        });
    }

    function getPageTipArticleDetails(id)
    {
        var url = "{{ route('admin.pagetip.edit',':id') }}";
            url = url.replace(':id', id);

        $.easyAjax({
            type: 'GET',
            url: url,
            messagePosition: '',
            data:  {},
            container: ".noteBox",
            error: function (response) {

                //set notes in box
                $('#right-sidebar-content').html(response.responseText);
            }
        });
    }

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
    $('.mark-notification-read').click(function () {
        console.log('hello from read notification');
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            type: 'POST',
            url: '{{ route("mark-notification-read") }}',
            data: {'_token': token},
            success: function (data) {
                if (data.status == 'success') {
                    $('.notificationList').remove();
                    $('.top-notification-count').html('0');
                    var notifications = '{{ route('admin.notices.index') }}';
                    window.location.href = notifications;
                    //$('#top-notification-dropdown .notify').remove();
                }
            }
        });

    });

    $('.show-all-notifications').click(function () {
        var url = '{{ route('show-all-member-notifications')}}';
        $('#modelHeading').html('View Unread Notifications');
        $.ajaxModal('#projectTimerModal', url);
    });

    $('.submit-search').click(function () {
        $(this).parent().submit();
    });

    $(function () {
        // $('.selectpicker').selectpicker();
    });

    $('.language-switcher').change(function () {
        var lang = $(this).val();
        $.easyAjax({
            url: '{{ route("admin.settings.change-language") }}',
            data: {'lang': lang},
            success: function (data) {
                if (data.status == 'success') {
                    window.location.reload();
                }
            }
        });
    });

    //    sticky notes script
    var stickyNoteOpen = $('#open-sticky-bar');
    var stickyNoteClose = $('#close-sticky-bar');
    var stickyNotes = $('#footer-sticky-notes');
    var viewportHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
    var stickyNoteHeaderHeight = stickyNotes.height();

    

    // $('#sticky-note-list').css('max-height', viewportHeight-150);

   /* stickyNoteOpen.click(function () {
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
    })*/



    $('body').on('click', '.right-side-toggle', function () {
        $(".right-sidebar").slideDown(50).removeClass("shw-rside");
    });
    $('body').on('click', '#sticky-note-top', function () {
        $(".right-sidebar").addClass("right-sidebar-width-auto");
        $(".right-sidebar").slideDown(50).addClass("show shw-rside");
        //
        $('#right-sidebar-content').html($('#footer-sticky-notes').html());
    });
    $('body').on('click', '#job-activity-top', function () {
        $(".right-sidebar").addClass("right-sidebar-width-auto");
        $(".right-sidebar").slideDown(50).addClass("show shw-rside");

        getJobActivityData();
        //$('#right-sidebar-content').html('hii');
    });

    function getPageTips(id)
    {
        $(".right-sidebar").addClass("right-sidebar-page-tips");
        $(".right-sidebar").slideDown(50).addClass("show shw-rside");
        getPageTipArticles(id);
    }

    function showArticleDetails(id)
    {
        $(".right-sidebar").addClass("right-sidebar-page-tips");
        $(".right-sidebar").slideDown(50).addClass("show shw-rside");
        getPageTipArticleDetails(id);
    }


    $(document).on('click',".right_side_toggle", function(){
        $(".right-sidebar").removeClass("right-sidebar-width-auto");
        $(".right-sidebar").slideUp(50).removeClass("show shw-rside");
        // $('#right_side_bar').toggleClass('show shw-rside');
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

    $('.table-responsive').on('show.bs.dropdown', function () {
        $('.table-responsive').css( "overflow", "inherit" );
    });

    $('.table-responsive').on('hide.bs.dropdown', function () {
        $('.table-responsive').css( "overflow", "auto" );
    })

    $('#mobile-filter-toggle').click(function () {
        $('.filter-section').toggle();
    })

    $('#sticky-note-toggle').click(function () {
        $('#footer-sticky-notes').toggle();
        // $('#sticky-note-toggle').hide();
    })

    $(document).ready(function () {
        //Side menu active hack
        /*setTimeout(function(){
            var getActiveMenu = $('#side-menu  li.active li a.active').length;
            // console.log(getActiveMenu);
            if(getActiveMenu > 0) {
                $('#side-menu  li.active li a.active').parent().parent().parent().find('a:first').addClass('active');
            }

        }, 200);*/

    })

    $('body').on('click', '.toggle-password', function() {
        var $selector = $(this).parent().find('input.form-control');
        $(this).toggleClass("fa-eye fa-eye-slash");
        var $type = $selector.attr("type") === "password" ? "text" : "password";
        $selector.attr("type", $type);
    });

    var currentUrl = '{{ request()->route()->getName() }}';
    $('body').on('click', '.filter-section-close', function() {
        localStorage.setItem('filter-'+currentUrl, 'hide');

        $('.filter-section').toggle();
        $('.filter-section-show').toggle();
        $('.data-section').toggleClass("col-md-9 col-md-12")
    });

    $('body').on('click', '.filter-section-show', function() {
        localStorage.setItem('filter-'+currentUrl, 'show');

        $('.filter-section-show').toggle();
        $('.data-section').toggleClass("col-md-9 col-md-12")
        $('.filter-section').toggle();
    });

    var currentUrl = '{{ request()->route()->getName() }}';
    var checkCurrentUrl = localStorage.getItem('filter-'+currentUrl);
    if (checkCurrentUrl == "hide") {
        $('.filter-section-show').show();
        $('.data-section').removeClass("col-md-9")
        $('.data-section').addClass("col-md-12")
        $('.filter-section').hide();
    } else if (checkCurrentUrl == "show") {
        $('.filter-section-show').hide();
        $('.data-section').removeClass("col-md-12")
        $('.data-section').addClass("col-md-9")
        $('.filter-section').show();
    }
</script>

<!--Ads Scripts-->
@if($topAds->status == 1)
<script>
    $(window).scroll(function() { 

        /* var margin =  window.scrollY;
        if (margin <= 39) {
            $(".page-sidebar").css("margin-top", '18px');
            
        } else{
            var mtop = margin - 39;
            $(".page-sidebar").css("margin-top",mtop); 
        }
console.log(margin); */
        var scroll = $(window).scrollTop();
        if (scroll >= 18) {
            $(".page-sidebar").removeClass("leftBar");        
        } else {
            $(".page-sidebar").addClass("leftBar");
        }
    });
</script>
@endif

@if($popupAds->status == 1 && !empty($global->company_phone))
<script>  
if(localStorage.getItem('adsPopState') != 'shown')
{
    var duration = '{{$popupAds->duration}}';
    duration = duration*1000;
    $(window).on('load', function() {
        $('#adsModal').modal('show');
        setTimeout(function() {
            $("#adsModal").modal('hide');
        }, duration);
    });
    localStorage.setItem('adsPopState','shown')
}

</script>
@endif



@if($toasterAds->status == 1)
<script>    
$(document).ready(function() {
    if(localStorage.getItem('adsToasterState') != 'shown')
    {
        $(".toasterAds").show();
        var toasterDuration = '{{$toasterAds->duration}}';
            toasterDuration = toasterDuration*1000;
                setTimeout(function() {
                    $(".toasterAds").hide();
            }, toasterDuration);
        localStorage.setItem('adsToasterState','shown')
    }
});
</script>
@endif

@stack('footer-script')
<style type="text/css">
.field-icon {
  float: right;
  margin-right: 6px;
  margin-top: -20px;
  position: relative;
  z-index: 1;
  cursor:pointer;
}

</style>

<script>
$('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true,
    weekStart:'{{ $global->week_start }}',
    dateFormat: '{{ $global->date_picker_format }}',
    language: 'en'
});

</script>

<script>
$('body').on('click', '.noticeShow', function () {
    var noticeId = $(this).data('notice-id');
    var url = "{{ route('admin.notices.show',':id') }}";
    url = url.replace(':id', noticeId);
    $('#modelHeading').html('Notice');
    $.ajaxModal('#noticeDetailModal', url);
});
</script>

</body>
</html>

