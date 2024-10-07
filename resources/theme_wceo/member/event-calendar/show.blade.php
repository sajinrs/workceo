<div id="event-detail">

    <div class="modal-header">
        <h5 class="modal-title">@lang('app.menu.Events') @lang('app.details')</h5>
        <button class="close btn-close-outside-event" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30" /></button>
    </div>
    <div class="modal-body event-job-details">
        {!! Form::open(['id'=>'updateEvent','class'=>'ajax-form','method'=>'GET']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <h3 class="txt-{{ $event->label_color }}">
                        {{ ucfirst($event->event_name) }}
                    </h3>
                    <div class="job-description">
                        {{ ucfirst($event->description) }}
                    </div>
                </div>
            </div>

            <hr />
           
            <div class="row">
                <div class="col-md-12 address">
                    {{ $event->where }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 event-time">

                    <div class="row">
                        <div class="col-md-2 p-r-0">
                                <label>@lang('modules.events.startOn'):</label>
                        </div>
                        <div class="col-md-10 p-l-0">
                            <div class="event-date">
                                {{ $event->start_date_time->format('M d,Y'. ' - '.$global->time_format) }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2 p-r-0">
                                <label>@lang('modules.events.endOn'):</label>
                        </div>
                        <div class="col-md-10 p-l-0">
                            <div class="event-date">
                                {{ $event->end_date_time->format('M d,Y'. ' - '.$global->time_format) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($event->attendee)
            <hr />
            <div class="row">
                <div class="col-md-12">
                    <label>Attendees:</label> <br />
                    @foreach($event->attendee as $member)
                    <img data-toggle="tooltip" data-original-title="{{$member->user->name}}" src="{{$member->user->image_url}}" alt="user" class="img-circle rounded-circle m-b-5" width="40" height="40">
                    @endforeach
                </div>
            </div>
            @endif
            
        </div>
        {!! Form::close() !!}

    </div>
    <div class="modal-footer">
            <button type="button" class="btn btn-block btn-secondary form-control" data-dismiss="modal">Close</button>
        @if($user->can('delete_events'))
            <button type="button" class="btn btn-block btn-danger delete-event form-control"> @lang('app.delete')</button>
        @endif
        @if($user->can('edit_events'))
            <button type="button" class="btn btn-block btn-primary save-event form-control"> @lang('app.edit')</button>
        @endif
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
