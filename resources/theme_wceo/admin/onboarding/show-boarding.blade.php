




<div class="modal-body">
<button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30" /></button>
    <div class="portlet-body">

       <div class="boardin-details text-center">
            <h2>{{$boarding->popup_title}}</h2>

            <p><img src="{{ $boarding->image_url}}" alt="" width="600"/> </p>

            <p class="text-left">{{$boarding->popup_description}}</p>

            <div class="pull-right">
            <button type="button" class="btn btn-outline-secondary m-r-10 btn-back-task" data-dismiss="modal">Back to Tasks</button>
            <a style="margin-right: 2px;" href="{{$boarding->popup_link}}" @if($boarding->type == 'external') target="_blank" @endif class="btn btn-primary">Get Started</a>
            </div>

       </div>       
        
    </div>
</div>

<script>
$('.btn-back-task, #boardingModal .close').click(function(){
    setupChecklist();
});
</script>

