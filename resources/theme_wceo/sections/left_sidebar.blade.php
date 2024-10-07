@php $sidebarAds = sidebarAds(); @endphp
<div class="sidebar custom-scrollbar slimscroll">

        <!-- .User Profile -->
        <ul class="sidebar-menu" id="side-menu">

            <li><a href="{{ route('admin.dashboard') }}"  class="sidebar-header"><i data-feather="home"></i> <span class="hide-menu">@lang('app.menu.dashboard') </span></a> </li>

            @if(in_array("events", $modules))
                <li><a href="{{ route('admin.events.index') }}"  class="sidebar-header"> <i data-feather="calendar"></i> <span class="hide-menu">@lang('app.menu.Schedules')</span></a> </li>
            @endif

            @if(in_array('clients',$modules) || in_array('leads',$modules))
                <li><a href="{{ route('admin.clients.index') }}" class="sidebar-header"><i data-feather="users"></i> <span class="hide-menu"> @lang('app.menu.clients') <span class="fa arrow"></span> </span></a>
                    <ul class="sidebar-submenu">
                        @if(in_array('clients',$modules))
                            <li><a href="{{ route('admin.clients.index') }}" class="waves-effect">@lang('app.all') @lang('app.menu.clients')</a> </li>
                        @endif
                            <li><a href="{{ route('admin.all-contacts.index') }}">Contacts</a></li>
                        @if(in_array('leads',$modules))
                            <li><a href="{{ route('admin.leads.index') }}" class="waves-effect">@lang('app.menu.lead')</a>
                        </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if(in_array('projects', $modules) || in_array('tasks', $modules) || in_array('timelogs', $modules) || in_array('contracts', $modules))
                <li><a href="{{ route('admin.projects.index') }}"  class="sidebar-header"><i data-feather="clipboard"></i> <span class="hide-menu"> @lang('app.menu.jobs') <span class="fa arrow"></span> </span></a>
                    <ul class="sidebar-submenu">
                        @if(in_array('projects',$modules))
                            <li><a href="{{ route('admin.projects.index') }}" class="waves-effect">@lang('app.all') @lang('app.menu.jobs') </a> </li>
                        @endif
                        @if(in_array('tasks',$modules))
                            <li><a href="{{ route('admin.all-tasks.index') }}">@lang('app.menu.tasks')</a></li>
                            {{--<li class="hidden-sm hidden-xs"><a href="{{ route('admin.taskboard.index') }}">@lang('modules.tasks.taskBoard')</a></li>--}}
                            {{--<li><a href="{{ route('admin.task-calendar.index') }}">@lang('app.menu.taskCalendar')</a></li>--}}
                        @endif
                        @if(in_array("estimates", $modules))
                            <li><a href="{{ route('admin.estimates.index') }}">@lang('app.menu.estimates')</a> </li>
                        @endif
                        @if(in_array('timelogs',$modules))
                            {{--<li><a href="{{ route('admin.all-time-logs.index') }}" class="waves-effect">@lang('app.menu.timeLogs')</a> </li>--}}
                        @endif

                    </ul>
                </li>
            @endif

            @if((in_array("estimates", $modules)  || in_array("invoices", $modules)  || in_array("payments", $modules) || in_array("expenses", $modules)  ))
                <li><a href="{{ route('admin.all-invoices.index') }}"  class="sidebar-header"><i data-feather="dollar-sign"></i> <span class="hide-menu"> @lang('app.menu.money') @if($unreadExpenseCount > 0) <div class="notify notification-color"><span class="heartbit"></span><span class="point"></span></div>@endif <span class="fa arrow"></span> </span></a>
                    <ul class="sidebar-submenu">

                        @if(in_array("invoices", $modules))
                            <li><a href="{{ route('admin.all-invoices.index') }}">@lang('app.menu.invoices')</a> </li>
                        @endif

                        @if(in_array('contracts', $modules))
                            <li><a href="{{ route('admin.contracts.index') }}" class="waves-effect">@lang('app.menu.contracts')</a></li>
                        @endif                       

                        @if(in_array("expenses", $modules))
                            <li><a href="{{ route('admin.expenses.index') }}">@lang('app.menu.expenses') @if($unreadExpenseCount > 0) <div class="notify notification-color"><span class="heartbit"></span><span class="point"></span></div>@endif</a> </li>
                        @endif

                        @if(in_array("payments", $modules))
                            <li><a href="{{ route('admin.payments.index') }}">@lang('app.menu.payments')</a> </li>
                        @endif


                        {{--@if(in_array("invoices", $modules))--}}
                            {{--<li><a href="{{ route('admin.all-credit-notes.index') }}">@lang('app.menu.credit-note')</a> </li>--}}
                        {{--@endif--}}
                    </ul>
                </li>
            @endif
            @role('admin')

            @if(in_array('employees',$modules) || in_array('attendance',$modules))
            <li>
                <a href="{{ route('admin.employees.index') }}"  class="sidebar-header"><i data-feather="thumbs-up"></i> @if($unreadMessageCount > 0)<span class="badge message-count">{{ $unreadMessageCount }}</span> @endif
                    <span class="hide-menu"> @lang('app.menu.team') <span class="fa arrow"></span> </span></a>
                <ul class="sidebar-submenu">
                    @if(in_array('employees',$modules))
                        <li><a href="{{ route('admin.employees.index') }}">@lang('app.menu.employees')</a></li>
                        <li><a href="{{ route('admin.vehicles.index') }}">@lang('app.vehicles')</a></li>
                        <!--<li><a href="{{ route('admin.teams.index') }}">@lang('app.departments')</a></li>-->
                    @endif
                        
                    @if(in_array('attendance',$modules))
                    <li><a href="{{ route('admin.attendances.summary') }}" class="waves-effect">@lang('app.menu.attendance')</a> </li>
                    @endif
                    <li><a href="{{ route('admin.tickets.index') }}" class="waves-effect">@lang('app.menu.tickets')</a> </li>
                    @if(in_array("messages", $modules))
                        <li><a href="{{ route('admin.user-chat.index') }}"><span class="hide-menu">@lang('app.menu.messages') @if($unreadMessageCount > 0)<span class="label label-rouded label-custom pull-right">{{ $unreadMessageCount }}</span> @endif</span></a> </li>
                    @endif

                </ul>
            </li>
            @endif

            {{--App Menu--}}
            {{--<li>
                <a href="javascript:void(0);"  class="sidebar-header"><i class="fas fa-dice-d6"></i>
                     <span class="hide-menu"> @lang('app.menu.apps') <span class="fa arrow"></span> </span></a>
                <ul class="sidebar-submenu">
                    @if(in_array("messages", $modules))
                        <li><a href="{{ route('admin.user-chat.index') }}"><span class="hide-menu">@lang('app.menu.messages') @if($unreadMessageCount > 0)<span class="label label-rouded label-custom pull-right">{{ $unreadMessageCount }}</span> @endif</span></a> </li>
                    @endif
                    <li><a href="#"><span class="hide-menu">@lang('app.menu.fileManager')</span></a> </li>
                    <li><a href="#"><span class="hide-menu">@lang('app.menu.inventory')</span></a> </li>

                </ul>
            </li>--}}
            {{--App menu--}}
