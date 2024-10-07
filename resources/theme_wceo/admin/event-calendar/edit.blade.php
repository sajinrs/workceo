<div class="modal-header">
    <h5 class="modal-title">@lang('app.edit') @lang('app.menu.Events')</h5>
    <button class="close btn-close-outside" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30" /></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            {!! Form::open(['id'=>'updateEvent','class'=>'ajax-form event-form','method'=>'PUT']) !!}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-1 p-r-0">
                        <div class="event-icon"><i data-feather="check"></i></div>
                    </div>
                    <div class="col-md-11">                                    
                        <div class="form-group form-label-group">
                            <input placeholder="-" type="text" name="event_name" id="event_name" class="form-control form-control-lg" value="{{ $event->event_name }}" />
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
                                    <input placeholder="-" type="text" name="start_date" id="start_date" class="form-control form-control-lg" value="{{ $event->start_date_time->format($global->date_format) }}" />
                                    <label for="start_date" class="required">@lang('modules.events.startDate')</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="bootstrap-timepicker timepicker form-group form-label-group">
                                    <input placeholder="-" type="text" name="start_time" id="start_time" class="form-control form-control-lg" value="{{ $event->start_date_time->format($global->time_format) }}" />
                                    <label for="start_time" class="required">@lang('modules.events.startTime')</label>
                                </div>
                            </div>

                            <div class="col-md-6 p-r-0">
                                <div class="form-group form-label-group">
                                    <input placeholder="-" type="text" name="end_date" id="end_date" class="form-control form-control-lg" value="{{ $event->end_date_time->format($global->date_format) }}"/>
                                    <label for="end_date" class="required">@lang('modules.events.endDate')</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="bootstrap-timepicker timepicker form-group form-label-group">
                                    <input placeholder="-" type="text" name="end_time" id="end_time" class="form-control form-control-lg" value="{{ $event->end_date_time->format($global->time_format) }}" />
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
                            <textarea placeholder="-" name="description" id="description2" class="form-control form-control-lg">{{ $event->description }}</textarea>
                            <label for="description2" class="required">@lang('modules.events.eventDescription')</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-1 p-r-0">
                        <div class="event-icon"><i data-feather="map-pin"></i></div>
                    </div>
                    <div class="col-md-11">                                    
                        <div class="form-group form-label-group">
                            <input placeholder="-" type="text" name="where" id="where" class="form-control form-control-lg" value="{{ $event->where }}" />
                            <label for="where" class="required">@lang('modules.events.address')</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-1 p-r-0">
                        <div class="event-icon"></div>
                    </div>
                    <div class="col-xs-12 col-md-11 m-b-15">
                        <a href="javascript:;" id="show-attendees" class="text-info"><i class="icon-people"></i> @lang('modules.events.viewAttendees') ({{ count($event->attendee) }})</a>
                    </div>

                    <div id="edit-attendees" style="display: none;">
                        <div class="col-xs-12 col-md-12" >
                            <div class="row">
                                <div class="col-md-1 p-r-0">
                                    <div class="event-icon"></div>
                                </div>

                                <div class="col-md-11">
                                    <div class="col-xs-12 col-md-12 p-0" style="max-height: 210px; overflow-y: auto;">
                                        <div class="table-responsive m-b-10">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Employee</th>
                                                    <th scope="col">Action</th>                         
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($event->attendee as $key => $emp)
                                                <tr>
                                                    <th scope="row">{{$key+1}}</th>
                                                    <td>{{ ucwords($emp->user->name) }}</td>
                                                    <td><a href="javascript:;" data-attendee-id="{{ $emp->id }}" class="btn btn-xs btn-rounded btn-danger remove-attendee"><i class="fa fa-times"></i> @lang('app.remove')</a></td>                          
                                                </tr>
                                                @endforeach                      
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-md-12 p-l-0">
                                        <div class="form-group">
                                            <div class="col-xs-7">
                                                <div class="checkbox checkbox-info">
                                                    <input id="edit-all-employees" name="all_employees" value="true" type="checkbox">
                                                    <label for="edit-all-employees">@lang('modules.events.allTeamMembers')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-1 p-r-0">
                                <div class="event-icon"><i data-feather="users"></i></div>
                            </div>

                            <div class="col-md-11" >
                                <div class="form-group">
                                    <select class="select3 m-b-10 select2-multiple  col-md-12 form-control form-control-lg" multiple="multiple"
                                            data-placeholder="Choose Members" name="user_id[]" id="emp_id">
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
            </div>

            <div class="row">
                <div class="col-md-1 p-r-0">
                    <div class="event-icon"></div>
                </div>
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-5">                                    
                            <div class="checkbox checkbox-info">
                                <input id="edit-repeat-event" name="repeat" value="yes" @if($event->repeat == 'yes') checked @endif type="checkbox">
                                <label for="edit-repeat-event">@lang('modules.events.repeat')</label>
                            </div>
                        </div>

                        <div class="col-md-7"> 
                            <div class="pull-right">
                                <div class="input-group">
                                    <span class="pull-left colorselector_label">@lang('modules.sticky.colors')</span>
                                    <ul class="icolors">
                                        <li data-color="red" class="red selectColor">
                                            @if($event->label_color == 'red') <i class="fas fa-check"></i> @endif
                                        </li>
                                        <li data-color="green" class="green selectColor">
                                            @if($event->label_color == 'green') <i class="fas fa-check"></i> @endif
                                        </li>
                                        <li data-color="blue" class="blue selectColor">
                                            @if($event->label_color == 'blue') <i class="fas fa-check"></i> @endif
                                        </li>
                                        <li data-color="yellow" class="yellow selectColor">
                                            @if($event->label_color == 'yellow') <i class="fas fa-check"></i> @endif
                                        </li>
                                        <li data-color="orange" class="orange selectColor">
                                            @if($event->label_color == 'orange') <i class="fas fa-check"></i> @endif
                                        </li>
                                    </ul>
                                    <input type="hidden" name="label_color" id="labelColorEdit" value="{{$event->label_color}}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   

            <div id="repeatOptions" class="row m-t-20" @if($event->repeat == 'no') style="display: none" @endif>
                <div class="col-md-1">
                    <div class="event-icon"></div>
                </div>

                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-4 edit-repeat-fields p-r-0">
                            <div class="form-group form-label-group">
                                <input placeholder="-" type="number" min="1" value="{{ $event->repeat_every }}" name="repeat_count" class="form-control form-control-lg">
                                <label for="repeat_count">@lang('modules.events.repeatEvery')</label>
                            </div>
                        </div>
                        <div class="col-md-4 edit-repeat-fields">
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

                        <div class="col-md-4 edit-repeat-fields">
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
            </div>  

            </div>
            {!! Form::close() !!}
        </div>
    </div>

