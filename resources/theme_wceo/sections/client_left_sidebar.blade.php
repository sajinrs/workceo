
<div class="sidebar custom-scrollbar slimscroll">

    <ul class="sidebar-menu" id="side-menu">


        <li class="user-pro hidden-md  hidden-sm  hidden-lg">
            @if(is_null($user->image))
                <a href="#" class="sidebar-header"><img src="{{ asset('img/default-profile-3.png') }}" alt="user-img" class="img-circle"> <span class="hide-menu">{{ (strlen($user->name) > 24) ? substr(ucwords($user->name), 0, 20).'..' : ucwords($user->name) }}
                        <span class="fa arrow"></span></span>
                </a>
            @else
                <a href="#" class="sidebar-header"><img src="{{ asset_url('avatar/'.$user->image) }}" alt="user-img" class="img-circle"> <span class="hide-menu">{{ ucwords($user->name) }}
                        <span class="fa arrow"></span></span>
                </a>
            @endif
            <ul class="sidebar-submenu">
                <li><a href="{{ route('client.profile.index') }}"><i class="ti-user"></i> @lang("app.menu.profileSettings")</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();"
                    ><i class="fa fa-power-off"></i> @lang('app.logout')</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </li>


        <li><a href="{{ route('client.dashboard.index') }}" class="sidebar-header"><i class="icofont icofont-home"></i> <span class="hide-menu">@lang('app.menu.dashboard') </span></a> </li>

        @if(in_array('projects',$modules))
            <li><a href="{{ route('client.projects.index') }}" class="sidebar-header"><i class="fas fa-list-alt"></i> <span class="hide-menu">@lang('app.menu.jobs') </span> @if($unreadProjectCount > 0) <div class="notify notification-color"><span class="heartbit"></span><span class="point"></span></div>@endif</a> </li>
        @endif
        @if(in_array('products',$modules))
            {{--<li><a href="{{ route('client.products.index') }}" class="sidebar-header"><i class="icon-layers"></i> <span class="hide-menu">@lang('app.menu.products') </span> @if($unreadProjectCount > 0) <div class="notify notification-color"><span class="heartbit"></span><span class="point"></span></div>@endif</a> </li>--}}
        @endif

        @if(in_array('tickets',$modules))
            {{--<li><a href="{{ route('client.tickets.index') }}" class="sidebar-header"><i class="ti-ticket"></i> <span class="hide-menu">@lang("app.menu.tickets") </span></a> </li>--}}
        @endif

        @if(in_array('invoices',$modules))
            <li><a href="{{ route('client.invoices.index') }}" class="sidebar-header"><i class="fas fa-money-bill-wave-alt"></i> <span class="hide-menu">@lang('app.menu.invoices') </span> @if($unreadInvoiceCount > 0) <div class="notify notification-color"><span class="heartbit"></span><span class="point"></span></div>@endif</a> </li>
        @endif

        @if(in_array('estimates',$modules))
            {{--<li><a href="{{ route('client.estimates.index') }}" class="sidebar-header"><i class="icon-doc"></i> <span class="hide-menu">@lang('app.menu.estimates') </span> @if($unreadEstimateCount > 0) <div class="notify notification-color"><span class="heartbit"></span><span class="point"></span></div>@endif</a> </li>--}}
        @endif

        @if(in_array('payments',$modules))
            <li><a href="{{ route('client.payments.index') }}" class="sidebar-header"><i class="fas fa-money-bill-wave-alt"></i> <span class="hide-menu">@lang('app.menu.payments') </span> @if($unreadPaymentCount > 0) <div class="notify notification-color"><span class="heartbit"></span><span class="point"></span></div>@endif</a> </li>
        @endif

        @if(in_array('events',$modules))
            <li><a href="{{ route('client.events.index') }}" class="sidebar-header"><i class="fas fa-calendar-check"></i> <span class="hide-menu">@lang('app.menu.Schedules')</span></a> </li>
        @endif

        @if(in_array('contracts',$modules))
            <li><a href="{{ route('client.contracts.index') }}" class="sidebar-header"><i class="fa fa-file"></i> <span class="hide-menu">@lang('app.menu.contracts')</span></a> </li>
        @endif

        @if($gdpr->enable_gdpr)
            <li><a href="{{ route('client.gdpr.index') }}" class="sidebar-header"><i class="icon-lock"></i> <span class="hide-menu">@lang('app.menu.gdpr')</span></a> </li>
        @endif

        @if(in_array('notices',$modules))
            {{--<li><a href="{{ route('client.notices.index') }}" class="sidebar-header"><i class="ti-layout-media-overlay"></i> <span class="hide-menu">@lang("app.menu.noticeBoard") </span></a> </li>--}}
        @endif

        @if(in_array('messages',$modules))
            @if($messageSetting->allow_client_admin == 'yes' || $messageSetting->allow_client_employee == 'yes')
                <li><a href="{{ route('client.user-chat.index') }}" class="sidebar-header"><i class="icon-envelope"></i> <span class="hide-menu">@lang('app.menu.messages') @if($unreadMessageCount > 0)<span class="label label-rouded label-custom pull-right">{{ $unreadMessageCount }}</span> @endif</span></a> </li>
            @endif
        @endif

    </ul>

</div>


