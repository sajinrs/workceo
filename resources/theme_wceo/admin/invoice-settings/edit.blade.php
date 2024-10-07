@extends('layouts.app')

@section('page-title')
   <div class="col-md-12">
        <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a  href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>

@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/image-picker/image-picker.css') }}">
@endpush

@section('content')


 <div class="container-fluid">
   <div class="row">
        <div class="col-md-3">
        @include('sections.admin_setting_menu')
       </div>
        <div class="col-md-9">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                          <h5>@lang('modules.invoiceSettings.updateTitle') </h5>
                           
                        </div>
                        {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">

                    


                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="white-box">

                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-label-group form-group">
                                                    <input type="text" id="invoice_prefix" name="invoice_prefix"
                                                            class="form-control form-control-lg" value="{{ $invoiceSetting->invoice_prefix }}" placeholder="*">
                                                        <label for="invoice_prefix" class="fomr-control required">@lang('modules.invoiceSettings.invoicePrefix')</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-label-group form-group">
                                                    <input type="number" min="2" class="form-control form-control-lg" id="invoice_digit" name="invoice_digit"
                                                           value="{{ $invoiceSetting->invoice_digit }}" placeholder="*">
                                                    <label for="invoice_digit" class="fomr-control">@lang('modules.invoiceSettings.invoiceDigit')</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-label-group form-label-group form-group">
                                                
                                                    <input type="text" class="form-control form-control-lg" id="invoice_look_like" readonly>
                                                        <label for="invoice_prefix">@lang('modules.invoiceSettings.invoiceLookLike')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-label-group form-label-group form-group">
                                                    <input type="text" class="form-control form-control-lg" id="estimate_prefix" name="estimate_prefix"
                                                           value="{{ $invoiceSetting->estimate_prefix }}" placeholder="*">
                                                    <label for="estimate_prefix" class="fomr-control required">@lang('modules.invoiceSettings.estimatePrefix')</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-label-group form-label-group form-group">
                                                   
                                                    <input type="number" min="2" class="form-control form-control-lg" id="estimate_digit" name="estimate_digit"
                                                           value="{{ $invoiceSetting->estimate_digit }}" placeholder="*">
                                                    <label for="estimate_digit" class="fomr-control">@lang('modules.invoiceSettings.estimateDigit')</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-label-group form-label-group form-group">
                                                    <input type="text" class="form-control form-control-lg" id="estimate_look_like" readonly>
                                                      <label for="estimate_look_like">@lang('modules.invoiceSettings.estimateLookLike')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-label-group form-label-group form-group">
                                                 
                                                    <input type="text" class="form-control form-control-lg" id="credit_note_prefix" name="credit_note_prefix"
                                                           value="{{ $invoiceSetting->credit_note_prefix }}" placeholder="*">
                                                              <label for="credit_note_prefix" class="fomr-control required">@lang('modules.invoiceSettings.credit_notePrefix')</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-label-group form-label-group form-group">
                                                   
                                                    <input type="number" min="2" class="form-control form-control-lg" id="credit_note_digit" name="credit_note_digit"
                                                           value="{{ $invoiceSetting->credit_note_digit }}">
                                                            <label for="credit_note_digit" class="fomr-control">@lang('modules.invoiceSettings.credit_noteDigit')</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-label-group form-label-group form-group">
                                                  
                                                    <input type="text" class="form-control form-control-lg" id="credit_note_look_like" readonly>
                                                      <label for="credit_note_look_like" class="fomr-control">@lang('modules.invoiceSettings.credit_noteLookLike')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                                <div class="form-group Invoicetemplate m-t-20">
                                                    <label for="template"><b>@lang('app.invoice') @lang('modules.invoiceSettings.template')</b></label>
                                                    <select name="template" class="image-picker show-labels show-html">
                                                        <option data-img-src="{{ asset('invoice-template/1.png') }}"
                                                                @if($invoiceSetting->template == 'invoice-1') selected @endif
                                                                value="invoice-1">Template
                                                            1
                                                        </option>
                                                        <option data-img-src="{{ asset('invoice-template/2.png') }}"
                                                                @if($invoiceSetting->template == 'invoice-2') selected @endif
                                                                value="invoice-2">Template
                                                            2
                                                        </option>
                                                        <option data-img-src="{{ asset('invoice-template/3.png') }}"
                                                                @if($invoiceSetting->template == 'invoice-3') selected @endif
                                                                value="invoice-3">Template
                                                            3
                                                        </option>
                                                        <option data-img-src="{{ asset('invoice-template/4.png') }}"
                                                                @if($invoiceSetting->template == 'invoice-4') selected @endif
                                                                value="invoice-4">Template
                                                            4
                                                        </option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                        <label for="due_after">@lang('modules.invoiceSettings.dueAfter')</label>
                                                    <div>
                                                        <div class="input-group ">
                                                            <input type="number" id="due_after" name="due_after" class="form-control form-control-lg" value="{{ $invoiceSetting->due_after }}">
                                                            <div class="input-group-addon  form-control-lg f-16 graybg"><span class="invoicePrefix">@lang('app.days')</span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <div class="form-label-group form-group m-t-25">
                                                    <input type="text" id="gst_number" name="gst_number"
                                                            class="form-control form-control-lg" value="{{ $invoiceSetting->invoice_prefix }}" placeholder="*">
                                                    <label for="gst_number" class="fomr-control required">@lang('app.gstNumber')</label>
                                                </div>

                                                
                                            </div>
                                            <div class="col-md-4">
                                            <div class="form-group m-t-25">
                                                <div class="row">
                                                <div class="col-md-4">
                                                        <div class="switch-showcase icon-state">
                                                            <label class="switch">
                                                            <input type="checkbox" name="show_gst" @if($invoiceSetting->show_gst == 'yes') checked @endif ><span class="switch-state"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <label class="control-label col-md-8 pt-2 pl-0">@lang('app.showGst')</label>

                                                    
                                                </div>
                                            </div>

                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-label-group form-label-group form-group">
                                                  
                            <textarea name="invoice_terms" id="invoice_terms" class="form-control"
                                      rows="4">{{ $invoiceSetting->invoice_terms }}</textarea>
                                        <label for="invoice_terms">@lang('modules.invoiceSettings.invoiceTerms')</label>
                                                </div>
                                            </div>

                                                <div class="form-label-group form-label-group form-group">
                                                    <label for="exampleInputPassword1"> @lang('modules.invoiceSettings.logo')</label>

                                                    <div class="col-md-4 pr-0">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-new thumbnail"
                                                                 style="width: 242px;">
                                                                <img src="{{$invoiceSetting->logo_url}}" alt="" class="fileinput-preview-set"/>
                                                <input type="hidden" id="logo_url" value="{{ $invoiceSetting->logo_url }}">

                                                            </div>
                                                            <div class="fileinput-preview fileinput-exists thumbnail thumbnailset"
                                                                 style="max-width: 200px; max-height: 80px;"></div>
                                                            <div>
                                <span class="btn btn-primary btn-file btn-block">
                                    <span class="fileinput-new selectImages"> @lang('app.selectImage') </span>
                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                    <input type="file" name="logo" id="logo"> </span>
                                                                <a href="javascript:;"
                                                                   class="btn btn-danger removeall fileinput-exists"
                                                                   data-dismiss="fileinput"> @lang('app.remove') </a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>


                                            
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

     </div>
    <div class="card-footer text-right">
        <div class="form-actions col-md-3  offset-md-9 ">
            <button type="submit" id="save-form" class="btn btn-primary form-control"> @lang('app.update')</button>
        </div>
    </div>
        {!! Form::close() !!}
    </div>
    <!-- .row -->



    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/image-picker/image-picker.min.js') }}"></script>

<script>
    $(".image-picker").imagepicker();
    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.invoice-settings.update', $invoiceSetting->id)}}',
            container: '#editSettings',
            type: "POST",
            redirect: true,
            file: true,
            data: $('#editSettings').serialize()
        })
    });

    $('#invoice_prefix, #invoice_digit, #estimate_prefix, #estimate_digit, #credit_note_prefix, #credit_note_digit').on('keyup', function () {
        genrateInvoiceNumber();
    });

    genrateInvoiceNumber();

    function genrateInvoiceNumber() {
        var invoicePrefix = $('#invoice_prefix').val();
        var invoiceDigit = $('#invoice_digit').val();
        var invoiceZero = '';
        for ($i=0; $i<invoiceDigit-1; $i++){
            invoiceZero = invoiceZero+'0';
        }
        invoiceZero = invoiceZero+'1';
        var invoice_no = invoicePrefix+'#'+invoiceZero;
        $('#invoice_look_like').val(invoice_no);

        var estimatePrefix = $('#estimate_prefix').val();
        var estimateDigit = $('#estimate_digit').val();
        var estimateZero = '';
        for ($i=0; $i<estimateDigit-1; $i++){
            estimateZero = estimateZero+'0';
        }
        estimateZero = estimateZero+'1';
        var estimate_no = estimatePrefix+'#'+estimateZero;
        $('#estimate_look_like').val(estimate_no);

        var creditNotePrefix = $('#credit_note_prefix').val();
        var creditNoteDigit = $('#credit_note_digit').val();
        var creditNoteZero = '';
        for ($i=0; $i<creditNoteDigit-1; $i++){
            creditNoteZero = creditNoteZero+'0';
        }
        creditNoteZero = creditNoteZero+'1';
        var creditNote_no = creditNotePrefix+'#'+creditNoteZero;
        $('#credit_note_look_like').val(creditNote_no);
    }
    function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('.fileinput-preview-set').attr('src', e.target.result);
        $('.thumbnailset').attr('style','display:none !important');
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}
$(".removeall").click(function() {
     $('.btn-file').addClass('btn-block');
     $('.selectImages').show();
     $('.fileinput-preview-set').attr('src', $('#logo_url').val());
     $('.fileinput-exists').hide();
 });
$("#logo").change(function() {
    $('.btn-file').removeClass('btn-block');
    $('.selectImages').hide();

    $('.fileinput-exists').css('display', 'inline-block');
  readURL(this);
});
</script>
@endpush

