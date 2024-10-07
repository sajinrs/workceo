<div  id="sticky-note-header">
    <div class="row">
        <div class="col-10">
            <h5 class="modal-title font-weight-bold text-uppercase">@lang('app.menu.pageTips')</h5>
        </div>
        <div class="col-2 text-right"><a class="right_side_toggle" href="javascript:void(0);"><i class="fa fa-times fa-lg"></i></a></div>
        <div class="col-12 m-t-10">
            <a href="http://www.workceo.com/knowledgebase" target="_blank" class="btn btn-outline-primary btn-block">Search All Knowledge Base</a>
        </div>
    </div>
</div>
<div class="page-articles m-t-20 m-l-25">
    <h4 class="text-uppercase">Related Topics</h4>
    <ul>
        @forelse($articles as $article)
        <li><a href="javascript:;" onclick="showArticleDetails({{ $article->id }})">{{ $article->title }}</a></li>
        @empty
        <p>No articles</p>
        @endforelse
    </ul>
</div>