@if(in_array('clients',$modules) || in_array('projects',$modules) || in_array('employees',$modules) || in_array('leads',$modules))
            <li><a href="{{ route('admin.map.index') }}"  class="sidebar-header"> <i data-feather="map-pin"></i> <span class="hide-menu">@lang('app.menu.map')</span></a> </li>
@endif
            @endrole

            @if(in_array("reports", $modules))
                <li><a href="{{ route('admin.reports.index') }}"  class="sidebar-header"><i data-feather="bar-chart"></i> <span class="hide-menu"> @lang('app.menu.reports') <span class="fa arrow"></span> </span></a>
                   {{-- <ul class="sidebar-submenu">
                        <li><a href="{{ route('admin.task-report.index') }}">@lang('app.menu.taskReport')</a></li>
                        <li><a href="{{ route('admin.time-log-report.index') }}">@lang('app.menu.timeLogReport')</a></li>
                        <li><a href="{{ route('admin.finance-report.index') }}">@lang('app.menu.financeReport')</a></li>
                        <li><a href="{{ route('admin.income-expense-report.index') }}">@lang('app.menu.incomeVsExpenseReport')</a></li>
                        <li><a href="{{ route('admin.leave-report.index') }}">@lang('app.menu.leaveReport')</a></li>
                        <li><a href="{{ route('admin.attendance-report.index') }}">@lang('app.menu.attendanceReport')</a></li>
                    </ul> --}}
                </li>
            @endif

           @role('admin')
            <li><a href="{{ route('admin.faqs.index') }}"  class="sidebar-header"><i data-feather="star"></i> <span class="hide-menu"> @lang('app.menu.workcoach') <span class="fa arrow"></span> </span></a></li>

            {{--<li class="pb-30"><a href="#"  class="sidebar-header"><i class="fa fa-user"></i> <span class="hide-menu"> @lang('app.menu.admin')</span></a>

                <ul class="sidebar-submenu">
                    <li><a href="{{ route('admin.settings.index') }}" ><span class="hide-menu"> @lang('app.menu.settings')</span></a></li>
                    <li><a href="{{ route('admin.billing') }}"> <span class="hide-menu">@lang('app.menu.account') & @lang('app.menu.billing')</span></a> </li>
                    @if(in_array('employees', $modules) || in_array('attendance', $modules) || in_array('holidays', $modules) || in_array('leaves', $modules))
                        <li><a href="{{ route('admin.employees.index') }}"><span class="hide-menu">@lang('app.menu.employees')&@lang('app.menu.attendance')</span></a></li>
                    @endif
                    <li><a href="{{ route('admin.faqs.index') }}"><span class="hide-menu"> @lang('app.menu.knowledgeBase')</span></a></li>
                    <li><a href="{{ route('admin.all-tasks.index') }}">@lang('app.menu.tasks')</a></li>

                </ul>
            </li>--}}
            @endrole




            @if(in_array('employees', $modules) || in_array('attendance', $modules) || in_array('holidays', $modules) || in_array('leaves', $modules))
                {{--<li><a href="{{ route('admin.employees.index') }}"  class="sidebar-header"><i class="ti-user"></i> <span class="hide-menu"> @lang('app.menu.hr') <span class="fa arrow"></span> </span></a>--}}
                    {{--<ul class="sidebar-submenu">--}}
                        {{--@if(in_array('employees',$modules))--}}
                            {{--<li><a href="{{ route('admin.employees.index') }}">@lang('app.menu.employeeList')</a></li>--}}
                            {{--<li><a href="{{ route('admin.teams.index') }}">@lang('app.department')</a></li>--}}
                            {{--<li><a href="{{ route('admin.designations.index') }}">@lang('app.menu.designation')</a></li>--}}
                        {{--@endif--}}
                        {{--@if(in_array('attendance',$modules))--}}
                            {{--<li><a href="{{ route('admin.attendances.summary') }}" class="waves-effect">@lang('app.menu.attendance')</a> </li>--}}
                        {{--@endif--}}
                        {{--@if(in_array('holidays',$modules))--}}
                            {{--<li><a href="{{ route('admin.holidays.index') }}" class="waves-effect">@lang('app.menu.holiday')</a>--}}
                            {{--</li>--}}
                        {{--@endif--}}
                        {{--@if(in_array('leaves',$modules))--}}
                            {{--<li><a href="{{ route('admin.leaves.pending') }}" class="waves-effect">@lang('app.menu.leaves')</a> </li>--}}
                        {{--@endif--}}
                    {{--</ul>--}}
                {{--</li>--}}
            @endif

            {{--@if(in_array("products", $modules))
                <li><a href="{{ route('admin.products.index') }}"  class="sidebar-header"><i class="icon-basket"></i> <span class="hide-menu">@lang('app.menu.products') </span></a> </li>
            @endif
            @if(in_array("tickets", $modules))
                <li><a href="{{ route('admin.tickets.index') }}"  class="sidebar-header"><i class="ti-ticket"></i> <span class="hide-menu">@lang('app.menu.tickets')</span> @if($unreadTicketCount > 0) <div class="notify notification-color"><span class="heartbit"></span><span class="point"></span></div>@endif</a> </li>
            @endif
            @if(in_array("messages", $modules))
                --}}{{--<li><a href="{{ route('admin.user-chat.index') }}"  class="sidebar-header"><i class="icon-envelope"></i> <span class="hide-menu">@lang('app.menu.messages') @if($unreadMessageCount > 0)<span class="label label-rouded label-custom pull-right">{{ $unreadMessageCount }}</span> @endif</span></a> </li>--}}{{--
            @endif
            @if(in_array("notices", $modules))
                <li><a href="{{ route('admin.notices.index') }}"  class="sidebar-header"><i class="ti-layout-media-overlay"></i> <span class="hide-menu">@lang('app.menu.noticeBoard') </span></a> </li>
            @endif--}}
            @role('admin')
                {{--<li> <a href="{{ route('admin.billing') }}"  class="sidebar-header"><i class="icon-book-open"></i> <span class="hide-menu"> @lang('app.menu.billing')</span></a>            </li>--}}
            @endrole

            @foreach ($worksuitePlugins as $item)
                @if(in_array(strtolower($item), $modules))
                    @if(View::exists(strtolower($item).'::sections.left_sidebar'))
                        @include(strtolower($item).'::sections.left_sidebar')
                    @endif
                @endif
            @endforeach

            {{--<li><a href="{{ route('admin.faqs.index') }}"  class="sidebar-header"><i class="icon-docs"></i> <span class="hide-menu"> @lang('app.menu.faq')</span></a></li>--}}
            
            @if($sidebarAds->status == 1)
          <li><a href="{{ route('admin.billing') }}"><li class="ad_block_sidebar"> <img src="{{ $sidebarAds->image_url ?? '' }}" alt=""/> </li></a>
                </li>   @endif



        </ul>


    </div>



