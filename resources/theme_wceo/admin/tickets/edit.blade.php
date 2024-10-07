@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
<style>
    .tagsarea .select2.select2-container {
    display: none;
}
.tagsarea .bootstrap-tagsinput {
    width: 100%;
    padding: 12px;
}
.form-control.form-control-lg.tagsarea {
    padding: 0;
    border: none;
}
.tagsarea .bootstrap-tagsinput .tag{padding:5px;}
</style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.update')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-5">
                <div class="card">
                {!! Form::open(['id'=>'updateTicket1','class'=>'ajax-form updateTicket','method'=>'POST']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('app.update') {{ __($pageTitle) }} <span class="text-info text-uppercase pull-right"></span></h4>
                        
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-label-group form-group"> 
                                        <select  name="agent_id" id="agent_id" class="form-control form-control-lg" placeholder="-" >
                                            <option value="">-</option>
                                            @forelse($groups as $group)
                                                @if(count($group->enabled_agents) > 0)
                                                    <optgroup label="{{ ucwords($group->group_name) }}">
                                                        @foreach($group->enabled_agents as $agent)
                                                            <option
                                                                    @if($agent->user->id == $ticket->agent_id)
                                                                    selected
                                                                    @endif
                                                                    value="{{ $agent->user->id }}">{{ ucwords($agent->user->name).' ['.$agent->user->email.']' }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            @empty
                                                <option value="">@lang('messages.noGroupAdded')</option>
                                            @endforelse
                                        </select>
                                        <label for="agent_id" class="control-label">@lang('modules.tickets.agent')</label>
                                    </div>
                                </div>


                            </div>

                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-label-group form-group">  
                                        <select class="form-control selectpicker add-type" name="type_id" id="type_id" data-style="form-control">
                                            @forelse($types as $type)
                                                <option
                                                        @if($type->id == $ticket->type_id)
                                                        selected
                                                        @endif
                                                        value="{{ $type->id }}">{{ ucwords($type->type) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noTicketTypeAdded')</option>
                                            @endforelse
                                        </select>
                                        <label for="type_id" class="control-label">@lang('modules.invoices.type') </label>
                                    </div>
                                </div>

                                <div class="col-md-5 pl-0">
                                    <a href="javascript:;" id="add-type" class="btn btn-sm btn-outline btn-block btn-primary"><i class="fa fa-plus"></i> @lang('modules.tickets.addType')</a>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-label-group form-group">  
                                        <select class="form-control form-control-lg" name="priority" id="priority">
                                            <option @if($ticket->priority == 'low') selected @endif value="low">@lang('app.low')</option>
                                            <option @if($ticket->priority == 'medium') selected @endif value="medium">@lang('app.medium')</option>
                                            <option @if($ticket->priority == 'high') selected @endif value="high">@lang('app.high')</option>
                                            <option @if($ticket->priority == 'urgent') selected @endif value="urgent">@lang('app.urgent')</option>
                                        </select>
                                        <label for="priority" class="required">@lang('modules.tasks.priority') </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-label-group form-group">  
                                        <select class="form-control form-control-lg" name="channel_id" id="channel_id" data-style="form-control">
                                        @forelse($channels as $channel)
                                            <option value="{{ $channel->id }}"
                                                    @if($channel->id == $ticket->channel_id)
                                                    selected
                                                    @endif
                                            >{{ ucwords($channel->channel_name) }}</option>
                                        @empty
                                            <option value="">@lang('messages.noTicketChannelAdded')</option>
                                        @endforelse
                                        </select>
                                        <label for="channel_id" class="control-label">@lang('modules.tickets.channelName')</label>
                                    </div>
                                </div>

                                <div class="col-md-5 pl-0">
                                    <a href="javascript:;" id="add-channel" class="btn btn-sm btn-outline btn-block btn-primary"><i class="fa fa-plus"></i> @lang('modules.tickets.addChannel')</a>
                                </div>

                                <div class="col-md-12">
                                    <label for="tags" class="control-label">@lang('modules.tickets.tags')</label>
                                    <div class="form-control form-control-lg tagsarea">  
                                        
                                        <select multiple data-role="tagsinput" name="tags[]" id="tags">
                                            @foreach($ticket->tags as $tag)
                                                <option value="{{ $tag->tag->tag_name }}">{{ $tag->tag->tag_name }}</option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions col-md-3  offset-md-9 ">
                        <button type="button" class="btn btn-primary submit-ticket-2">@lang('app.save')</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

            <div class="col-sm-7">
                <div class="card">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-md-7">  <h4 class="page-title mb-0"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} </h4> </div>
                            <div class="col-md-5">
                                <span class="text-primary text-uppercase font-bold pull-right">@lang('modules.tickets.ticket') # {{ $ticket->id }}</span>
                                <span id="ticket-status" class="m-r-5 pull-right">
                                <label class="badge
                                    @if($ticket->status == 'open')
                                        badge-danger
                                @elseif($ticket->status == 'pending')
                                        badge-warning
                                @elseif($ticket->status == 'resolved')
                                    badge-info
                                @elseif($ticket->status == 'closed')
                                    badge-success
                                @endif
                                        ">{{ $ticket->status }}</label>
                                </span>
                            </div>
                        </div>   
                    </div>

                    <div class="card-body">
                        {!! Form::open(['id'=>'updateTicket2','class'=>'ajax-form updateTicket','method'=>'PUT', 'files' => true]) !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12 m-b-10">
                                    <h4 class="text-capitalize text-primary">{{ $ticket->subject }}</h4>

                                    <div class="font-12">{{ $ticket->created_at->timezone($global->timezone)->format($global->date_format.' '.$global->time_format) }} &bull; {{ ucwords($ticket->requester->name). ' <'.$ticket->requester->email.'>' }}</div>
                                </div>

                            {!! Form::hidden('status', $ticket->status, ['id' => 'status']) !!}
                            </div>

                            <div id="ticket-messages" class="@if(count($ticket->reply) > 11) slimscroll @endif" >

                        @forelse($ticket->reply as $reply)
                            <div class="panel-body msgReply @if($reply->user->id == $user->id) bg-owner-reply @else bg-other-reply @endif " id="replyMessageBox_{{$reply->id}}">

                                <div class="row m-b-5">

                                    <div class="col-xs-2 col-md-1">
                                        <img src="{{ $reply->user->image_url }}"  alt="user" class="img-circle" width="40" height="40">
                                    </div>
                                    <div class="col-xs-8 col-md-10">
                                        <h5 class="m-t-0 mb-0 font-bold">
                                            <a
                                                    @if($reply->user->hasRole('employee'))
                                                    href="{{ route('admin.employees.show', $reply->user_id) }}"
                                                    @elseif($reply->user->hasRole('client'))
                                                    href="{{ route('admin.clients.show', $reply->user_id) }}"
                                                    @endif
                                                    class="text-inverse">{{ ucwords($reply->user->name) }}
                                                
                                            </a>
                                        </h5>
                                        <span class="replyTime">{{ $reply->created_at->timezone($global->timezone)->format($global->date_format.' '.$global->time_format) }}</span>

                                        <div class="ticketMessage">
                                            {!! ucfirst(nl2br($reply->message)) !!}
                                        </div>
                                    </div>

                                    <div class="col-xs-2 col-md-1">
                                        <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete"
                                        data-file-id="{{ $reply->id }}"
                                        class="btn-trash sa-params" data-pk="list"><i
                                                    class="fa fa-trash"></i></a>
                                    </div>


                                </div>
                                @if(sizeof($reply->files) > 0)
                                    <div class="bg-white" id="list">
                                        <ul class="list-group m-t-15" id="files-list">
                                            @forelse($reply->files as $file)
                                                <li class="list-group-item justify-content-between align-items-center">
                                                {{ $file->filename }}
                                                <span class="pull-right">
                                                    <small class="text-muted m-r-10">{{ $file->created_at->diffForHumans() }}</small>

                                                <a target="_blank" href="{{ $file->file_url }}" data-toggle="tooltip" data-original-title="View" class="badge badge-info"><i class="fa fa-search m-r-0"></i></a>
                                                @if(is_null($file->external_link))
                                                                &nbsp;&nbsp;
                                                                <a href="{{ route('admin.ticket-files.download', $file->id) }}"
                                                                data-toggle="tooltip" data-original-title="Download"
                                                                class="badge badge-dark"><i
                                                                            class="fa fa-download"></i></a>
                                                            @endif
                                               
                                                    </span>

                                                   
                                                </li>
                                            @empty
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            @lang('messages.noFileUploaded')
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforelse

                                        </ul>
                                    </div>
                                <!--/row-->
                                @endif
                            </div>
                        @empty
                            <div class="panel-body b-b">

                                <div class="row">

                                    <div class="col-md-12">
                                        @lang('messages.noMessage')
                                    </div>

                                </div>
                                <!--/row-->

                            </div>
                        @endforelse
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-sm" id="reply-toggle" type="button"><i class="fa fa-reply"></i> @lang('app.reply')
                           </button>
                        </div>
                    </div>

                    <div id="reply-section" style="display: none;">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">@lang('modules.tickets.reply') </label></label>
                                    <textarea class="textarea_editor form-control" rows="10" name="message"
                                            id="message"></textarea>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row m-b-20">
                            <div class="col-md-12">
                                <div class="form-group">
                                        <label class="control-label">@lang('app.file') </label>
                                    <input type="file" name="file[]" id="file" class="form-control" multiple>
                                </div>
                            </div>
                        <!--/row-->
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-right">
                                <div class="btn-group dropup m-r-10">
                                    <button aria-expanded="true" data-toggle="dropdown"
                                            class="btn btn-info btn-outline dropdown-toggle waves-effect waves-light"
                                            type="button"><i class="fa fa-bolt"></i> @lang('modules.tickets.applyTemplate')
                                        <span class="caret"></span></button>
                                    <ul role="menu" class="dropdown-menu">
                                        @forelse($templates as $template)
                                            <li><a href="javascript:;" data-template-id="{{ $template->id }}"
                                                class="apply-template">{{ ucfirst($template->reply_heading) }}</a></li>
                                        @empty
                                            <li>@lang('messages.noTemplateFound')</li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="btn-group dropup ticketSubmit">
                                    <button aria-expanded="true" data-toggle="dropdown"
                                            class="btn btn-primary dropdown-toggle waves-effect waves-light"
                                            type="button">@lang('app.submit') <span class="caret"></span></button>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        <li>
                                            <a href="javascript:;" class="submit-ticket" data-status="open">@lang('app.submit')
                                                as Open
                                                <span style="width: 15px; height: 15px;"
                                                    class="btn btn-danger btn-small btn-circle">&nbsp;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" class="submit-ticket"
                                            data-status="pending">@lang('app.submit') as @lang('app.pending')
                                                <span style="width: 15px; height: 15px;"
                                                    class="btn btn-warning btn-small btn-circle">&nbsp;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" class="submit-ticket"
                                            data-status="resolved">@lang('app.submit') as Resolved
                                                <span style="width: 15px; height: 15px;"
                                                    class="btn btn-info btn-small btn-circle">&nbsp;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" class="submit-ticket"
                                            data-status="closed">@lang('app.submit') as Closed
                                                <span style="width: 15px; height: 15px;"
                                                    class="btn btn-success btn-small btn-circle">&nbsp;</span>
                                            </a>
                                        </li>
                                    </ul>
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




    

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="ticketModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection


@push('footer-script')
<script src="{{ asset('plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.custom.js')}}"></script>

<script>
    

    /* $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    }); */

    $('#message').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen"]]
        ]
    });

    $('#reply-toggle').click(function () {
        $('#reply-toggle').hide();
        $('#reply-section').show();
    })

    $('.apply-template').click(function () {
        var templateId = $(this).data('template-id');
        var token = '{{ csrf_token() }}';

        $.easyAjax({
            url: '{{route('admin.replyTemplates.fetchTemplate')}}',
            type: "POST",
            data: {_token: token, templateId: templateId},
            success: function (response) {
                if (response.status == "success") {
                    //var editorObj = $("#message").data('wysihtml5');
                    /* var editor = editorObj.editor;
                    editor.setValue(response.replyText); */
                    $("#message").summernote("code", response.replyText);
                }
            }
        })
    })


    $('.submit-ticket').click(function () {

        var status = $(this).data('status');
        $('#status').val(status);

        $.easyAjax({
            url: '{{route('admin.tickets.update', $ticket->id)}}',
            container: '#updateTicket2',
            type: "POST",
            data: $('#updateTicket2').serialize(),
            file: true,
            success: function (response) {
                if(response.status == 'success'){
                    $('#scroll-here').remove();

                    if(response.lastMessage != null){
                        $('#ticket-messages').append(response.lastMessage);
                    }
                    //$('#message').data("wysihtml5").editor.clear();
                    $('#message').summernote('reset');
                    $('#file').val('');
                    
                    // update status on top
                    if(status == 'open')
                        $('#ticket-status').html('<label class="badge badge-danger">'+status+'</label>');
                    else if(status == 'pending')
                        $('#ticket-status').html('<label class="badge badge-warning">'+status+'</label>');
                    else if(status == 'resolved')
                        $('#ticket-status').html('<label class="badge badge-info">'+status+'</label>');
                    else if(status == 'closed')
                        $('#ticket-status').html('<label class="badge badge-success">'+status+'</label>');

                    scrollChat();
                }
            }
        })
    });

    $('.submit-ticket-2').click(function () {

        $.easyAjax({
            url: '{{route('admin.tickets.updateOtherData', $ticket->id)}}',
            container: '#updateTicket1',
            type: "POST",
            data: $('#updateTicket1').serialize()
        })
    });

    $('#add-type').click(function () {
        var url = '{{ route("admin.ticketTypes.createModal")}}';
        $('#modelHeading').html("{{ __('app.addNew').' '.__('modules.tickets.ticketTypes') }}");
        $.ajaxModal('#ticketModal', url);
    })

    $('#add-channel').click(function () {
        var url = '{{ route("admin.ticketChannels.createModal")}}';
        $('#modelHeading').html("{{ __('app.addNew').' '.__('modules.tickets.ticketTypes') }}");
        $.ajaxModal('#ticketModal', url);
    })

    function setValueInForm(id, data) {
        $('#' + id).html(data);
        $('#' + id).selectpicker('refresh');
    }

    function scrollChat() {
        /* $('#ticket-messages').animate({
            scrollTop: $('#ticket-messages')[0].scrollHeight
        }, 'slow'); */
    }

    scrollChat();

    $('body').on('click', '.sa-params', function () {
                var id = $(this).data('file-id');
                var deleteView = $(this).data('pk');
                swal({

                    title: "Are you sure?",
                    text: "You will not be able to recover the deleted reply!",
                    icon: "warning",
                    buttons: ["No, cancel please!", "Yes, delete it!"],
                    dangerMode: true
            })
            .then((willDelete) => {
                    if (willDelete) {

                        var url = "{{ route('admin.tickets.reply-delete',':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'GET',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            success: function (response) {
                                if (response.status == "success") {
                                $.unblockUI();
                                $('#replyMessageBox_'+id).fadeOut();
                                }
                            }
                        });
                    }
                });
            });

    
</script>
@endpush
