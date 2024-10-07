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

    <title> @lang('app.superAdminPanel') | {{ __($pageTitle) }}</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/fontawesome.css')}}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/icofont.css')}}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/themify.css')}}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/flag-icon.css')}}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/feather-icon.css')}}">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/chartist.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/prism.css')}}">
    <link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap.css')}}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dev_style.css')}}">
    <link id="color" rel="stylesheet" href="{{ asset('themes/wceo/assets/css/light-1.css')}}" media="screen">
    <!-- Responsive css-->
    @php
    $time = date("H:i:s", time()); 
    @endphp
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/responsive.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/superadmin-responsive.css')}}?ver={{$time}}">

    <link href="{{ asset('plugins/froiden-helper/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}" rel="stylesheet">


    @stack('head-script')

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

                                if((db_onesignal_id == null || db_onesignal_id !== userId) && userId !== null){ //update onesignal ID if it is new
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
                            // OneSignal.showHttpPrompt();
                            // OneSignal.registerForPushNotifications({
                            //         modalPrompt: true
                            // });
                        }

                        OneSignal.getUserId(function(userId) {
                            console.log("OneSignal User ID:", userId);
                            // (Output) OneSignal User ID: 270a35cd-4dda-4b3f-b04e-41d7463a2316
                            let db_onesignal_id = '{{ $user->onesignal_player_id }}';
                            console.log('database id : '+db_onesignal_id);

                            if((db_onesignal_id == null || db_onesignal_id !== userId) && userId !== null){ //update onesignal ID if it is new
                                updateOnesignalPlayerId(userId);
                            }


                        });


                        OneSignal.showHttpPrompt();
                    });

                }
            });
        </script>
    @endif

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
<div class="page-wrapper compact-wrapper  wceo-wrapper">
    <!-- Page Header Start-->
    <div class="page-main-header">
        <div class="main-header-right row">
            <div class="main-header-left d-lg-none">
                <div class="logo-wrapper">

                    <a href="{{ route('super-admin.dashboard') }}">
                        <img src="{{ $global->logo_url }}" alt="home" class=" admin-logo"/>
                    </a>

                </div>
            </div>
            <div class="mobile-sidebar d-block">
                <div class="media-body text-right switch-sm">
                    <label class="switch"><a href="#"><i id="sidebar-toggle" data-feather="align-left"></i></a></label>
                </div>
            </div>
            <div class="nav-right col p-0">
                <ul class="nav-menus">
                    <li>
                        <h3>WORKCEO</h3>
                        {{--<form class="form-inline search-form" action="#" method="get">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="Typeahead Typeahead--twitterUsers">--}}
                                    {{--<div class="u-posRelative">--}}
                                        {{--<input class="Typeahead-input form-control-plaintext" id="demo-input" type="text" name="q" placeholder="Search...">--}}
                                        {{--<div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div><span class="d-sm-none mobile-search"><i data-feather="search"></i></span>--}}
                                    {{--</div>--}}
                                    {{--<div class="Typeahead-menu"></div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</form>--}}
                    </li>
                    <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>

                    <li class="onhover-dropdown"><i data-feather="bell"></i>
                        @if(count($user->unreadNotifications) > 0)
                            <span class="dot"></span>
                        @endif

                        <ul class="notification-dropdown onhover-show-div">
                            <li>@lang('app.newNotifications') <span class="badge badge-pill badge-primary pull-right">{{ count($user->unreadNotifications) }}</span></li>
                            @foreach ($user->unreadNotifications as $notification)
                                @if(view()->exists('notifications.superadmin.'.\Illuminate\Support\Str::snake(class_basename($notification->type))))
                                    @include('notifications.superadmin.'.\Illuminate\Support\Str::snake(class_basename($notification->type)))
                                @endif
                            @endforeach

                            @if(count($user->unreadNotifications) > 0)
                                <li class="bg-light txt-dark">
                                    <a class="text-center mark-notification-read"
                                       href="javascript:;"> @lang('app.markRead') <i class="fa fa-check"></i> </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li class="onhover-dropdown">
                        <div class="media align-items-center">
                            <img class="align-self-center pull-right img-50 rounded-circle" src="{{ $user->image_url }}" alt="{{ ucwords($user->name) }}">
                            <div class="dotted-animation"><span class="animate-circle"></span><span class="main-circle"></span></div>
                        </div>
                        <ul class="profile-dropdown onhover-show-div p-20">
                            <li><b>{{ ucwords($user->name) }}</b></li>
                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();"
                                    ><i data-feather="log-out"></i> @lang('app.logout')</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form></li>
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
    <!-- Page Header Ends                              -->

    <!-- Page Body Start-->
    <div class="page-body-wrapper sidebar-icon">
        <!-- Page Sidebar Start-->
        <div class="page-sidebar">
            <div class="main-header-left d-none d-lg-block">
                <div class="logo-wrapper">
                    <a href="{{ route('super-admin.dashboard') }}">
                        <img width="70px" src="{{ $global->logo_url }}" alt="home" class=" admin-logo"/>
                    </a>
                </div>
            </div>
            <!-- Left navbar-header -->
            @include('sections.super_admin_left_sidebar')
            <!-- Left navbar-header end -->

        </div>
        <!-- Page Sidebar Ends-->
        <!-- Right sidebar Start-->
        <div class="right-sidebar" id="right_side_bar">
            <div class="container p-0">
                <div class="modal-header p-l-20 p-r-20">
                    <div class="col-sm-8 p-0">
                        <h6 class="modal-title font-weight-bold">FRIEND LIST</h6>
                    </div>
                    <div class="col-sm-4 text-right p-0"><i class="mr-2" data-feather="settings"></i></div>
                </div>
            </div>
            <div class="friend-list-search mt-0">
                <input type="text" placeholder="search friend"><i class="fa fa-search"></i>
            </div>
            <div class="chat-box">
                <div class="people-list friend-list">
                    <ul class="list">
                        <li class="clearfix"><img class="rounded-circle user-image" src="./../assets/images/user/1.jpg" alt="">
                            <div class="status-circle online"></div>
                            <div class="about">
                                <div class="name">Vincent Porter</div>
                                <div class="status"> Online</div>
                            </div>
                        </li>
                        <li class="clearfix"><img class="rounded-circle user-image" src="./../assets/images/user/2.png" alt="">
                            <div class="status-circle away"></div>
                            <div class="about">
                                <div class="name">Ain Chavez</div>
                                <div class="status"> 28 minutes ago</div>
                            </div>
                        </li>
                        <li class="clearfix"><img class="rounded-circle user-image" src="./../assets/images/user/8.jpg" alt="">
                            <div class="status-circle online"></div>
                            <div class="about">
                                <div class="name">Kori Thomas</div>
                                <div class="status"> Online</div>
                            </div>
                        </li>
                        <li class="clearfix"><img class="rounded-circle user-image" src="./../assets/images/user/4.jpg" alt="">
                            <div class="status-circle online"></div>
                            <div class="about">
                                <div class="name">Erica Hughes</div>
                                <div class="status"> Online</div>
                            </div>
                        </li>
                        <li class="clearfix"><img class="rounded-circle user-image" src="./../assets/images/user/5.jpg" alt="">
                            <div class="status-circle offline"></div>
                            <div class="about">
                                <div class="name">Ginger Johnston</div>
                                <div class="status"> 2 minutes ago</div>
                            </div>
                        </li>
                        <li class="clearfix"><img class="rounded-circle user-image" src="./../assets/images/user/6.jpg" alt="">
                            <div class="status-circle away"></div>
                            <div class="about">
                                <div class="name">Prasanth Anand</div>
                                <div class="status"> 2 hour ago</div>
                            </div>
                        </li>
                        <li class="clearfix"><img class="rounded-circle user-image" src="./../assets/images/user/7.jpg" alt="">
                            <div class="status-circle online"></div>
                            <div class="about">
                                <div class="name">Hileri Jecno</div>
                                <div class="status"> Online</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Right sidebar Ends-->
        <div class="page-body">
            @if (!empty($__env->yieldContent('other-section')))
                <div class="row">

                    @endif


            @yield('page-title')


            @if (!empty($__env->yieldContent('filter-section')))
                <div class="col-md-3 filter-section">
                    <h5><i class="fa fa-sliders"></i> @lang('app.filterResults')</h5>
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


            <div class="
            {{--@if (!empty($__env->yieldContent('filter-section')) || !empty($__env->yieldContent('other-section')))--}}
            @if (!empty($__env->yieldContent('other-section')))
                    col-md-9
