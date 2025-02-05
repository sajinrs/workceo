<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
<style>
    .panel-black .panel-heading a, .panel-inverse .panel-heading a {
        color: unset !important;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">@lang('app.update') @lang('app.menu.offlinePaymentMethod')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span
                aria-hidden="true">×</span></button>

</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'updateMethods','class'=>'ajax-form','method'=>'PUT']) !!}

        <div class="form-body">

            <div class="form-group">
                <label>@lang('modules.offlinePayment.method')</label>
                <input type="text" name="name" id="name" value="{{ $method->name ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                <label>@lang('modules.offlinePayment.description')</label>
                <textarea id="description" name="description"
                          class="form-control summernote">{{ $method->description ?? '' }}</textarea>
            </div>
            <div class="form-group">
                <label class="control-label">@lang('app.status')</label>
                <select class="select2 form-control" data-placeholder="@lang("app.status")" id="status" name="status">
                    <option @if($method->status == 'yes') selected
                            @endif value="yes">@lang('modules.offlinePayment.active')</option>
                    <option @if($method->status == 'no') selected
                            @endif value="no">@lang('modules.offlinePayment.inActive')</option>

                </select>
            </div>

        </div>

        {!! Form::close() !!}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
    <button type="button" id="save-method" class="btn btn-info save-event waves-effect waves-light"><i
                class="fa fa-check"></i> @lang('app.save')
    </button>
</div>

<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script>

    //    save project members
    $('#save-method').click(function () {
        var url = '{{route('super-admin.offline-payment-setting.update', $method->id)}}';
        $.easyAjax({
            url: url,
            container: '#updateMethods',
            type: "POST",
            data: $('#updateMethods').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    window.location.reload();
                }
            }
        })
    });
</script>

