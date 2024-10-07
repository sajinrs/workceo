<div class="modal-header">
   
    <h5 class="modal-title">@lang('app.addNew') @lang('app.menu.offlinePaymentMethod')</h5>
     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'createMethods','class'=>'ajax-form','method'=>'POST']) !!}

        <div class="form-body">

            <div class="form-group">
                <label>@lang('modules.offlinePayment.method')</label>
                <input type="text" name="name" id="name" class="form-control">
            </div>
            <div class="form-group">
                <label>@lang('modules.offlinePayment.description')</label>
                <textarea id="description" name="description" class="form-control"></textarea>
            </div>

        </div>

        {!! Form::close() !!}
    </div>
</div>
<div class="modal-footer">

    <button type="button" id="save-method" class="btn btn-primary save-event waves-effect waves-light"> @lang('app.save')
    </button>
    <button type="button" class=" btn btn-secondary save-event waves-effect " data-dismiss="modal" aria-hidden="true">Close</button>
</div>

<script>

    $('#description').summernote({
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


    //    save project members
    $('#save-method').click(function () {
        $.easyAjax({
            url: '{{route('admin.offline-payment-setting.store')}}',
            container: '#createMethods',
            type: "POST",
            data: $('#createMethods').serialize(),
            success: function (response) {
                if (response.status === "success") {
                    $.unblockUI();
                    window.location.reload();
                }
            }
        })
    });
</script>

