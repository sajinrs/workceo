@extends('layouts.super-admin')

@push('head-script')
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
                            <li class="breadcrumb-item"><a
                                        href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item "><a
                                        href="{{ route('super-admin.packages.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.addNew')</li>
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
                    {!! Form::open(['id'=>'createClient','class'=>'card ajax-form','method'=>'POST']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('app.add') @lang('app.template') @lang('app.info')</h4>
                        <div class="card-options"><a class="card-options-collapse" href="#" data-toggle="card-collapse"><i
                                        class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#"
                                                                            data-toggle="card-remove"><i
                                        class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('app.name')</label>
                                        <input type="text" id="name" name="name" value="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('app.max') @lang('app.menu.employees')</label>
                                        <input type="number" name="max_employees" id="max_employees" value=""
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('app.annual') @lang('app.price')
                                            ({{ $global->currency->currency_symbol }})</label>
                                        <input type="number" name="annual_price" id="annual_price" value=""
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('app.monthly') @lang('app.price')
                                            ({{ $global->currency->currency_symbol }})</label>
                                        <input type="number" name="monthly_price" id="monthly_price" value=""
                                               class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="checkbox checkbox-primary">
                                            <input id="free_trial" type="checkbox" name="free_trial" value="yes" />
                                            <label for="free_trial"> Free Trial</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="trialDays" class="col-md-4 d-none">
                                    <div class="form-group">
                                        <label class="control-label required">Days</label>
                                        <input type="number" name="trial_days" id="days" class="form-control" value="0" />
                                    </div>
                                </div>
                            </div>

                            <hr class="mt-4 mb-4">

                            <h5>@lang('app.select') @lang('app.module')</h5>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="checkbox checkbox-info">
                                            <input id="select_all_permission"

                                                   class="select_all_permission" type="checkbox">
                                            <label for="select_all_permission">@lang('modules.permission.selectAll')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="row form-group module-in-package p-l-15">
                                        @foreach($modules as $module)
                                            <div class="col-md-2">
                                                <div class="checkbox checkbox-inline checkbox-info m-b-10">
                                                    <input id="{{ $module->module_name }}"
                                                           name="module_in_package[{{ $module->id }}]"
                                                           value="{{ $module->module_name }}" class="module_checkbox"
                                                           type="checkbox">
                                                    <label for="{{ $module->module_name }}">{{ ucfirst($module->module_name) }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.description')</label>
                                        <textarea name="description" id="description" rows="5" value=""
                                                  class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                <label>Plan Image</label>
                                <div class="form-group">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" >
                                            <img id="profImageTemp" />
                                        </div>
                                        <!-- <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="max-width: 200px; max-height: 150px;"></div> -->
                                             <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                        <div>
                                <span class="btn btn-sm btn-success btn-file">
                                    <span class="fileinput-new"> @lang('app.selectImage') </span>
                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                    <input type="file" name="pack_image" id="packImage"> </span>
                                            <a href="javascript:;" class="btn btn-sm btn-danger removeImg fileinput-exists"
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
                                        class="fa fa-check"></i> @lang('app.save')</button>

                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('themes/wceo/assets/js/image-cropper/cropper.js')}}"></script>
    <script>
        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('super-admin.packages.store')}}',
                container: '#createClient',
                type: "POST",
                redirect: true,
                file: (document.getElementById("packImage").files.length == 0) ? false : true,
                data: $('#createClient').serialize()
            })
        });

        $('.select_all_permission').change(function () {
            if ($(this).is(':checked')) {
                $('.module_checkbox').prop('checked', true);
            } else {
                $('.module_checkbox').prop('checked', false);
            }
        });

        $('#free_trial').click(function(){
            if($(this).prop("checked") == true){
                $('#trialDays').removeClass('d-none');
            }
            else {
                $('#trialDays').addClass('d-none');
                $('#days').val(0);
            }
        });
    </script>

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
        aspectRatio: 16 / 9,
        preview: '.img-preview',
        crop: function (e) {
          $dataX.val(Math.round(e.detail.x));
          $dataY.val(Math.round(e.detail.y));
          $dataHeight.val(Math.round(e.detail.height));
          $dataWidth.val(Math.round(e.detail.width));          
        },
        zoomable: false,
        minCropBoxWidth: 325,
        minCropBoxHeight: 205,
        minContainerWidth: 200,
    minContainerHeight: 100,
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
  

  var $inputImage = $('#packImage');
  if (URL) {
    $inputImage.change(function () {
        $('.fileinput-preview').hide();
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

$(document).ready(function(){
    $('.removeImg').click(function(){
        $('.cropper-container').hide();       
        //$('#profImage').css('display', 'block'); 
    });
});


</script>
@endpush

