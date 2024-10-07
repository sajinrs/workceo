<div id="event-detail">

    <div class="modal-header">
        <h5 class="modal-title">@lang('app.project') @lang('app.details')</h5>
        <button class="close btn-close-outside-event" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30" /></button>

    </div>
    <div class="modal-body event-job-details">
         <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <h3 class="" style="color: #3788d8">{{ ucfirst($project->project_name) }} </h3>
                    <div class="client-info">
                        {{ $project->client->name }} | @if($project->client->mobile) {{$project->client->mobile}} | @endif {{ $project->client->email }}
                    </div>
                    <div class="job-description">
                        {!! $project->project_summary !!}
                    </div>
                </div>
            </div>

            <hr />
           
            <div class="row">
                <div class="col-md-12 address">
                    {{ $project->client->address }}
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
                                {{ $project->start_date->format('M d,Y') }} - {{ date($global->time_format,strtotime($project->start_time)) }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2 p-r-0">
                                <label>@lang('modules.events.endOn'):</label>
                        </div>
                        <div class="col-md-10 p-l-0">
                            <div class="event-date">
                                {{ $project->deadline->format('M d,Y') }} - {{ date($global->time_format,strtotime($project->end_time)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($project->members)
            <hr />
            <div class="row">
                <div class="col-md-12">
                    <label>Assigned Employees</label> <br />
                    @foreach($project->members as $member)
                    <img data-toggle="tooltip" data-original-title="{{$member->user->name}}" src="{{$member->user->image_url}}" alt="user" class="img-circle rounded-circle m-b-5" width="40" height="40">
                    @endforeach
                </div>
            </div>
            @endif
        </div>

    </div>
    <div class="modal-footer">
           
            {{--<div class="col-md-3">
                <button type="button" class="btn btn-danger delete-event form-control"> @lang('app.delete')</button>
            </div>--}}
                <a href="{{route('member.projects.show', $project->id)}}" class="btn btn-block btn-primary"> @lang('app.view') @lang('app.details')</a>
    </div>

</div>
