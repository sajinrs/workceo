
<div class="chat-user-profile">
	<div class="image">
		<div class="avatar text-center">
			@if(is_null($member->image))
				<img src="{{ asset('img/default-profile-3.png') }}" width="158" alt="{{$member->name}}" />
			@else
				<img src="{{ asset_url('avatar/'.$member->image) }}" width="158" alt="{{$member->name}}" />
			@endif
		</div>
	</div>

	<div class="user-content">
		<h5 class="m-t-15 m-b-0 text-center">{{$member->name}}</h5>							
		<hr class="m-t-5">
		<p><label>Telephone</label><br />
		<span class="gray-text">{{$member->mobile}}</span></p>

		<p><label>Email</label><br />
		<span class="gray-text">{{$member->email}}</span></p>

		<p><label>Media</label> <a href="javascript:;" onclick="allMedia({{$member->id}})" class="pull-right">Show more</a></p>

		<ul class="user-media">
		@forelse($chatDetails as $key => $chatDetail)
			@if($key < 3)
				@php $extension = pathinfo(storage_path($chatDetail->attachment_url), PATHINFO_EXTENSION); @endphp
				<li>
					@if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'png')
						<img src="{{$chatDetail->attachment_url}}" />
					@elseif($extension == 'pdf')
						<a href="{{$chatDetail->attachment_url}}" download><img class="pull-right attachment-image" src="{{ asset('img/pdf-placeholder.jpg') }}" /></a>
					@elseif($extension == 'mp4' || $extension == 'avi')
						<a href="{{$chatDetail->attachment_url}}" download><img class="pull-right attachment-image" src="{{ asset('img/video-placeholder.jpg') }}" /></a>
					@else
						<a href="{{$chatDetail->attachment_url}}" download><img class="pull-right attachment-image" src="{{ asset('img/attachement_placeholder.jpg') }}" /></a>									
					@endif
					@if($key == 2 && $mediaCount > 4) <h5 class="text">+{{$mediaCount - 3}}</h5> @endif	
				</li>
			@endif
		@empty
			<div class="message">No media found!</div>
		@endforelse
		</ul>

	</div>
</div>

<div class="modal fade bs-modal-md in" id="mediaModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" id="modal-data-application">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modelHeading">Media</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				Loading...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<script>
function allMedia(id)
{
	var url = "{{ route('member.user-chat.member-media',':id') }}";
		url = url.replace(':id', id);

		$('#mediaModal').modal('show');

	$.easyAjax({
		type: 'GET',
		url: url,
		messagePosition: '',
		data:  {media:'all'},
		container: ".user-content",
		error: function (response) {
			$('#mediaModal .modal-content').html(response.responseText);
		}
	});
}
</script>