@else
                    col-md-12
@endif
                    data-section">

                <button class="btn btn-default btn-xs btn-circle btn-outline m-t-5 filter-section-show hidden-sm hidden-md" style="display:none"><i class="fa fa-chevron-right"></i></button>
                @if (!empty($__env->yieldContent('filter-section')) || !empty($__env->yieldContent('other-section')))
                    <div class="row hidden-md hidden-lg">
                        <div class="col-xs-12 p-l-25 m-t-10">
                            <button class="btn btn-inverse btn-outline" id="mobile-filter-toggle"><i class="fa fa-sliders"></i></button>
                        </div>
                    </div>
                @endif



            <!-- .row -->
                @yield('content')

                {{--@include('sections.right_sidebar')--}}
        </div>
        @if (!empty($__env->yieldContent('other-section')))
           </div>
            @endif
        </div>
    </div>

    <!-- footer start-->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 footer-copyright">
                    <p class="mb-0 ml-10"></p>
                </div>
                <div class="col-md-6">
                    <p class="pull-right mb-0">Copyright 2020 © WORKCEO All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    </div>



</div>
<!-- /#wrapper -->

{{--sticky note modal--}}
<div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            Loading ...
        </div>
    </div>
</div>

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
{{--sticky note modal ends--}}


<!-- latest jquery-->
<script src="{{ asset('themes/wceo/assets/js/jquery-3.2.1.min.js')}}"></script>
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
<script src="{{ asset('themes/wceo/assets/js/chart/chartist/chartist.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/knob/knob.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/knob/knob-chart.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/prism/prism.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/clipboard/clipboard.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/counter/jquery.waypoints.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/counter/jquery.counterup.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/counter/counter-custom.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/custom-card/custom-card.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/dashboard/default.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/notify/index.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/typeahead/handlebars.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/typeahead/typeahead.bundle.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/typeahead/typeahead.custom.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/chat-menu.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/height-equal.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/tooltip-init.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/typeahead-search/handlebars.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/typeahead-search/typeahead-custom.js')}}"></script>
<!-- Plugins JS Ends-->
<!--Slimscroll JavaScript For custom scroll-->
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<!-- Theme js-->
<script src="{{ asset('themes/wceo/assets/js/script.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/theme-customizer/customizer.js')}}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
<script src="{{ asset('plugins/froiden-helper/helper.js') }}"></script>
<script src="{{ asset('plugins/bower_components/toast-master/js/jquery.toast.js') }}"></script>

