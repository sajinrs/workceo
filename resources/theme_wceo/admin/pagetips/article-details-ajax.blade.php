<div  id="sticky-note-header" class="article-details">
    <div class="row">
        <div class="col-10">
            <h5 class="modal-title font-weight-bold text-uppercase">@lang('app.menu.pageTips')</h5>
        </div>
        <div class="col-2 text-right"><a class="right_side_toggle" href="javascript:void(0);"><i class="fa fa-times fa-lg"></i></a></div>

        <div class="col-6 m-t-10 p-r-0">
            <a href="javascript:;" onclick="getPageTips({{$article->module_id}})" class="btn btn-outline-primary btn-block">< Back to Related Topics</a>
        </div>
        <div class="col-6 m-t-10">
            <a href="http://www.workceo.com/knowledgebase" target="_blank" class="btn btn-outline-primary btn-block">Search Knowledge Base >
</a>
        </div>
    </div>
</div>
<div class="page-articles m-t-20 m-l-25">
    {!! $article->description !!}
</div>