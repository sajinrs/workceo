@extends('layouts.super-admin')


@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/image-cropper.css') }}">
@endpush

@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="edit-profile">
            <div class="row">
                <div class="col-lg-12">

                    {!! Form::open(['id'=>'updateEmployee','class'=>'card ajax-form','method'=>'PUT']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">Edit Profile</h4>
                        <div class="card-options"><a class="card-options-collapse" href="#" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                        <div class="row">
                            <div class="col-md-8 ">
                                <div class="form-group">
                                    <label>@lang('app.name')</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           value="{{ $userDetail->name }}" autocomplete="nope">
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>@lang('app.email')</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                           value="{{ $userDetail->email }}" autocomplete="nope">
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>@lang('modules.employees.employeePassword')</label>
                                    <input type="password" style="display: none">
                                    <input type="password" name="password" id="password" class="form-control" autocomplete="nope">
                                    <span class="help-block"> @lang('modules.profile.passwordNote')</span>
                                </div>
                            </div>
                            <!--/span-->
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label>@lang('modules.profile.profilePicture')</label>
                                <div class="form-group">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" >
                                            <img id="profImage" src="{{ $userDetail->image_url }}" alt=""/>
                                            <img id="profImageTemp" />
                                        </div>
                                        <!-- <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="max-width: 200px; max-height: 150px;"></div> -->
                                             <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                        <div>
                                <span class="btn btn-sm btn-success btn-file">
                                    <span class="fileinput-new"> @lang('app.selectImage') </span>
                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                    <input type="file" name="image" id="image"> </span>
                                            <a href="javascript:;" class="btn btn-sm btn-danger fileinput-exists"
                                               data-dismiss="fileinput"> @lang('app.remove') </a>
                                        </div>
                                    </div>
                                </div>

                                <input id="dataX" type="hidden" name="x">
                                <input id="dataY" type="hidden" name="y">
                                <input id="dataWidth" type="hidden" name="width">
                                <input id="dataHeight" type="hidden" name="height">
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-primary"><i
                                        class="fa fa-check"></i> @lang('app.update')</button>
                            <a href="{{ route('super-admin.dashboard') }}" class="btn btn-secondary">@lang('app.back')</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>


    <!-- Container-fluid Ends-->
    <div class="clearfix"></div>
@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
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
        minCropBoxWidth: 150,
        minCropBoxHeight: 150,
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
  

  var $inputImage = $('#image');
  if (URL) {
    $inputImage.change(function () {
        $('#profImage, .fileinput-preview').hide();
        $('.fileinput-new.thumbnail').css('display', 'block');
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
        $("#joining_date, .date-picker").datepicker({
            todayHighlight: true,
            autoclose: true,
            weekStart:'{{ $global->week_start }}',
            format: '{{ $global->date_picker_format }}',
        });


        $('#save-form').click(function (e) {
            $.easyAjax({
                url: '{{route('super-admin.profile.update', [$userDetail->id])}}',
                container: '#updateEmployee',
                type: "POST",
                redirect: true,
                file: true,
                success: function (data) {
                    if (data.status == 'success') {
                        window.location.reload();
                    }
                }
            })
        });
    </script>
@endpush

