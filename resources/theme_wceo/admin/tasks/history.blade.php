<div class="row">
    <div class="col-md-12 m-t-20"   id="project-timeline">
        <h6>@lang('modules.projects.activityTimeline') </h6>
        <div class="timeline-small  m-t-30">
            @foreach($task->history as $activ)
                <div class="media">
                    <div class="timeline-round m-r-30 timeline-line-1">
                        <img class="img-circle" src="{{ $activ->user->image_url }}" width="50" height="50" alt="">

                    </div>
                    <div class="media-body">
                        <p>{{ __("modules.tasks.".$activ->details) }} {{ ucwords($activ->user->name) }} <label style="color:#fff; background: {{ $activ->board_column->label_color }}" class="badge">{{ $activ->board_column->column_name }}</label></p>

                        @if (!is_null($activ->sub_task_id))
                            <p><small class="text-info">{{ $activ->sub_task->title }}</small></p>
                        @endif
                        <h6><span class="pull-right f-10">{{ $activ->created_at->timezone($global->timezone)->format($global->date_format)." ".$activ->created_at->timezone($global->timezone)->format($global->time_format) }}</span></h6>

                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
