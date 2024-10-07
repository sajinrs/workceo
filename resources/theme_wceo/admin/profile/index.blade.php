@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/image-cropper.css') }}">
@endpush

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
                          <h5>@lang('modules.profile.updateTitle') </h5>
                           
                        </div>
                    {!! Form::open(['id'=>'updateProfile','class'=>'ajax-form','method'=>'PUT']) !!}
                     <div  class="card-body">
             
                        <div class="form-body" >
                                <div class="vtabs customvtab m-t-10">



                            <div class="tab-content">
                                <div id="vhome3" class="tab-pane active">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                           
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6 ">
                                                        <div class="form-label-group form-group">
                                                            <input type="text" name="first_name" id="first_name" class="form-control form-control-lg" value="{{ $userDetail->first_name }}" placeholder="*">
                                                            <label for="first_name" class="col-form-label required">@lang('modules.profile.yourFame')</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-label-group form-group">
                                                            <input type="text" name="last_name" id="lname" class="form-control form-control-lg" value="{{ $userDetail->last_name }}" placeholder="*">
                                                            <label for="lname" class="col-form-label required">@lang('modules.profile.yourLame')</label>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    
                                                </div>

                                                <div class="row">
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-label-group form-group">
                                                            <input type="email" name="email" id="email" class="form-control form-control-lg" value="{{ $userDetail->email }}" placeholder="*">
                                                            <label for="email" class="col-form-label required">@lang('modules.profile.yourEmail')</label>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-label-group form-group">
                                                            <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="*">
                                                            <label for="password" class="col-form-label">@lang('modules.profile.yourPassword')</label>
                                                            <span class="help-block"> @lang('modules.profile.passwordNote')</span>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>@lang('modules.profile.profilePicture')</label>

                                                        <div class="form-label-group form-group">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail">
                                                                    <img id="profImage" src="{{ $userDetail->image_url  }}" alt="" />
                                                                    <img id="profImageTemp" />
                                                                </div>
                                                                    <input type="hidden" id="logo_url" value="{{ $userDetail->image_url }}">
                                                                <div class="fileinput-preview fileinput-exists thumbnail thumbnailset"
                                                                    style="max-width: 150px; max-height: 100px;"></div>
                                                                <div>
                                        <span class="btn btn-primary btn-block  btn-file">
                                            <span class="fileinput-new selectImages"> @lang('app.selectImage') </span>
                                            <span class="fileinput-exists"> @lang('app.change') </span>
                                            <input type="file" name="image" id="logo"> </span>
                                                                    <a href="javascript:;"
                                                                    class="btn btn-danger removeall fileinput-exists"
                                                                    data-dismiss="fileinput"> @lang('app.remove') </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <input id="dataX" type="hidden" name="x">
                                                        <input id="dataY" type="hidden" name="y">
                                                        <input id="dataWidth" type="hidden" name="width">
                                                        <input id="dataHeight" type="hidden" name="height">

                                                    </div>
                                                    <div class="col-md-8">
                                                        <br>
                                                        <div class="form-label-group form-group">
                                                            <textarea name="address" id="address" rows="8" class="form-control form-control-lg" placeholder="*">@if(!empty($employeeDetail)){{ $employeeDetail->address }}@endif</textarea>
                                                            <label for="address" class="control-label">@lang('modules.profile.yourAddress')</label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div> <!--form-body -->
                                            
                                        </div>
                                    </div><!--row-->

                                    <div class="clearfix"></div>
                                </div><!--vhome3-->
                            </div><!--tab-content-->

                        </div> <!--customvtab-->
                    </div>    <!-- .form-body -->
                </div> <!--card-body-->

                <div class="card-footer text-right">
                    <div class="form-actions col-md-3  offset-md-9 ">
                        <button type="submit" id="save-form-2" class="btn btn-primary form-control"> @lang('app.update')</button>
                    </div>
                </div>
                {!! Form::close() !!}
    </div> <!--panel-inverse-->
    </div>
    </div>
    </div>
    </div>

    

@endsection

@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/image-cropper/cropper.js')}}"></script>

<!--Image crop-->
<script>
$(function () {
  'use strict';
  var console = window.console || { log: function () {} };
  var URL = window.URL || window.webkitURL;
  var $image = $('#profImageTemp');
  var $dataX = $('#dataX');
  var $dataY = $('#dataY');
  var $dataHeight = $('#dataHeight');
  var $dataWidth = $('#dataWidth');  
  var options = {
        aspectRatio: 9 / 9,
        preview: '.img-preview',
        crop: function (e) {
          $dataX.val(Math.round(e.detail.x));
          $dataY.val(Math.round(e.detail.y));
          $dataHeight.val(Math.round(e.detail.height));
          $dataWidth.val(Math.round(e.detail.width));          
        },
        zoomable: false,
        minCropBoxWidth: 50,
        minCropBoxHeight: 50,
      };
  var originalImageURL = $image.attr('src');
  var uploadedImageName = 'cropped.jpg';
  var uploadedImageType = 'image/jpeg';
  var uploadedImageURL;
  $('[data-toggle="tooltip"]').tooltip();
  $image.cropper(options);
  if (!$.isFunction(document.createElement('canvas').getContext)) {
    $('button[data-method="getCroppedCanvas"]').prop('disabled', true);
  }
  if (typeof document.createElement('cropper').style.transition === 'undefined') {
    $('button[data-method="rotate"]').prop('disabled', true);
    $('button[data-method="scale"]').prop('disabled', true);
  }  
  

  var $inputImage = $('#logo');
  if (URL) {
    $inputImage.change(function () {
        $('#profImage').hide();
      var files = this.files;
      var file;
      if (!$image.data('cropper')) {
        return;
      }
      if (files && files.length) {
        file = files[0];
        if (/^image\/\w+$/.test(file.type)) {
          uploadedImageName = file.name;
          uploadedImageType = file.type;
          if (uploadedImageURL) {
            URL.revokeObjectURL(uploadedImageURL);
          }
          uploadedImageURL = URL.createObjectURL(file);
          $image.cropper('destroy').attr('src', uploadedImageURL).cropper(options);
          //$inputImage.val('');
        } else {
          window.alert('Please choose an image file.');
        }
      }
    });
  } else {
    $inputImage.prop('disabled', true).parent().addClass('disabled');
  }
});

</script>
<script>
    $('#save-form-2').click(function () {
        $.easyAjax({
            url: '{{route('admin.profile-settings.update', [$userDetail->id])}}',
            container: '#updateProfile',
            type: "POST",
            redirect: true,
            file: (document.getElementById("logo").files.length == 0) ? false : true,
            data: $('#updateProfile').serialize(),
            success: function (data) {
                if (data.status == 'success') {
                    window.location.reload();
                }
            }
        })
    });
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
    $('#profImage').show();
    $('.cropper-container.cropper-bg').hide();
    $('.btn-file').addClass('btn-block');
    $('.selectImages').show();
    $('.fileinput-preview-set').attr('src', $('#logo_url').val());
    $('.fileinput-exists').hide();
    $("#logo").val('');
 });
$("#logo").change(function() {
    $('.btn-file').removeClass('btn-block');
    $('.selectImages').hide();

    $('.fileinput-exists').css('display', 'inline-block');
  readURL(this);
});
</script>
@endpush
