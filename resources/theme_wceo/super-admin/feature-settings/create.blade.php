<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

</div>

{!!  Form::open(['url' => '' ,'method' => 'post', 'id' => 'add-edit-form','class'=>'form-horizontal']) 	 !!}
<div class="modal-body">
    <div class="box-body">
        <h5 class="modal-title">@lang('modules.featureSetting.addFeature')</h5>

        @if(isset($type) && $type != 'image' && $type != 'apps')
            <div class="form-group">
                <label class="col-sm-2 control-label">@lang('app.icon')</label>
                <div class="btn-group col-sm-10">
                    <button data-selected="graduation-cap" type="button"
                            class="icp icp-dd btn btn-primary dropdown-toggle iconpicker-component col-sm-2"
                            data-toggle="dropdown">
                        <i class="fa fa-fw"></i>
                        <span class="caret"></span>
                    </button>
                    <div class="dropdown-menu"></div>
                </div>
            </div>
            <input class="" name="icon" id="iconInput" type="hidden"/>

        @endif
        <input class="" name="type" id="type" value="{{ $type }}" type="hidden"/>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="name">@lang('app.title')</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="title">
                <div class="form-control-focus"></div>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="name">@lang('app.description')</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control summernote" id="description" rows="3"
                          name="description"> </textarea>
                <div class="form-control-focus"></div>
                <span class="help-block"></span>
            </div>
        </div>

        @if(isset($type) && $type == 'image' || $type == 'apps')
            <div class="form-group">
                <label class="col-sm-2 control-label" for="exampleInputPassword1">@lang('app.image') (400x352)</label>
                <div class="col-sm-10">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail"
                             style="width: 200px; height: 150px;">
                            <img src="{{asset('front/img/tools.png')}}"
                                 alt=""/>
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail"
                             style="max-width: 200px; max-height: 150px;"></div>

                        <span class="btn btn-info btn-file">
                                <span class="fileinput-new"> @lang('app.selectImage') </span>
                                <span class="fileinput-exists"> @lang('app.change') </span>
                                <input type="file" name="image" id="image"> </span>
                        <a href="javascript:;" class="btn btn-danger fileinput-exists"
                           data-dismiss="fileinput"> @lang('app.remove') </a>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class=" col-sm-offset-2 col-sm-10 ">
                    <div class="alert alert-info"><i
                                class="fa fa-info-circle"></i> @lang('messages.FeatureImageSizeMessage')</div>
                </div>
            </div>
        @endif

    </div>
</div>

<div class="modal-footer">
    <div class=" text-right">
        <button id="save" type="button" class="btn btn-primary">@lang('app.submit')</button>
    </div>
</div>
{{ Form::close() }}

<link rel="stylesheet" href="{{ asset('public/plugins/iconpicker/css/fontawesome-iconpicker.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">

<script src="{{ asset('plugins/iconpicker/js/fontawesome-iconpicker.js') }}"></script>
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

    $('.icp-dd').iconpicker({
        title: 'Dropdown with picker',
        component: '.btn > i',
        templates: {
            iconpickerItem: '<a role="button" href="javascript:;" class="iconpicker-item"><i></i></a>'
        }
    });
    $(function () {
        $('.icp').on('iconpickerSelected', function (e) {
            $('#iconInput').val(e.iconpickerValue);
        });
    });
    $('#save').click(function () {
        $.easyAjax({
            url: '{{route('super-admin.feature-settings.store')}}',
            container: '#add-edit-form',
            type: "POST",
            file: true,
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload();
                }
            }
        })
        return false;
    })
</script>

