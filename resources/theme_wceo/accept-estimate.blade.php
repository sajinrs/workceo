<div class="modal-header">
    <h5 class="modal-title">@lang('modules.estimates.signatureAndConfirmation')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'acceptEstimate','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row ">
                <div class="col-md-12 m-b-10">
                    <div class="form-group row">
                        <label class="col-md-3">@lang('modules.estimates.firstName')</label>
                        <div class="col-md-9">
                            <input type="text" name="first_name" id="first_name" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-12 m-b-10">
                    <div class="form-group row">
                        <label class="col-md-3">@lang('modules.estimates.lastName')</label>
                        <div class="col-md-9">
                            <input type="text" name="last_name" id="last_name" class="form-control">
                        </div>

                    </div>
                </div>
                <div class="col-md-12 m-b-10">
                    <div class="form-group row">
                        <label class="col-md-3">@lang('modules.lead.email')</label>
                        <div class="col-md-9">
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-12 m-b-10">
                    <div class="form-group">
                        <label>@lang('modules.estimates.signature')</label>
                        <div class="wrapper form-control SignatureWrap">
                            <canvas id="signature-pad" class="signature-pad"></canvas>
                        </div>

                    </div>
                </div>

                <div class="col-md-12 m-b-10">
                    <button id="undo" class="btn btn-secondary">@lang('modules.estimates.undo')</button>
                    <button id="clear" class="btn btn-primary m-l-10">@lang('modules.estimates.clear')</button>
                </div>
                    
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<div class="modal-footer">
    <div class="form-actions">
        <button type="button" id="save-signature" class="btn btn-primary"> @lang('app.sign')</button>
    </div>
</div>

<script>
    $(function () {
        var canvas = document.getElementById('signature-pad');

// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
        function resizeCanvas() {
            // When zoomed out to less than 100%, for some very strange reason,
            // some browsers report devicePixelRatio as less than 1
            // and only part of the canvas is cleared then.
            var ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        }

        window.onresize = resizeCanvas;
        resizeCanvas();

        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
        });

        document.getElementById('clear').addEventListener('click', function (e) {
            e.preventDefault();
            signaturePad.clear();
        });

        document.getElementById('undo').addEventListener('click', function (e) {
            e.preventDefault();
            var data = signaturePad.toData();
            if (data) {
                data.pop(); // remove the last dot or line
                signaturePad.fromData(data);
            }
        });

    });
    $('#save-signature').click(function () {
        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var email = $('#email').val();
        var signature = signaturePad.toDataURL('image/png');

        if (signaturePad.isEmpty()) {
            return $.showToastr("Please provide a signature first.", 'error');
        }
        $.easyAjax({
            url: '{{route('front.accept-estimate', md5($id))}}',
            container: '#acceptEstimate',
            type: "POST",
            data: {
                first_name:first_name,
                last_name:last_name,
                email:email,
                signature:signature,
                _token: '{{ csrf_token() }}'
            },
        })
    });
</script>