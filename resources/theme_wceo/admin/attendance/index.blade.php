@extends('layouts.app')



@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="{{ route('admin.attendances.create') }}" class="btn btn-primary btn-sm">@lang('modules.attendance.markAttendance') <i data-feather="plus"></i> </a>

                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(15)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>
                    </div>
                </div>
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>

    <div class="container-fluid product-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs border-tab nav-primary" id="info-tab" role="tablist">
                            <li class="nav-item"><a class="nav-link" id="info-summary-tab" href="{{ route('admin.attendances.summary') }}" aria-selected="true">@lang('app.summary')</a></li>

                            <li class="nav-item"><a class="nav-link active" href="{{ route('admin.attendances.index') }}">@lang('modules.attendance.attendanceByMember')</a></li>

                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.attendances.attendanceByDate') }}">@lang('modules.attendance.attendanceByDate')</a></li>
                        </ul>
                        <div class="tab-content" id="info-tabContent">
                            <div class="tab-pane fade show active" id="info-summary" role="tabpanel" aria-labelledby="info-summary-tab">
                                <div class="form-body">
                                    <div class="row">                                
                                        
                                        <div class="col-md-4">
                                            <div class="form-group">   
                                            <label class="control-label">@lang('app.selectDateRange')</label> 
                                            
                                            <div class="input-daterange input-group" id="date-range">
                                                <input type="text" class="form-control" autocomplete="off" id="start-date" placeholder="@lang('app.startDate')" value="{{ $startDate->format($global->date_format) }}"/>
                                                
                                                <div class="input-group-prepend input-group-append"><span class="input-group-text">@lang('app.to')</span></div>
                                                <input type="text" class="form-control" id="end-date"  autocomplete="off" placeholder="@lang('app.endDate')" value="{{ $endDate->format($global->date_format) }}"/>
                                            </div>

                                                
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">                              
                                            <label class="control-label">@lang('modules.timeLogs.employeeName')</label>
                            <select class="select2 form-control" data-placeholder="Choose Employee" id="user_id" name="user_id">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                @endforeach
                            </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                            <label class="control-label">&nbsp;</label>                              
                                                <button type="button" id="apply-filter" class="btn btn-primary btn-block">@lang('app.apply')</button>
                                            </div>
                                        </div>

                                    </div>                      

                                </div>
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12">
                <div class="product-sidebar">
                        <div class="filter-section">
                            <div class="card browser-widget bw-project">
                                <div class="media card-body" style="padding: 10px">
                                    <div class="media-body align-self-center">
                                        <div>
                                            <p>@lang('modules.attendance.totalWorkingDays')</p>
                                            <h4><span class="counter" id="totalWorkingDays">{{ $totalWorkingDays }}</span></h4>
                                        </div>
                                        <div>
                                            <p>@lang('modules.attendance.daysPresent') </p>
                                            <h4><span class="counter" id="daysPresent">{{ $daysPresent }}</span></h4>
                                        </div>
                                        <div>
                                            <p>@lang('app.days') @lang('modules.attendance.late') </p>
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


                <div class="col-md-12 col-sm-12">
                    <div class="card browser-widget bw-project">
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
                            <tbody id="attendanceData">
                            </tbody>
                        </table>
</div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    
    
    

@endsection

@push('footer-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script>
<script src="{{ asset('themes/wceo/assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/select2/select2-custom.js')}}"></script>


<script>
    var startDate = '{{ $startDate->format('Y-m-d') }}';
    var endDate = '{{ $endDate->format('Y-m-d') }}';

    /* $('#start-date, #end-date').datepicker({
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
    }) */

    // $('.input-daterange-datepicker').on('apply.daterangepicker', function (ev, picker) {
    //     startDate = picker.startDate.format('YYYY-MM-DD');
    //     endDate = picker.endDate.format('YYYY-MM-DD');
    //     showTable();
    // });

    jQuery('#start-date, #end-date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
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

        var startDate = $('#start-date').val();
        var endDate = $('#end-date').val();

        var userId = $('#user_id').val();
        if (userId == "") {
            userId = 0;
        }


        //refresh counts
        var url = '{!!  route('admin.attendances.refreshCount', [':startDate', ':endDate', ':userId']) !!}';
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
        var url2 = '{!!  route('admin.attendances.employeeData', [':startDate', ':endDate', ':userId']) !!}';

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
                        var url = "{{ route('admin.attendances.destroy',':id') }}";
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

    function exportData(){

        var employee = $('#employee').val();
        var status   = $('#status').val();
        var role     = $('#role').val();

        var url = '{{ route('admin.employees.export', [':status' ,':employee', ':role']) }}';
        url = url.replace(':role', role);
        url = url.replace(':status', status);
        url = url.replace(':employee', employee);

        window.location.href = url;
    }

    function exportData(){

        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var employee = $('#employee').val();

        var url = '{{ route('admin.attendances.export', [':startDate' ,':endDate' ,':employee']) }}';
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':employee', employee);

        window.location.href = url;
    }

</script>
@endpush