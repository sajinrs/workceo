<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
<style>
    .panel-black .panel-heading a, .panel-inverse .panel-heading a {
        color: unset !important;
    }
</style>
<div class="modal-header">

    <h5 class="modal-title" id="exampleModalLabel">@lang('app.addNew') @lang('app.menu.offlinePaymentMethod')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span
                aria-hidden="true">×</span></button>

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
                <textarea id="description" name="description" class="form-control summernote"></textarea>
            </div>

        </div>

        {!! Form::close() !!}
    </div>
</div>
<div class="modal-footer">
    <!--     <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button> -->
    <button type="button" id="save-method" class="btn btn-primary  save-event waves-effect waves-light"><i
                class="fa fa-check"></i> @lang('app.save')
    </button>
</div>

<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script>


    //    save project members
    $('#save-method').click(function () {
        $.easyAjax({
            url: '{{route('super-admin.offline-payment-setting.store')}}',
            container: '#createMethods',
            type: "POST",
            data: $('#createMethods').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    window.location.reload();
                }
            }
        })
    });
</script>

