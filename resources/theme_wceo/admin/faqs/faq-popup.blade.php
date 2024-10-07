

@php $inturl = $faq->internal_url; @endphp

@if (!preg_match("@^[hf]tt?ps?://@", $inturl))
    @php $inturl = "http://" . $inturl; @endphp
@endif

<div class="modal-body">
    <button class="close btn-close-outside" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30" /></button>
    <div class="portlet-body">

       <div class="workcoach-details">
            {!!$faq->description!!}

            <div align="center"><a href="{{$inturl}}" class="btn btn-primary text-center">Page Tips</a></div>
       </div>       
    </div>
</div>



