<div class="modal-header">
    <h5 class="modal-title"><i class="icon-note"></i> @lang("modules.messages.startConversation")</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

{!! Form::open(['id'=>'createChat','class'=>'ajax-form','method'=>'POST']) !!}
<div class="modal-body">
    

        
        <div class="form-body">
            @if($messageSetting->allow_client_admin == 'yes')
            <div class="form-group">
            <div class="radio-list">
                <label class="radio-inline p-0">
                    <div class="radio radio-info">
                        <input type="radio" name="user_type" id="user_employee" value="employee" checked>
                        <label for="user_employee">@lang('app.menu.employees')</label>
                    </div>
                </label>
                <label class="radio-inline">
                    <div class="radio radio-info">
                        <input type="radio" name="user_type" id="user_client" value="client">
                        <label for="user_client">@lang('app.menu.clients')</label>
                    </div>
                </label>
            </div>
        </div>
            @endif

            <div class="row">
                <div class="col-md-12 " id="member-list">
                    <div class="form-group">
                        <label>@lang("modules.messages.chooseMember")</label>
                        <select class="select2 form-control" data-placeholder="@lang("modules.messages.chooseMember")" name="user_id" id="user_id">
                            @foreach($members as $member)
                                <option
                                        value="{{ $member->id }}">{{ ucwords($member->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if($messageSetting->allow_client_admin == 'yes')
                <div class="col-md-12 " id="client-list" style="display: none">
                    <div class="form-group">
                        <label>@lang("modules.client.clientName")</label>
                        <select class="select2 form-control" data-placeholder="@lang("modules.client.clientName")" name="client_id" id="client_id">
                            @foreach($clients as $client)
                                <option
                                        value="{{ $client->id }}">{{ ucwords($client->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">@lang("modules.messages.message")</label>
                        <textarea name="message" class="form-control" id="message" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>

        
        
    
</div>
<div class="modal-footer">
    <div class="form-actions">
        <button type="button" id="post-message" class="btn btn-primary"><i class="fa fa-send-o"></i> @lang("modules.messages.send")</button>
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
{!! Form::close() !!}




<script>

    $('.select2').select2();

    $("input[name=user_type]").click(function () {
        if($(this).val() == 'client'){
            $('#member-list').hide();
            $('#client-list').show();
        }
        else{
            $('#client-list').hide();
            $('#member-list').show();
        }
    })

    $('#post-message').click(function () {
        $.easyAjax({
            url: '{{route('admin.user-chat.message-submit')}}',
            container: '#createChat',
            type: "POST",
            data: $('#createChat').serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    var blank = "";
                    $('#submitTexts').val('');

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

                    $('#newChatModal').modal('hide');
                }
            }
        })
    });
</script>