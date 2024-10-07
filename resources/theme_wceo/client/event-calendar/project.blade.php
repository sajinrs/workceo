<div id="event-detail">

    <div class="modal-header">
        <h5 class="modal-title">@lang('app.project') @lang('app.details')</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

    </div>
    <div class="modal-body">
         <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <h3 class="" style="color: #3788d8">
                            {{ ucfirst($project->project_name) }}
                        </h3>
                        <p class="font-normal"> &mdash; <i>at</i> {{ $project->client->address }}</p>
                        <p class="font-normal"> &mdash; <i></i> {{ $project->client->name }}</p>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('app.description')</label>
                        <p>{!! $project->project_summary !!}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 col-md-4 ">
                    <div class="form-group">
                        <label>@lang('modules.events.startOn')</label>
                        <p><b style="color: #3788d8">{{ $project->start_date->format('M d,Y') }} - {{ date($global->time_format,strtotime($project->start_time)) }}</b></p>
                    </div>
                </div>
                <div class="col-xs-6 col-md-4">
                    <div class="form-group">
                        <label>@lang('modules.events.endOn')</label>
                        <p><b style="color: #3788d8">{{ $project->deadline->format('M d,Y') }} - {{ date($global->time_format,strtotime($project->end_time)) }}</b></p>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="modal-footer">
        <div class="row width-100">
            <div class="col-md-3 offset-6">
                <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">Close</button>
            </div>
            {{--<div class="col-md-3">
                <button type="button" class="btn btn-danger delete-event form-control"> @lang('app.delete')</button>
            </div>--}}
            <div class="col-md-3">
                <a href="{{route('client.projects.show', $project->id)}}" class="btn btn-primary save-event form-control"> @lang('app.view') @lang('app.details')</a>
            </div>
        </div>
    </div>

</div>
