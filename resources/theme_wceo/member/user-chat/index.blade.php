@extends('layouts.member-app')

@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        <a href="javascript:;" id="new-chat" class="btn btn-primary btn-sm"> @lang("modules.messages.startConversation") <i data-feather="message-square"></i></a>
                    </div>
                </div>
                <!-- Bookmark Ends-->

            </div>
        </div>
    </div>


    <div class="container-fluid">       
        <div class="row">
            <div class="col-md-3 p-r-0 call-chat-sidebar col-sm-12">
                <div class="card">
                  <div class="card-body chat-body">
                    <div class="chat-box">
                      <!-- Chat left side Start-->
                      <div class="chat-left-aside">
                       
                        <div class="people-list" id="people-list">
                          <div class="search">
                            <form class="theme-form">
                              <div class="form-group">
                                <input class="form-control" type="text" id="userSearch" placeholder="@lang("modules.messages.searchContact")"><i class="fa fa-search"></i>
                              </div>
                            </form>
                          </div>
                          <ul class="list userList">
                            @forelse($userList as $users)
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
                                    @lang("messages.noUser")
                                </li>
                            @endforelse                            
                          </ul>
                        </div>
                      </div>
                      <!-- Chat left side Ends-->
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 call-chat-body">
                <div class="card">
                  <div class="card-body p-0">
                    <div class="row chat-box">
                      <!-- Chat right side start-->
                      <div class="col pr-0 chat-right-aside">
                        <!-- chat start-->
                        <div class="chat">
                          <!-- chat-header start-->
                          
                          <!-- chat-header end-->
                          <div class="chat-history chat-msg-box custom-scrollbar">
                            <ul class="chat-list  p-t-30 chats"></ul>                            
                          </div>
                          <!-- end chat-history-->
                          {!! Form::open(['id'=>'createMessage','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="chat-message clearfix">
                                <div class="row">
                                    <div class="col-xl-12 d-flex">
                                    
                                        <div class="input-group text-box">
                                        <input type="text" name="message" id="submitTexts" autocomplete="off" placeholder="@lang("modules.messages.typeMessage")" class="form-control input-txt-bx">
                                        <input id="dpID" value="{{$dpData}}" type="hidden"/>
                                        <input id="dpName" value="{{$dpName}}" type="hidden"/>
                                        <input type="file" class="d-none" id="attachment" name="attachment" />
                                        <label for="attachment"><i class="icon-clip"></i></label>
										<a data-toggle="modal" href="#emojiModal" class="smiley-box2"><span  class="picker" style="font-size: 30px"><i class="icon-face-smile"></i></span></a>
                                        
                                        <div class="input-group-append">
                                            <button id="submitBtn" class="btn btn-primary" type="button">@lang("modules.messages.send")</button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                          
                        </div>
                    </div>
                      
                    </div>
                  </div>
                </div>
            </div>

            <div class="col-md-3 p-l-0">

            	<div class="card">
					<div class="card-body member-info">
						<ul class="nav nav-tabs border-tab nav-primary" id="info-tab" role="tablist">
							<li class="nav-item">
								<a class="nav-link active text-primary" id="profile-info-tab" data-toggle="tab" href="#teamMember" role="tab">Team Member</a>
							</li>
							<li class="nav-item">
								<a class="nav-link text-primary" id="contact-info-tab" data-toggle="tab" href="#info-contact" role="tab">My Info</a>
							</li>
						</ul>

						<div class="tab-content" id="info-tabContent">
							
							<div class="tab-pane fade show active" id="teamMember" role="tabpanel" aria-labelledby="profile-info-tab">
								
								
							</div>

							<div class="tab-pane fade" id="info-contact" role="tabpanel" aria-labelledby="contact-info-tab">
							
								<div class="chat-user-profile">
									<div class="image">
										<div class="avatar text-center">
											@if(is_null($user->image))
												<img width="158" src="{{ asset('img/default-profile-3.png') }}" alt="{{ ucwords($user->name) }}">
											@else
												<img width="158" src="{{ asset_url('avatar/'.$user->image) }}" alt="{{ ucwords($user->name) }}">
											@endif											
										</div>
									</div>
									<div class="user-content">
										<h5 class="m-t-15 m-b-0 text-center">{{ ucwords($user->name) }}</h5>							
										<hr class="m-t-5">
										<p>
										<select id="userStatus" name="chat_status">
						  				</select>
						  				</p>
										<p><label>Telephone</label><br />
										<span class="gray-text">{{$user->mobile}}</span></p>

										<p><label>Email</label><br />
										<span class="gray-text">{{$user->email}}</span></p>										
									</div>
								</div>
							</div>
						</div>
					</div>
                </div>
            </div>

        </div>
    </div>


    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="newChatModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">@lang('modules.timeLogs.startTimer')</h5>
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
    {{--Ajax Modal Ends--}}

    {{--Ajax Modal Ends--}}
    <div class="modal fade" id="emojiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Emojis</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                        <ul class="nav nav-tabs nav-right" id="right-tab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="right-home-tab" data-toggle="tab" href="#right-home" role="tab" aria-controls="right-home" aria-selected="true">&#128522;</a></li>
                            <li class="nav-item"><a class="nav-link" id="profile-right-tab" data-toggle="tab" href="#right-profile" role="tab" aria-controls="profile-icon" aria-selected="false">&#128578;</a></li>
                            <li class="nav-item"><a class="nav-link" id="contact-right-tab" data-toggle="tab" href="#right-contact" role="tab" aria-controls="contact-icon" aria-selected="false">&#129380;</a></li>
                        </ul>
                        <div class="tab-content" id="right-tabContent">
                            <div class="tab-pane fade show active" id="right-home" role="tabpanel" aria-labelledby="right-home-tab">
                                @for($i=128512;$i<128568;$i++)
                                    <span class="emoji_icon" style='font-size:32px; cursor: pointer;' id="{{$i}}">&#{{$i}};</span>
                                @endfor
                            </div>
                            <div class="tab-pane fade" id="right-profile" role="tabpanel" aria-labelledby="profile-right-tab">
                                @for($i=128577;$i<128592;$i++)
                                    <span class="emoji_icon" style='font-size:32px; cursor: pointer;' id="{{$i}}">&#{{$i}};</span>
                                @endfor
                                @for($i=129293;$i<129339;$i++)
                                    <span class="emoji_icon" style='font-size:32px; cursor: pointer;' id="{{$i}}">&#{{$i}};</span>
                                @endfor
                            </div>
                            <div class="tab-pane fade" id="right-contact" role="tabpanel" aria-labelledby="contact-right-tab">
                                @for($i=129340;$i<129488;$i++)
                                    <span class="emoji_icon" style='font-size:32px; cursor: pointer;' id="{{$i}}">&#{{$i}};</span>
                                @endfor
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('footer-script')
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>

<script type="text/javascript">

    $('.chat-left-inner > .chatonline').slimScroll({
        height: '100%',
        position: 'right',
        size: "0px",
        color: '#dcdcdc',

    });
    $(function () {
        $(window).load(function () { // On load
            $('.chat-list').css({'height': (($(window).height()) - 370) + 'px'});
        });
        $(window).resize(function () { // On resize
            $('.chat-list').css({'height': (($(window).height()) - 370) + 'px'});
        });
    });

    // this is for the left-aside-fix in content area with scroll

    $(function () {
        $(window).load(function () { // On load
            $('.chat-left-inner').css({
                'height': (($(window).height()) - 240) + 'px'
            });
        });
        $(window).resize(function () { // On resize
            $('.chat-left-inner').css({
                'height': (($(window).height()) - 240) + 'px'
            });
        });
    });


    $(".open-panel").click(function () {
        $(".chat-left-aside").toggleClass("open-pnl");
        $(".open-panel i").toggleClass("ti-angle-left");
    });
</script>
<script>

    $(function () {
        $('#userList').slimScroll({
            height: '350px'
        });
    });

    var dpButtonID = "";
    var dpName = "";
    var scroll = true;

    var dpClassID = '{{$dpData}}';

    if (dpClassID) {
        $('#dp_' + dpClassID).addClass('active');
    }

    //getting data
    getChatData(dpButtonID, dpName);

    window.setInterval(function(){
        if ($('#mediaModal.in.show').length == 0) {
            getChatData(dpButtonID, dpName);
        }
        /// call your function here
    }, 30000);

    $('#submitTexts').keypress(function (e) {

        var key = e.which;
        if (key == 13)  // the enter key code
        {
            e.preventDefault();
            $('#submitBtn').click();
            return false;
        }
    });

    $("#attachment").change(function(e) {
		submitMessage(e);
    });

    //submitting message
    $('#submitBtn').on('click', function (e) {
        submitMessage(e);
    });

    //submitting message
    function submitMessage(e)
    {
        e.preventDefault();
        //getting values by input fields
        var submitText = $('#submitTexts').val();
        var dpID = $('#dpID').val();
        //checking fields blank
        if ( (submitText == "" || submitText == undefined || submitText == null) && (document.getElementById("attachment").files.length == 0) ) {
            $('#errorMessage').html('<div class="alert alert-danger"><p>Message field cannot be blank</p></div>');
            return;
        } else if ( (dpID == '' || submitText == undefined) && (document.getElementById("attachment").files.length == 0) ) {
            $('#errorMessage').html('<div class="alert alert-danger"><p>No user for message</p></div>');
            return;
        } else {

            var url = "{{ route('member.user-chat.message-submit') }}";
            var token = "{{ csrf_token() }}";
            $.easyAjax({
                type: 'POST',
                url: url,
                messagePosition: '',
                data: {'message': submitText, 'user_id': dpID, '_token': token},
                file: (document.getElementById("attachment").files.length == 0) ? false : true,
                container: "#createMessage",
                blockUI: true,
                redirect: false,
                success: function (response) {
                    var blank = "";
                    $('#submitTexts').val('');
                    $('#attachment').val("");

                    //getting values by input fields
                    var dpID = $('#dpID').val();
                    var dpName = $('#dpName').val();


                    //set chat data
                    getChatData(dpID, dpName);

                    //set user list
                    $('.userList').html(response.userList);

                    //set active user
                    if (dpID) {
                        $('#dp_' + dpID + 'a').addClass('active');
                    }
                }
            });
        }

        return false;
    }

    //getting all chat data according to user
    //submitting message
    $("#userSearch").keyup(function (e) {
        var url = "{{ route('member.user-chat.user-search') }}";

        $.easyAjax({
            type: 'GET',
            url: url,
            messagePosition: '',
            data: {'term': this.value},
            container: ".userList",
            success: function (response) {
                //set messages in box
                $('.userList').html(response.userList);
            }
        });
    });

    //getting all chat data according to user
    function getChatData(id, dpName, scroll) {
        var getID = '';
        $('#errorMessage').html('');
        if (id != "" && id != undefined && id != null) {
            $('.userList li.active ').removeClass('active');
            $('#dpa_' + id).closest('li').addClass('active');
            $('#dpID').val(id);
            getID = id;
            $('#badge_' + id).val('');
        } else {
            $('.userList li:first-child a').addClass('active');
            getID = $('#dpID').val();
        }

        getMemberChatMedia(getID);

        var url = "{{ route('member.user-chat.index') }}";

        $.easyAjax({
            type: 'GET',
            url: url,
            messagePosition: '',
            data: {'userID': getID},
            container: ".chats",
            success: function (response) {
                //set messages in box
                $('.chats').html(response.chatData);
                scrollChat();
            }
        });
    }

    function scrollChat() {
        $(".chat-history").animate({
            scrollTop: $(
                '.chat-history').get(0).scrollHeight
        }, 2000);
    }

    $('#new-chat').click(function () {
        var url = '{{ route('member.user-chat.create')}}';
        $('#modelHeading').html('Start Conversation');
        $.ajaxModal('#newChatModal',url);
    })

    $('.emoji_icon').on('click',function () {
        $('#emojiModal').modal('toggle');
        var submitText = $(this).attr('id');
        var dpID = $('#dpID').val();
        submitText = '&#'+ submitText;
        var url = "{{ route('member.user-chat.emoji-submit') }}";
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            type: 'POST',
            url: url,
            messagePosition: '',
            data: {'message': submitText, 'user_id': dpID, '_token': token},
            container: "#createMessage",
            blockUI: true,
            redirect: false,
            success: function (response) {
                var blank = "";
                //$('#submitTexts').val('');


                //getting values by input fields
                var dpID = $('#dpID').val();
                var dpName = $('#dpName').val();


                //set chat data
                getChatData(dpID, dpName);

                //set user list
                $('.userList').html(response.userList);

                //set active user
                if (dpID) {
                    $('#dp_' + dpID + 'a').addClass('active');
                }
            }
        });
    });

    function getMemberChatMedia(getID)
	{
		var url = "{{ route('member.user-chat.member-media',':id') }}";
            url = url.replace(':id', getID);

        $.easyAjax({
            type: 'GET',
            url: url,
            messagePosition: '',
            data:  {},
            container: ".chats",
            error: function (response) {
                //set notes in box
               $('#teamMember').html(response.responseText);
            }
        });
	}

    $('#userStatus').change(function(){
        var status = $(this).val();
        var url = "{{ route('member.user-chat.chat-status') }}";
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'status': status, '_token': token},
            container: "#info-contact",
            redirect: false,
            success: function (response) {
                
            }
        });
    })

    var chatStatus = '{{$user->chat_status}}';
    $('#userStatus').select2({
            minimumResultsForSearch: -1,
            data: [{
                id: 'available',
                text: '<i class="text-success fas fa-circle"></i> Available',
            },{
                id: 'busy',
                text: '<i class="text-danger fas fa-circle"></i> Busy',
                selected: (chatStatus == "busy") ? true : false                
            },{
                id: 'disturb',
                text: '<i class="text-warning fas fa-circle"></i> Do not disturb',
                selected: (chatStatus == "disturb") ? true : false
            },{
                id: 'back',
                text: '<i class="text-purple fas fa-circle"></i> Be right back',
                selected: (chatStatus == "back") ? true : false
            },{
                id: 'away',
                text: '<i class="text-dark fas fa-circle"></i> Away',
                selected: (chatStatus == "away") ? true : false
            }],
            escapeMarkup: function(markup) {
                return markup;
            }
        });

</script>
@endpush