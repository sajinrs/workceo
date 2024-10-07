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
                          <h5>@lang('modules.accountSettings.updateTitle') </h5>
                           
                        </div>

                    {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                        <div  class="card-body">
             
                        <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">

                   

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-label-group form-group">
                                                <input type="text" class="form-control form-control-lg" id="company_name" name="company_name" placeholder="*" value="{{ $global->company_name }}">
                                                <label class="col-form-label required" for="company_name">@lang('modules.accountSettings.companyName')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-label-group form-group">
                                                <input type="email" class="form-control form-control-lg" id="company_email" name="company_email" placeholder="*" value="{{ $global->company_email }}">
                                                <label class="col-form-label required" for="company_email">@lang('modules.accountSettings.companyEmail')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-label-group form-group">
                                                <input type="tel" class="form-control form-control-lg" id="company_phone" name="company_phone" placeholder="*" value="{{ $global->company_phone }}">
                                                <label class="col-form-label" for="company_phone">@lang('modules.accountSettings.companyPhone')</label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="exampleInputPassword1">@lang('modules.accountSettings.companyLogo')</label>
                                            <div class="form-label-group form-group imggroup">
                                                
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail"
                                                             style="width: 100%;">
                                                            <img src="{{ $global->logo_url }}"
                                                                 alt="" class="fileinput-preview-set" />
                                  <input type="hidden" id="logo_url" value="{{ $global->logo_url }}">
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail thumbnailset"
                                                             style="max-width: 150px; max-height: 100px;"></div>
                                                        <div>
                                <span class="btn btn-primary btn-block  btn-file">
                                    <span class="fileinput-new selectImages"> @lang('app.selectImage') </span>
                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                    <input type="file" name="logo" id="logo"> </span>
                                                            <a href="javascript:;" class="btn btn-danger removeall fileinput-exists"
                                                               data-dismiss="fileinput"> @lang('app.remove') </a>
                                                        </div>
                                                    </div>

                                            </div>
                                        </div>
                                        <div class="col-md-4">  <label for=""></label>
                                            <div class="form-label-group form-group">
                                                <input type="text" class="form-control form-control-lg" id="website" name="website" placeholder="*" value="{{ $global->website }}">
                                                <label for="website" class="col-form-label">@lang('modules.accountSettings.companyWebsite')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">  <label for=""></label>
                                            <div class="form-label-group form-group">
                                                <textarea class="form-control form-control-lg" id="address" placeholder="*" rows="5" name="address">{{ $global->address }}</textarea>
                                                <label for="address" class="col-form-label required">@lang('modules.accountSettings.companyAddress')</label>
                                            </div>
                                        </div>
                                    </div>


                                    <hr>

                                    <div class="row m-t-40">
                                        
                                        <div class="col-md-4">
                                            <div class="form-label-group form-group">
                                              
                                                <select name="currency_id" id="currency_id" class="form-control form-control-lg hide-search" placeholder="*">
                                                    @foreach($currencies as $currency)
                                                        <option
                                                                @if($currency->id == $global->currency_id) selected @endif
                                                        value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                                                    @endforeach
                                                </select>
                                                  <label for="currency_id" class="col-form-label">@lang('modules.accountSettings.defaultCurrency')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-label-group form-group">
                                             
                                                <select name="timezone" id="timezone" class="form-control form-control-lg select2" placeholder="*">
                                                    @foreach($timezones as $tz)
                                                        <option @if($global->timezone == $tz) selected @endif>{{ $tz }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="timezone" class="col-form-label">@lang('modules.accountSettings.defaultTimezone')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-label-group form-group">
                                              
                                                <select name="date_format" id="date_format" class="form-control form-control-lg hide-search" placeholder="*">
                                                    <option value="d-m-Y" @if($global->date_format == 'd-m-Y') selected @endif >d-m-Y ({{ $dateObject->format('d-m-Y') }}) </option>
                                                    <option value="m-d-Y" @if($global->date_format == 'm-d-Y') selected @endif >m-d-Y ({{ $dateObject->format('m-d-Y') }}) </option>
                                                    <option value="Y-m-d" @if($global->date_format == 'Y-m-d') selected @endif >Y-m-d ({{ $dateObject->format('Y-m-d') }}) </option>
                                                    <option value="d.m.Y" @if($global->date_format == 'd.m.Y') selected @endif >d.m.Y ({{ $dateObject->format('d.m.Y') }}) </option>
                                                    <option value="m.d.Y" @if($global->date_format == 'm.d.Y') selected @endif >m.d.Y ({{ $dateObject->format('m.d.Y') }}) </option>
                                                    <option value="Y.m.d" @if($global->date_format == 'Y.m.d') selected @endif >Y.m.d ({{ $dateObject->format('Y.m.d') }}) </option>
                                                    <option value="d/m/Y" @if($global->date_format == 'd/m/Y') selected @endif >d/m/Y ({{ $dateObject->format('d/m/Y') }}) </option>
                                                    <option value="m/d/Y" @if($global->date_format == 'm/d/Y') selected @endif >m/d/Y ({{ $dateObject->format('m/d/Y') }}) </option>
                                                    <option value="Y/m/d" @if($global->date_format == 'Y/m/d') selected @endif >Y/m/d ({{ $dateObject->format('Y/m/d') }}) </option>
                                                    <option value="d-M-Y" @if($global->date_format == 'd-M-Y') selected @endif >d-M-Y ({{ $dateObject->format('d-M-Y') }}) </option>
                                                    <option value="d/M/Y" @if($global->date_format == 'd/M/Y') selected @endif >d/M/Y ({{ $dateObject->format('d/M/Y') }}) </option>
                                                    <option value="d.M.Y" @if($global->date_format == 'd.M.Y') selected @endif >d.M.Y ({{ $dateObject->format('d.M.Y') }}) </option>
                                                    <option value="d-M-Y" @if($global->date_format == 'd-M-Y') selected @endif >d-M-Y ({{ $dateObject->format('d-M-Y') }}) </option>
                                                    <option value="d M Y" @if($global->date_format == 'd M Y') selected @endif >d M Y ({{ $dateObject->format('d M Y') }}) </option>
                                                    <option value="d F, Y" @if($global->date_format == 'd F, Y') selected @endif >d F, Y ({{ $dateObject->format('d F, Y') }}) </option>
                                                    <option value="D/M/Y" @if($global->date_format == 'D/M/Y') selected @endif >D/M/Y ({{ $dateObject->format('D/M/Y') }}) </option>
                                                    <option value="D.M.Y" @if($global->date_format == 'D.M.Y') selected @endif >D.M.Y ({{ $dateObject->format('D.M.Y') }}) </option>
                                                    <option value="D-M-Y" @if($global->date_format == 'D-M-Y') selected @endif >D-M-Y ({{ $dateObject->format('D-M-Y') }}) </option>
                                                    <option value="D M Y" @if($global->date_format == 'D M Y') selected @endif >D M Y ({{ $dateObject->format('D M Y') }}) </option>
                                                    <option value="d D M Y" @if($global->date_format == 'd D M Y') selected @endif >d D M Y ({{ $dateObject->format('d D M Y') }}) </option>
                                                    <option value="D d M Y" @if($global->date_format == 'D d M Y') selected @endif >D d M Y ({{ $dateObject->format('D d M Y') }}) </option>
                                                    <option value="dS M Y" @if($global->date_format == 'dS M Y') selected @endif >dS M Y ({{ $dateObject->format('dS M Y') }}) </option>
                                                </select>
                                                  <label for="date_format" class="col-form-label">@lang('modules.accountSettings.dateFormat')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-label-group form-group">
                                               
                                                <select name="time_format" id="time_format" class="form-control form-control-lg hide-search" placeholder="*">
                                                    <option value="h:i A" @if($global->time_format == 'H:i A') selected @endif >12 Hour  (6:20 PM) </option>
                                                    <option value="h:i a" @if($global->time_format == 'H:i a') selected @endif >12 Hour  (6:20 pm) </option>
                                                    <option value="H:i" @if($global->time_format == 'H:i') selected @endif >24 Hour  (18:20) </option>
                                                </select>
                                                 <label for="time_format" class="col-form-label">@lang('modules.accountSettings.timeFormat')</label>
                                            </div>
                                        </div>
                                   
                                        <div class="col-md-4">
                                            <div class="form-label-group form-group">
                                              
                                                <select name="week_start" id="week_start" class="form-control form-control-lg hide-search" placeholder="*">
                                                    <option value="0" @if($global->week_start == '0') selected @endif >Sunday</option>
                                                    <option value="1" @if($global->week_start == '1') selected @endif>Monday </option>
                                                    <option value="2" @if($global->week_start == '2') selected @endif>Tuesday</option>
                                                    <option value="3" @if($global->week_start == '3') selected @endif>Wednesday</option>
                                                    <option value="4" @if($global->week_start == '4') selected @endif>Thursday</option>
                                                    <option value="5" @if($global->week_start == '5') selected @endif>Friday</option>
                                                    <option value="6" @if($global->week_start == '6') selected @endif>Saturday</option>
                                                </select>
                                                  <label for="week_start" class="col-form-label">@lang('modules.accountSettings.weekStartFrom')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-label-group form-group">
                                              
                                                <select name="locale" id="locale" class="form-control form-control-lg hide-search" placeholder="*">
                                                    <option @if($global->locale == "en") selected @endif value="en">English
                                                    </option>
                                                    @foreach($languageSettings as $language)
                                                        <option value="{{ $language->language_code }}" @if($global->locale == $language->language_code) selected @endif >{{ $language->language_name }}</option>
                                                    @endforeach
                                                </select>
                                                  <label for="locale" class="col-form-label">@lang('modules.accountSettings.changeLanguage')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-label-group form-group">
                                                <input type="text" class="form-control form-control-lg "  id="latitude" name="latitude" value="{{ $global->latitude }}" placeholder="*">
                                                <label for="latitude" class="col-form-label">@lang('app.latitude')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-label-group form-group">
                                                <input type="text" class="form-control form-control-lg" id="longitude" name="longitude" value="{{ $global->longitude }}" placeholder="*">
                                                <label for="longitude" class="col-form-label">@lang('app.longitude')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-label-group form-group">
                                                <div class="row">
                                                    <label class="control-label col-md-8 pt-2 pr-0">@lang('app.dashboardActionPopup')</label> 
                                                        <div class="switch-showcase icon-state">
                                                            <label class="switch"> 
                                                                <input type="checkbox" name="dashboard_action_popup_status" @if($global->dashboard_action_popup_status == 'active') checked @endif><span class="switch-state"></span>
                                                                
                                                            </label><?php //echo $global->dashboard_action_popup_status; ?>
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

        <div class="card-footer text-right">
            <div class="form-actions col-md-3  offset-md-9 ">
                <button type="submit" id="save-form" class="btn btn-primary form-control"> @lang('app.update')</button>
            </div>
        </div>
        {!! Form::close() !!}


    </div>
    <!-- .row -->

@endsection

@push('footer-script')
<!-- <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script> -->
<!--  <script src="{{ asset('themes/wceo/assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/select2/select2-custom.js')}}"></script> -->
   

<script>
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: "{{route('admin.settings.update', [$global->id])}}",
            container: '#editSettings',
            type: "POST",
            redirect: true,
            file: true
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