</div>
<div class="modal-footer">
    <a href="javascript:;" class="cancel-text" data-dismiss="modal">@lang('app.cancel')</a>
    <button type="button" class="btn btn-primary save-event waves-effect waves-light">@lang('app.update')</button>

</div>

<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>


<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>

<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js')}}"></script>

<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-colorselector/bootstrap-colorselector.min.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/popover-custom.js')}}"></script>


<script src="{{ asset('themes/wceo/assets/js/icons/feather-icon/feather-icon.js')}}"></script>

<script>
    jQuery('#start_date, #end_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language:'en'
    })

    

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
            url: '{{route('admin.events.update', $event->id)}}',
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
            $('#repeatOptions').show();
        }
        else{
            $('#repeatOptions').hide();
        }
    })

    $('#show-attendees').click(function () {
        $('#edit-attendees').slideToggle();
    })

    $('.remove-attendee').click(function () {
        var row = $(this);
        var attendeeId = row.data('attendee-id');
        var url = '{{route('admin.events.removeAttendee')}}';

        $.easyAjax({
            url: url,
            type: "POST",
            data: { attendeeId: attendeeId, _token: '{{ csrf_token() }}'},
            success: function (response) {
                if(response.status == 'success'){
                    row.closest('tr').fadeOut();
                }
            }
        })
    });

    jQuery(document).ready(function () {
        //$("#colorselector").select2('destroy');
        $('#edit-colorselector').colorselector();

        $('.color-picker ul.dropdown-menu li').click(function(){
            $(".color-picker ul.dropdown-menu li a .fas.fa-check").remove();
            $(this).find('a').append('<i class="fas fa-check"></i>');
        });
        $('.color-picker ul.dropdown-menu li a.selected').append('<i class="fas fa-check"></i>');

        $('#edit-all-employees').change(function() {
            if($('#edit-all-employees').is(":checked")){
                $("#emp_id > option").prop("selected","selected");
                $("#emp_id").trigger("change");
            } else {
                $("#emp_id").val(null).trigger("change");
            }
        });
    });

    $(document).ready(function(){
        $('.icolors li').click(function(){
            var color = $(this).data('color');
            $('.icolors li').html('');
            $(this).html('<i class="fas fa-check"></i>');
            $('#labelColorEdit').val(color);
        });

        $('#description2').scroll(function() {
            $('label[for="description2"]').hide();
        });
    });

</script>