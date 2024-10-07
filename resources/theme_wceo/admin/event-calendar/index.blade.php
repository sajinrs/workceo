@extends('layouts.app')

@push('head-script')
    {{--<link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>--}}
    {{--<link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet' />--}}

    {{--<link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>--}}
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">--}}

    <link href="{{ asset('themes/wceo/assets/plugins/calendar/main.min.css')}}" rel='stylesheet' />

    {{-- <link href="{{ asset('themes/wceo/assets/plugins/calendar/core/main.css')}}" rel='stylesheet' />
     <link href="{{ asset('themes/wceo/assets/plugins/calendar/daygrid/main.css')}}" rel='stylesheet' />
     <link href="{{ asset('themes/wceo/assets/plugins/calendar/timegrid/main.css')}}" rel='stylesheet' />
     <link href="{{ asset('themes/wceo/assets/plugins/calendar/list/main.css')}}" rel='stylesheet' />
     --}} <link href="{{ asset('themes/wceo/assets/plugins/calendar/bootstrap/main.css')}}" rel='stylesheet' />


    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorselector/bootstrap-colorselector.min.css') }}">

   
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap-datetimepicker.css') }}">
<style>
    .btn-colorselector {
        width: 48px;
        height: 48px;
    }
    .colorselector_label{
        line-height: 48px;
        padding-right: 10px;
    }
    .select2-selection__rendered {
        padding-left: 10px !important;
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        <a class="btn btn-primary btn-sm" href="#" onclick="addEvent()">Add Event <i data-feather="plus"></i></a>
                        <a class="btn btn-primary btn-sm" href="#" onclick="addJob()">Add Job <i data-feather="plus"></i></a>

                        <a href="javascript:;" class="btn btn-outline-primary btn-sm page-tips-btn" onclick="getPageTips(2)"><span>@lang('app.menu.pageTips')</span> <i data-feather="alert-circle"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card wceo-calendar-div">
                    <div class="card-body">
                        <div class="calendar-wrap">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- .row -->

    <!-- BEGIN MODAL -->
    <div class="modal fade bs-modal-md in event-content" id="my-event" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('modules.events.addEvent')</h5>
                    <button class="close btn-close-outside" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30" /></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::open(['id'=>'createEvent','class'=>'ajax-form event-form','method'=>'POST']) !!}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-1 p-r-0">
                                        <div class="event-icon"><i data-feather="check"></i></div>
                                    </div>
                                    <div class="col-md-11">                                    
                                        <div class="form-group form-label-group">
                                            <input placeholder="-" type="text" name="event_name" id="event_name" class="form-control form-control-lg">
                                            <label for="event_name" class="required">@lang('modules.events.eventName')</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-1 p-r-0">
                                        <div class="event-icon"><i data-feather="calendar"></i></div>
                                    </div>
                                    
                                    <div class="col-md-11">
                                        <div class="row">
                                            <div class="col-md-6 p-r-0">
                                                <div class="form-group form-label-group">
                                                    <input placeholder="-" type="text" name="start_date" id="start_date" class="form-control form-control-lg">
                                                    <label for="start_date" class="required">@lang('modules.events.startDate')</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="bootstrap-timepicker timepicker form-group form-label-group">
                                                    <input placeholder="-" type="text" name="start_time" id="start_time" class="form-control form-control-lg">
                                                    <label for="start_time" class="required">@lang('modules.events.startTime')</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6 p-r-0">
                                                <div class="form-group form-label-group">
                                                    <input placeholder="-" type="text" name="end_date" id="end_date" class="form-control form-control-lg">
                                                    <label for="end_date" class="required">@lang('modules.events.endDate')</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="bootstrap-timepicker timepicker form-group form-label-group">
                                                    <input placeholder="-" type="text" name="end_time" id="end_time" class="form-control form-control-lg">
                                                    <label for="end_time" class="required">@lang('modules.events.endTime')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-1 p-r-0">
                                        <div class="event-icon"><i data-feather="align-left"></i></div>
                                    </div>
                                    <div class="col-md-11">                                    
                                        <div class="form-group form-label-group">
                                            <textarea placeholder="-" name="description" id="description" class="form-control form-control-lg"></textarea>
                                            <label for="description" class="required">@lang('modules.events.eventDescription')</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-1 p-r-0">
                                        <div class="event-icon"><i data-feather="map-pin"></i></div>
                                    </div>
                                    <div class="col-md-11">                                    
                                        <div class="form-group form-label-group">
                                            <input placeholder="-" type="text" name="where" id="where" class="form-control form-control-lg">
                                            <label for="where" class="required">@lang('modules.events.address')</label>
                                        </div>
                                    </div>
                                </div>                                

                                <div class="row">
                                    <div class="col-md-1 p-r-0">
                                        <div class="event-icon"></div>
                                    </div>
                                    <div class="col-md-11">                                    
                                        <div class="checkbox checkbox-info m-l-15">
                                            <input style="position: absolute" id="all-employees" name="all_employees" value="true" type="checkbox">
                                            <label for="all-employees">@lang('modules.events.allTeamMembers')</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="attendees">
                                    <div class="col-md-1 p-r-0">
                                        <div class="event-icon"><i data-feather="users"></i></div>
                                    </div>
                                    <div class="col-md-11">                                    
                                        <div class="form-group">
                                            <select class="form-control select2 m-b-10 select2-multiple col-md-12" multiple="multiple" placeholder="_"
                                                    data-placeholder="@lang('modules.messages.chooseMember')" name="user_id[]" id="user_id">
                                                @foreach($employees as $emp)
                                                    <option value="{{ $emp->id }}">{{ ucwords($emp->name) }} @if($emp->id == $user->id)
                                                            (YOU) @endif</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-1 p-r-0">
                                        <div class="event-icon"></div>
                                    </div>
                                    <div class="col-md-11">
                                        <div class="row">
                                            <div class="col-md-5">                                    
                                                <div class="checkbox checkbox-info">
                                                    <input id="repeat-event" name="repeat" value="yes" type="checkbox">
                                                    <label class="col-form-label m-t-0" for="repeat-event">@lang('modules.events.repeat')</label>
                                                </div>
                                            </div>

                                            <div class="col-md-7"> 
                                                <div class="pull-right">
                                                    <div class="input-group">
                                                        <span class="pull-left colorselector_label">@lang('modules.sticky.colors')</span>
                                                        <ul class="icolors">
                                                            <li data-color="red" class="red selectColor"></li>
                                                            <li data-color="green" class="green selectColor"></li>
                                                            <li data-color="blue" class="blue selectColor"></li>
                                                            <li data-color="yellow" class="yellow selectColor"><i class="fas fa-check"></i></li>
                                                            <li data-color="orange" class="orange selectColor"></li>
                                                        </ul>
                                                        <input type="hidden" name="label_color" id="labelColor" value="yellow" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                  

                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="event-icon"></div>
                                    </div>

                                    <div class="col-md-11">
                                        <div class="row">
                                            <div class="col-md-4 repeat-fields p-r-0">
                                                <div class="form-group form-label-group">
                                                    <input placeholder="-" type="number" min="1" value="1" id="repeat_count" name="repeat_count" class="form-control form-control-lg">
                                                    <label for="repeat_count">@lang('modules.events.repeatFrequency')</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 repeat-fields">
                                                <div class="form-group form-label-group p-r-0">
                                                    <select placeholder="-" name="repeat_type" id="" class="form-control hide-search form-control-lg">
                                                        <option value="day">Day(s)</option>
                                                        <option value="week">Week(s)</option>
                                                        <option value="month">Month(s)</option>
                                                        <option value="year">Year(s)</option>
                                                    </select>
                                                    <label for="repeat_type">@lang('modules.events.repeatInterval')</label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 repeat-fields">
                                                <div class="form-group form-label-group">
                                                    <input placeholder="-" type="text" name="repeat_cycles" id="repeat_cycles" class="form-control form-control-lg">
                                                    <label for="repeat_cycles">
                                                        @lang('modules.events.repeatCycle')
                                                        <a class="example-popover text-primary" type="button" data-container="body"  data-trigger="hover" data-toggle="popover" data-placement="top" data-html="true" data-content="@lang('modules.events.cyclesToolTip')"><i class="fa fa-info-circle"></i></a>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
                <div class="modal-footer p-t-0">
                    <a href="javascript:;" class="cancel-text" data-dismiss="modal">@lang('app.cancel')</a>
                    <button type="button" class="btn btn-primary save-event">@lang('app.save')</button>
                </div>
            </div>
        </div>
    </div>

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="eventDetailModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary default" data-dismiss="modal">Close</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{--Event edit Modal--}}
    <div class="modal fade bs-modal-md in event-content" id="eventEditModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
            <div id="event-detail">
                <div class="modal-header">
                    <h5 class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary default" data-dismiss="modal">Close</button>

                </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="projectDetailModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary default" data-dismiss="modal">Close</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

    {{--Job Add Modal--}}
    <div class="modal fade bs-modal-md in event-content" id="jobAddModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                @include('admin.event-calendar.job-add')
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

    {{--Client Add Modal--}}
    <div class="modal fade bs-modal-md in event-content" id="clientModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                @include('admin.event-calendar.client-add')
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script>--}}
    <script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
    {{--<script src="{{ asset('themes/wceo/assets/plugins/calendar/rrule/main.min.js')}}"></script>--}}

    <!-- rrule lib -->
    <script src='https://cdn.jsdelivr.net/npm/rrule@2.6.4/dist/es5/rrule.min.js'></script>

    <!-- fullcalendar bundle -->
    {{--<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.2.0/main.min.js'></script>--}}
    <script src="{{ asset('themes/wceo/assets/plugins/calendar/main.min.js')}}"></script>

    <!-- the rrule-to-fullcalendar connector. must go AFTER the rrule lib -->
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/rrule@5.2.0/main.global.min.js'></script>

    <script>

        var taskEvents = [];
        var projectEvents = [];
        var taskResources = [];
        let events = {!! $events !!};
        let projects = {!! $projects !!};


        let event_colours = {'red':'#ed4040','yellow':'#f1c411','green':'#00c292', 'orange':'#ff9f40'}

        var calendarLocale = '{{ $global->locale }}';

        var firstDay = '{{ $global->week_start }}';

        var getEventDetail = function (id, duration) {
            var dstart = '';
            var dend = '';

            var url = `{{ route('admin.events.show', ':id')}}`;

            url = url.replace(':id', id);

            $('#modelHeading').html('Event');
            $.ajaxModal('#eventDetailModal', url);
        }

        var getProjectDetail = function (id, duration) {
            var dstart = '';
            var dend = '';

            var url = `{{ route('admin.events.show-project', ':id')}}`;

            url = url.replace(':id', id);

            $('#modelHeading').html('Event');
            $.ajaxModal('#projectDetailModal', url);
        }

    </script>

    <script src="{{ asset('js/event-calendar.js') }}"></script>

    <script src="{{ asset('js/cbpFWTabs.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-colorselector/bootstrap-colorselector.min.js') }}"></script>




    <script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>

    <script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js')}}"></script>

    <script src="{{ asset('themes/wceo/assets/js/popover-custom.js')}}"></script>





    <script>
    jQuery(document).ready(function () {
        $("#colorselector").select2('destroy');

        $('.color-picker ul.dropdown-menu li').click(function(){
            $(".color-picker ul.dropdown-menu li a .fas.fa-check").remove();
            $(this).find('a').append('<i class="fas fa-check"></i>');
        });
        $('.color-picker ul.dropdown-menu li a.selected').append('<i class="fas fa-check"></i>');
    })

    jQuery('#start_date, #end_date, .job_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language:'en'
    })

    $('#colorselector').colorselector();

    $('#start_time, #end_time, .job_time').datetimepicker({
        format: 'LT',
        icons: {
            time: 'fa fa-clock',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-check',
            clear: 'fa fa-trash',
            close: 'fa fa-times'
        },
        //onChangeDateTime:calculateTime
    });


    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    function addEventModal(start, end, allDay){
        if(start){
            var sd = new Date(start);
            var curr_date = sd.getDate();
            if(curr_date < 10){
                curr_date = '0'+curr_date;
            }
            var curr_month = sd.getMonth();
            curr_month = curr_month+1;
            if(curr_month < 10){
                curr_month = '0'+curr_month;
            }
            var curr_year = sd.getFullYear();

            $('#start_date').val('{{ \Carbon\Carbon::now()->format($global->date_format) }}');

            var ed = new Date(start);
            var curr_date = sd.getDate();
            if(curr_date < 10){
                curr_date = '0'+curr_date;
            }
            var curr_month = sd.getMonth();
            curr_month = curr_month+1;
            if(curr_month < 10){
                curr_month = '0'+curr_month;
            }
            var curr_year = ed.getFullYear();
            $('#end_date').val('{{ \Carbon\Carbon::now()->format($global->date_format) }}');

            $('#start_date, #end_date').datepicker('destroy');
            jQuery('#start_date, #end_date').datepicker({
                autoclose: true,
                todayHighlight: true,
                weekStart:'{{ $global->week_start }}',
                dateFormat: '{{ $global->date_picker_format }}',
                language:'en'
            })
        }

        $('#my-event').modal('show');

    }

    $('.save-event').click(function () {
        $.easyAjax({
            url: '{{route('admin.events.store')}}',
            container: '#createEvent',
            type: "POST",
            data: $('#createEvent').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    })
    $('.repeat-fields').hide();
    $('#repeat-event').change(function () {
        if($(this).is(':checked')){
            $('.repeat-fields').show();
        }
        else{
            $('.repeat-fields').hide();
        }
    });

    $('#all-employees').change(function() {
        if($('#all-employees').is(":checked")){
            $("#user_id > option").prop("selected","selected");
            $("#user_id").trigger("change");
        } else {
            $("#user_id").val(null).trigger("change");
        }
    });

    $('#all_job_members').change(function() {
        if($('#all_job_members').is(":checked")){
            $("#job_user_id > option").prop("selected","selected");
            $("#job_user_id").trigger("change");
        } else {
            $("#job_user_id").val(null).trigger("change");
        }
    });

    $(document).ready(function(){
        $('.icolors li').click(function(){
            var color = $(this).data('color');
            $('.icolors li').html('');
            $(this).html('<i class="fas fa-check"></i>');
            $('#labelColor').val(color);
        });
    });

    /** Invoice Category */

    $('#cat_id').change(function (e) {
        var cat_id = $(this).val();        
        var url = "{{ route('admin.all-invoices.get-category-products',':id') }}";
        url = url.replace(':id', cat_id);
        $.easyAjax({
            type: 'GET',
            dataType: 'JSON',
            url: url,
            success: function (data) {
                $('#products').html('');
                $('#products').append('<option value="">--</option>');
                $.each(data, function (index, data) {
                    $('#products').append('<option value="' + data.id + '">' + data.name + '</option>');
                });
            }
        });
    });

    $('body').on('change', '#products', function () {
        var id = $(this).val();     
        $.easyAjax({
            url:'{{ route('admin.all-invoices.update-item-event') }}',
            type: "GET",
            data: { id: id },
            success: function(response) {
                $('#jobInvoice').html(response.view);
                var noOfRows = $(document).find('#jobInvoice .item-row').length;
                var i = $(document).find('.item_name').length-1;
                var itemRow = $(document).find('#jobInvoice .item-row:nth-child('+noOfRows+') select.type');
                itemRow.attr('id', 'multiselect');
                itemRow.attr('name', 'taxes['+i+'][]');
                $(document).find('#multiselect').select2();
                calculateTotal();
                $("#products")[0].selectedIndex = 0;
            }
        });
    });

    $('#jobAddModal').on('keyup change','.type, .quantity, .cost_per_item', function () {
        calculateTotal();
    });

    function calculateTotal(){
        var subtotal = 0;
        //var discount = 0;
        var tax = '';
        var taxList = new Object();
        var taxTotal = 0;
        $(".quantity").each(function (index, element) {
            var itemTax = [];
            var itemTaxName = [];
            $('select.type option:selected').each(function (index) {
                itemTax[index] = $(this).data('rate');
                itemTaxName[index] = $(this).text();
            });
            var itemTaxId = $('select.type').val();
            var quantity =  $(this).val();
            var amount = parseFloat($('.cost_per_item').val()) * quantity;
            $('.amount').val(decimalupto2(amount).toFixed(2));

            if(isNaN(amount)){ amount = 0; }

            subtotal = (parseFloat(subtotal)+parseFloat(amount)).toFixed(2);

            if(itemTaxId != ''){
                for(var i = 0; i<=itemTaxName.length; i++)
                {
                    if(typeof (taxList[itemTaxName[i]]) === 'undefined'){
                        taxList[itemTaxName[i]] = ((parseFloat(itemTax[i])/100)*parseFloat(amount));
                    }
                    else{
                        taxList[itemTaxName[i]] = parseFloat(taxList[itemTaxName[i]]) + ((parseFloat(itemTax[i])/100)*parseFloat(amount));
                    }
                }
            }
        });

        $.each( taxList, function( key, value ) {
            if(!isNaN(value)){
                tax = tax+'<div class="offset-md-8 col-md-2 text-right p-t-10">'
                    +key
                    +'</div>'
                    +'<p class="form-control-static col-xs-6 col-md-2" >'
                    +'<span class="tax-percent">'+(decimalupto2(value)).toFixed(2)+'</span>'
                    +'</p>';
                taxTotal = taxTotal+decimalupto2(value);
            }
        });
        

        

        
        /* if(isNaN(subtotal)){  subtotal = 0; }

        $('.sub-total').html(decimalupto2(subtotal).toFixed(2));
        $('.sub-total-field').val(decimalupto2(subtotal)); */

        /* var discountType = $('#discount_type').val();
        var discountValue = $('.discount_value').val();

        if(discountValue != ''){
            if(discountType == 'percent'){
                discount = ((parseFloat(subtotal)/100)*parseFloat(discountValue));
            }
            else{
                discount = parseFloat(discountValue);
            }

        } */

    //show tax
    //$('#invoice-taxes').html(tax);


    //calculate total
        var totalAfterDiscount = decimalupto2(subtotal);

        totalAfterDiscount = (totalAfterDiscount < 0) ? 0 : totalAfterDiscount;

        var total = decimalupto2(totalAfterDiscount+taxTotal);

        $('.total').html(total.toFixed(2));
        $('.total-field').val(total.toFixed(2));
        

    }

    function decimalupto2(num) {
        var amt =  Math.round(num * 100) / 100;
        return parseFloat(amt.toFixed(2));
    }

    $('#saveJob').click(function () {
        $.easyAjax({
            url: '{{route('admin.projects.store')}}',
            container: '#createProject',
            type: "POST",
            redirect: true,
            data: $('#createProject').serialize(),
            success: function(response){
                if(response.status == 'success'){
                    window.location.reload();
                }              
            }
        })
    });

    //Create Client
    $('#newClient').change(function () {
        if($(this).is(':checked')){
            $('#clientModal').modal({
                show:true,
                backdrop: 'static',
                keyboard: false,
            });
            $('#jobAddModal').modal('hide');
        }        
    });

    $("#clientModal").on("hidden.bs.modal", function () {
        $('#jobAddModal').modal('show');
    });

    $('#saveClient').click(function () {
        $.easyAjax({
            url: '{{route('admin.clients.store')}}',
            container: '#createClient',
            type: "POST",
            redirect: true,
            data: $('#createClient').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    $('#client_id').html(response.teamData);
                    $("#client_id").select2();
                    $('#clientModal').modal('hide');
                    $("#newClient").prop("checked", false);
                    $('#createClient')[0].reset();
                }
            }
        })
    });

    $('#description').scroll(function() {
        $('label[for="description"]').hide();
    });

    $('#project_summary').scroll(function() {
        $('label[for="project_summary"]').hide();
    });

    function addEvent()
    {
        $('#my-event').modal({
            show:true,
            backdrop: 'static',
            keyboard: false,
        });
    }

    function addJob()
    {
        $('#jobAddModal').modal({
            show:true,
            backdrop: 'static',
            keyboard: false,
        });
    }

</script>

@endpush
