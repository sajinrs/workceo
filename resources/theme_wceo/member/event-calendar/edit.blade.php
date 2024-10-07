<div class="modal-header">
    <h5 class="modal-title"><i class="icon-pencil"></i> @lang('app.edit') @lang('app.menu.Events')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    {!! Form::open(['id'=>'updateEvent','class'=>'ajax-form','method'=>'PUT']) !!}
    <div class="form-body">
        <div class="row">
            <div class="col-md-8 ">
                <div class="form-group form-label-group">
                    <input placeholder="-" type="text" name="event_name" id="event_name" value="{{ $event->event_name }}" class="form-control form-control-lg">
                    <label for="event_name" class="required">@lang('modules.events.eventName')</label>
                </div>
            </div>

            <div class="col-md-2 ">
                <span class="pull-left colorselector_label">@lang('modules.sticky.colors')</span>
                <div class="form-group pull-left">

                    <select id="edit-colorselector" name="label_color">
                        <option value="yellow" data-color="#f1c411" @if($event->label_color == 'yellow') selected @endif>Yellow</option>
                        <option value="red" data-color="#ed4040" @if($event->label_color == 'red') selected @endif>Red</option>
                        <option value="green" data-color="#00c292" @if($event->label_color == 'green') selected @endif>Green</option>
                    </select>
                </div>
            </div>


        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="form-group form-label-group">
                    <textarea placeholder="-" name="description" id="description" class="form-control form-control-lg">{{ $event->description }}</textarea>
                    <label for="description" class="required">@lang('modules.events.eventDescription')</label>
                </div>
            </div>
            <div class="col-md-4 ">
                <div class="form-group form-label-group">
                    <textarea placeholder="-" name="where" id="where" class="form-control form-control-lg">{{ $event->where }}</textarea>
                    <label for="where" class="required">@lang('modules.events.where')</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-3 ">
                <div class="form-group form-label-group">
                    <input placeholder="-" type="text" name="start_date" id="start_date" value="{{ $event->start_date_time->format($global->date_format) }}" class="form-control form-control-lg">
                    <label for="start_date" class="required">@lang('modules.events.startDate')</label>
                </div>
            </div>
            <div class="col-xs-5 col-md-3">
                <div class="form-group form-label-group bootstrap-timepicker timepicker">
                    <input placeholder="-" type="text" name="start_time" id="start_time" value="{{ $event->start_date_time->format($global->time_format) }}"   class="form-control form-control-lg">
                    <label for="start_time" class="required">@lang('modules.events.startTime')</label>
                </div>
            </div>

            <div class="col-xs-6 col-md-3">
                <div class="form-group form-label-group">
                    <input type="text" name="end_date" id="end_date" value="{{ $event->end_date_time->format($global->date_format) }}" class="form-control form-control-lg">
                    <label for="end_date" class="required">@lang('modules.events.endDate')</label>
                </div>
            </div>
            <div class="col-xs-5 col-md-3">
                <div class="form-group form-label-group bootstrap-timepicker timepicker">
                    <input type="text" name="end_time" id="end_time" value="{{ $event->end_date_time->format($global->time_format) }}"
                           class="form-control form-control-lg">
                    <label for="end_time" class="required">@lang('modules.events.endTime')</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12 m-b-15">
                <a href="javascript:;" id="show-attendees" class="text-info"><i class="icon-people"></i> @lang('modules.events.viewAttendees') ({{ count($event->attendee) }})</a>
            </div>
            <div class="col-xs-12 col-md-12"  id="edit-attendees" style="display: none;">
                <div class="row">
                    <div class="col-xs-12 col-md-12" style="max-height: 210px; overflow-y: auto;">
                        <div class="form-group">
                            <ul class="list-group">
                                @foreach($event->attendee as $emp)
                                    <li class="list-group-item">{{ ucwords($emp->user->name) }}
                                        <a href="javascript:;" data-attendee-id="{{ $emp->id }}" class="btn btn-xs btn-rounded btn-danger pull-right remove-attendee"><i class="fa fa-times"></i> @lang('app.remove')</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3" >
                        <div class="form-group">
                            {{--<label class="col-xs-3 m-t-10">@lang('modules.events.addAttendees')</label>--}}
                            <div class="col-xs-7">
                                <div class="checkbox checkbox-info">
                                    <input id="edit-all-employees" name="all_employees" value="true"
                                           type="checkbox">
                                    <label for="edit-all-employees">@lang('modules.events.allEmployees')</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9" >
                        <div class="form-group">
                            <select class="select3 m-b-10 select2-multiple  col-md-12 form-control form-control-lg" multiple="multiple"
                                    data-placeholder="Choose Members, Clients" name="user_id[]">
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ ucwords($emp->name) }} @if($emp->id == $user->id)
                                            (YOU) @endif</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xs-12 col-md-3">
                <div class="form-group">
                    <div class="col-xs-7">
                        <div class="checkbox checkbox-info">
                            <input id="edit-repeat-event" name="repeat" value="yes" @if($event->repeat == 'yes') checked @endif
                            type="checkbox">
                            <label for="edit-repeat-event">@lang('modules.events.repeat')</label>
                        </div>
                    </div>
                </div>
            </div>
            <div @if($event->repeat == 'no') style="display: none" @endif class="col-xs-6 col-md-3 edit-repeat-fields">
                <div class="form-group form-label-group">
                    <input placeholder="-" type="number" min="1" value="{{ $event->repeat_every }}" name="repeat_count" class="form-control form-control-lg">
                    <label for="repeat_count">@lang('modules.events.repeatEvery')</label>
                </div>
            </div>
            <div @if($event->repeat == 'no') style="display: none" @endif class="col-xs-6 col-md-3 edit-repeat-fields">
                <div class="form-group form-label-group">
                    <select  placeholder="-" name="repeat_type" id="" class="form-control form-control-lg">
                        <option @if($event->repeat_type == 'day') selected @endif value="day">Day(s)</option>
                        <option @if($event->repeat_type == 'week') selected @endif value="week">Week(s)</option>
                        <option @if($event->repeat_type == 'month') selected @endif value="month">Month(s)</option>
                        <option @if($event->repeat_type == 'year') selected @endif value="year">Year(s)</option>
                    </select>
                    <label for="repeat_type">@lang('modules.events.repeatInterval')</label>
                </div>
            </div>

            <div @if($event->repeat == 'no') style="display: none" @endif class="col-xs-6 col-md-3 edit-repeat-fields">
                <div class="form-group form-label-group">
                    <input placeholder="-" type="text" value="{{ $event->repeat_cycles }}" name="repeat_cycles" id="repeat_cycles_edit" class="form-control form-control-lg">
                    <label for="repeat_cycles_edit">
                        @lang('modules.events.repeatCycle')
                        <a class="example-popover text-primary" type="button" data-container="body"  data-trigger="hover" data-toggle="popover" data-placement="top" data-html="true" data-content="@lang('modules.events.cyclesToolTip')"><i class="fa fa-info-circle"></i></a>
                    </label>
                </div>
            </div>

        </div>

    </div>
    {!! Form::close() !!}

