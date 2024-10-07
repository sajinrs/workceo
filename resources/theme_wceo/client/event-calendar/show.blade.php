<div id="event-detail">

    <div class="modal-header">
        <h5 class="modal-title">@lang('app.menu.Events') @lang('app.details')</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>
    <div class="modal-body">
        {!! Form::open(['id'=>'updateEvent','class'=>'ajax-form','method'=>'GET']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <h3 class="txt-{{ $event->label_color }}">
                            {{ ucfirst($event->event_name) }}
                        </h3>
                        <p class="font-normal"> &mdash; <i>at</i> {{ $event->where }}</p>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('app.description')</label>
                        <p>{{ ucfirst($event->description) }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 col-md-4 ">
                    <div class="form-group">
                        <label>@lang('modules.events.startOn')</label>
                        <p><b class="txt-{{ $event->label_color }}">{{ $event->start_date_time->format('M d,Y'. ' - '.$global->time_format) }}</b></p>
                    </div>
                </div>
                <div class="col-xs-6 col-md-4">
                    <div class="form-group">
                        <label>@lang('modules.events.endOn')</label>
                        <p><b class="txt-{{ $event->label_color }}">{{ $event->end_date_time->format('M d,Y'. ' - '.$global->time_format) }}</b></p>
                    </div>
                </div>

            </div>
        </div>
        {!! Form::close() !!}

    </div>
    <div class="modal-footer">
        <div class="row width-100">
            <div class="col-md-3 @if($user->can('delete_events')) offset-3 @else offset-9 @endif">
                <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">Close</button>
            </div>
            @if($user->can('delete_events'))
                <div class="col-md-3">
                    <button type="button" class="btn btn-danger delete-event form-control"> @lang('app.delete')</button>
                </div>
            @endif
            @if($user->can('edit_events'))
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary save-event form-control"> @lang('app.edit')</button>
                </div>
            @endif
        </div>
    </div>

</div>

<script>

    $('.save-event').click(function () {
        $.easyAjax({
            url: '{{route('member.events.edit', $event->id)}}',
            container: '#updateEvent',
            type: "GET",
            data: $('#updateEvent').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    $('#event-detail').html(response.view);
                }
            }
        })
    })

    $('.delete-event').click(function(){
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted event!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
        })
            .then((willDelete) => {
                if (willDelete) {

                    var url = "{{ route('member.events.destroy', $event->id) }}";

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                window.location.reload();
                            }
                        }
                    });
                }
            });
    });


</script>
