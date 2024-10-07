<div  id="sticky-note-header">
    <div class="row">
        <div class="col-10">
            <h5 class="modal-title font-weight-bold">NOTES <span class="text-gray-dark">{{count($stickyNotes)}}</span></h5>
        </div>
        <div class="col-2 text-right"><a class="right_side_toggle" href="javascript:void(0);"><i class="fa fa-times fa-lg"></i></a></div>
        <div class="col-12 m-t-10">
            <a href="javascript:;" onclick="showCreateNoteModal()" class="btn btn-outline-primary btn-block">@lang("modules.sticky.addNote")</a>
        </div>
    </div>
</div>
<div id="sticky-note-list">

    @foreach($stickyNotes as $note)
        <div class="row sticky-note" id="stickyBox_{{$note->id}}">
            <div class="col-9 sticky-date">
                <span class="badge badge-{{$note->colour}} b-none"> {{ $note->updated_at->diffForHumans() }}</span>
            </div>
            <div class="col-3">    <a href="javascript:;"  onclick="showEditNoteModal({{$note->id}})"><i class="fas fa-edit text-gray-dark"></i></a>
                <a href="javascript:;" class="m-l-5" onclick="deleteSticky({{$note->id}})" ><i class="fa fa-times text-gray-dark"></i></a>
            </div>
            <div class="col-12 m-t-10"><div class="sticky-note-desc">{!! nl2br($note->note_text)  !!}</div></div>

            {{--<div class="well">
                <span></span>
                <hr>
                <div class="row font-12">
                    <div class="col-xs-9">
                        @lang("modules.sticky.lastUpdated"): {{ $note->updated_at->diffForHumans() }}
                    </div>
                    <div class="col-xs-3">
                     </div>
                </div>
            </div>--}}
        </div>
    @endforeach

</div>