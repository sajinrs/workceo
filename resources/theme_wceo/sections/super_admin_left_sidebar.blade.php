<div class="sidebar custom-scrollbar slimscroll">
    <ul class="sidebar-menu" id="side-menu">
        <li><a href="{{ route('super-admin.dashboard') }}" class="sidebar-header"><i class="icofont icofont-home"></i> <span class="hide-menu">@lang('app.menu.dashboard') </span></a>
            {{--<img src="{{ asset('themes/wceo/assets/icons/dashboard.svg') }}">--}}
            {{--<ul class="sidebar-submenu">--}}
                {{--<li><a class="active" href="index.html"><i class="fa fa-circle"></i>Default</a></li>--}}
                {{--<li><a href="dashboard-ecommerce.html"><i class="fa fa-circle"></i>E-commerce</a></li>--}}
                {{--<li><a href="dashboard-university.html"><i class="fa fa-circle"></i>University</a></li>--}}
                {{--<li><a href="dashboard-bitcoin.html"><i class="fa fa-circle"></i>Crypto</a></li>--}}
                {{--<li><a href="dashboard-server.html"><i class="fa fa-circle"></i>Server</a></li>--}}
                {{--<li><a href="dashboard-project.html"><i class="fa fa-circle"></i>Project Dashboard</a></li>--}}
            {{--</ul>--}}
        </li>

        <li><a href="{{ route('super-admin.profile.index') }}" class="sidebar-header"><i class="fa fa-user-circle"></i> <span class="hide-menu">@lang('modules.employees.profile') </span></a></li>
        <li><a href="{{ route('super-admin.packages.index') }}" class="sidebar-header"><i class="fa fa-cubes"></i> <span class="hide-menu">@lang('app.menu.templates') </span></a> </li>

        <li><a href="{{ route('super-admin.companies.index') }}" class="sidebar-header"> <i class="fa fa-th-list"></i> <span class="hide-menu">@lang('app.menu.companies') </span></a> </li>
        <li><a href="{{ route('super-admin.invoices.index') }}" class="sidebar-header"><i class="fa fa-print"></i> <span class="hide-menu">@lang('app.menu.invoices') </span></a> </li>
        {{--<li><a href="{{ route('super-admin.faq-category.index') }}" class="sidebar-header"><i class="fa fa-question-circle"></i> <span class="hide-menu">@lang('app.menu.faq') </span></a> </li>--}}
        <li><a href="{{ route('super-admin.super-admin.index') }}" class="sidebar-header"> <i class="icofont icofont-people"></i>  <span class="hide-menu">@lang('app.superAdmin') </span></a> </li>
        {{--<li><a href="{{ route('super-admin.offline-plan.index') }}" class="sidebar-header"><i class="fa fa-user-secret"></i> <span class="hide-menu">@lang('app.offlineRequest') @if($offlineRequestCount > 0)<div class="notify notification-color"><span class="heartbit"></span><span class="point"></span></div>@endif</span> </a> </li>--}}
        <li><a href="{{ route('super-admin.faq-category.index') }}" class="sidebar-header"><i class="fa fa-trophy"></i> <span class="hide-menu">@lang('app.menu.workcoach') </span></a></li>
        <li><a href="{{ route('super-admin.ads-space.index') }}" class="sidebar-header"><i class="fa fa-bullhorn"></i> <span class="hide-menu">@lang('app.menu.adspace') </span></a></li>
        li><a href="{{ route('super-admin.onboarding.index') }}" class="sidebar-header"><i class="fa fa-graduation-cap"></i> <span class="hide-menu">@lang('app.menu.onBoarding') </span></a></li>
        <li><a href="{{ route('super-admin.settings.index') }}" class="sidebar-header"><i class="fa fa-gear"></i> <span class="hide-menu">@lang('app.menu.settings') </span></a>

        </li>
        <li><a href="{{ route('super-admin.theme-settings') }}" class="sidebar-header"><i class="fa fa-cogs"></i> <span class="hide-menu">@lang('app.front') @lang('app.menu.settings') </span></a>
        </li>
        <li><a href="{{ route('super-admin.pagetips.index') }}" class="sidebar-header"><i data-feather="alert-circle"></i> <span class="hide-menu">@lang('app.menu.pageTips') </span></a></li>
    </ul>
</div>






















