@forelse($userLists as $users)

    <li id="dp_{{$users->id}}" class="clearfix">
        <a href="javascript:void(0)" id="dpa_{{$users->id}}" onclick="getChatData('{{$users->id}}', '{{$users->name}}')">
        @if(is_null($users->image))
            <img src="{{ asset('img/default-profile-3.png') }}" alt="user-img" class="rounded-circle user-image">
        @else
            <img src="{{ asset_url('avatar/'.$users->image) }}" alt="user-img"
                    class="rounded-circle user-image">
        @endif
        
        <div class="status-circle {{$users->chat_status}}"></div>
            <div class="about">
                <div class="uer-info">
                    <span class="user-name" @if($users->message_seen == 'no' && $users->user_one != $user->id) class="font-bold" @endif> 
                        {{$users->name}}                                    
                    </span> 
                </div>

                <span class="designation">{{$users->designation_name}}</span>
                <small class="text-simple">
                    <br />
                    @if(\App\User::isAdmin($users->id))
                        <label class="btn btn-danger btn-xs btn-outline">Admin</label>
                    @elseif(\App\User::isClient($users->id))
                        <label class="btn btn-success btn-xs btn-outline">Client</label>
                    @else
                        <label class="btn btn-warning btn-xs btn-outline">Employee</label>
                    @endif
                        <span class="seenTime">@if($users->last_message){{  \Carbon\Carbon::parse($users->last_message)->diffForHumans(null, true)}} @endif</span>
                </small>
                @php $unSeenCOunt = chantUnseenCount($users->from) @endphp
                @if($unSeenCOunt != 0)
                <div class="waiting-msg-count">{{chantUnseenCount($users->from)}}</div>
                @endif
            </div>
            
            </a>
    </li>    
@empty
    <li>
        <a href="javascript:void(0)">
            <span>
                @lang('messages.noConversation')
            </span>
        </a>
    </li>
@endforelse
