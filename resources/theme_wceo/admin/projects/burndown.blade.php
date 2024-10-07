@extends('layouts.app')


@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/daterange-picker.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} #{{ $project->id }} - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">Burndown Chart</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @include('admin.projects.show_project_menu')

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Burndown Chart</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="" id="filter-form" class="m-b-20">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input-daterange input-group" id="date-range">
                                                <input type="text" class="form-control" id="start-date" placeholder="@lang('app.startDate')"
                                                       value="{{ $startDate }}"/>
                                                <span class="input-group-addon bg-info b-0 text-white">@lang('app.to')</span>
                                                <input type="text" class="form-control" id="end-date" placeholder="@lang('app.endDate')"
                                                       value="{{ $endDate }}"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label col-xs-12">&nbsp;</label>
                                                <button type="button" id="apply-filters" class="btn btn-primary col-md-6">@lang('app.apply')</button>
                                                <button type="button" id="reset-filters" class="btn btn-secondary col-md-5 col-md-offset-1"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>


                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12" id="task-list-panel">
                                <div><canvas id="burndown43"></canvas></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>


    
@endsection

@push('footer-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/daterangepicker.js')}}"></script>
<script>
    /**
     * Sum elements of an array up to the index provided.
     */

    function showBurnDown(elementId, burndownData, scopeChange = [], dates) {

        var speedCanvas = document.getElementById(elementId);

        Chart.defaults.global.defaultFontFamily = "Arial";
        Chart.defaults.global.defaultFontSize = 14;

        var speedData = {
            labels: JSON.parse(dates),
            datasets: [
                {
                    label: "@lang('modules.burndown.actual')",
                    borderColor: "#6C8893",
                    backgroundColor: "#6C8893",
                    lineTension: 0,
                    borderDash: [5, 5],
                    fill: false,
                    data: scopeChange,
                    steppedLine: true
                },
                {
                    label: "@lang('modules.burndown.ideal')",
                    data: burndownData,
                    fill: false,
                    borderColor: "#ccc",
                    backgroundColor: "#ccc",
                    lineTension: 0,
                },
            ]
        };

        var chartOptions = {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    boxWidth: 80,
                    fontColor: 'black'
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        max: Math.round(burndownData[0] * 2)
                    }
                }]
            },
            responsive: true
        };

        var lineChart = new Chart(speedCanvas, {
            type: 'line',
            data: speedData,
            options: chartOptions
        });

    }
</script>


<script>
    jQuery('#start-date, #end-date').daterangepicker({
            locale: {
                format: '{{ strtoupper($global->date_picker_format) }}'
            },
            "alwaysShowCalendars": true,
            autoApply: true,
            autoUpdateInput: false,
            language: '{{ $global->locale }}',
            weekStart:'{{ $global->week_start }}',
        }, function(start, end, label) {
            var selectedStartDate = start.format('DD-MM-YYYY'); // selected start
            var selectedEndDate = end.format('DD-MM-YYYY'); // selected end

            $startDateInput = $('#start-date');
            $endDateInput = $('#end-date');

            // Updating Fields with selected dates
            $startDateInput.val(selectedStartDate);
            $endDateInput.val(selectedEndDate);
            var endDatePicker = $endDateInput.data('daterangepicker');
            endDatePicker.setStartDate(selectedStartDate);
            endDatePicker.setEndDate(selectedEndDate);
            var startDatePicker = $startDateInput.data('daterangepicker');
            startDatePicker.setStartDate(selectedStartDate);
            startDatePicker.setEndDate(selectedEndDate);

        });

    
    function loadChart(){
        var startDate = $('#start-date').val();
        if (startDate == '') { startDate = null; }

        var endDate = $('#end-date').val();
        if (endDate == '') { endDate = null; }

        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: '{{route('admin.projects.burndown-chart', [$project->id])}}',
            container: '#section-line-3',
            type: "GET",
            redirect: false,
            data: {'_token': token, startDate: startDate, endDate: endDate},
            success: function (data) {
                showBurnDown ("burndown43", JSON.parse(data.deadlineTasks), JSON.parse(data.uncompletedTasks), data.datesArray);
            }
        });
    }

    $('#apply-filters').click(function () {
        loadChart();
    });

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
        $('#filter-form').find('select').select2();
        loadChart();
    });
    loadChart();

    $('ul.showProjectTabs .burndownChart .nav-link').addClass('active');
</script>
@endpush

