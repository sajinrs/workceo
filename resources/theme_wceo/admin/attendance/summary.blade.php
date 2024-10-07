@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap-datetimepicker.min.css') }}">

@endpush

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
                            <li class="nav-item"><a class="nav-link active" id="info-summary-tab" href="{{ route('admin.attendances.summary') }}" aria-selected="true">@lang('app.summary')</a></li>

                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.attendances.index') }}">@lang('modules.attendance.attendanceByMember')</a></li>

                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.attendances.attendanceByDate') }}">@lang('modules.attendance.attendanceByDate')</a></li>
                        </ul>
                        <div class="tab-content" id="info-tabContent">
                            <div class="tab-pane fade show active" id="info-summary" role="tabpanel" aria-labelledby="info-summary-tab">
                                <div class="form-body">
                                    <div class="row">                                
                                        
                                        <div class="col-md-4">
                                            <div class="form-group">                              
                                                <label class="control-label">@lang('modules.timeLogs.employeeName')</label>
                                                <select class="select2 form-control" data-placeholder="Choose Employee" id="user_id" name="user_id">
                                                    <option value="0">--</option>
                                                    @foreach($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">                              
                                                <label class="control-label">@lang('app.select') @lang('app.month')</label>
                                                <select class="select2 form-control hide-search" data-placeholder="" id="month">
                                                    <option @if($month == '01') selected @endif value="01">@lang('app.january')</option>
                                                    <option @if($month == '02') selected @endif value="02">@lang('app.february')</option>
                                                    <option @if($month == '03') selected @endif value="03">@lang('app.march')</option>
                                                    <option @if($month == '04') selected @endif value="04">@lang('app.april')</option>
                                                    <option @if($month == '05') selected @endif value="05">@lang('app.may')</option>
                                                    <option @if($month == '06') selected @endif value="06">@lang('app.june')</option>
                                                    <option @if($month == '07') selected @endif value="07">@lang('app.july')</option>
                                                    <option @if($month == '08') selected @endif value="08">@lang('app.august')</option>
                                                    <option @if($month == '09') selected @endif value="09">@lang('app.september')</option>
                                                    <option @if($month == '10') selected @endif value="10">@lang('app.october')</option>
                                                    <option @if($month == '11') selected @endif value="11">@lang('app.november')</option>
                                                    <option @if($month == '12') selected @endif value="12">@lang('app.december')</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">                              
                                                <label class="control-label">@lang('app.select') @lang('app.year')</label>
                                                <select class="select2 form-control hide-search" data-placeholder="" id="year">
                                                    @for($i = $year; $i >= ($year-4); $i--)
                                                        <option @if($i == $year) selected @endif value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <label class="control-label">&nbsp;</label>                              
                                                <button type="button" id="apply-filter" class="btn btn-sm btn-primary btn-block">@lang('app.apply')</button>
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
    <div class="container-fluid product-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div id="attendance-data"  class="table-responsive"> </div>
                </div>
            </div>
        </div>
    </div>

    {{--Timer Modal--}}
    <div class="modal fade bs-modal-lg in" id="attendanceModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">Modal title</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Timer Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js') }}"></script>

<script>
    
    $('#apply-filter').click(function () {
       showTable();
    });    

    function showTable() {

        var year = $('#year').val();
        var month = $('#month').val();      
        var userId = $('#user_id').val();
      
        //refresh counts
        var url = '{!!  route('admin.attendances.summaryData') !!}';

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            data: {
                '_token': token,
                year: year,
                month: month,
                userId: userId
            },
            url: url,
            success: function (response) {
               $('#attendance-data').html(response.data);
            }
        });

    }

    showTable();

    $('#attendance-data').on('click', '.view-attendance',function () {
        var attendanceID = $(this).data('attendance-id');
        var url = '{!! route('admin.attendances.info', ':attendanceID') !!}';
        url = url.replace(':attendanceID', attendanceID);

        $('#modelHeading').html('{{__("app.menu.attendance") }}');
        $.ajaxModal('#projectTimerModal', url);
    });

    $('#attendance-data').on('click', '.edit-attendance',function (event) {
        var attendanceDate = $(this).data('attendance-date');
        var userData       = $(this).closest('tr').children('td:first');
        var userID         = userData[0]['firstChild']['nextSibling']['dataset']['employeeId'];
        var year           = $('#year').val();
        var month          = $('#month').val();

        var url = '{!! route('admin.attendances.mark', [':userid',':day',':month',':year',]) !!}';
        url = url.replace(':userid', userID);
        url = url.replace(':day', attendanceDate);
        url = url.replace(':month', month);
        url = url.replace(':year', year);

        $('#modelHeading').html('{{__("app.menu.attendance") }}');
        $.ajaxModal('#projectTimerModal', url);
    });

    function editAttendance (id) {
        $('#projectTimerModal').modal('hide');

        var url = '{!! route('admin.attendances.edit', [':id']) !!}';
        url = url.replace(':id', id);
        console.log('sri ram');
        $('#modelHeading').html('{{__("app.menu.attendance") }}');
        $.ajaxModal('#attendanceModal', url);
    }
</script>
@endpush