  <!-- include libraries(jQuery, bootstrap) -->
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
<div class="modal-header">
    <h5 class="modal-title"> @lang('app.edit') @lang('app.menu.adspace')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

{!! Form::open(['id'=>'editAds','class'=>'ajax-form']) !!}
<div class="modal-body">
    <div class="portlet-body">

    @if($ads->category_id == 2)
        @php $imgDimension = '320x40'; @endphp
    @elseif($ads->category_id == 3)
        @php $imgDimension = '110x200'; @endphp
    @elseif($ads->category_id == 4)
        @php $imgDimension = '450x450'; @endphp
    @elseif($ads->category_id == 5)
        @php $imgDimension = '950x200'; @endphp
    @else
        @php $imgDimension = '150x200'; @endphp
    @endif
       
        <input type="hidden" name="_method" value="PUT">
        <div class="form-body">
            @if($ads->category_id == 1)
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label>@lang('app.content')</label>
                            <textarea class="form-control" name="content">{{ $ads->content ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label>@lang('app.buttonText')</label>
                            <input type="text" name="button_text" class="form-control" value="{{ $ads->button_text ?? '' }}">
                        </div>
                    </div>
                </div>
            @endif

            @if($ads->category_id == 4 || $ads->category_id == 5)
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('app.durationinSeconnds')</label>
                        <input type="number" name="duration" class="form-control" value="{{ $ads->duration ?? '' }}">
                    </div>
                </div>
            </div>
            @endif
            
            @if($ads->category_id != 1 && $ads->category_id != 6) 
                <div class="row">
                    <div class="col-md-6">
                        <label>@lang('app.image') ({{$imgDimension}})</label>
                            <div class="form-group">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                        <img src="{{ $ads->image_url ?? '' }}" alt=""/>
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                            style="max-width: 200px; max-height: 150px;"></div>
                                    <div>
                                <span class="btn btn-sm btn-success btn-file">
                                <span class="fileinput-new"> @lang('app.selectImage') </span>
                                <span class="fileinput-exists"> @lang('app.change') </span>
                                <input type="file" name="image" id="adsImage"> </span>
                                        <a href="javascript:;" class="btn btn-sm btn-danger fileinput-exists"
                                            data-dismiss="fileinput"> @lang('app.remove') </a>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            
                @else
                    <input class="d-none"  type="file" id="adsImage" />
            @endif

                @if($ads->category_id == 6)
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label>Footer First Section</label>
                            <textarea class="form-control" name="footer[col1]" id="first_block" cols="30" rows="10">{{ $footerData->col1 ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label>Footer Second Section</label>
                            <textarea class="form-control" name="footer[col2]" id="second_block" cols="30" rows="10">{{ $footerData->col2 ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label>Footer Third Section</label>
                            <textarea class="form-control" name="footer[col3]" id="third_block" cols="30" rows="10">{{ $footerData->col3 ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label>Footer Fourth Section</label>
                            <textarea class="form-control" name="footer[col4]" id="fourth_block" cols="30" rows="10">{{ $footerData->col4 ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label>Footer Fifth Section</label>
                            <textarea class="form-control" name="footer[col5]" id="fifth_block" cols="30" rows="10">{{ $footerData->col5 ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            @endif
               

                <div class="row">
                    <label class="control-label col-md-1 pt-2">@lang('app.status')</label>

                    <div class="col-md-4 pl-0">
                        <div class="switch-showcase icon-state">
                            <label class="switch">
                                <input name="status" type="checkbox" value="1" @if($ads->status == 1) checked @endif ><span class="switch-state"></span>
                            </label>
                        </div>
                    </div>
                </div>                        
            
        </div>
        
    </div>
</div>

<div class="modal-footer">
    <div class="form-actions">
        <button type="button" id="save-faq-category" onclick="saveAds({{ $ads->id }});return false;" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>       
    </div>
</div>
{!! Form::close() !!}

<!-- summernote css/js -->
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.custom.js')}}"></script>
<script type="text/javascript">
    $('#first_block').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        dialogsInBody: true,
        background: true,
        toolbar: [
        // [groupName, [list of button]]
        ['style'],
        ['style', ['clear', 'bold', 'italic', 'underline']],
        ['fontname', ['fontname']],
        ['font', ['fontsize', 'color']],     
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link','picture', 'doc', 'video']],
        ['fullscreen', ['fullscreen']],
        ['help', ['help']],
        ['view', ['codeview']]
        ]
        });
    $('#second_block').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        dialogsInBody: true,
        background: true,
        toolbar: [
        // [groupName, [list of button]]
        ['style'],
        ['style', ['clear', 'bold', 'italic', 'underline']],
        ['fontname', ['fontname']],
        ['font', ['fontsize', 'color']],     
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link','picture', 'doc', 'video']],
        ['fullscreen', ['fullscreen']],
        ['help', ['help']],
        ['view', ['codeview']]
        ]
    });
    $('#third_block').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        dialogsInBody: true,
        background: true,
        toolbar: [
        // [groupName, [list of button]]
        ['style'],
        ['style', ['clear', 'bold', 'italic', 'underline']],
        ['fontname', ['fontname']],
        ['font', ['fontsize', 'color']],     
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link','picture', 'doc', 'video']],
        ['fullscreen', ['fullscreen']],
        ['help', ['help']],
        ['view', ['codeview']]
        ]
    });
    $('#fourth_block').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        dialogsInBody: true,
        background: true,
        toolbar: [
        // [groupName, [list of button]]
        ['style'],
        ['style', ['clear', 'bold', 'italic', 'underline']],
        ['fontname', ['fontname']],
        ['font', ['fontsize', 'color']],     
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link','picture', 'doc', 'video']],
        ['fullscreen', ['fullscreen']],
        ['help', ['help']],
        ['view', ['codeview']]
        ]
    });
    $('#fifth_block').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        dialogsInBody: true,
        background: true,
        toolbar: [
        // [groupName, [list of button]]
        ['style'],
        ['style', ['clear', 'bold', 'italic', 'underline']],
        ['fontname', ['fontname']],
        ['font', ['fontsize', 'color']],     
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link','picture', 'doc', 'video']],
        ['fullscreen', ['fullscreen']],
        ['help', ['help']],
        ['view', ['codeview']]
        ]
    });
</script>