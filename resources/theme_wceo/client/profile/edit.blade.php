@extends('layouts.client-app')

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
                            <li class="breadcrumb-item"><a href="{{ route('client.dashboard.index') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>
                

            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->

            <div class="col-sm-12">
                <div class="product-grid">
                    

                    <div class="product-wrapper-grid">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">@lang('modules.profile.updateTitle')</h4>
                                    </div>
                                    <div class="card-body">
                                    {!! Form::open(['id'=>'updateProfile','class'=>'ajax-form','method'=>'PUT']) !!}
                                    <div class="form-body">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-label-group form-group">      
                                                    <input type="text" name="first_name" id="first_name" class="form-control form-control-lg" placeholder="-" autocomplete="nope" value="{{ $clientDetail->first_name }}">  
                                                    <label for="first_name" class="required">@lang('app.firstName')</label>  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-label-group form-group">      
                                                    <input type="text" name="last_name" id="last_name" class="form-control form-control-lg" placeholder="-" autocomplete="nope" value="{{ $clientDetail->last_name }}">  
                                                    <label for="last_name" class="required">@lang('app.lasttName')</label>  
                                                </div>
                                            </div> 

                                             <div class="col-md-6">
                                                <div class="form-label-group form-group">      
                                                    <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="-" autocomplete="nope" value="{{ $clientDetail->email }}">  
                                                    <label for="email" class="required">@lang('modules.profile.yourEmail')</label>  
                                                </div>
                                            </div>                                          

                                            <div class="col-md-6">
                                                <div class="form-label-group form-group">    
                                                    <input type="tel" name="mobile" id="mobile" class="form-control form-control-lg" placeholder="-" value="{{ $clientDetail->mobile }}">
                                                    <label for="mobile">@lang('modules.profile.yourMobileNumber')</label>
                                                </div>
                                            </div>
                                           
                                            <!--/span-->
                                        </div>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-label-group form-group">      
                                                    <input type="text" name="company_name" id="company_name" class="form-control form-control-lg" placeholder="-" autocomplete="nope" value="{{ $clientDetail->company_name }}">  
                                                    <label for="lname" class="required">@lang('modules.client.companyName')</label>  
                                                </div>
                                            </div> 
                                            
                                            <div class="col-md-6">
                                                <div class="form-label-group form-group">
                                                    <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="-">
                                                    <label for="password">@lang('modules.profile.yourPassword')</label>
                                                    <span class="help-block"> @lang('modules.profile.passwordNote')</span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <!--/row-->

                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-label-group form-group">      
                                                    <input type="text" name="website" id="website" class="form-control form-control-lg" value="{{ $clientDetail->website }}" placeholder="-"> 
                                                    <label for="website">@lang('modules.client.website')</label>  
                                                </div>
                                            </div> 
                                            
                                            <div class="col-md-6">
                                                <div class="form-label-group form-group">      
                                                    <input type="text" id="gst_number" name="gst_number" class="form-control form-control-lg" placeholder="-" value="{{ $clientDetail->gst_number ?? '' }}">  
                                                    <label for="gst_number">@lang('app.gstNumber')</label>  
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-label-group form-group">   
                                                    <textarea name="address" id="address" rows="5"
                                                            class="form-control form-control-lg">@if(!empty($clientDetail)){{ $clientDetail->address }}@endif</textarea>
                                                    <label for="address" class="control-label">@lang('modules.client.address')</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-label-group form-group">   
                                                    <textarea name="shipping_address" id="shipping_address" rows="5"
                                                            class="form-control form-control-lg">@if(!empty($clientDetail)){{ $clientDetail->shipping_address }}@endif</textarea>
                                                    <label for="shipping_address" class="control-label">@lang('app.shippingAddress')</label>
                                                </div>
                                            </div>

                                            
                                        </div>

                                        

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-label-group form-group"> 

                                                <div class="form-label-group form-group">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail">
                                                            <img id="profImage" src="{{ $clientDetail->image_url }}" alt=""/>
                                                            <img id="profImageTemp" />
                                                        </div>
                                                        
                                                        <div>
                                                            <span class="btn btn-info btn-file">
                                                            <span class="fileinput-new"> @lang('app.selectImage') </span>
                                                            <span class="fileinput-exists"> @lang('app.change') </span>
                                                            <input type="file" name="image" id="Profilepic"> </span>
                                                            <a href="javascript:;" class="btn btn-secondary fileinput-exists removeImg" data-dismiss="fileinput"> @lang('app.remove') </a>
                                                        </div>
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
                        <div class="card-footer text-right">
                            <div class="form-actions col-md-3  offset-md-9 ">
                                <button type="submit" id="save-form" class="btn btn-primary form-control" data-original-title="" title="">  @lang('app.update')</button>
                            </div>
                        </div>

                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

    

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
  

  var $inputImage = $('#Profilepic');
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

<script data-name="basic">
    (function(){
        $(window).on('load', function() {
            $('.cropper-drag-box, .cropper-crop-box').hide();
        });
        function readURL(input) 
        {
            $(".fileinput, .thumbnail").toggleClass('fileinput-new fileinput-exists');
            if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            
            reader.onload = function(e) {
            $('.fileinput-new.thumbnail img, .fileinput-exists.thumbnail img').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

    $("#Profilepic").change(function() {
        readURL(this);
    });

    $('.removeImg').click(function(){
        $('.fileinput-exists img').attr('src', '');
        $(".fileinput, .thumbnail").toggleClass('fileinput-exists fileinput-new');        
    });

        $("#department").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        $("#designation").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

// tag remvoed callback
            function onRemoveTag(e){
            }

// on character(s) added/removed (user is typing/deleting)
            function onInput(e){
            }

// invalid tag added callback
            function onInvalidTag(e){
            }

// invalid tag added callback
            function onTagClick(e){
            }

        })()
</script>

<script>
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('client.profile.update', [$userDetail->id])}}',
            container: '#updateProfile',
            type: "POST",
            redirect: true,
            file: (document.getElementById("Profilepic").files.length == 0) ? false : true,
            data: $('#updateProfile').serialize()
        })
    });
</script>
@endpush