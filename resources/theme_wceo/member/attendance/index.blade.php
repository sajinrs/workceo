@extends('layouts.member-app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/daterange-picker.css') }}">
@endpush

@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->

            <div class="col-sm-12">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            
                            <div class="card-body">
                                <div class="row">
                                @if($user->can('add_attendance'))
                                    <div class="sttabs tabs-style-line col-md-12">
                                        <div class="white-box">
                                            <nav>
                                                <ul>
                                                    <li><a href="{{ route('member.attendances.summary') }}"><span>@lang('app.summary')</span></a>
                                                    </li>
                                                    <li class="tab-current"><a href="{{ route('member.attendances.index') }}"><span>@lang('modules.attendance.attendanceByMember')</span></a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>                                       
                                    @endif

                                    <div class="col-md-3">
                                        <label class="control-label">@lang('app.selectDateRange')</label>

                                        <div class="form-group">
                                            <input class="form-control input-daterange-datepicker" type="text" name="daterange"
                                                value="{{ $startDate->format($global->date_format) }}"/>
                                        </div>
                                    </div>

                                    @if($user->can('view_attendance'))
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">@lang('modules.timeLogs.employeeName')</label>
                                            <select class="select2 form-control" data-placeholder="Choose Employee" id="user_id" name="user_id">
                                                @foreach($employees as $employee)
                                                    <option @if($userId == $employee->id) selected @endif value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group m-t-30">
                                            <button type="button" id="apply-filter" class="btn btn-primary btn-sm">@lang('app.apply')</button>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="browser-widget bw-project">
                                    <div class="media">
                                        <div class="media-body align-self-center count_by_status">

                                            <div>
                                                <p>@lang('modules.attendance.totalWorkingDays')</p>
                                                <h4><span class="counter" id="totalWorkingDays">{{$totalWorkingDays}}</span></h4>
                                            </div>
                                            <div>
                                                <p>@lang('modules.attendance.daysPresent')</p>
                                                <h4><span class="counter" id="daysPresent">{{$daysPresent}}</span></h4>
                                            </div>
                                            <div>
                                                <p>@lang('app.days') @lang('modules.attendance.late')</p>
                                                <h4><span class="counter" id="daysLate">{{ $daysLate }}</span></h4>
                                            </div>
                                            <div>
                                                <p>@lang('modules.attendance.halfDay')</p>
                                                <h4><span class="counter" id="halfDays">{{ $halfDays }}</span></h4>
                                            </div>
                                            <div>
                                                <p>@lang('app.days') @lang('modules.attendance.absent')</p>
                                                <h4><span class="counter" id="absentDays">{{ (($totalWorkingDays - $daysPresent) < 0) ? '0' : ($totalWorkingDays - $daysPresent) }}</span> </h4>
                                            </div>
                                            <div>
                                                <p>@lang('modules.attendance.holidays')</p>
                                                <h4><span class="counter" id="holidayDays">{{ $holidays }}</span></h4>
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
    </div>
    <!-- .row -->

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>@lang('app.date')</th>
                                <th>@lang('app.status')</th>
                                <th>@lang('modules.attendance.clock_in')</th>
                                <th>@lang('modules.attendance.clock_out')</th>
                                <th>@lang('app.others')</th>
                            </tr>
                            </thead>
                            <tbody id="attendanceData"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

@endsection

@push('footer-script')


    <script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/daterangepicker.js')}}"></script>

    <script>
    var startDate = '{{ $startDate->format('Y-m-d') }}';
    var endDate = '{{ $endDate->format('Y-m-d') }}';

    $('.input-daterange-datepicker').daterangepicker({
        buttonClasses: ['btn', 'btn-sm'],
        cancelClass: 'btn-inverse',
        "locale": {
            "applyLabel": "{{ __('app.apply') }}",
            "cancelLabel": "{{ __('app.cancel') }}",
            "daysOfWeek": [
                "{{ __('app.su') }}",
                "{{ __('app.mo') }}",
                "{{ __('app.tu') }}",
                "{{ __('app.we') }}",
                "{{ __('app.th') }}",
                "{{ __('app.fr') }}",
                "{{ __('app.sa') }}"
            ],
            "monthNames": [
                "{{ __('app.january') }}",
                "{{ __('app.february') }}",
                "{{ __('app.march') }}",
                "{{ __('app.april') }}",
                "{{ __('app.may') }}",
                "{{ __('app.june') }}",
                "{{ __('app.july') }}",
                "{{ __('app.august') }}",
                "{{ __('app.september') }}",
                "{{ __('app.october') }}",
                "{{ __('app.november') }}",
                "{{ __('app.december') }}",
            ],
            "firstDay": {{ $global->week_start }},
        }
    })

    $('.input-daterange-datepicker').on('apply.daterangepicker', function (ev, picker) {
        startDate = picker.startDate.format('YYYY-MM-DD');
        endDate = picker.endDate.format('YYYY-MM-DD');
        showTable();
    });

    $('#apply-filter').click(function () {
       showTable();
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    var table;

    function showTable() {
        var userId = $('#user_id').val();
        if (typeof userId === 'undefined') {
            userId = '{{ $userId}}';
        }


        //refresh counts
        var url = '{!!  route('member.attendances.refreshCount', [':startDate', ':endDate', ':userId']) !!}';
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':userId', userId);

        $.easyAjax({
            type: 'GET',
            url: url,
            success: function (response) {
                $('#daysPresent').html(response.daysPresent);
                $('#daysLate').html(response.daysLate);
                $('#halfDays').html(response.halfDays);
                $('#totalWorkingDays').html(response.totalWorkingDays);
                $('#absentDays').html(response.absentDays);
                $('#holidayDays').html(response.holidays);
                initConter();
            }
        });

        //refresh datatable
        var url2 = '{!!  route('member.attendances.employeeData', [':startDate', ':endDate', ':userId']) !!}';

        url2 = url2.replace(':startDate', startDate);
        url2 = url2.replace(':endDate', endDate);
        url2 = url2.replace(':userId', userId);

        $.easyAjax({
            type: 'GET',
            url: url2,
            success: function (response) {
                $('#attendanceData').html(response.data);
            }
        });
    }

    $('#attendanceData').on('click', '.delete-attendance', function(){
        var id = $(this).data('attendance-id');
        swal({

            title: "Are you sure?",
            text: "You will not be able to recover the deleted attendance record!",
            icon: "warning",
            buttons: ["No, cancel please!", "Yes, delete it!"],
            dangerMode: true
    })
    .then((willDelete) => {
            if (willDelete) {

                var url = "{{ route('member.attendances.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            showTable();
                        }
                    }
                });
            }
        });
    });    


    function initConter() {
        $(".counter").counterUp({
            delay: 100,
            time: 1200
        });
    }

    showTable();

</script>
<script>
    $('#clock-in').click(function () {
        var workingFrom = $('#working_from').val();

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            url: '{{route('member.attendances.store')}}',
            type: "POST",
            data: {
                working_from: workingFrom,
                _token: token
            },
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    })

    @if(!is_null($currenntClockIn))
    $('#clock-out').click(function () {

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            url: '{{route('member.attendances.update', $currenntClockIn->id)}}',
            type: "PUT",
            data: {
                _token: token
            },
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    })
    @endif

</script>
@endpush