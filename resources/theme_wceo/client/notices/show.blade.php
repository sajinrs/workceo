
<div id="event-detail">
    <div class="modal-header">
        <h5 class="modal-title"><a class="primary-icon"><i class="{{$notice->icon}}"></i></a> {{ $notice->heading }}</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>

    <div class="modal-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    {!! $notice->description !!}
                </div>

            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="{{ route('client.notices.index') }}" class="btn btn-primary">View All</a>
        </button>
    </div>
</div>