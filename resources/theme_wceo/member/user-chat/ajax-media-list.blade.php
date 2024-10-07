<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">Media</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <div class="row">
        @forelse($chatDetails as $key => $chatDetail)
            @php $extension = pathinfo(storage_path($chatDetail->attachment_url), PATHINFO_EXTENSION); @endphp
                <div class="col-md-3">
                    <div class="media-img">
                        @if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'png')
                            <a href="{{$chatDetail->attachment_url}}" target="_blank"><img src="{{$chatDetail->attachment_url}}" /></a>
                        @elseif($extension == 'pdf')    
                            <a href="{{$chatDetail->attachment_url}}" download><img class="pull-right attachment-image" src="{{ asset('img/pdf-placeholder.jpg') }}" /></a>
                        @elseif($extension == 'mp4' || $extension == 'avi') 
                            <a href="{{$chatDetail->attachment_url}}" download><img class="pull-right attachment-image" src="{{ asset('img/video-placeholder.jpg') }}" /></a>
                        @else                    
                            <a href="{{$chatDetail->attachment_url}}" download><img class="pull-right attachment-image" src="{{ asset('img/attachement_placeholder.jpg') }}" /></a>
				        @endif
                    </div>
                </div>
        
        @empty
			<div class="col-md-12">No media found!</div>
		@endforelse
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>