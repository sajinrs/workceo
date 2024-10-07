@extends('layouts.app')

@push('head-script')
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/select2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/prism.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/tour.css') }}">
    <style>
        .fc-event {
            font-size: 10px !important;
        }

        #calendar .fc-view-container .fc-view .fc-more-popover {
            top: 136px !important;
            left: 105px !important;
        }


       
    </style>
@endpush
@section('page-title')

@endsection

@push('head-script')
    {{--<link rel="stylesheet" href="{{ asset('plugins/bower_components/calendar/dist/fullcalendar.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}"><!--Owl carousel CSS -->
    {{--<link rel="stylesheet" href="{{ asset('plugins/bower_components/owl.carousel/owl.carousel.min.css') }}"><!--Owl carousel CSS -->--}}
    {{--<link rel="stylesheet" href="{{ asset('plugins/bower_components/owl.carousel/owl.theme.default.css') }}"><!--Owl carousel CSS -->--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/owlcarousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/calendar.css') }}">
    <style>.col-in {
            padding: 0 20px !important;

        }

        .fc-event {
            font-size: 10px !important;
        }

        @media (min-width: 769px) {
            #wrapper .panel-wrapper {
                height: 530px;
                overflow-y: auto;
            }
        }

    </style>
@endpush

@section('content')
    

    <!-- New design starts -->

    

    <div class="bookmark widget-settings">
        {!! Form::open(['id'=>'createProject','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="btn-group dropdown keep-open pull-right m-l-10 ">
            <button aria-expanded="true" data-toggle="dropdown"
                    class="btn b-all dropdown-toggle settings-dropdown-btn" title="@lang('modules.dashboard.dashboardWidgets')"
                    type="button"><i class="icon-settings"></i>
            </button>
            <ul class="dropdown-menu  dropdown-menu-right dashboard-settings @if(topAds()->status == 1) topads @endif">
                <li class="b-b"><h4>@lang('modules.dashboard.dashboardWidgets')</h4></li>

                @foreach ($widgets as $widget)
                    @php
                        $wname = \Illuminate\Support\Str::camel($widget->widget_name);
                    @endphp
                    @if($widget->widget_name != 'settings_leaves' && $widget->widget_name != 'client_feedbacks' && $widget->widget_name != 'total_hours_logged' && $widget->widget_name != 'completed_tasks')
                    <li>
                        <div class="checkbox checkbox-info ">
                            <input id="{{ $widget->widget_name }}" name="{{ $widget->widget_name }}"
                                    value="true"
                                    @if ($widget->status)
                                    checked
                                    @endif
                                    type="checkbox">
                            <label for="{{ $widget->widget_name }}">@lang('modules.dashboard.' . $wname)</label>
                        </div>
                    </li>
                    @endif
                @endforeach

                <li style="width:100%">
                
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="button" id="save-form" class="btn btn-primary btn-sm btn-block">@lang('app.save')</button>
                        </div>

                        <div class="col-sm-6 p-l-10 p-r-20">
                            <button type="button" class="btn btn-outline-primary gray btn-sm btn-block closeBtn">@lang('app.close')</button>
                        </div>
                    </div>
                    
                    
                </li>

            </ul>
        </div>
        {!! Form::close() !!}

        {{--<select class="selectpicker language-switcher  pull-right" data-width="fit">
            <option value="en" @if($global->locale == "en") selected @endif data-content='<span class="flag-icon flag-icon-us"></span>'>En</option>
            @foreach($languageSettings as $language)
                <option value="{{ $language->language_code }}" @if($global->locale == $language->language_code) selected @endif  data-content='<span class="flag-icon flag-icon-{{ $language->language_code }}"></span>'>{{ $language->language_code }}</option>
            @endforeach
        </select>--}}
    </div>

    <div class="container-fluid p-t-30">
        @if(in_array('user_action_menu_bar',$activeWidgets))
        <div class="row">
            <div class="col-md-12">
                <ul class="action-menu-bar">
                    @if(in_array('projects', $modules))
                        <li><a href="{{ route('admin.projects.create') }}"><i data-feather="clipboard"></i> Add New Job</a></li>
                    @endif

                    @if(in_array('clients', $modules))
                        <li><a href="{{ route('admin.clients.create') }}"><i data-feather="users"></i> Add New Client</a></li>
                    @endif

                    @if(in_array('estimates', $modules))
                        <li><a href="{{ route('admin.estimates.create') }}"><i data-feather="file-text"></i> Create Estimate</a></li>
                    @endif

                    @if(in_array('payments', $modules))
                        <li><a href="{{ route('admin.payments.create') }}"><i data-feather="dollar-sign"></i> Add Payment</a></li>
                    @endif

                    @if(in_array('clients',$modules) || in_array('projects',$modules) || in_array('employees',$modules) || in_array('leads',$modules))
                        <li><a href="{{ route('admin.map.index') }}?tab=vehicle"><i data-feather="truck"></i> Track Vehicle</a></li>
                    @endif

                    @if(in_array('attendance', $modules))
                        <li><a href="{{ route('admin.attendances.create') }}"><i data-feather="clock"></i> Add Attendance</a></li>
                    @endif

                    @if(in_array('messages', $modules))
                        <li><a href="{{ route('admin.user-chat.index') }}"><i data-feather="message-square"></i> Create Message</a></li>
                    @endif

                    @if(in_array('invoices', $modules))
                        <li><a href="{{ route('admin.all-invoices.index') }}?tab=unpaid" class="last-item"><i data-feather="alert-triangle"></i> See Late Invoices</a></li>
                    @endif
                </ul>
            </div>
        </div>
        @endif
            
        <div class="row">        
            <div class="col-lg-6">
                <div class="row">
                    @if(in_array('projects',$modules))
                        <!-- Job Count -->
                        <div class="col-md-6">
                            <div id="step3" class="card card-db-block">
                                <div class="card-header project-header">
                                    <div class="row">
                                        <div class="col-sm-7 col-md-7 col-7">
                                            <h5>Job Count</h5>
                                        </div>
                                        <div class="col-sm-5 col-md-5 col-5 p-l-0">
                                            <div class="select2-drpdwn-project select-options">
                                                <select class="form-control f-z-12 p-0 form-control-primary btn-square db_date_range_dd"  id="jobs_date_range">
                                                    <option value="0">Today</option>
                                                    <option value="-7">Past 7 Days</option>
                                                    <option value="-14">Past 14 Days</option>
                                                    <option value="-30">Past 30 Days</option>
                                                    <option value="+30">Next 30 Days</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" id="job_block">
                                       <!-- Loader starts-->
                                        <div class="loader-box row">
                                            <div class="loader col-12">
                                                <div class="line bg-primary"></div>
                                                <div class="line bg-primary"></div>
                                                <div class="line bg-primary"></div>
                                                <div class="line bg-primary"></div>
                                            </div>
                                        </div>
                                        <!-- Loader ends-->
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="btn-bottom">
                                                <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-primary width-100" type="button"
                                                        data-original-title="" title="">SEE JOBS
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Job Revenue -->
                        <div class="col-md-6">
                            <div id="step4" class="card card-db-block">
                                <div class="card-header project-header">
                                    <div class="row">
                                        <div class="col-sm-7 col-md-7 col-7">
                                            <h5>Job Revenue</h5>
                                        </div>
                                        <div class="col-sm-5 col-md-5 col-5 p-l-0">
                                            <div class="select2-drpdwn-project select-options">
                                                <select class="form-control f-z-12 p-0 form-control-primary btn-square db_date_range_dd"
                                                        id="revenue_date_range">
                                                    <option value="0">Today</option>
                                                    <option value="-7">Past 7 Days</option>
                                                    <option value="-14">Past 14 Days</option>
                                                    <option value="-30">Past 30 Days</option>
                                                    <option value="+30">Next 30 Days</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" id="revenue_block">
                                    <!-- Loader starts-->
                                    <div class="loader-box row">
                                        <div class="loader col-12">
                                            <div class="line bg-primary"></div>
                                            <div class="line bg-primary"></div>
                                            <div class="line bg-primary"></div>
                                            <div class="line bg-primary"></div>
                                        </div>
                                    </div>
                                    <!-- Loader ends-->
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="btn-bottom">
                                                <a  href="{{ route('admin.projects.index') }}" class="btn btn-outline-primary width-100" type="button"
                                                        data-original-title="" title="">SEE MONEY
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step8"></div>
                        <!-- Leadboard -->
                        <div class="col-md-6">
                        <div id="step5" class="card card-db-block">
                            <div class="card-header project-header">
                                <div class="row">
                                    <div class="col-sm-7 col-md-7 col-7">
                                        <h5>Leadboard</h5>
                                    </div>
                                    <div class="col-sm-5 col-md-5 col-5">
                                        <div class="select2-drpdwn-project select-options">
                                            <select class="form-control form-control-primary btn-square db_date_range_dd"
                                                    id="leadboard_date_range">
                                                <option value="jobs">Jobs</option>
                                                <option value="time">Time</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="f-z-10">{{ $counts->totalEmployees }} Total team members</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" id="leadboard_block">
                                <!-- Loader starts-->
                                <div class="loader-box row">
                                    <div class="loader col-12">
                                        <div class="line bg-primary"></div>
                                        <div class="line bg-primary"></div>
                                        <div class="line bg-primary"></div>
                                        <div class="line bg-primary"></div>
                                    </div>
                                </div>
                                <!-- Loader ends-->
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="btn-bottom">
                                            <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-primary width-100" type="button"
                                                    data-original-title="" title="">SEE TEAM
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(in_array('invoices',$modules))
                        <!-- Recent invoices -->
                        <div class="col-md-6">

                            <div id="step6" class="card card-db-block">
                                <div class="card-header project-header">
                                    <div class="row">
                                        <div class="col-sm-8 col-md-8 col-8">
                                            <h5>Recent Invoices</h5>
                                        </div>
                                        <div class="col-sm-4 col-md-4 col-4">
                                            <div class="select2-drpdwn-project select-options">
                                                <select class="form-control form-control-primary btn-square db_date_range_dd p-0"
                                                        id="invoice_date_range">
                                                    <option value="paid">Paid</option>
                                                    <option value="due">Due</option>
                                                   </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="f-z-10"><span class="text-danger"><b>{{$total_due}}</b></span> Total Due</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" id="invoice_block">
                                    <!-- Loader starts-->
                                    <div class="loader-box row">
                                        <div class="loader col-12">
                                            <div class="line bg-primary"></div>
                                            <div class="line bg-primary"></div>
                                            <div class="line bg-primary"></div>
                                            <div class="line bg-primary"></div>
                                        </div>
                                    </div>
                                    <!-- Loader ends-->
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="btn-bottom">
                                                <a href="{{ route('admin.all-invoices.index') }}" class="btn btn-outline-primary width-100" type="button"
                                                        data-original-title="" title="">SEE INVOICES
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @if(in_array('projects',$modules))
                <div class="col-lg-6">
                <div id="step7" class="card card-db-block">
                    <div class="card-header project-header">
                        <div class="row">
                            <div class="col-sm-8 col-md-8 col-8">
                                <h5>Job & Team Members</h5>
                            </div>
                            <div class="col-sm-4 col-md-4 col-4">
                                <div class="select2-drpdwn-project select-options">
                                    <select class="form-control f-z-12 p-0 form-control-primary btn-square db_date_range_dd"  id="map_date_range">
                                        <option value="0">Today</option>
                                        <option value="-7">Past 7 Days</option>
                                        <option value="-14">Past 14 Days</option>
                                        <option value="-30">Past 30 Days</option>
                                        <option value="+30">Next 30 Days</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body"  id="map_block">
                        <div id="map" style="height: 530px; width: auto;"></div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="btn-bottom">
                                    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-primary width-100" type="button"
                                       data-original-title="" title="">SEE SCHEDULE
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="btn-bottom">
                                    <a href="{{ route('admin.map.index') }}" class="btn btn-outline-primary width-100" type="button"
                                       data-original-title="" title="">SEE MAP
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div id="map" style="height: 295px; width: auto;"></div>--}}
                {{-- <img src="https://maps.googleapis.com/maps/api/staticmap?center=Brooklyn+Bridge,New+York,NY&zoom=10&size=300x350&scale=2&maptype=roadmap
                &markers=color:blue%7Clabel:S%7C40.702147,-74.015794&markers=color:green%7Clabel:G%7C40.711614,-74.012318
                &markers=color:red%7Clabel:C%7C40.718217,-73.998284
                &key=AIzaSyCsLN7tz9Ww5Lt2hDS4KqaBrb8clNSwdkQ" alt="Points of Interest in Lower Manhattan" border="0" height="96%">
                --}}
                </div>
            @endif
        </div>
    </div>

<!--New design ends-->






<!-- Container-fluid starts-->
<div class="container-fluid">
@if(!is_null($global->licence_expire_on) && $global->status == 'license_expired')
<div class="row">
<div class="col-xl-8 xl-100 alert alert-danger ">

    <p class="text-white">@lang('messages.licenseExpiredNote') <a href="{{route('admin.billing')}}"
                                                                  class="pull-right btn btn-xs btn-success">{{ __('app.menu.billing') }}
            <i class="fa fa-shopping-cart"></i></a></p>
</div>
</div>
@endif
@if($company->package->default == 'yes' || $company->package->default == 'trial')
@if($packageSetting && !$packageSetting->all_packages)
<div class="row">
    <div class="col-xl-8 xl-100 alert alert-danger ">
        <p class="text-white">@lang('messages.purchasePackageMessage')
            <a href="{{route('admin.billing')}}"
               class="pull-right btn btn-success">{{ __('app.menu.billing') }}
                <i class="fa fa-shopping-cart"></i></a></p>
    </div>
</div>
@endif
@endif
<!-- counter widgets -->
<div class="row">
<div class="col-xl-12 xl-100">
<div class="row wceo-dash-wigets">

    @if(in_array('clients',$modules)  && in_array('total_clients',$activeWidgets))
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <!--<a href="{{ route('admin.clients.index') }}">-->
                <div class="card o-hidden">
                    <div class=" b-r-4 card-body">
                        <div class="icon pull-right"><a href="{{  route('admin.clients.index') }}"> <i data-feather="external-link"></i></a></div>
                        <div class="media static-top-widget">
                            <div class="bg-primary dash-wiget-icon align-self-center text-center"><i
                                        data-feather="user"></i></div>
                            <div class="media-body"><h4 class="dash-content mb-0 counteNumer">{{ $counts->totalClients }}</h4>
                            <span class="m-0">@lang('modules.dashboard.totalClients')</span>
                                
                            </div>
                        </div>
                    </div>
                </div>
            <!--</a>-->
        </div>
    @endif

    @if(in_array('employees',$modules)  && in_array('total_employees',$activeWidgets))
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <!--<a href="{{ route('admin.employees.index') }}">-->
                <div class="card o-hidden">
                    <div class=" b-r-4 card-body">
                        <div class="icon pull-right"><a href="{{  route('admin.employees.index') }}"> <i data-feather="external-link"></i></a></div>
                        <div class="media static-top-widget">
                            <div class="bg-primary dash-wiget-icon align-self-center text-center"><i
                                        data-feather="users"></i></div>
                            <div class="media-body"><h4 class="dash-content mb-0 counteNumer">{{ $counts->totalEmployees }}</h4>
                                <span class="m-0">@lang('modules.dashboard.totalEmployees')</span>
                            </div>
                        </div>
                    </div>
                </div>
            <!--</a>-->
        </div>
    @endif

    @if(in_array('projects',$modules)  && in_array('total_projects',$activeWidgets))
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <!--<a href="{{ route('admin.projects.index') }}">-->
                <div class="card o-hidden">
                    <div class=" b-r-4 card-body">
                        <div class="icon pull-right"><a href="{{  route('admin.projects.index') }}"> <i data-feather="external-link"></i></a></div>
                        <div class="media static-top-widget">
                            <div class="bg-primary dash-wiget-icon align-self-center text-center"><i
                                        data-feather="layers"></i></div>
                            <div class="media-body"><h4 class="dash-content mb-0 counteNumer">{{ $counts->totalProjects }}</h4>
                                <span class="m-0">@lang('modules.dashboard.totalProjects')</span>
                            </div>
                        </div>
                    </div>
                </div>
           <!-- </a>-->
        </div>
    @endif

    @if(in_array('invoices',$modules)  && in_array('total_unpaid_invoices',$activeWidgets))
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <!--<a href="{{ route('admin.all-invoices.index') }}">-->
                <div class="card o-hidden">
                    <div class=" b-r-4 card-body">
                        <div class="icon pull-right"><a href="{{  route('admin.all-invoices.index') }}"> <i data-feather="external-link"></i></a></div>
                        <div class="media static-top-widget">
                            <div class="bg-primary dash-wiget-icon align-self-center text-center"><i
                                        data-feather="file-text"></i></div>
                            <div class="media-body"><h4 class="dash-content mb-0 counteNumer">{{ $counts->totalUnpaidInvoices }}</h4>
                                <span class="m-0">@lang('modules.dashboard.totalUnpaidInvoices')</span>
                            </div>
                        </div>
                    </div>
                </div>
            <!--</a>-->
        </div>
    @endif

    @if(in_array('tasks',$modules)  && in_array('total_pending_tasks',$activeWidgets))
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <!--<a href="{{ route('admin.all-tasks.index') }}">-->
                <div class="card o-hidden">
                    <div class=" b-r-4 card-body">
                        <div class="icon pull-right"><a href="{{  route('admin.all-tasks.index') }}"> <i data-feather="external-link"></i></a></div>
                        <div class="media static-top-widget">
                            <div class="bg-primary dash-wiget-icon align-self-center text-center"><i
                                        data-feather="alert-triangle"></i></div>
                            <div class="media-body"><h4 class="dash-content mb-0 counteNumer">{{ $counts->totalPendingTasks }}</h4>
                                <span class="m-0">@lang('modules.dashboard.totalPendingTasks')</span>
                            </div>
                        </div>
                    </div>
                </div>
            <!--</a>-->
        </div>
    @endif

    {{--@if(in_array('timelogs',$modules)  && in_array('total_hours_logged',$activeWidgets))
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <a href="#">
                <div class="card o-hidden">
                    <div class=" b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="bg-primary dash-wiget-icon align-self-center text-center"><i
                                        data-feather="watch"></i></div>
                            <div class="media-body"><h4 class="dash-content mb-0 counteNumer" style="font-size: 16px;">{{ $counts->totalHoursLogged }}</h4>
                                <span class="m-0">@lang('modules.dashboard.totalHoursLogged')</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif --}}

    {{--@if(in_array('tasks',$modules) && in_array('completed_tasks',$activeWidgets))
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <!--<a href="{{ route('admin.all-tasks.index') }}">-->
                <div class="card o-hidden"><div id="icon"><a href="{{  route('admin.all-tasks.index') }}">
                    <i data-feather="external-link"></i></a></div>
                    <div class=" b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="bg-primary dash-wiget-icon align-self-center text-center"><i
                                        data-feather="check-square"></i></div>
                            <div class="media-body"><h4 class="dash-content mb-0 counteNumer">{{ $counts->totalCompletedTasks }}</h4>
                                <span class="m-0">@lang('modules.dashboard.totalCompletedTasks')</span>
                            </div>
                        </div>
                    </div>
                </div>
            <!--</a>-->
        </div>
    @endif--}}

    @if(in_array('attendance',$modules)  && in_array('total_today_attendance',$activeWidgets))
        <div class="col-sm-6 col-xl-3 col-lg-6">
           <!-- <a href="{{ route('admin.attendances.index') }}">-->
                <div class="card o-hidden">
                    <div class=" b-r-4 card-body">
                        <div class="icon pull-right"><a href="{{  route('admin.attendances.index') }}"> <i data-feather="external-link"></i></a></div>
                        <div class="media static-top-widget">
                            <div class="bg-primary dash-wiget-icon align-self-center text-center"><i
                                        data-feather="user-check"></i></div>
                            <div class="media-body"><h4 class="mb-0 dash-content">
                                    <span class="counteNumer">@if($counts->totalEmployees > 0){{ round((($counts->totalTodayAttendance/$counts->totalEmployees)*100), 2) }}@else
                                            0 @endif </span>% </h4>
                                    <span class="text-muted">({{ $counts->totalTodayAttendance.'/'.$counts->totalEmployees }})</span>
                                
                                <span class="m-0">Today's Attendance</span>
                                
                            </div>
                        </div>
                    </div>
                </div>
            <!--</a>-->
        </div>
    @endif

    @if(in_array('tickets',$modules) && in_array('total_resolved_tickets',$activeWidgets))
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <!--<a href="{{ route('admin.tickets.index') }}">-->
                <div class="card o-hidden">
                    <div class=" b-r-4 card-body">
                        <div class="icon pull-right"><a href="{{  route('admin.tickets.index') }}"> <i data-feather="external-link"></i></a></div>
                        <div class="media static-top-widget">
                            <div class="bg-primary dash-wiget-icon align-self-center text-center"><i
                                        data-feather="check-circle"></i></div>
                            <div class="media-body"><h4 class="dash-content mb-0 counteNumer">{{ floor($counts->totalResolvedTickets) }}</h4>
                                <span class="m-0">@lang('modules.tickets.totalResolvedTickets')</span>
                            </div>
                        </div>
                    </div>
                </div>
            <!--</a>-->
        </div>
    @endif

    @if(in_array('tickets',$modules)   && in_array('total_unresolved_tickets',$activeWidgets))
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <!--<a href="{{ route('admin.tickets.index') }}">-->
                <div class="card o-hidden">
                    <div class=" b-r-4 card-body">
                        <div class="icon pull-right"><a href="{{  route('admin.tickets.index') }}"> <i data-feather="external-link"></i></a></div>
                        <div class="media static-top-widget">
                            <div class="bg-primary dash-wiget-icon align-self-center text-center"><i
                                        data-feather="alert-circle"></i></div>
                            <div class="media-body"><h4 class="dash-content mb-0 counteNumer">{{ floor($counts->totalUnResolvedTickets) }}</h4>
                                <span class="m-0">@lang('modules.tickets.totalUnresolvedTickets')</span>  
                            </div>
                        </div>
                    </div>
                </div>

            <!--</a>-->
        </div>
    @endif
</div>
</div>
</div>

<!-- Recent Earnings , new tickets -->
<div class="row">

@if(in_array('payments',$modules)  && in_array('recent_earnings',$activeWidgets))
<div class="col-xl-6">
    <div class="card">
        <div class="card-header">
            <h4 class="black-text"><span>@lang('modules.dashboard.recentEarnings')</span><a href="{{ route('admin.payments.index') }}" class="btn btn-outline-primary gray-bg btn-sm pull-right">View All Payments</a></h4>
        </div>
            <div class="card-body">
                <div id="morris-area-chart" style="height: 190px;"></div>
                    <span class="help-block" style="line-height: 2em;"><span class="badge badge-danger">@lang('app.note'):</span> @lang('messages.earningChartNote') 
                        <a href="{{ route('admin.settings.index') }}"><i class="fa fa-arrow-right"></i></a>
                    </span>
            </div>
    </div>
</div>
@endif
@if(in_array('tickets',$modules)  && in_array('new_tickets',$activeWidgets))
<div class="col-md-6">
    <div class="card">
        <div class="card-header"><h4 class="black-text"><span>@lang('modules.dashboard.newTickets')</span><a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-primary gray-bg btn-sm pull-right">View All Tickets</a></h4></div>
            <div class="card-body custom-scroll"> 
                <div class="notifiaction-media">
                    <div class="media">
                        <div class="media-body">
                            <table class="table ticketlist table-borderless">
                                <thead>
                                    <tr>
                                    <th scope="col"> <span class="underline">Ticket Number</span> </th>
                                    <th scope="col"> <span class="underline">Ticket Title</span></th>
                                    <th scope="col"> <span class="underline">Date Submitted</span></th>
                                    </tr>
                                </thead>
                                @forelse($newTickets as $key=>$newTicket)
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="circle-left"></div>
                                            <h6 class="f-12"><a
                                                        href="{{ route('admin.tickets.edit', $newTicket->id) }}"> {{  ucfirst($newTicket->ticket_number) }}</a>
                                                
                                            </h6></td>
                                        <td>{{  ucfirst($newTicket->subject) }}</td>
                                        <td><span class="f-12">{{ ucwords($newTicket->created_at->format('m/d/Y')) }}</span></td>
                                    </tr>                        
                                    @empty
                                        <tr>
                                            <td><p>@lang("messages.noTicketFound")</p></td>
                                        </tr>
                                    @endforelse            
                                </tbody>
                            </table>
                        </div>
                    </div>           
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Leaves fullcalendar-->
{{--<div class="row">

@if(in_array('leaves',$modules)  && in_array('settings_leaves',$activeWidgets))
<div class="col-md-12">
    <div class=" calendar-wrap">
        <div class="card">
            <div class="card-header"><h5>@lang('app.menu.leaves')</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

</div>--}}
<!-- Overdue Tasks , Pending FollowUp -->
<div class="row">

@if(in_array('tasks',$modules)  && in_array('overdue_tasks',$activeWidgets))
<div class="col-md-6">
    <div class="card">
        <div class="card-header"><h4 class="black-text"><span>@lang('modules.dashboard.overdueTasks')</span><a href="{{ route('admin.all-tasks.index') }}" class="btn btn-outline-primary gray-bg btn-sm pull-right">View All Tasks</a></h4></div>
        <div class="card-body custom-scroll">
            <ul class="list-task list-group" data-role="tasklist">
                <li class="list-group-item" data-role="task">
                    <strong>@lang('app.title')</strong> <span
                            class="pull-right"><strong>@lang('modules.dashboard.dueDate')</strong></span>
                </li>
                @forelse($pendingTasks as $key=>$task)
                    @if((!is_null($task->project_id) && !is_null($task->project) ) || is_null($task->project_id))
                        <li class="list-group-item" data-role="task">
                            <div class="row">
                                <div class="col-md-9">
                                    {!! ($key+1).'. <a href="javascript:;" data-task-id="'.$task->id.'" class="show-task-detail">'.ucfirst($task->heading).'</a>' !!}
                                    @if(!is_null($task->project_id) && !is_null($task->project))
                                        <a href="{{ route('admin.projects.show', $task->project_id) }}"
                                        class="text-danger">{{ ucwords($task->project->project_name) }}</a>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label class="badge badge-danger pull-right">{{ $task->due_date->format($global->date_format) }}</label>
                                </div>
                            </div>
                        </li>
                    @endif
                @empty

                    <li class="list-group-item" data-role="task">
                        <div class="text-center">
                            <div class="empty-space" style="height: 200px;">
                                <div class="empty-space-inner">
                                    <div class="icon" style="font-size:20px"><i class="fa fa-edit"></i>
                                    </div>
                                    <div class="title m-b-15">@lang("messages.noOpenTasks")</div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endif
@if(in_array('leads',$modules)  && in_array('pending_follow_up',$activeWidgets))
<div class="col-md-6">
    <div class="card">
        <div class="card-header"><h4 class="black-text"><span>@lang('modules.dashboard.leadsPendingFollowUp')</span><a href="{{ route('admin.leads.index') }}" class="btn btn-outline-primary gray-bg btn-sm pull-right">View All Leads</a></h4></div>
        <div class="card-body custom-scroll">
            <ul class="list-task list-group" data-role="tasklist">
                <li class="list-group-item" data-role="task">
                    <strong>@lang('app.title')</strong> <span
                            class="pull-right"><strong>@lang('modules.dashboard.followUpDate')</strong></span>
                </li>
                @forelse($pendingLeadFollowUps as $key=>$follows)
                    <li class="list-group-item" data-role="task">
                        <div class="row">
                            <div class="col-md-9">
                                {{ ($key+1) }}.

                                <a href="{{ route('admin.leads.show', $follows->lead_id) }}"
                                class="text-danger">{{ ucwords($follows->lead->company_name) }}</a>

                            </div>
                            <div class="col-md-3">
                                <label class="badge badge-danger pull-right">{{ $follows->next_follow_up_date->format($global->date_format) }}</label>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item" data-role="task">
                        <div class="text-center">
                            <div class="empty-space" style="height: 200px;">
                                <div class="empty-space-inner">
                                    <div class="icon" style="font-size:20px"><i
                                                class="fa fa-user-plus"></i>
                                    </div>
                                    <div class="title m-b-15">@lang("messages.noPendingLeadFollowUps")
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endif

</div>

<!-- Project Activity Timeline, User activity timeline  -->
<div class="row">

@if(in_array('projects',$modules)  && in_array('project_activity_timeline',$activeWidgets))
<div class="col-md-6" id="project-timeline">
    <div class="card">
        <div class="card-header"><h4 class="black-text"><span>@lang('modules.dashboard.projectActivityTimeline')</span><a href="{{ route('admin.projects.index') }}" class="btn btn-outline-primary gray-bg btn-sm pull-right">View All Jobs</a></h4></div>
        <!--<div class="card-body slimscroll" >-->
        <div class="card-body custom-scroll">

        <div class="timeline-small projActivity renewtimeLine ">
            @forelse($projectActivities as $activ)
            <div class="media">
                <div class="timeline-round m-r-30 timeline-line-1 bg-primary"></div>
                <div class="media-body">
                    <h6><a href="{{ route('admin.projects.show', $activ->project_id) }}" class="text-dark">{{ ucwords($activ->project->project_name) }}
                    </a> <span class="pull-right f-12">{{ $activ->created_at->diffForHumans() }}</span></h6>
                    <span class="sl-date"> {{ $activ->activity }}</span><br>
                </div>
            </div>
            @empty
                <div class="text-center">
                    <div class="empty-space" style="height: 200px;">
                        <div class="empty-space-inner">
                            <div class="title m-b-15">@lang("messages.noprojectActivityTimeline") </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>


        </div>
    </div>
</div>
@endif

@if(in_array('employees',$modules)  && in_array('user_activity_timeline',$activeWidgets))
<div class="col-md-6">
    <div class="card">
        <div class="card-header"><h4 class="black-text"><span>@lang('modules.dashboard.userActivityTimeline')</span><a href="{{ route('admin.employees.index') }}" class="btn btn-outline-primary gray-bg btn-sm pull-right">View All Employees</a></h4></div>
        <!--<div class="card-body slimscroll">-->
        <div class="card-body custom-scroll">
        <div class="new-users userActivity">
            @forelse($userActivities as $key=>$activity)
            <div class="media">
                <img class="rounded-circle image-radius m-r-15" src="{{ $activity->user->image_url }}" width="45" height="45" alt="user">
                <div class="media-body">
                    <h6 class="mb-0 f-w-700">{{ ucfirst($activity->activity) }}</h6>
                    <p>{!! $activity->created_at->diffForHumans()  !!} ( {!! ucwords($activity->user->name)  !!} )</p>
                </div>
                <!--<span class="pull-right f-12">{{ $activity->created_at->diffForHumans() }}</span>-->
            </div>
            @if(count($userActivities) > ($key+1))
                <hr>
                @endif
            @empty
                <div class="text-center">
                    <div class="empty-space" style="height: 200px;">
                        <div class="empty-space-inner">
                            <div class="title m-b-15">@lang("messages.noActivityByThisUser")</div>
                        </div>
                    </div>
                </div>
            @endforelse



    </div>


        </div>
    </div>
</div>
@endif

</div>
<!-- Project Activity Timeline, new tickets -->
<div class="row">
{{--@if(in_array('client_feedbacks',$activeWidgets))
<div class="col-sm-6">
    <div class="card">
        <div class="card-header">
            <h5>@lang('modules.projects.clientFeedback')</h5>
        </div>
        <div class="card-body">
            <!-- Carousel items -->
            @forelse($feedbacks as $key=>$feedback)
                <div class="owl-carousel owl-theme " id="owl-carousel-1">
                    <div class="@if($key == 0) active @endif item">
                        <h5>{!! substr($feedback->feedback,0,70).'...' !!}</h5>
                        @if(!is_null($feedback->client))
                            <div class="twi-user">
                                <img src="{{ $feedback->client->image_url }}" alt="user"
                                     class="img-circle img-responsive pull-left">
                                <h5 class="m-b-0">{{ ucwords($feedback->client->name) }}</h5>
                                <p class="">{{ ucwords($feedback->project_name) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center">
                    <div class="empty-space" style="height: 200px;">
                        <div class="empty-space-inner">
                            <p class="">@lang('messages.noFeedbackReceived')</p>
                        </div>
                    </div>
                </div>
            @endforelse

        </div>
    </div>

</div>
@endif --}}


</div>
</div>
<!-- Container-fluid Ends-->
<div class="clearfix"></div>


{{--Ajax Modal--}}
<div class="modal fade bs-modal-md in" id="eventDetailModal" role="dialog" aria-labelledby="myModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg" id="modal-data-application">
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

{{--Ajax Modal--}}
<div class="modal fade bs-modal-md in" id="subTaskModal" role="dialog" aria-labelledby="myModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-md" id="modal-data-application">
<div class="modal-content">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <span class="caption-subject font-red-sunglo bold uppercase"
          id="subTaskModelHeading">Sub Task e</span>
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


@if(empty($global->company_phone))
<div id="adminSignupModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-body">

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    {!! Form::open(['id'=>'adminSignup','class'=>'ajax-form f1','method'=>'POST']) !!}
                    <form class="f1" method="post">
                        <div class="f1-steps text-center">
                            <div class="f1-progress">
                                <div class="f1-progress-line" data-now-value="16.66" data-number-of-steps="3"></div>
                            </div>
                            <div class="f1-step active">
                                <div class="f1-step-icon">1</div>
                            </div>
                            <div class="f1-step">
                                <div class="f1-step-icon">2</div>
                            </div>
                            <div class="f1-step">
                                <div class="f1-step-icon">3</div>
                            </div>
                            <div class="f1-step">
                                <div class="f1-step-icon">4</div>
                            </div>
                        </div>

                        <div class="text-center">
                            <img class="rounded-circle image-radius" src="{{ asset('img/onboard.png') }}" alt="" width="100" height="100" />
                        </div>
                    <fieldset>

                        <div class="text-center m-b-20">
                            <h1>Welcome {{ ucwords($user->first_name) }}!</h1>
                            <h1 class="m-t-0">Let's set up your account.</h1>
                        </div>



                        <div class="col-md-6 offset-md-3">
                            <div class="form-label-group form-group">
                                <input class="form-control form-control-lg" type="text" name="name" id="name" placeholder="{{ __('modules.employees.fullName') }}" value="{{ ucwords($user->name) }}" required="" />
                                <label for="name" class="col-form-label required">@lang('modules.employees.fullName')</label>
                            </div>
                        </div>

                        <div class="col-md-6 offset-md-3">
                            <div class="form-label-group form-group">
                                <input class="form-control form-control-lg" type="text" name="mobile" id="mobile" placeholder="Your phone number" value="{{ ucwords($user->mobile) }}" required="" />
                                <label for="mobile" class="col-form-label required">Your phone number</label>
                            </div>
                        </div>



                        <div class="f1-buttons text-center m-t-30">
                            <button class="btn btn-primary btn-next" type="button">Next</button>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="text-center m-b-30">
                            <h1>Let’s set up your business:</h1>
                            <span class="sub-text">Tell us about your company so we can set up your dashboard.</span>
                        </div>

                        <div class="col-md-8 offset-md-2">
                            <div class="form-label-group form-group">
                                <input placeholder="@lang('modules.client.companyName')" type="text" id="company_name" name="company_name" class="form-control form-control-lg" >
                                <label for="company_name" class="col-form-label required">@lang('modules.client.companyName')</label>
                            </div>
                        </div>

                        <div class="col-md-8 offset-md-2">
                            <div class="form-group">
                                <label>Company size (including you)</label>
                                <div class="btn-group btn-group-toggle cmp-size" data-toggle="buttons">
                                    <label class="btn btn-light">
                                        <input type="radio" checked name="company_size" value="Just Me" required /> Just Me
                                    </label>
                                    <label class="btn btn-light">
                                        <input type="radio" name="company_size" value="2-3 People" required /> 2-3 People
                                    </label>
                                    <label class="btn btn-light">
                                        <input type="radio" name="company_size" value="4-10 People" required /> 4-10 People
                                    </label>
                                    <label class="btn btn-light">
                                        <input type="radio" name="company_size" value="10+ People" required /> 10+ People
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 offset-md-2">
                            <div class="form-label-group form-group">
                                <select placeholder="-" class="hide-search form-control form-control-lg" data-placeholder="Select Industry" name="industry" id="industry" required>
                                    <option value="">--</option>
                                    @foreach($industries as $industry)
                                        <option value="{{$industry->id}}">{{$industry->name}}</option>
                                    @endforeach
                                </select>
                                <label for="industry" class="col-form-label required">Select Industry</label>
                            </div>
                        </div>

                        <div class="col-md-8 offset-md-2">
                            <div class="form-group">
                                <label>What's your business address?</label>
                                <div id="addressMap"></div>
                                <div id="infowindow-content">
                                    <span id="place-name" class="title"></span><br />
                                    <span id="place-address"></span>
                                </div>
                                <input id="companyAddress" name="cmp_address" class="form-control form-control-lg" type="text" placeholder="Enter Business Name and Location" />
                            </div>
                        </div>

                        <div class="f1-buttons text-center m-t-30">
                            <button class="btn btn-outline-primary btn-previous" type="submit">Previous</button>
                            <button class="btn btn-primary btn-next" type="button">Next</button>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="text-center m-b-30">
                            <h1>What do you need help with?</h1>
                            <span class="sub-text">You can choose as many features as you like:</span>
                        </div>
                        <div class="col-md-12 p-0">
                            <div class="form-group feature-box">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    @foreach($interests as $key=> $interest)
                                        @if($key < 6)
                                        <label class="btn btn-light">
                                            <input type="checkbox" name="interest" value="{{$interest->id}}" /> <i class="{{$interest->icon}}"></i> <br />
                                            {{$interest->name}}
                                        </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 p-0">
                            <div class="form-group feature-box">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                @foreach($interests as $key=> $interest)
                                        @if($key >= 6)
                                        <label class="btn btn-light">
                                            <input type="checkbox" name="interest" value="{{$interest->id}}" /> <i class="{{$interest->icon}}"></i> <br />
                                            {{$interest->name}}
                                        </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="features" name="features" />

                        <div class="f1-buttons text-center m-t-30">
                            <button class="btn btn-outline-primary btn-previous" type="submit">Previous</button>
                            <button class="btn btn-primary btn-next" type="button">Next</button>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="text-center m-b-30">
                            <h1>You’re all finished!</h1>
                        </div>

                        <div class="col-md-6 offset-md-3">
                            <div class="form-label-group form-group">
                                <select class="hide-search form-control form-control-lg" name="source" id="source" placeholder="-" required>
                                    @foreach($findus as $find)
                                        <option value="{{$find->id}}">{{$find->source}}</option>
                                    @endforeach
                                </select>
                                <label for="source" class="col-form-label required">How did you find out about WorkCEO?</label>
                            </div>
                            <p class="text-center">Would you like to learn more about our features from one of our experts?</p>
                        </div>
                        <div class="f1-buttons text-center m-t-30">
                            <button data-type="complete" class="btn btn-outline-primary btn-submit" type="submit">Complete</button>
                            <button data-type="demo" class="btn btn-primary btn-submit" type="submit">Book a Demo</button>
                        </div>
                    </fieldset>
                    {!! Form::close() !!}

                    <div id="demoCalendar" class="d-none">
                        <h1 class="text-center m-0">Book a Demo</h1>
                        <div class="calendly-inline-widget" data-url="https://calendly.com/workceo/workceo-demo?hide_event_type_details=1&hide_gdpr_banner=1&primary_color=0661f7" style="min-width:320px;height:630px;"></div>
                        <script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>

                        <div class="f1-buttons m-t-30 text-right">
                            <button type="button" class="btn btn-primary btn-show-tour" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
@elseif(isset($zoho_subscription_details) && ( $zoho_subscription_details['status'] == 'trial' || $zoho_subscription_details['status'] == 'trial_expired' || $planExpire == 'yes'))

    <div class="modal fade bs-modal-md in" id="trialModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">

            <!--Trial Expired-->
            @if($zoho_subscription_details['status'] == 'trial_expired' || $planExpire == 'yes') 
                <div class="modal-body trial-exp">
                    
                    <div class="portlet-body">

                        <div class="row">                         
                            
                            <div class="col-md-12">
                                <h2 class="text-center">Your Free Trial has Expired!</h2>
                                <p>Thank you for signing up for WorkCEO! We hope<br /> you have enjoyed your free trial. To continue using WorkCEO for your field team needs, subscribe to a<br /> paid plan now to resume your account.</p>
                            </div>
                            <div class="col-md-12">
                                <a class="btn btn-block btn-primary" href="{{ route('admin.billing') }}">Select a Plan</a>
                                <p class="cinfo">Contact us to <a href="https://calendly.com/workceo/workceo-demo" target="_blank">Schedule a Demo</a> or call <a href="tel:1-888-340-9675">1-888-340-WORK</a>.
                            </div>
                        </div>       
                        
                    </div>
                </div>
            @elseif($zoho_subscription_details['trial_remaining_days'] == 0) 
                <div class="modal-body">
                    <button class="close btn-close-outside" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30"></button>
                    <div class="portlet-body">

                        <div class="row">                         
                            
                            <div class="col-md-12">
                                <h2 class="text-center" style="font-size: 21px;">Your Free Trial Will Expire Today!</h2>
                                <p>View your <a href="javascript:;" onclick="setupChecklist()">Setup Checklist</a> for Getting Started Guide, Training Videos & More!</p>
                            </div>
                            <div class="col-md-12">
                                <a class="btn btn-block btn-primary" href="{{ route('admin.billing') }}">Select a Plan</a>
                                <p class="cinfo">Contact us to <a href="https://calendly.com/workceo/workceo-demo" target="_blank">Schedule a Demo</a> or call <a href="tel:1-888-340-9675">1-888-340-WORK</a>.
                            </div>
                        </div>       
                        
                    </div>
                </div>
            @else
                    <div class="modal-body">
                        <button class="close btn-close-outside" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30"></button>
                        <div class="portlet-body">

                            <div class="row">                         
                                <div class="col-md-5">
                                    <div class="trial-days text-center">
                                        <h1>{{$zoho_subscription_details['trial_remaining_days']}}</h1>
                                        <h4>{{$zoho_subscription_details['trial_remaining_days'] == 1 ? 'Day' : 'Days'}}</h4>
                                    </div>
                                </div>
                                <div class="col-md-7 p-l-0">
                                    <h2>Your free trial ends in {{$zoho_subscription_details['trial_remaining_days']}} {{$zoho_subscription_details['trial_remaining_days'] == 1 ? 'day' : 'days'}}</h2>
                                    <p>View your <a href="javascript:;" onclick="setupChecklist()">Setup Checklist</a><br /> for Getting Started Guide, Training Videos & More!</p>
                                </div>
                                <div class="col-md-12">
                                    <a class="btn btn-block btn-primary" href="{{ route('admin.billing') }}">Select a Plan</a>
                                    <p class="cinfo">Contact us to <a href="https://calendly.com/workceo/workceo-demo" target="_blank">Schedule a Demo</a> or call <a href="tel:1-888-340-9675">1-888-340-WORK</a>.
                                </div>
                            </div>       
                            
                        </div>
                    </div>
                @endif
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

   
@endif


<!--Checklist Popup-->
<div id="checklistModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
                Loading....
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-modal-md in" id="boardingModal" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
        <div class="modal-dialog modal-md" id="faq-modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
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

@endsection


@push('footer-script')
<script>
var job_block_url = "{{ route('admin.dashboard.job-block',':date_range') }}";
var revenue_block_url = "{{ route('admin.dashboard.revenue-block',':date_range') }}";
var leadboard_block_url = "{{ route('admin.dashboard.leadboard-block',':date_range') }}";
var invoice_block_url = "{{ route('admin.dashboard.invoice-block',':date_range') }}";
var map_block_url = "{{ route('admin.dashboard.map-block',':date_range') }}";

var taskEvents = [
@foreach($leaves as $leave)
{
id: '{{ ucfirst($leave->id) }}',
title: '{{ ucfirst($leave->user->name) }}',
start: '{{ $leave->leave_date }}',
end: '{{ $leave->leave_date }}',
className: 'bg-{{ $leave->type->color }}'
},
@endforeach
];

var getEventDetail = function (id) {
var url = '{{ route('admin.leaves.show', ':id')}}';
url = url.replace(':id', id);

$('#modelHeading').html('Event');
$.ajaxModal('#eventDetailModal', url);
}

var calendarLocale = '{{ $global->locale }}';
var firstDay = '{{ $global->week_start }}';

$('.leave-action').click(function () {
var action = $(this).data('leave-action');
var leaveId = $(this).data('leave-id');
var url = '{{ route("admin.leaves.leaveAction") }}';

$.easyAjax({
type: 'POST',
url: url,
data: {'action': action, 'leaveId': leaveId, '_token': '{{ csrf_token() }}'},
success: function (response) {
    if (response.status == 'success') {
        window.location.reload();
    }
}
});
})

$('.btn-submit').click(function () {
var type = $(this).data('type');
$.easyAjax({
url: '{{route('admin.dashboard.update-company')}}',
container: '#adminSignup',
type: "POST",
data: $('#adminSignup').serialize(),
success: function (response) {
    if(response.status == 'success'){
        if(response.status == 'success'){
            if(type == 'complete')
            {
                $('#adminSignupModal').modal('hide');
                showTour();
            } else {
                $('#adminSignup').hide();
                $('#demoCalendar').removeClass('d-none');
            }

        }
    }
}
})
});


</script>


{{--
<script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>
--}}

<script src="{{ asset('plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
{{--<script src="{{ asset('plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>--}}

<!-- jQuery for carousel -->
{{--<script src="{{ asset('plugins/bower_components/owl.carousel/owl.carousel.min.js') }}"></script>--}}
{{--<script src="{{ asset('plugins/bower_components/owl.carousel/owl.custom.js') }}"></script>--}}
<script src="{{ asset('themes/wceo/assets/js/owlcarousel/owl.carousel.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/owlcarousel/owl-custom.js') }}"></script>

<!--weather icon -->
<script src="{{ asset('plugins/bower_components/skycons/skycons.js') }}"></script>

{{--<script src="{{ asset('plugins/bower_components/calendar/jquery-ui.min.js') }}"></script>--}}
{{--<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>--}}

{{--<script src="{{ asset('plugins/bower_components/calendar/dist/fullcalendar.min.js') }}"></script>--}}
{{--<script src="{{ asset('plugins/bower_components/calendar/dist/jquery.fullcalendar.js') }}"></script>--}}


{{--<script src="{{ asset('themes/wceo/assets/js/jquery.ui.min.js') }}"></script>--}}
<script src="{{ asset('themes/wceo/assets/js/calendar/moment.min.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/calendar/fullcalendar.min.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/calendar/fullcalendar-custom.js') }}"></script>


{{--<script src="{{ asset('plugins/bower_components/calendar/dist/locale-all.js') }}"></script>--}}
{{--<script src="{{ asset('js/event-calendar.js') }}"></script>--}}
{{--<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>--}}

<script src="{{ asset('themes/wceo/assets/js/chart/chartist/chartist.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/morris-chart/raphael.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/morris-chart/morris.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/morris-chart/prettify.min.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/chartjs/chart.min.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/flot-chart/excanvas.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/flot-chart/jquery.flot.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/flot-chart/jquery.flot.time.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/flot-chart/jquery.flot.categories.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/flot-chart/jquery.flot.stack.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/flot-chart/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/flot-chart/jquery.flot.symbol.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/google/google-chart-loader.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/peity-chart/peity.jquery.js') }}"></script>


<script src="{{ asset('themes/wceo/assets/js/prism/prism.min.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/clipboard/clipboard.min.js') }}"></script>
{{--<script src="{{ asset('themes/wceo/assets/js/counter/jquery.waypoints.min.js') }}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/counter/jquery.counterup.min.js') }}"></script>--}}
{{--<script src="{{ asset('themes/wceo/assets/js/counter/counter-custom.js') }}"></script>--}}
<script src="{{ asset('themes/wceo/assets/js/custom-card/custom-card.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/form-wizard/form-wizard-three.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/tour/intro.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/tour/intro-init.js')}}"></script>

{{--<script src="{{ asset('themes/wceo/assets/js/dashboard/project-custom.js') }}"></script>--}}

<script src="{{ asset('themes/wceo/assets/js/script.js') }}"></script>
{{--<script src="{{ asset('themes/wceo/assets/js/theme-customizer/customizer.js') }}"></script>--}}
<script>



$(window).scroll(startCounter);
function startCounter()
{
if ($(window).scrollTop() > 350)
{
$(window).off("scroll", startCounter);
$('.counteNumer').each(function () {
var $this = $(this);
jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
    duration: 2000,
    easing: 'swing',
    step: function () {
    $this.text(Math.ceil(this.Counter));
    }
});
});
}
}

@if(in_array('payments',$modules)  && in_array('recent_earnings',$activeWidgets))
$(document).ready(function () {
var chartData = {!!  $chartData !!};
/*var chartData = [{"date": "2020-03-15", "total": 6194}, {
"date": "2020-03-17",
"total": 4988
}, {"date": "2020-03-18", "total": 9000}, {"date": "2020-03-19", "total": 6165}, {
"date": "2020-03-23",
"total": 5296
}, {"date": "2020-03-25", "total": 9558}, {"date": "2020-03-29", "total": 4617}, {
"date": "2020-03-30",
"total": 4647
}, {"date": "2020-04-01", "total": 3522}, {"date": "2020-04-07", "total": 4811}, {
"date": "2020-04-16",
"total": 4484
}, {"date": "2020-04-17", "total": 6752}, {"date": "2020-04-18", "total": 5844}, {
"date": "2020-04-19",
"total": 5291
}, {"date": "2020-04-20", "total": 2839}, {"date": "2020-04-23", "total": 4335}, {
"date": "2020-04-25",
"total": 6190
}, {"date": "2020-04-27", "total": 1512}, {"date": "2020-04-28", "total": 5422}, {
"date": "2020-05-02",
"total": 1249
}, {"date": "2020-05-05", "total": 7464}, {"date": "2020-05-06", "total": 10086}, {
"date": "2020-05-07",
"total": 5584
}, {"date": "2020-05-08", "total": 5147}, {"date": "2020-05-09", "total": 4732}];
*/
/* var chartData2 = [{"date": "2020-03-15", "total": 6194}, {
"date": "2020-03-17",
"total": 4988
}, {"date": "2020-03-18", "total": 2000}, {"date": "2020-03-19", "total": 6165}, {
"date": "2020-03-23",
"total": 5296
}];*/



function barChart() {

Morris.Bar({
    element: 'morris-area-chart',
    data: chartData,
    xkey: 'date',
    ykeys: ['total'],
    labels: ['Earning'],
    pointSize: 3,
    fillOpacity: 0,
    pointStrokeColors: ['#e20b0b'],
    behaveLikeLine: true,
    gridLineColor: '#e0e0e0',
    lineWidth: 2,
    hideHover: 'auto',
    lineColors: ['#e20b0b'],
    resize: true

});



}

@if(in_array('payments',$modules))
barChart();
@endif

// $(".counter").counterUp({
//     delay: 100,
//     time: 1200
// });

$('.vcarousel').carousel({
interval: 3000
})
/*$('.vcarousel').owlCarousel({
loop:true
})*/

var icons = new Skycons({"color": "#ffffff"}),
list = [
    "clear-day", "clear-night", "partly-cloudy-day",
    "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
    "fog"
],
i;
for (i = list.length; i--;) {
var weatherType = list[i],
    elements = document.getElementsByClassName(weatherType);
for (e = elements.length; e--;) {
    icons.set(elements[e], weatherType);
}
}
icons.play();


})
@endif
$('.show-task-detail').click(function () {
$(".right-sidebar").slideDown(50).addClass("shw-rside");

var id = $(this).data('task-id');
var url = "{{ route('admin.all-tasks.show',':id') }}";
url = url.replace(':id', id);

$.easyAjax({
type: 'GET',
url: url,
success: function (response) {
    if (response.status == "success") {
        $('#right_side_bar').css({"right": "2px", "z-index": "999"});
        $('#right-sidebar-content').html(response.view);
    }
}
});
})

$('#save-form').click(function () {
$.easyAjax({
url: '{{route('admin.dashboard.widget')}}',
container: '#createProject',
type: "POST",
redirect: true,
data: $('#createProject').serialize()
})
});

</script>

<script>
var map2, geocoder;
// Initialize and add the map
function initMap() {

map2 = new google.maps.Map(
document.getElementById('map'), {center: {lat: -34.397, lng: 150.644},zoom: 10,
fullscreenControl: false,
streetViewControl: false,
mapTypeControl: false,
});
geocoder = new google.maps.Geocoder();
// The marker, positioned at Uluru
//  var marker = new google.maps.Marker({position: uluru, map: map});

}

</script>
<script>
$('.dashboard-settings input[type="checkbox"]').click(function(){
$('.dashboard-settings').addClass('show');
});

$('.closeBtn').click(function(){
$('.dashboard-settings').removeClass('show');
})
</script>

<!-- <script defer
src="https://maps.googleapis.com/maps/api/js?key={{ Config::get('app.google_map_key') }}&callback=initMap&libraries=places&v=weekly">
</script> -->

<script
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsLN7tz9Ww5Lt2hDS4KqaBrb8clNSwdkQ&callback=initMap&libraries=places&v=weekly"
async
></script>

<script src="{{ asset('js/admin/dashboard_script.js') }}"></script>
<script>
$(".mobile-toggle").click(function(){
if($(".nav-menus").hasClass('open')){
$('#right_side_bar').removeClass('show');
$('.form-control-plaintext').removeClass('open');
}
$(".nav-menus").toggleClass("open");
});
</script>



@if(empty($global->company_phone))
<script src="{{ asset('js/admin/map_address_autocomplete.js') }}"></script>
<script>
$(window).on('load', function() {
initMapAutoComplete();
$('#adminSignupModal').modal({
show:true,
backdrop: 'static',
keyboard: false,
});

$('.hide-search').select2({
minimumResultsForSearch: -1
});
});

var checkbox = '.feature-box .btn.btn-light input[type="checkbox"]';
$('#features').val('');
$('#adminSignupModal input[type=checkbox]').prop("checked", false);
$(checkbox).on("change",function ()
{
var feature ="";
$(checkbox+':checked').each(function()
{
feature += $(this).val()+",";
});
$('#features').val(feature.substring(0, feature.length - 1));
});
</script>


@endif


<!-- dashboars action popup-->
@if($company->dashboard_action_popup_status == 'active')
    <div id="dashboardActionPopupModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <button class="close btn-close-outside" type="button" data-dismiss="modal" aria-label="Close"><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30" /></button>
                <div class="modal-content">
                    <div class="modal-body ">
                        
                        <div class="popup-wrap">
                            <div class="popup-main">
                                <div class="logoWrap">
                                        <img src="{{ asset('img/wrkceo-logo.png') }}" alt="">
                                 </div>

                                    <div class="popbox-wrap">
                                        <div class="popup-box">
                                            <div class="poopup-head">
                                                <div class="titletxt"> Team</div>
                                                    <div class="srch-rgt">
                                                        <span class="counter">{{ $counts->totalEmployees }}</span>
                                                        <a href="{{ route('admin.employees.index') }}" class="srch-item">
                                                            <i class="fa fa-search"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="popup-mid">
                                                    <img src="{{ asset('img/TrackTeam.png') }}" alt="">
                                                </div>
                                                <div class="blue-btn-btm">
                                                    <a href="{{ route('admin.map.index') }}">Track Team</a>
                                                </div>
                                        </div>

                                        <div class="popup-box">
                                            <div class="poopup-head">
                                                <div class="titletxt"> Clients</div>
                                                    <div class="srch-rgt">
                                                        <span class="counter">{{ $counts->totalClients }}</span>
                                                        <a href="{{ route('admin.clients.index') }}" class="srch-item">
                                                        <i class="fa fa-search"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            <div class="popup-mid">
                                                <img src="{{ asset('img/AddClient.png') }}" alt="">
                                            </div>
                                            <div class="blue-btn-btm">
                                            <a href="{{ route('admin.clients.create') }}">@lang('modules.client.addClient') </a>
                                                
                                            </div>
                                        </div>

                                        <div class="popup-box">
                                            <div class="poopup-head">
                                                <div class="titletxt"> Jobs</div>
                                                <div class="srch-rgt">
                                                    <span class="counter">{{ $counts->totalProjects }}</span>
                                                    <a href="{{ route('admin.projects.index') }}" class="srch-item">
                                                        <i class="fa fa-search"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="popup-mid">
                                                <img src="{{ asset('img/ScheduleJob.png') }}" alt="">
                                            </div>
                                            <div class="blue-btn-btm">
                                                <a href="{{ route('admin.projects.create') }}">Schedule Job</a>
                                            </div>
                                        </div>
                                    </div>
                                <div class="popup-footer">
                             <p>You can disable this popup in <a href="{{ route('admin.settings.index') }}">Account Settings</a></p>
                        </div>
                    </div>
                </div>
        
                </div>
            </div>
            
        </div>
    </div>
@endif
@if($company->dashboard_action_popup_status == 'active')
<script>  

   
    $(window).on('load', function() {
        $('#dashboardActionPopupModal').modal('show');
        setTimeout(function() {
            $("#dashboardActionPopupModal").modal('hide');
        }, duration);
    });
    
    


</script>
@endif

<script>

//Checklist Manage

function setupChecklist()
{
    var url = '{{ route('admin.dashboard.checklist-setup')}}';
    $.ajaxModal('#checklistModal', url); 
}

$('.btn-show-tour').click(function(){
    showTour();
});


function viewChecklistDetails(id) 
{        
    var url = '{{ route('admin.dashboard.onboarding-view', ':id')}}';
    url = url.replace(':id', id);
    $("#boardingModal").modal({
        backdrop: 'static',
        keyboard: false
    });
    $.ajaxModal('#boardingModal', url);    
    $('#checklistModal').modal('hide');
    $('.modal-backdrop.show').css('opacity', '.7');
    
}







      /* if (introj._currentStep === 2) {
      alert(1);
      $('.introjs-helperLayer').addClass('remove-bg');
    if (addEntityForm.controls.taxId.value !== '') {
      $('.introjs-nextbutton').removeClass('introjs-disabled');
      $('.introjs-nextbutton').get(0).onclick = original_onclick;
    } else {
      $('.introjs-nextbutton').addClass('introjs-disabled');
      $('.introjs-nextbutton').get(0).onclick = null;
    }
  } */
      
</script>

@if(isset($zoho_subscription_details) && ( $zoho_subscription_details['status'] == 'trial' || $zoho_subscription_details['status'] == 'trial_expired' || $planExpire == 'yes'))
<script>
$('#trialModal').modal({
    show:true,
    backdrop: 'static',
    keyboard: false,
});
</script>
@endif

@endpush

