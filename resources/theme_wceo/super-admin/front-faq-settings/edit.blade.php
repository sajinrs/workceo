<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

</div>

{!!  Form::open(['url' => '' ,'method' => 'put', 'id' => 'add-edit-form','class'=>'form-horizontal']) 	 !!}
<div class="modal-body">
    <div class="box-body">
        <h4 class="modal-title">@lang('app.faq')</h4>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="question">@lang('app.question')</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" id="question" value="{{ $frontFaq->question }}" name="question">
                <div class="form-control-focus"></div>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="answer">@lang('app.answer')</label>
            <div class="col-sm-12">
                <textarea type="text" class="form-control summernote" id="answer" rows="3"
                          name="answer"> {{ $frontFaq->answer }}</textarea>
                <div class="form-control-focus"></div>
                <span class="help-block"></span>
            </div>
        </div>

    </div>
</div>

<div class="modal-footer">
    <div class="text-right">
        <button id="save" type="button" class="btn btn-primary">@lang('app.update')</button>
    </div>
</div>
{{ Form::close() }}
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script>
    $('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen"]]
        ]
    });
    $('#save').click(function () {
        var url = '{{ route('super-admin.faq-settings.update', $frontFaq->id)}}';
        $.easyAjax({
            url: url,
            container: '#add-edit-form',
            type: "POST",
            data: $('#add-edit-form').serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload();
                }
            }
        })
        return false;
    })
</script>

