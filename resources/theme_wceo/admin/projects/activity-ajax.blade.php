<div  id="sticky-note-header">
    <div class="row">
        <div class="col-10">
            <h5 class="modal-title font-weight-bold">JOB ACTIVITY</h5>
        </div>
        <div class="col-2 text-right"><a class="right_side_toggle" href="javascript:void(0);"><i class="fa fa-times fa-lg"></i></a></div>
        <div class="col-12 m-t-10">
            <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-primary btn-block">VIEW JOBS</a>
        </div>
    </div>
</div>
<div id="sticky-note-list">
    <div class="timeline-small timeline-top-list m-t-30">
        @foreach($activities as $activ)
        <div class="media">
            <div class="timeline-round timeline-line-1 bg-primary"><i data-feather="shopping-bag"></i></div>
            <div class="media-body">
                <strong><a href="{{route('admin.projects.show', $activ->project->id)}}">{{ $activ->project->project_name }}</a></strong>
                <p>{{ $activ->activity }}</p>
                <span class="pull-left f-9 date">{{ $activ->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>