</div>
<div class="modal-footer">
    <div class="row width-100">
        <div class="col-md-3 offset-6">
            <button type="button" class="btn btn-secondary waves-effect form-control " data-dismiss="modal">Close</button>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-primary save-event waves-effect waves-light form-control ">@lang('app.update')</button>
        </div>
    </div>
</div>



<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>


<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>

<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js')}}"></script>

<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-colorselector/bootstrap-colorselector.min.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/popover-custom.js')}}"></script>

<script>
    jQuery('#start_date, #end_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language:'en'
    })

    $('#edit-colorselector').colorselector();

    $('#start_time, #end_time').datetimepicker({
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

    $(".select3").select2();


    $('.save-event').click(function () {
        $.easyAjax({
            url: '{{route('member.events.update', $event->id)}}',
            container: '#updateEvent',
            type: "PUT",
            data: $('#updateEvent').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    })

    $('#edit-repeat-event').change(function () {
        if($(this).is(':checked')){
            $('.edit-repeat-fields').show();
        }
        else{
            $('.edit-repeat-fields').hide();
        }
    })

    $('#show-attendees').click(function () {
        $('#edit-attendees').slideToggle();
    })

    $('.remove-attendee').click(function () {
        var row = $(this);
        var attendeeId = row.data('attendee-id');
        var url = '{{route('member.events.removeAttendee')}}';

        $.easyAjax({
            url: url,
            type: "POST",
            data: { attendeeId: attendeeId, _token: '{{ csrf_token() }}'},
            success: function (response) {
                if(response.status == 'success'){
                    row.closest('.list-group-item').fadeOut();
                }
            }
        })
    })

</script>