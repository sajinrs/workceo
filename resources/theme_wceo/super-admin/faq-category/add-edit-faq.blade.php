<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
<div class="modal-header">
    <h5 class="modal-title">@if(isset($faq->id)) @lang('app.edit') @else @lang('app.addNew') @endif @lang('app.menu.workcoach')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>

{!! Form::open(['id'=>'addEditFaq','class'=>'ajax-form']) !!}
<div class="modal-body">
    <div class="portlet-body">

        @if(isset($faq->id)) <input type="hidden" name="_method" value="PUT"> @endif
        <input type="hidden" name="faq_category_id" value="{{ $faqCategoryId }}">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('app.title')</label>
                        <input type="text" name="title" class="form-control" value="{{ $faq->title ?? '' }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 ">
                    <div class="radio-list">
                        <label class="radio-inline p-0">
                            <div class="radio radio-info">
                                <input type="radio" name="type" id="external" value="external" checked>
                                <label for="external">@lang('app.externaLink')</label>
                            </div>
                        </label>&nbsp;&nbsp;&nbsp;

                        
                    </div>

                    <div id="externalLink" class="form-group">
                        <input type="text" name="external_url" placeholder="External Link" class="form-control" value="{{ $faq->external_url ?? '' }}">
                    </div>
                </div>

                <div class="col-md-6 ">
                    <div class="radio-list">
                        <label class="radio-inline">
                            <div class="radio radio-info">
                                <input type="radio" name="type" id="internal" value="internal" {{ ($faq->popup_type == 'internal') ? 'checked' : '' }}>
                                <label for="internal">@lang('app.internalPopup')</label>
                            </div>
                        </label>
                    </div>

                    <div id="internalLink" class="form-group">
                        <input type="text" name="internal_url" class="form-control" placeholder="Internal Link" value="{{ $faq->internal_url ?? '' }}">
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-3 p-r-0">
                    <label>@lang('app.image') (270x150)</label>
                    <div class="form-group">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 100px;">
                                <img src="{{ $faq->image_url ?? '' }}" alt=""/>
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail"
                                 style="max-width: 200px; max-height: 100px;"></div>
                            <div>
                                <span class="btn btn-sm btn-success btn-file">
                                    <span class="fileinput-new"> @lang('app.selectImage')</span>
                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                    <input type="file" name="image" id="faqImage"> </span>
                                <a href="javascript:;" class="btn btn-sm btn-danger fileinput-exists"
                                   data-dismiss="fileinput"> @lang('app.remove') </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 p-l-0">
                    <div id="description" class="form-group {{ ($faq->popup_type != 'internal') ? 'd-none' : '' }}">
                        <label>@lang('app.description')</label>
                        <textarea name="description" class="form-control summernote2">{!! $faq->description ?? '' !!}</textarea>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<div class="modal-footer">
    <div class="form-actions">
        <button type="button" id="save-faq-category"
                onclick="saveFAQ({{ $faqCategoryId }} {{ isset($faq->id) ? ','.$faq->id : '' }});return false;"
                class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')</button>
        @if(isset($faq->id))
            <button type="button" onclick="deleteFAQ({{ $faqCategoryId }}, {{  $faq->id }});return false;"
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
            ['insert', ['picture']],
            ['view', ['codeview']],
        ]
    });
    

    $(document).ready(function(){
        $("input[name='type']").on('change', function() {
            var type = $(this).val();
            if(type == 'internal'){
                $('#description').removeClass('d-none');
                //$('#externalLink').addClass('d-none');
            } else {
                $('#description').addClass('d-none');
                //$('#externalLink').removeClass('d-none');
            }
        });
    });
</script>