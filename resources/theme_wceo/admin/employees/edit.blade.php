@extends('layouts.app')

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
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.edit')</li>
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
                {!! Form::open(['id'=>'updateEmployee','class'=>'ajax-form','method'=>'PUT']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.employees.updateTitle')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-label-group form-group">                                    
                                        <input type="text" name="employee_id" id="employee_id" class="form-control form-control-lg" placeholder="-" autocomplete="nope" value="{{ $employeeDetail->employee_id }}">
                                        <label for="employee_id" class="required">@lang('modules.employees.employeeId')</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-5">
                                    <div class="form-label-group form-group">      
                                        <input type="text" name="first_name" id="first_name" class="form-control form-control-lg" placeholder="-" autocomplete="nope" value="{{ $userDetail->first_name }}">  
                                        <label for="first_name" class="required">@lang('modules.employees.employeeFname')</label>  
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-label-group form-group">      
                                        <input type="text" name="last_name" id="lname" class="form-control form-control-lg" placeholder="-" autocomplete="nope" value="{{ $userDetail->last_name }}">  
                                        <label for="lname" class="required">@lang('modules.employees.employeeLname')</label>  
                                    </div>
                                </div>

                            </div>
                            <!--/row-->                            

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                         
                                        <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="-" autocomplete="nope" value="{{ $userDetail->email }}">
                                        <label for="email" class="required">@lang('modules.employees.employeeEmail')</label>
                                        <span class="help-block">@lang('modules.employees.emailNote')</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-label-group form-group">                                        
                                        <input type="password" style="display: none">
                                        <input type="password" name="password" id="password" class="form-control form-control-lg" autocomplete="nope">
                                        <label for="password" class="required">@lang('modules.employees.employeePassword')</label>
                                        <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span class="help-block"> @lang('modules.employees.passwordNote') </span>
                                    </div>
                                </div> 

                                <div class="col-md-3">
                                    <div class="form-label-group form-group">                                       
                                        <div class="checkbox checkbox-info">
                                            <input id="random_password" name="random_password" value="true" type="checkbox">
                                            <label for="random_password">@lang('modules.client.generateRandomPassword')</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                         
                                        <input autocomplete="nope" type="text" id="slack_username" name="slack_username" class="form-control form-control-lg" placeholder="-" value="{{ $employeeDetail->slack_username ?? '' }}">
                                        <label for="slack_username" class="control-label">@ @lang('modules.employees.slackUsername')</label>
                                    </div>  
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                               
                                        <input type="text" autocomplete="off"  name="joining_date" id="joining_date" class="form-control form-control-lg" placeholder="-" @if($employeeDetail) value="{{ $employeeDetail->joining_date->format($global->date_format) }}"
                                        @endif>
                                        <label for="joining_date" class="required">@lang('modules.employees.joiningDate')</label>
                                    </div>  
                                </div>                             
                            </div>                           

                            <div class="row">
                                <div class="col-md-6">                                 
                                    <div class="form-label-group form-group">
                                        <input type="text" autocomplete="off" name="last_date" id="end_date" class="form-control form-control-lg" placeholder="-" @if($employeeDetail) value="@if($employeeDetail->last_date) {{ $employeeDetail->last_date->format($global->date_format) }} @endif"
                                        @endif>
                                        <label for="end_date" class="control-label">@lang('modules.employees.lastDate')</label>
                                    </div>
                                </div>   
                                <div class="col-md-6"> 
                                    <div class="form-label-group form-group">                                   
                                        <select name="gender" id="gender" class="hide-search form-control form-control-lg" placeholder="-">
                                            <option @if($userDetail->gender == 'male') selected
                                                    @endif value="male">@lang('app.male')</option>
                                            <option @if($userDetail->gender == 'female') selected
                                                    @endif value="female">@lang('app.female')</option>
                                            <option @if($userDetail->gender == 'others') selected
                                                    @endif value="others">@lang('app.others')</option>
                                        </select>  
                                        <label for="gender" class="control-label">@lang('modules.employees.gender')</label>
                                    </div>                                  
                                </div>                            
                            </div>

                            <div class="row">
                                <div class="col-md-4 ">
                                    <div class="form-label-group form-group">                                        
                                        <select class="select2 m-b-10 select2-multiple form-control " multiple="multiple" name='tags[]' id="tags" data-placeholder="@lang('app.skills')" >
                                            @forelse($skills as $key=>$skill)
                                                <option value="{{ $skill->id }}"
                                                @if(in_array($skill->id, $userDetail->skillIds())) selected @endif
                                                >{{ ucwords($skill->name) }}</option>
                                            @empty
                                                <option value="">@lang('No Skill Added')</option>
                                            @endforelse
                                        </select>
                                        <label for="tags" class="col-form-label">@lang('app.skills')</label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <a href="javascript:;" id="addSkill" class="btn btn-sm btn-outline btn-block btn-primary"><i
                                                        class="fa fa-plus"></i> @lang('app.add') @lang('app.skills')</a>
                                        
                                    </div>
                                </div>  
                                <div class="col-md-6">   
                                    <div class="form-label-group form-group">                              
                                        <select name="designation" id="designation" class="form-control form-control-lg" placeholder="-">
                                            @forelse($designations as $designation)
                                                <option @if(isset($employeeDetail->designation_id) && $employeeDetail->designation_id == $designation->id) selected @endif value="{{ $designation->id }}">{{ $designation->name }}</option>
                                            @empty
                                                <option value="">@lang('messages.noRecordFound')</option>
                                            @endforelse()
                                        </select>   
                                        <label for="designation" class="required">@lang('app.designation') <a href="javascript:;" id="designation-setting" ><i class="icofont icofont-gear"></i></a></label>             
                                    </div>                  
                                </div>                            
                            </div>

                            <div class="row">
                                <div class="col-md-6">                                 
                                    <div class="form-label-group form-group">                                        
                                        <select name="department" id="department" class="form-control form-control-lg" placeholder="-">
                                            <option value="">--</option>
                                            @foreach($teams as $team)
                                                <option @if(isset($employeeDetail->department_id) && $employeeDetail->department_id == $team->id) selected @endif value="{{ $team->id }}">{{ $team->team_name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="department" class="required">@lang('app.department') <a href="javascript:;" id="department-setting" ><i class="icofont icofont-gear"></i></a></label>
                                    </div>
                                </div>   
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                     
                                        <input type="tel" name="mobile" id="mobile"  value="{{ $userDetail->mobile }}" class="form-control form-control-lg" placeholder="-" autocomplete="nope">  
                                        <label for="mobile" class="control-label">@lang('app.mobile')</label> 
                                    </div>                         
                                </div>                            
                            </div>

                            <div class="row">
                                <div class="col-md-6">                                 
                                    <div class="form-label-group form-group">                                       
                                        <input type="text" name="hourly_rate" id="hourly_rate" value="{{ $employeeDetail->hourly_rate ?? '' }}" class="form-control form-control-lg" placeholder="-">
                                        <label for="hourly_rate" class="control-label">@lang('modules.employees.hourlyRate')</label>
                                    </div>
                                </div>   
                                <div class="col-md-3"> 
                                    <div class="form-label-group form-group">                               
                                        <select name="login" id="login" class="hide-search form-control form-control-lg" placeholder="-">
                                            <option @if($userDetail->login == 'enable') selected @endif value="enable">@lang('app.enable')</option>
                                            <option @if($userDetail->login == 'disable') selected @endif value="disable">@lang('app.disable')</option>
                                        </select>        
                                        <label for="login" class="control-label">@lang('app.login')</label>
                                    </div>                
                                </div> 

                                <div class="col-md-3"> 
                                    <div class="form-label-group form-group">                              
                                        <select name="status" id="status" class="hide-search form-control form-control-lg">
                                            <option @if($userDetail->status == 'active') selected
                                                    @endif value="active">@lang('app.active')</option>
                                            <option @if($userDetail->status == 'deactive') selected
                                                    @endif value="deactive">@lang('app.deactive')</option>
                                        </select>
                                        <label for="status" class="control-label">@lang('app.status')</label>
                                    </div>                
                                </div>                            
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group">   
                                      <textarea name="address"  id="address"  rows="5" class="form-control form-control-lg" placeholder="-">{{ $employeeDetail->address ?? '' }}</textarea>   
                                      <label for="address" class="control-label">@lang('app.address')</label>                                            
                                    </div>
                                </div>
                            </div>                    
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group"> 

                                    <div class="form-label-group form-group">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail">
                                                <img id="profImage" src="{{ $userDetail->image_url }}" alt=""/>
                                                <img id="profImageTemp" />
                                            </div>
                                            
                                            <div>
                                                <span class="btn btn-primary btn-file">
                                                <span class="fileinput-new"> @lang('app.selectImage') </span>
                                                <span class="fileinput-exists"> @lang('app.change') </span>
                                                <input type="file" name="image" id="Profilepic"> </span>
                                                <a href="javascript:;" class="btn btn-outline-primary gray fileinput-exists removeImg" data-dismiss="fileinput"> @lang('app.remove') </a>
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

                            <div class="row">
                            @if(isset($fields)) @foreach($fields as $field)
                            <div class="col-md-6">
                                <label>{{ ucfirst($field->label) }}</label>
                                <div class="form-group">
                                    @if( $field->type == 'text')
                                    <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}"
                                        value="{{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}">                                    @elseif($field->type == 'password')
                                    <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}"
                                        value="{{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}">                                    @elseif($field->type == 'number')
                                    <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}"
                                        value="{{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}">                                    @elseif($field->type == 'textarea')
                                    <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" id="{{$field->name}}" cols="3">{{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>                                    @elseif($field->type == 'radio')
                                    <div class="radio-list">
                                        @foreach($field->values as $key=>$value)
                                        <label class="radio-inline @if($key == 0) p-0 @endif">
                                                                <div class="radio radio-info">
                                                                    <input type="radio"
                                                                           name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                                           id="optionsRadios{{$key.$field->id}}"
                                                                           value="{{$value}}"
                                                                           @if(isset($employeeDetail) && $employeeDetail->custom_fields_data['field_'.$field->id] == $value) checked
                                                                           @elseif($key==0) checked @endif>>
                                                                    <label for="optionsRadios{{$key.$field->id}}">{{$value}}</label>
                                    </div>
                                    </label>
                                    @endforeach
                                </div>
                                @elseif($field->type == 'select') {!! Form::select('custom_fields_data['.$field->name.'_'.$field->id.']', $field->values,
                                isset($employeeDetail)?$employeeDetail->custom_fields_data['field_'.$field->id]:'',['class'
                                => 'form-control gender']) !!} @elseif($field->type == 'checkbox')
                                <div class="mt-checkbox-inline">
                                    @foreach($field->values as $key => $value)
                                    <label class="mt-checkbox mt-checkbox-outline">
                                                                <input name="custom_fields_data[{{$field->name.'_'.$field->id}}][]"
                                                                       type="checkbox" value="{{$key}}"> {{$value}}
                                                                <span></span>
                                                            </label> @endforeach
                                </div>
                                @elseif($field->type == 'date')
                                <input type="text" class="form-control date-picker" size="16" name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                    value="{{ ($employeeDetail->custom_fields_data['field_'.$field->id] != '') ? \Carbon\Carbon::createFromFormat('m/d/Y', $employeeDetail->custom_fields_data['field_'.$field->id])->format('m/d/Y') : \Carbon\Carbon::now()->format('m/d/Y')}}">                                @endif
                                <div class="form-control-focus"></div>
                                <span class="help-block"></span>

                            </div>
                        </div>
                        @endforeach @endif

                    </div>

                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <div class="form-actions col-md-6 offset-md-7">
                            <div class="row">
                                <div class="col-md-5 pr-0">
                                    <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-primary gray form-control" >@lang('app.cancel')</a>
                                </div>
                                <div class="col-md-5 pr-0">
                                <button type="submit" id="save-form" class="btn btn-primary form-control">@lang('app.save')</button>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>   
    

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="departmentModel" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
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
<!-- <script src="{{ asset('themes/wceo/assets/js/image-cropper/cropper-main.js')}}"></script> -->

<!--Image crop-->
<script>

$('#addSkill').click(function(){
    var url = '{{ route('admin.employees.create-skill')}}';
    $('#modelHeading').html('Manage Skills');
    $.ajaxModal('#departmentModel', url);
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


    $('#joining_date, .date-picker, #end_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });


    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.employees.update', [$userDetail->id])}}',
            container: '#updateEmployee',
            type: "POST",
            redirect: true,
            file: (document.getElementById("Profilepic").files.length == 0) ? false : true,
            data: $('#updateEmployee').serialize()
        })
    });

    $('#department-setting').on('click', function (event) {
        event.preventDefault();
        var url = '{{ route('admin.teams.quick-create')}}';
        $('#modelHeading').html("@lang('messages.manageDepartment')");
        $.ajaxModal('#departmentModel', url);
    });

    $('#designation-setting').on('click', function (event) {
        event.preventDefault();
        var url = '{{ route('admin.designations.quick-create')}}';
        $('#modelHeading').html("@lang('messages.manageDepartment')");
        $.ajaxModal('#departmentModel', url);
    });

    $(function() {
  $('#image').cropper({
    zoomable: false
  });
  $('#image').cropper({
    autoCrop: false
  });
  $('.crop-drag').cropper({
    movable: false
  });
  $('.crop-min').cropper({
    minCropBoxWidth: 150,
    minCropBoxHeight: 150
  });
});
</script>

@endpush