{{--sticky note script--}}
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bower_components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/icheck/icheck.init.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup-init.js') }}"></script>

<script src="{{ asset('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>


<!-- Plugin used-->
<script>
    $('.mark-notification-read').click(function () {
        console.log('hello from read notification');
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            type: 'POST',
            url: '{{ route("mark-superadmin-notification-read") }}',
            data: {'_token': token},
            success: function (data) {
                if (data.status == 'success') {
                    $('.top-notifications').remove();
                    $('.top-notification-count').html('0');
                    $('#top-notification-dropdown .notify').remove();
                }
            }
        });

    });

    $('.show-all-notifications').click(function () {
        var url = '{{ route('show-all-super-admin-notifications')}}';
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

    $('body').on('click', '.right-side-toggle', function () {
        $(".right-sidebar").slideDown(50).removeClass("shw-rside");
    })


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
        $('#sticky-note-toggle').hide();
    })

    $(document).ready(function () {
        //Side menu active hack
        setTimeout(function(){
            var getActiveMenu = $('#side-menu  li.active li a.active').length;
             console.log(getActiveMenu);
            if(getActiveMenu > 0) {
                $('#side-menu  li.active li a.active').parent().parent().parent().find('a:first').addClass('active');
            }

        }, 200);

    })

    $('body').on('click', '.toggle-password', function() {
        var $selector = $(this).parent().find('input');
        $(this).toggleClass("fa-eye fa-eye-slash");
        var $type = $selector.attr("type") === "password" ? "text" : "password";
        $selector.attr("type", $type);
    });
    var currentUrl = '{{ request()->route()->getName() }}';
    $('body').on('click', '.filter-section-close', function() {
        localStorage.setItem('filter-'+currentUrl, 'hide');

        $('.filter-section').toggle();
        $('.filter-section-show').toggle();
        // $('.data-section').toggleClass("col-md-9 col-md-12")
    });

    $('body').on('click', '.filter-section-show', function() {
        localStorage.setItem('filter-'+currentUrl, 'show');

        $('.filter-section-show').toggle();
        // $('.data-section').toggleClass("col-md-9 col-md-12")
        $('.filter-section').toggle();
    });

    var currentUrl = '{{ request()->route()->getName() }}';
    var checkCurrentUrl = localStorage.getItem('filter-'+currentUrl);
    if (checkCurrentUrl == "hide") {
        $('.filter-section-show').show();
        // $('.data-section').removeClass("col-md-9")
        $('.data-section').addClass("col-md-12")
        $('.filter-section').hide();
    } else if (checkCurrentUrl == "show") {
        $('.filter-section-show').hide();
        $('.data-section').removeClass("col-md-12")
        // $('.data-section').addClass("col-md-9")
        $('.filter-section').show();
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
@stack('footer-script')

</body>
</html>
