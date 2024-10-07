@extends('layouts.client-app')

@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                {{--<div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                       
                    </div>
                </div>--}}
                

            </div>
        </div>
    </div>

    <div class="container-fluid">
                                   
        <div class="row">

        <div class="col-xl-3 m-b-20">
                
                <div class="m-t-20">
                    <div class="media">
                    <img class="rounded-circle image-radius companyLogo m-r-15" src="{{ $global->logo_url }}" alt="{{ ucwords($user->name) }}">

                    {{--<div class="media-body m-t-0">
                        <h4 class="mb-0 f-w-500">{{ ucwords($user->name) }}</h4>
                        <p class="text-dark m-b-5">{{$user->email }} <br />
                        {{$user->mobile }}</p>                       

                        <a title="Settings" href="{{ route('client.profile.index') }}" class="btn btn-primary setingBtn"><i class="fa fa-cog"></i></a>
                        
                    </div>--}}
                    </div>
                        
                        
                </div>
            </div>

            <div class="col-xl-9 m-b-20 companyInfo">
                <div class="row">
                    <div class="col-md-4">
                        <i class="fas fa-phone-square-alt"></i>
                        <h4>PHONE <br /> <span>{{ $global->company_phone }}</span></h4>                        
                    </div>
                    <div class="col-md-4">
                        <i class="fas fa-envelope-open-text"></i>
                        <h4>EMAIL <br /> <span>{{ $global->company_email }}</span></h4>
                    </div>
                    <div class="col-md-4">
                    <i class="fa fa-globe"></i>
                        <h4>WEBSITE <br /> <span>{{ $global->website }}</span></h4>
                    </div>
                </div>

                <div class="row wceo-dash-wigets empWidget">

                    @if(in_array('projects',$modules))
                    <div class="col-sm-6 col-xl-4 col-lg-6">
                        <a href="{{ route('client.projects.index') }}" data-original-title="" title="">
                            <div class="card o-hidden">
                                <div class=" b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="bg-primary dash-wiget-icon align-self-center text-center"><i data-feather="layers"></i></div>
                                        <div class="media-body"><span class="m-0">@lang('modules.dashboard.totalProjects')</span>
                                            <h4 class="mb-0 counteNumer">{{ $counts->totalProjects }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif

                    @if(in_array('tickets',$modules))
                    {{--<div class="col-sm-6 col-xl-3 col-lg-6">
                        <a href="{{ route('client.tickets.index') }}">
                            <div class="card o-hidden">
                                <div class=" b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="bg-primary dash-wiget-icon align-self-center text-center"><i data-feather="alert-circle"></i></div>
                                        <div class="media-body"><span class="m-0"> @lang('modules.tickets.totalUnresolvedTickets')</span>
                                            <h4 class="mb-0">{{ $counts->totalUnResolvedTickets }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>--}}
                    @endif

                    @if(in_array('invoices',$modules))
                    <div class="col-sm-6 col-xl-4 col-lg-6">
                        <a href="{{ route('client.invoices.index') }}">
                            <div class="card o-hidden">
                                <div class=" b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="bg-primary dash-wiget-icon align-self-center text-center"><i data-feather="check-circle"></i></div>
                                        <div class="media-body"><span class="m-0">@lang('modules.dashboard.totalPaidAmount')</span>
                                            <h4 class="mb-0 counteNumer">{{ currencyFormat($counts->totalPaidAmount) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-xl-4 col-lg-6">
                        <a href="{{ route('client.invoices.index') }}">
                            <div class="card o-hidden">
                                <div class=" b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="bg-primary dash-wiget-icon align-self-center text-center"><i data-feather="dollar-sign"></i></div>
                                        <div class="media-body"><span class="m-0">@lang('modules.dashboard.totalOutstandingAmount')</span>
                                            <h4 class="mb-0 counteNumer">{{ currencyFormat($counts->totalUnpaidAmount) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif               
                                            
                </div>
            </div>

            
        </div>
        
        
        <!-- Leaves fullcalendar-->
        
        <!-- Overdue Tasks , Pending FollowUp -->
        <div class="row">
                            
            @if(in_array('projects',$modules))
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h5>@lang("modules.dashboard.projectActivityTimeline")</h5></div>

                        <div class="card-body slimscroll">

                            <div class="timeline-small projActivity renewtimeLine ">
                                @forelse($projectActivities as $activity)
                                <div class="media">
                                    <div class="timeline-round m-r-30 timeline-line-1 bg-primary"></div>
                                    <div class="media-body">
                                        <h6><a href="{{ route('client.projects.show', $activity->project_id) }}" class="text-danger">{{ ucwords($activity->project_name) }}
                                        </a> <span class="pull-right f-12">{{ $activity->created_at->timezone($global->timezone)->diffForHumans() }}</span></h6>
                                        <span class="sl-date"> {{ $activity->activity }}</span><br>
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
        </div>
    </div>
@endsection




