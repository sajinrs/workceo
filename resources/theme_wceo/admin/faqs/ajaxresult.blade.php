<div class="row">
    @forelse($faqs as $faq)  
        <div class="col-xl-4 xl-35 col-sm-6">
        <div class="card">
            @php $exturl = $faq->external_url; @endphp
            @if (!preg_match("@^[hf]tt?ps?://@", $exturl))
                @php $exturl = "http://" . $exturl; @endphp
            @endif

            <div class="blog-box blog-grid product-box">
            <div class="product-img">
                <img class="img-fluid top-radius-blog" src="{{$faq->image_url ?? 'https://via.placeholder.com/200x150.png?text=Preview'}}" alt="">
                <div class="product-hover">
                <ul>
                    @if($faq->popup_type == 'external')
                        <li><a href="{{$exturl}}" target="_blank"><i class="fa fa-sign-in-alt"></i></a></li>
                    @else
                        <li><a href="javascript:;" onclick="workCoachDetails({{$faq->id}})"><i class="fa fa-sign-in-alt"></i></a></li>
                    @endif
                </ul>
                </div>
            </div>
            <div class="blog-details-main">
                <ul class="blog-social cat_name">
                    <li><a><i class="{{$faq->faq_category->fontawesome_code}} ??"></i> {{$faq->faq_category->name}}</a></li>
                </ul>
                <h6 class="blog-bottom-details">{{$faq->title}}</h6>
            </div>
            </div>
        </div>
        </div>
        @empty
        <div class="col-md-12"><div class="card"><div class="title p-20">@lang('messages.noFaqCreated')</div></div></div>
    @endforelse  
    <div class="col-md-12 text-center m-b-30">
                    {{ $faqs->links() }}
                  </div> 
</div>


<div class="modal fade bs-modal-md in" id="workCoachModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" id="faq-modal-data-application">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<script>
$(document).ready(function(){
    var maxHeight = 0;

$("#searchResult h6.blog-bottom-details").each(function(){
   if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
});

$("#searchResult h6.blog-bottom-details").height(maxHeight);
});

function workCoachDetails(id) 
{   
    var url = '{{ route('admin.faqs.faq-view', ':id')}}';
    url = url.replace(':id', id);
    $("#workCoachModal").modal('show');
    $.ajaxModal('#workCoachModal', url);       
}
</script>