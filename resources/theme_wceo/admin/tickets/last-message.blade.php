<div class="panel-body msgReply bg-owner-reply"  id="replyMessageBox_{{$reply->id}}">

    <div class="row m-b-5">

        <div class="col-xs-2 col-md-1">
            <img src="{{ $reply->user->image_url }}"
                                alt="user" class="img-circle" width="40" height="40">
        </div>
        <div class="col-xs-8 col-md-10">
            <h5 class="m-t-0 mb-0 font-bold"><a
                        @if($reply->user->hasRole('employee'))
                        href="{{ route('admin.employees.show', $reply->user_id) }}"
                        @elseif($reply->user->hasRole('client'))
                        href="{{ route('admin.clients.show', $reply->user_id) }}"
                        @endif
                        class="text-inverse">{{ ucwords($reply->user->name) }}</a>
            </h5>
            <span class="replyTime">{{ $reply->created_at->format($global->date_format.' '.$global->time_format) }}</span>

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
                    <a target="_blank" href="{{ asset_url('ticket-files/'.$reply->id.'/'.$file->hashname) }}"
                                    data-toggle="tooltip" data-original-title="View"
                                    class="badge badge-info"><i class="fa fa-search m-r-0"></i></a>

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
