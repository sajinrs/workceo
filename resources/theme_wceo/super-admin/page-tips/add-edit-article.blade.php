<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
<div class="modal-header">
    <h5 class="modal-title">@if(isset($faq->id)) @lang('app.edit') @else @lang('app.addNew') @endif @lang('app.article')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>

{!! Form::open(['id'=>'addEditArticle','class'=>'ajax-form','method'=>'POST']) !!}
<div class="modal-body">
    <div class="portlet-body">

        
        <input type="hidden" name="module_id" value="{{ $moduleId }}">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('app.title')</label>
                        <input type="text" name="title" class="form-control" value="{{ $article->title ?? '' }}">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>@lang('app.description')</label>
                        <textarea name="description" class="form-control summernote">{!! $article->description ?? '' !!}</textarea>
                    </div>
                </div>
            </div>
            

            

        </div>

    </div>
</div>

<div class="modal-footer">
    <div class="form-actions">
        <button type="button" id="save-faq-category"
                onclick="saveArticle({{ $moduleId }} {{ isset($article->id) ? ','.$article->id : '' }});return false;"
                class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')</button>
        @if(isset($article->id))
            <button type="button" onclick="deleteArticle({{ $moduleId }}, {{  $article->id }});return false;"
                    class="btn btn-danger"><i class="fa fa-trash"></i> @lang('app.delete')</button>
        @else
            <button type="button" class="btn btn-danger" data-dismiss="modal"> @lang('app.cancel')</button>
        @endif
            
    </div>
</div>
{!! Form::close() !!}

<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.custom.js')}}"></script>

<script>
    $('.summernote2').summernote({
        height: 300,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        dialogsInBody: true,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen"]],
            ['insert', ['link','picture']],
            ['view', ['codeview']],
        ]
    });
    

    
</script>