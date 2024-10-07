@extends('layouts.app')

@push('head-script')

<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/image-cropper.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dropzone.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.vehicles.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.addNew')</li>
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
                <div class="card">
                {!! Form::open(['id'=>'createVehicle','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.vehicles.createTitle')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                                                       

                            

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">      
                                        <input type="text" name="vehicle_name" id="vehicle_name" class="form-control form-control-lg" placeholder="-" autocomplete="nope">  
                                        <label for="vehicle_name" class="required">@lang('modules.vehicles.name')</label>  
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                               
                                        <input type="text" autocomplete="off"  name="license_plate" id="license_plate" class="form-control form-control-lg" placeholder="-">
                                        <label for="license_plate" class="required">@lang('modules.vehicles.licensePlate')</label>
                                    </div>  
                                </div>                             
                            </div>                         

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                        
                                        <select class="select2 m-b-10 form-control" id="user_type" data-placeholder="*" >
                                            <option value="operator">Operator</option>
                                            <option value="employee">Employee</option>
                                        </select>
                                        <label for="user_type" class="col-form-label">User Type</label>
                                    </div>
                                </div>

                                <div class="col-md-4 ">
                                    <div class="form-label-group form-group">                                        
                                        <select class="form-control form-control-lg" name='operator_id' id="operator" placeholder="*" >
                                            <option value="">--</option>
                                            @forelse($operators as $operator)
                                                <option value="{{ $operator->id }}">{{ ucwords($operator->name) }}({{$operator->email}})</option>
                                            @empty
                                                <option value="">@lang('No Operator Added')</option>
                                            @endforelse
                                        </select>
                                        <label for="operator" class="col-form-label">Operator</label>
                                    </div>
                                </div>

                                <div class="col-md-2 p-l-0">
                                    <div class="form-group">
                                        <a href="javascript:;" id="addOperator" class="btn btn-sm btn-outline btn-block btn-primary"><i class="fa fa-plus"></i> @lang('app.add') Operator</a>
                                    </div>
                                </div>  
                            </div>

                            <div class="row">
                                <div class="col-md-6">                                 
                                    <div class="form-label-group form-group">                                        
                                        <select name="year" id="year" class="select2 form-control form-control-lg" placeholder="-">
                                            <option value="">--</option>
                                            @for($i = 1980 ; $i <= date('Y'); $i++)
                                                <option value="{{$i}}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                        <label for="year" class="required">Year </label>
                                    </div>
                                </div>   
                                <div class="col-md-6">                                 
                                    <div class="form-label-group form-group">
                                        <input type="text" autocomplete="off" name="make" id="make" class="form-control form-control-lg" placeholder="-">
                                        <label for="make" class="required">Make</label>
                                    </div>
                                </div>  
                                
                                 
                            </div>

                            <div class="row">
                                <div class="col-md-6">                                 
                                    <div class="form-label-group form-group">
                                        <input type="text" autocomplete="off" name="model" id="model" class="form-control form-control-lg" placeholder="-">
                                        <label for="model" class="required">Model</label>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-label-group form-group">                               
                                        <select name="status" id="status" class="hide-search form-control form-control-lg" placeholder="-">
                                            <option value="active">Active</option>
                                            <option value="in_shop">In Shop</option>
                                            <option value="out_of_service">Out of Service</option>
                                            <option value="inactive">Inactive</option>
                                        </select>        
                                        <label for="status" class="control-label">Status</label>
                                    </div>                
                                </div>                            
                            </div>
                            
                           
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group"> 

                                    <div class="form-label-group form-group">
                                        <div class="fileinput fileinput-new " data-provides="fileinput">
                                            <div class="fileinput-new thumbnail profilethumb d-none">
                                                <img id="profImageTemp" data-original-title="" title="">
                                            </div>
                                            
                                            <div>
                                                <span class="btn btn-primary btn-file">
                                                <span class="fileinput-new"> Select Vehicle Photo </span>
                                                <span class="fileinput-exists"> @lang('app.change') </span>
                                                <input type="file" name="image" id="Profilepic"> </span>
                                                <a href="javascript:;" style="margin-top: -6px;" class="btn btn-outline-primary gray fileinput-exists removeImg" data-dismiss="fileinput"> @lang('app.remove') </a>
                                            </div>
                                        </div>
                                    </div>                                                                           
                                    </div>
                                    <input id="dataX" type="hidden" name="x">
                                    <input id="dataY" type="hidden" name="y">
                                    <input id="dataWidth" type="hidden" name="width">
                                    <input id="dataHeight" type="hidden" name="height">
                                </div>                                    

                                    

                                    <div class="col-md-12">                                    
                                    <h6 class="text-primary">Upload Photos </h6>
                                    <button type="button"
                                            class="btn btn-block btn-outline-info btn-sm col-md-2 select-image-button"
                                            style="margin-bottom: 10px;display: none "><i class="fa fa-upload"></i>
                                        File Select Or Upload
                                    </button>
                                    <div id="file-upload-box2">
                                        <div class="row" id="file-dropzone2">
                                            <div class="col-md-12">
                                                <div class="dropzone"
                                                     id="photo-upload-dropzone">
                                                    {{ csrf_field() }}
                                                    <div class="dz-message needsclick"><i class="icon-cloud-up"></i>
                                                        <h6>Drop files here or click to upload.</h6></span>
                                                    </div>
                                                    <div class="fallback">
                                                        <input name="file" type="file" multiple/>
                                                    </div>
                                                    <input name="image_url" type="hidden"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <br />
                                    <br />
                                        <h6 class="text-primary">Upload Documents </h6>
                                        <button type="button"
                                                class="btn btn-block btn-outline-info btn-sm col-md-2 select-image-button"
                                                style="margin-bottom: 10px;display: none "><i class="fa fa-upload"></i>
                                            File Select Or Upload
                                        </button>
                                        <div id="file-upload-box">
                                            <div class="row" id="file-dropzone">
                                                <div class="col-md-12">
                                                    <div class="dropzone"
                                                         id="file-upload-dropzone">
                                                        {{ csrf_field() }}
                                                        <div class="dz-message needsclick"><i class="icon-cloud-up"></i>
                                                            <h6>Drop files here or click to upload.</h6></span>
                                                        </div>
                                                        <div class="fallback">
                                                            <input name="file" type="file" multiple/>
                                                        </div>
                                                        <input name="image_url" id="image_url" type="hidden"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="vehicleID" id="vehicleID">
                                    </div>
                            </div> 

                            

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions col-md-3 offset-md-9">
                            <button type="submit" id="save-form" class="btn btn-primary form-control">@lang('app.save')</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="operatorModel" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">Modal title</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>                    
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection


@push('footer-script')


<script src="{{ asset('themes/wceo/assets/js/image-cropper/cropper.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/dropzone/dropzone.js')}}"></script>

<!--Image crop-->
<script>

$('#addOperator').click(function(){
    var url = '{{ route('admin.vehicles.create-operator')}}';
    $('#modelHeading').html('Manage Skills');
    $.ajaxModal('#operatorModel', url);
});

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
        $('.profilethumb').removeClass('d-none');
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

    $('#user_type').change(function (e) {
        var user_type = $(this).val();        
        var url = "{{ route('admin.vehicles.get-operators') }}";
        $.easyAjax({
            type: 'GET',
            dataType: 'JSON',
            url: url,
            data:{'user_type':user_type},
            success: function (data) {
                if(user_type == 'employee')
                    $('#addOperator').hide();
                else
                    $('#addOperator').show();

                $('#operator').html('');
                $('#operator').append('<option value="">--</option>');
                $.each(data, function (index, data) {
                    $('#operator').append('<option value="' + data.id + '">' + data.name + '('+data.email+')</option>');
                });
            }
        });
    });

    
    
    Dropzone.autoDiscover = false;
        //Dropzone class
        myDropzone = new Dropzone("div#file-upload-dropzone", {
            url: "{{ route('admin.vehicles.store-documents') }}",
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            paramName: "file",
            maxFilesize: 10,
            maxFiles: 10,
            acceptedFiles: "image/*,application/pdf",
            autoProcessQueue: false,
            uploadMultiple: true,
            addRemoveLinks: true,
            parallelUploads: 10,
            init: function () {
                myDropzone = this;

                this.on("error", function(file, message) { 
                    $.showToastr(message, 'error');
                    this.removeFile(file); 
                });

                this.on('addedfile', function(file) {
                    var ext = file.name.split('.').pop();
                    if (ext != 'png' && ext != 'jpg' && ext != 'jpeg') {
                        $(file.previewElement).find(".dz-image img").attr("src", "{{ asset('img/file-preview.png') }}").css('display', 'block');
                    } 
                });
            }
        });

        myDropzone.on('sending', function (file, xhr, formData) {
            console.log(myDropzone.getAddedFiles().length, 'sending');
            var ids = $('#vehicleID').val();
            formData.append('vehicle_id', ids);
            formData.append('type', 'document');
        });



        //Photo Upload
        photoDropzone = new Dropzone("div#photo-upload-dropzone", {
            url: "{{ route('admin.vehicles.store-documents') }}",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            paramName: "file",
            maxFilesize: 10,
            maxFiles: 10,
            acceptedFiles: "image/*",
            autoProcessQueue: false,
            uploadMultiple: true,
            addRemoveLinks:true,
            parallelUploads:10,
            init: function () {
                photoDropzone = this;

                this.on("error", function(file, message) { 
                    $.showToastr(message, 'error');
                    this.removeFile(file); 
                });
            }
        });

        photoDropzone.on('sending', function(file, xhr, formData) {
            console.log(photoDropzone.getAddedFiles().length,'sending');
            var ids = $('#vehicleID').val();
            formData.append('vehicle_id', ids);
            formData.append('type', 'photo');
        });



        $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.vehicles.store')}}',
            container: '#createVehicle',
            type: "POST",
            redirect: true,
            file: (document.getElementById("Profilepic").files.length == 0) ? false : true,
            data: $('#createVehicle').serialize(),
            success: function (data) {
                var photoUploadcompleted = false;
                var docUploadcompleted = false;
                if (myDropzone.getQueuedFiles().length <= 0) {
                    docUploadcompleted = true;
                }
                if (photoDropzone.getQueuedFiles().length <= 0) {
                    photoUploadcompleted = true;
                }
                if (myDropzone.getQueuedFiles().length > 0) {
                    vehicleID = data.vehicleID;
                    $('#vehicleID').val(data.vehicleID);
                    myDropzone.processQueue();
                    myDropzone.on('completemultiple', function () {
                        {{--var msgs = "@lang('messages.vehicleAdded')";--}}
                        {{--$.showToastr(msgs, 'success');--}}
                        docUploadcompleted = true;
                        console.log('complete doc upload');
                        if(photoUploadcompleted && docUploadcompleted){
                            var msgs = "@lang('messages.vehicleAdded')";
                            $.showToastr(msgs, 'success');
                            window.location.href = '{{ route('admin.vehicles.index') }}'
                        }
                    });
                }
                
                if (photoDropzone.getQueuedFiles().length > 0) {
                    vehicleID = data.vehicleID;
                    $('#vehicleID').val(data.vehicleID);
                    photoDropzone.processQueue();
                    photoDropzone.on('completemultiple', function () {
                        {{--var msgs = "@lang('messages.vehicleUpdatedSuccessfully')";--}}
                        {{--$.showToastr(msgs, 'success');--}}
                        {{--window.location.href = '{{ route('admin.vehicles.index') }}'--}}
                        photoUploadcompleted = true;
                        console.log('complete photo upload');
                        if(photoUploadcompleted && docUploadcompleted){
                            var msgs = "@lang('messages.vehicleAdded')";
                            $.showToastr(msgs, 'success');
                            window.location.href = '{{ route('admin.vehicles.index') }}'
                        }
                    });
                }
                
                
                if(myDropzone.files.length == 0 || photoDropzone.files.length == 0){
                    var msgs = "@lang('messages.vehicleAdded')";
                    $.showToastr(msgs, 'success');
                    window.location.href = '{{ route('admin.vehicles.index') }}'
                }
            }
        })
    });


</script>

<script data-name="basic">
    (function(){

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
        $('.thumbnail.profilethumb').addClass('d-none');
        $('#Profilepic').val('');
    });   


    })()
</script>


@endpush

