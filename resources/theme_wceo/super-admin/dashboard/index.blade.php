@extends('layouts.super-admin')


@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/calendar/dist/fullcalendar.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}"><!--Owl carousel CSS -->
    <link rel="stylesheet"
          href="{{ asset('plugins/bower_components/owl.carousel/owl.carousel.min.css') }}"><!--Owl carousel CSS -->
    <link rel="stylesheet"
          href="{{ asset('plugins/bower_components/owl.carousel/owl.theme.default.css') }}"><!--Owl carousel CSS -->

    <style>
        .col-in {
            padding: 0 20px !important;

        }

        .fc-event {
            font-size: 10px !important;
        }

    </style>
@endpush

@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href=""><i class="icofont icofont-home"></i></a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8 xl-100">
                <div class="row wceo-dash-wigets">
                    <div class="col-sm-6 col-xl-3 col-lg-6">
                        <div class="card o-hidden">
                            <div class=" b-r-4 card-body">
                                <div class="media static-top-widget">
                                    <div class="bg-primary dash-wiget-icon align-self-center text-center"><i
                                                data-feather="layers"></i></div>
                                    <div class="media-body"><span
                                                class="m-0">@lang('modules.dashboard.totalCompanies')</span>
                                        <h4 class="mb-0 counter">{{ $totalCompanies }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3 col-lg-6">
                        <div class="card o-hidden">
                            <div class=" b-r-4 card-body">
                                <div class="media static-top-widget">
                                    <div class="dash-wiget-bg2 dash-wiget-icon align-self-center text-center"><i
                                                data-feather="layers"></i></div>
                                    <div class="media-body"><span
                                                class="m-0">@lang('app.total') @lang('app.menu.packages')</span>
                                        <h4 class="mb-0 counter">{{ $totalPackages }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3 col-lg-6">
                        <div class="card o-hidden">
                            <div class=" b-r-4 card-body">
                                <div class="media static-top-widget">
                                    <div class="dash-wiget-bg3 dash-wiget-icon align-self-center text-center"><i
                                                data-feather="layers"></i></div>
                                    <div class="media-body"><span
                                                class="m-0">@lang('modules.dashboard.activeCompanies')</span>
                                        <h4 class="mb-0 counter">{{ $activeCompanies }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3 col-lg-6">
                        <div class="card o-hidden">
                            <div class=" b-r-4 card-body">
                                <div class="media static-top-widget">
                                    <div class="dash-wiget-bg4 dash-wiget-icon align-self-center text-center"><i
                                                data-feather="layers"></i></div>
                                    <div class="media-body"><span
                                                class="m-0">@lang('modules.dashboard.inactiveCompanies')</span>
                                        <h4 class="mb-0 counter">{{ $inactiveCompanies }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--<div class="col-sm-6 col-xl-3 col-lg-6">--}}
                    {{--<div class="card o-hidden">--}}
                    {{--<div class=" b-r-4 card-body">--}}
                    {{--<div class="media static-top-widget">--}}
                    {{--<div class="bg-primary dash-wiget-icon align-self-center text-center"><i data-feather="layers"></i></div>--}}
                    {{--<div class="media-body"><span class="m-0">@lang('modules.dashboard.licenseExpired')</span>--}}
                    {{--<h4 class="mb-0 counter">{{ $expiredCompanies }}</h4>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}


                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Earnings</h5>

                                <div class="card-header-right">
                                    <ul class="list-unstyled card-option">
                                        <li><i class="icofont icofont-simple-left"></i></li>
                                        <li><i class="icofont icofont-maximize full-card"></i></li>
                                        <li><i class="icofont icofont-minus minimize-card"></i></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="morris-area-chart" style="height: 340px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>@lang('modules.superadmin.recentRegisteredCompanies')</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">@lang('app.name')</th>
                                <th scope="col">@lang('app.email')</th>
                                <th scope="col">@lang('app.menu.packages')</th>
                                <th scope="col">@lang('app.date')</th>

                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentRegisteredCompanies as $key => $recent)
                                <tr>
                                    <td scope="row">{{ $key + 1 }} </td>
                                    <td>{{ $recent->company_name }} </td>
                                    <td>{{ $recent->company_email }} </td>
                                    <td>{{ ucwords($recent->package->name) }} ({{ ucwords($recent->package_type) }})
                                    </td>
                                    <td>{{ $recent->created_at->format('M j, Y') }} </td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td scope="row" colspan="4">@lang('messages.noRecordFound')</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>@lang('modules.superadmin.recentSubscriptions')</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">@lang('modules.client.companyName')</th>
                                <th scope="col">@lang('app.menu.packages')</th>
                                <th scope="col">@lang('app.date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentSubscriptions as $key => $recent)
                                <tr>
                                    <td scope="row">{{ $key + 1 }} </td>
                                    <td>{{ $recent->company_name }} </td>
                                    <td>{{ ucwords($recent->name) }} ({{ ucwords($recent->package_type) }})</td>
                                    <td>{{ \Carbon\Carbon::parse($recent->paid_on)->format('M j, Y') }} </td>
                                </tr>
                            @empty
                                <tr>
                                    <td scope="row">@lang('messages.noRecordFound')</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>@lang('modules.superadmin.recentLicenseExpiredCompanies')</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">@lang('modules.client.companyName')</th>
                                <th scope="col">@lang('app.menu.packages')</th>
                                <th scope="col">@lang('app.date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentExpired as $key => $recent)
                                <tr>
                                    <td scope="row">{{ $key + 1 }} </td>
                                    <td>{{ $recent->company_name }} </td>
                                    <td>{{ ucwords($recent->package->name) }} ({{ ucwords($recent->package_type) }})
                                    </td>
                                    <td>{{ $recent->updated_at->format('M j, Y') }} </td>
                                </tr>
                            @empty
                                <tr>
                                    <td scope="row" colspan="4">@lang('messages.noRecordFound')</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    <div class="clearfix"></div>
@endsection


@push('footer-script')

    <script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>

    <script src="{{ asset('plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>

    <!-- jQuery for carousel -->
    <script src="{{ asset('plugins/bower_components/owl.carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/owl.carousel/owl.custom.js') }}"></script>

    <!--weather icon -->
    <script src="{{ asset('plugins/bower_components/skycons/skycons.js') }}"></script>

    <script src="{{ asset('plugins/bower_components/calendar/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>

    <script>
        $(document).ready(function () {
            var chartData = {!!  $chartData !!};
            var chartData = [{"month": "Jan", "amount": 120}, {"month": "Feb", "amount": 40}, {
                "month": "Mar",
                "amount": 80
            }, {"month": "Apr", "amount": 10}, {"month": "May", "amount": 18}, {
                "month": "Jun",
                "amount": 100
            }, {"month": "Jul", "amount": 80}, {"month": "Aug", "amount": 50}, {
                "month": "Sep",
                "amount": 50
            }, {"month": "Oct", "amount": 68}, {"month": "Nov", "amount": 89}, {"month": "Dec", "amount": 20}];
            ;
            Morris.Bar({
                element: 'morris-area-chart',
                data: chartData,
                lineColors: ['#01c0c8'],
                xkey: ['month'],
                ykeys: ['amount'],
                labels: ['Earning'],
                pointSize: 0,
                lineWidth: 0,
                resize: true,
                fillOpacity: 0.8,
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                hideHover: 'auto',
                parseTime: false
            });

            $('.vcarousel').carousel({
                interval: 3000
            })
        });

        $(".counter").counterUp({
            delay: 100,
            time: 1200
        });

    </script>
    <script>
        @if(\Froiden\Envato\Functions\EnvatoUpdate::showReview())
        $(document).ready(function () {
            $('#reviewModal').modal('show');
        })

        function hideReviewModal(type) {
            var url = "{{ route('hide-review-modal',':type') }}";
            url = url.replace(':type', type);

            $.easyAjax({
                url: url,
                type: "GET",
                container: "#reviewModal",
            });
        }
        @endif
    </script>
@endpush
