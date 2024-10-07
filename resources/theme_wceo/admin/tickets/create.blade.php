@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
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
                            <li class="breadcrumb-item active">@lang('app.addNew')</li>
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
            <div class="col-sm-12">
                <div class="card">
                {!! Form::open(['id'=>'storeTicket','class'=>'ajax-form storeTicket','method'=>'POST']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">{{ __($pageTitle) }} <span class="text-primary text-uppercase pull-right">@lang('modules.tickets.ticket') # {{ (is_null($lastTicket)) ? "1" : ($lastTicket->id+1) }}</span></h4>
                        
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-label-group form-group">     
                                    <select name="user_id" id="user_id" class="form-control form-control-lg" placeholder="-">
                                        <option value="">@lang('app.select') @lang('modules.tickets.requesterName')</option>
                                        @foreach($requesters as $requester)
                                            <option value="{{ $requester->id }}">{{ ucwords($requester->name).' ['.$requester->email.']' }}</option>
                                        @endforeach
                                    </select>    
                                    <label for="user_id" class="required">@lang('modules.tickets.requesterName')</label>       
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">      
                                        <select  name="agent_id" id="agent_id" class="form-control form-control-lg" placeholder="-" >
                                            <option value="">Agent not assigned</option>
                                            @forelse($groups as $group)
                                                @if(count($group->enabled_agents) > 0)
                                                    <optgroup label="{{ ucwords($group->group_name) }}">
                                                        @foreach($group->enabled_agents as $agent)
                                                            <option value="{{ $agent->user->id }}">{{ ucwords($agent->user->name).' ['.$agent->user->email.']' }}</option>
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
                                <div class="col-md-3">
                                    <div class="form-label-group form-group">  
                                        <select class="form-control form-control-lg add-type" name="type_id" id="type_id">
                                            @forelse($types as $type)
                                                <option value="{{ $type->id }}">{{ ucwords($type->type) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noTicketTypeAdded')</option>
                                            @endforelse
                                        </select>
                                        <label for="type_id" class="control-label">@lang('modules.invoices.type') </label>
                                    </div>
                                </div>

                                <div class="col-md-3 pl-0">
                                    <a href="javascript:;" id="add-type" class="btn btn-sm btn-outline btn-block btn-primary"><i class="fa fa-plus"></i> @lang('modules.tickets.addType')</a>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-label-group form-group">  
                                        <select class="form-control form-control-lg" name="priority" id="priority">
                                            <option value="low">@lang('app.low')</option>
                                            <option value="medium">@lang('app.medium')</option>
                                            <option value="high">@lang('app.high')</option>
                                            <option value="urgent">@lang('app.urgent')</option>
                                        </select>
                                        <label for="priority" class="required">@lang('modules.tasks.priority') </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-label-group form-group">  
                                        <select class="form-control form-control-lg" name="channel_id" id="channel_id" data-style="form-control">
                                            @forelse($channels as $channel)
                                                <option value="{{ $channel->id }}">{{ ucwords($channel->channel_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noTicketChannelAdded')</option>
                                            @endforelse
                                        </select>
                                        <label for="channel_id" class="control-label">@lang('modules.tickets.channelName')</label>
                                    </div>
                                </div>

                                <div class="col-md-3 pl-0">
                                    <a href="javascript:;" id="add-channel" class="btn btn-sm btn-outline btn-block btn-primary"><i class="fa fa-plus"></i> @lang('modules.tickets.addChannel')</a>
                                </div>

                                <div class="col-md-6 m-b-20">
                                    <div class="form-control form-control-lg tagsarea">  
                                        <select multiple data-role="tagsinput" name="tags[]" id="tags" placeholder="@lang('modules.tickets.tags')">
                                          
                                        </select>
                                        
                                    </div>

                                    
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group">  
                                        <input type="text" id="subject" name="subject" class="form-control form-control-lg" placeholder="-">
                                        <label for="subject" class="required">@lang('modules.tickets.ticketSubject')</label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.tickets.ticketDescription')</label>
                                        <textarea name="description" id="description" class="form-control form-control-lg summernote"></textarea>
                                    </div>
                                </div>
                            </div>

                            {!! Form::hidden('status', 'open', ['id' => 'status']) !!}

                        </div>
                    </div>
                    <div class="card-footer text-right">

                    <div class="form-actions row">
                            <div class=" col-md-3 offset-md-7 pr-0">
                                <div class="btn-group dropup m-r-10">
                                    <button aria-expanded="true" data-toggle="dropdown"
                                        class="btn btn-primary btn-outline dropdown-toggle waves-effect waves-light"
                                        type="button"><i class="fa fa-bolt"></i> @lang('modules.tickets.applyTemplate')
                                    <span class="caret"></span></button>
                                    <ul role="menu" class="dropdown-menu">
                                        @forelse($templates as $template)
                                            <li><a href="javascript:;" data-template-id="{{ $template->id }}" class="apply-template">{{ ucfirst($template->reply_heading) }}</a></li>
                                        @empty
                                            <li>@lang('messages.noTemplateFound')</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>

                            <div class=" col-md-2 ticketSubmit ">
                                <button aria-expanded="true" data-toggle="dropdown"
                                        class="dropdown-toggle btn btn-primary form-control"
                                        type="button">@lang('app.submit') <span class="caret"></span></button>
                                <ul role="menu" class="dropdown-menu pull-right">
                                    <li>
                                        <a href="javascript:;" class="submit-ticket" data-status="open">@lang('app.submit') @lang('app.open')
                                            <span style="width: 15px; height: 15px;"
                                                class="btn btn-danger btn-small btn-circle">&nbsp;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="submit-ticket" data-status="pending">@lang('app.submit') @lang('app.pending')
                                            <span style="width: 15px; height: 15px;"
                                                class="btn btn-warning btn-small btn-circle">&nbsp;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="submit-ticket" data-status="resolved">@lang('app.submit') @lang('app.resolved')
                                            <span style="width: 15px; height: 15px;"
                                                class="btn btn-info btn-small btn-circle">&nbsp;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="submit-ticket" data-status="closed">@lang('app.submit') @lang('app.close')
                                            <span style="width: 15px; height: 15px;"
                                                class="btn btn-success btn-small btn-circle">&nbsp;</span>
                                        </a>
                                    </li>
                                </ul>

                            </div>
                          </div>

                        <div class="form-actions col-md-3 offset-md-9">

                        
                            
                        </div>
                    </div>
                    {!! Form::close() !!}
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
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary gray" data-dismiss="modal">Close</button>
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

    projectID = '';
    
    

    $('.apply-template').click(function () {
        var templateId = $(this).data('template-id');
        var token = '{{ csrf_token() }}';

        $.easyAjax({
            url: '{{route('admin.replyTemplates.fetchTemplate')}}',
            type: "POST",
            data: { _token: token, templateId: templateId },
            success: function (response) {
                if (response.status == "success") {
                    $(".summernote").summernote("code", response.replyText);
                    //var editorObj = $("#description").data('wysihtml5');
                    /* var editor = editorObj.editor;
                    editor.setValue(response.replyText); */
                }
            }
        })
    })

    $('.summernote').summernote({
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


    $('.submit-ticket').click(function () {

        var status = $(this).data('status');
        $('#status').val(status);

        $.easyAjax({
            url: '{{route('admin.tickets.store')}}',
            container: '.storeTicket',
            type: "POST",
            // file: true,
            data: $('.storeTicket').serialize(),
            success: function(response){
                if(myDropzone.getQueuedFiles().length > 0){
                    $('#ticketIDField').val(response.ticketReplyID);
                    myDropzone.processQueue();
                }
                else{
                    var msgs = "@lang('messages.ticketAddSuccess')";
                    $.showToastr(msgs, 'success');
                    window.location.href = '{{ route('admin.tickets.index') }}'
                }
            }
        })
    });

    $('#add-type').click(function () {
        var url = '{{ route("admin.ticketTypes.createModal")}}';
        $('#modelHeading').html("@lang('app.addNew') @lang('modules.tickets.ticketType')");
        $.ajaxModal('#ticketModal', url);
    })

    $('#add-channel').click(function () {
        var url = '{{ route("admin.ticketChannels.createModal")}}';
        $('#modelHeading').html("{{ __('app.addNew').' '.__('modules.tickets.ticketTypes') }}");
        $.ajaxModal('#ticketModal', url);
    })

    function setValueInForm(id, data){
        $('#'+id).html(data);
        //$('#'+id).selectpicker('refresh');
    }
</script>
@endpush