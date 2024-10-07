<div class="sidebar custom-scrollbar slimscroll">

    <!-- .User Profile -->

    <ul class="sidebar-menu" id="side-menu">
        {{--<li class="sidebar-search hidden-sm hidden-md hidden-lg">--}}
        {{--<!-- / Search input-group this is only view in mobile-->--}}
        {{--<div class="input-group custom-search-form">--}}
        {{--<input type="text" class="form-control" placeholder="Search...">--}}
        {{--<span class="input-group-btn">--}}
        {{--<button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>--}}
        {{--</span>--}}
        {{--</div>--}}
        {{--<!-- /input-group -->--}}
        {{--</li>--}}

        <li><a href="{{ route('member.dashboard') }}" class="sidebar-header"><i class="icofont icofont-home"></i> <span class="hide-menu">@lang("app.menu.dashboard") </span></a> </li>

        @if(in_array('clients',$modules))
            @if($user->can('view_clients'))
                <li><a href="{{ route('member.clients.index') }}" class="sidebar-header"><i class="icofont icofont-people"></i> <span class="hide-menu">@lang('app.menu.clients') </span></a> </li>
            @endif
        @endif

        @if(in_array('employees',$modules))
            @if($user->can('view_employees'))
                <li><a href="{{ route('member.employees.index') }}" class="sidebar-header"><i class="icon-user"></i> <span class="hide-menu">@lang('app.menu.employees') </span></a> </li>
            @endif
        @endif

        @if(in_array('projects',$modules))
            <li><a href="{{ route('member.projects.index') }}" class="sidebar-header"><i class="fas fa-list-alt"></i> <span class="hide-menu">@lang("app.menu.jobs") </span> @if($unreadProjectCount > 0) <div class="notify notification-color"><span class="heartbit"></span><span class="point"></span></div>@endif</a> </li>
        @endif

        @if(in_array('products',$modules) && $user->can('view_product'))
            <li><a href="{{ route('member.products.index') }}" class="sidebar-header"><i class="icon-basket"></i> <span class="hide-menu">@lang('app.menu.products') </span></a> </li>
        @endif

        @if(in_array('tasks',$modules))
            @if($user->can('view_tasks'))
            <li><a href="{{ route('member.task.index') }}" class="sidebar-header"><i class="ti-layout-list-thumb"></i> <span class="hide-menu"> @lang('app.menu.tasks') <span class="fa arrow"></span> </span></a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('member.all-tasks.index') }}">@lang('app.menu.tasks')</a></li>
                    <li class="hidden-sm hidden-xs"><a href="{{ route('member.taskboard.index') }}">@lang('modules.tasks.taskBoard')</a></li>
                    <li><a href="{{ route('member.task-calendar.index') }}">@lang('app.menu.taskCalendar')</a></li>
                </ul>
            </li>
            @endif
        @endif

        @if(in_array('leads',$modules))
            <li><a href="{{ route('member.leads.index') }}" class="sidebar-header"><i class="icofont icofont-people"></i> <span class="hide-menu">@lang('app.menu.lead') </span></a> </li>
        @endif

        @if(in_array('timelogs',$modules))
            <li><a href="{{ route('member.all-time-logs.index') }}" class="sidebar-header"><i class="fa fa-clock"></i> <span class="hide-menu">@lang('app.menu.timeLogs') </span></a> </li>
        @endif

        @if(in_array('attendance',$modules))
            @if($user->can('view_attendance'))
                <li><a href="{{ route('member.attendances.summary') }}" class="sidebar-header"><i class="fa fa-user-plus"></i> <span class="hide-menu">@lang("app.menu.attendance") </span></a> </li>
            @else
                <li><a href="{{ route('member.attendances.index') }}" class="sidebar-header"><i class="fa fa-user-plus"></i> <span class="hide-menu">@lang("app.menu.attendance") </span></a> </li>
            @endif
        @endif

        @if(in_array('holidays',$modules))
            @if($user->can('view_holidays'))
            <li><a href="{{ route('member.holidays.index') }}" class="sidebar-header"><i class="icon-calender"></i> <span class="hide-menu">@lang("app.menu.holiday") </span></a> </li>
            @endif
        @endif

        @if(in_array('tickets',$modules))
            @if($user->can('view_tickets'))
            <li><a href="{{ route('member.tickets.index') }}" class="sidebar-header"><i class="ti-ticket"></i> <span class="hide-menu">@lang("app.menu.tickets") </span></a> </li>
            @endif
        @endif

        @if((in_array('estimates',$modules) && $user->can('view_estimates'))
        || (in_array('invoices',$modules)  && $user->can('view_invoices'))
        || (in_array('payments',$modules) && $user->can('view_payments'))
        || (in_array('expenses',$modules)))

        @if($user->can('view_finance'))
            <li><a href="{{ route('member.finance.index') }}" class="sidebar-header"><i class="fa fa-money"></i> <span class="hide-menu"> @lang('app.menu.finance') @if($unreadExpenseCount > 0) <div class="notify notification-color"><span class="heartbit"></span><span class="point"></span></div>@endif <span class="fa arrow"></span> </span></a>
                <ul class="sidebar-submenu">
                    @if(in_array('estimates',$modules))
                        @if($user->can('view_estimates'))
                            <li><a href="{{ route('member.estimates.index') }}">@lang('app.menu.estimates')</a> </li>
                        @endif
                    @endif

                    @if(in_array('invoices',$modules))
                        @if($user->can('view_invoices'))
                            <li><a href="{{ route('member.all-invoices.index') }}">@lang('app.menu.invoices')</a> </li>
                        @endif
                    @endif

                    @if(in_array('payments',$modules))
                        @if($user->can('view_payments'))
                            <li><a href="{{ route('member.payments.index') }}">@lang('app.menu.payments')</a> </li>
                        @endif
                    @endif

                    @if(in_array('expenses',$modules))
                        <li><a href="{{ route('member.expenses.index') }}">@lang('app.menu.expenses') @if($unreadExpenseCount > 0) <div class="notify notification-color"><span class="heartbit"></span><span class="point"></span></div>@endif</a> </li>
                    @endif
                    @if(in_array('invoices',$modules))
                        @if($user->can('view_invoices'))
                            <li><a href="{{ route('member.all-credit-notes.index') }}">@lang('app.menu.credit-note') </a> </li>
                        @endif
                    @endif
                </ul>
            </li>
            @endif
        @endif

        @if(in_array('messages',$modules))
            <li><a href="{{ route('member.user-chat.index') }}" class="sidebar-header"><i class="icofont icofont-ui-text-loading"></i> @if($unreadMessageCount > 0) <span class="badge message-count">{{ $unreadMessageCount }}</span> @endif <span class="hide-menu">@lang("app.menu.messages") 
                    </span>
                </a>
            </li>
        @endif

        @if(in_array('events',$modules))
            <li><a href="{{ route('member.events.index') }}" class="sidebar-header"><i class="fas fa-calendar-check"></i> <span class="hide-menu">@lang('app.menu.Schedules')</span></a> </li>
        @endif

        @if(in_array('leaves',$modules))
            @if($user->can('view_leaves'))
            <li><a href="{{ route('member.leaves.index') }}" class="sidebar-header"><i class="icon-logout"></i> <span class="hide-menu">@lang('app.menu.leaves')</span></a> </li>
            @endif
        @endif

        @if(in_array('notices',$modules))
            <li><a href="{{ route('member.notices.index') }}" class="sidebar-header"><i class="fa fa-window-maximize"></i> <span class="hide-menu">@lang("app.menu.noticeBoard") </span></a> </li>
        @endif

        @foreach ($worksuitePlugins as $item)
            @if(in_array(strtolower($item), $modules))
                @if(View::exists(strtolower($item).'::sections.member_left_sidebar'))
                    @include(strtolower($item).'::sections.member_left_sidebar')
                @endif
            @endif
        @endforeach


    </ul>

</div>
