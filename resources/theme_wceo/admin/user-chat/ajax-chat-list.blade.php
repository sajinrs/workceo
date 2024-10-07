@forelse($chatDetails as $chatDetail)


    <li class="@if($chatDetail->from == $user->id) odd @else  @endif"> 
        <div class="chat-wrap">
            @if($chatDetail->from == $user->id)
                <div class="user-image-wrap pull-right">
                    @if(is_null($chatDetail->fromUser->image))
                        <img src="{{ asset('img/default-profile-3.png') }}" alt="user-img" class="rounded-circle float-right chat-user-img img-50">
                        {{$chatDetail->fromUser->first_name}}
                    @else
                        <img src="{{ asset_url('avatar/' . $chatDetail->fromUser->image) }}" alt="user-img" class="rounded-circle float-right chat-user-img img-50">
                        {{$chatDetail->fromUser->first_name}}
                    @endif  
                </div> 

                <div class="msg-wrap pull-right">
                    @if($chatDetail->message && $chatDetail->message_type == 'text')
                        <div class="message my-message pull-right msg-outgoing text-white from-msg">
                            {{ $chatDetail->message }}
                        </div>
                    @endif
                    
                    @if($chatDetail->message_type == 'emoji')
                       <div style="font-size: 34px" class="pull-right emoji-message from-msg">{!! $chatDetail->message !!}</div>
                    @endif
                    @if($chatDetail->attachment_url)
                    @php $extension = pathinfo(storage_path($chatDetail->attachment_url), PATHINFO_EXTENSION); @endphp
                        <div class="attachment-wrap">
                            @if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'png')
                                <img class="pull-right attachment-image" src="{{$chatDetail->attachment_url}}" />
                            @elseif($extension == 'pdf')
                                <a href="{{$chatDetail->attachment_url}}" download><img style="height:auto" class="pull-right attachment-image" src="{{ asset('img/pdf-placeholder.jpg') }}" /></a>
                            @elseif($extension == 'mp4' || $extension == 'avi')
                                <a href="{{$chatDetail->attachment_url}}" download><img style="height:auto" class="pull-right attachment-image" src="{{ asset('img/video-placeholder.jpg') }}" /></a>
                            @else
                                <a href="{{$chatDetail->attachment_url}}" download><img class="pull-right attachment-image" src="{{ asset('img/attachement_placeholder.jpg') }}" /></a>
                            @endif
                        </div>                       

                    @endif
                    <div class="message-data-time pull-right">{{ $chatDetail->created_at->timezone($global->timezone)->format('l, F j, Y'.' - '. $global->time_format) }}</div>
                </div>
                
            @else
                
                <div class="user-image-wrap incoming">
                    @if(is_null($chatDetail->fromUser->image))
                        <img src="{{ asset('img/default-profile-3.png') }}" alt="user-img" class="rounded-circle float-left chat-user-img img-50">
                        {{$chatDetail->fromUser->first_name}}
                    @else
                        <img src="{{ asset_url('avatar/' . $chatDetail->fromUser->image) }}" alt="user-img" class="rounded-circle float-left chat-user-img img-50">
                        {{$chatDetail->fromUser->first_name}}
                    @endif
                </div>
                
                <div class="msg-wrap">
                    @if($chatDetail->message_type == 'emoji')
                        <div style="font-size: 34px">
                            {!! $chatDetail->message !!}
                        </div>
                    @elseif($chatDetail->message && $chatDetail->message_type == 'text')
                        <div class="message my-message msg-incoming">
                            {{$chatDetail->message }}
                        </div>
                    @endif

                    @if($chatDetail->attachment_url)
                    @php $extension = pathinfo(storage_path($chatDetail->attachment_url), PATHINFO_EXTENSION); @endphp
                        <div class="attachment-wrap">
                            @if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'png')
                                <img class="pull-left attachment-image" src="{{$chatDetail->attachment_url}}" />
                            @elseif($extension == 'pdf')
                                <a href="{{$chatDetail->attachment_url}}" download><img class="pull-left attachment-image" src="{{ asset('img/pdf-placeholder.jpg') }}" /></a>
                            @elseif($extension == 'mp4' || $extension == 'avi')
                                <a href="{{$chatDetail->attachment_url}}" download><img class="pull-left attachment-image" src="{{ asset('img/video-placeholder.jpg') }}" /></a>
                            @else
                                <a href="{{$chatDetail->attachment_url}}" download><img class="pull-left attachment-image" src="{{ asset('img/attachement_placeholder.jpg') }}" /></a>
                            @endif
                        </div>
                    @endif

                    <span class="message-data-time">{{ $chatDetail->created_at->timezone($global->timezone)->format('l, F j, Y'.' - '. $global->time_format) }}</span>
                </div>           
            @endif
        </div>
    </li>
@empty
    <li><div class="message">@lang('messages.noMessage')</div></li>
@endforelse
