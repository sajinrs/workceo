@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
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
        @include('sections.gdpr_settings_menu')
       </div>
        <div class="col-md-9">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                          <h5>Right to be informed</h5>
                           
                        </div>
                        {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab">



                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12">
                                 
                                    
                                        <div class="col-md-12">
                                            
                                            <label for="">Enable Terms & Conditions customers footer</label>
                                                <div class="form-group m-checkbox-inline">
                                                    <div class="radio radio-primary">
                                                        <input id="terms_customer_footer1" type="radio" name="terms_customer_footer" value="1"  @if($gdprSetting->terms_customer_footer) checked @endif />
                                                        <label class="mb-0" for="terms_customer_footer1"><span class="digits"> Yes</span></label>
                                                    </div>
                                                    <div class="radio radio-primary">
                                                        <input id="terms_customer_footer2" type="radio" name="terms_customer_footer" value="0" @if($gdprSetting->terms_customer_footer==0) checked @endif />
                                                        <label class="mb-0" for="terms_customer_footer2"><span class="digits"> No</span></label>
                                                    </div>
                                                </div>
                                                
                                            <hr>
                                            <label for="">Terms and condition</label>
                                            <code class="text-success" ><a target="_blank" href="{{route('client.gdpr.terms')}}">{{route('client.gdpr.terms')}}</a></code>
                                            <div class="form-group">
                                                <textarea name="terms" id="" cols="30" rows="10" class="summer_description">
                                                    {{$gdprSetting->terms}}
                                                </textarea>

                                            </div>

                                            <hr>
                                            <label for="">Privacy and policy</label>
                                            <code class="text-danger" ><a target="_blank" href="{{route('client.gdpr.privacy')}}">{{route('client.gdpr.privacy')}}</a></code>
                                            <div class="form-group">
                                                <textarea name="policy" id="" cols="30" rows="10" class="summer_description">
                                                    {{$gdprSetting->policy}}
                                                </textarea>

                                            </div>
                                              
                                        </div>
                                   

                                </div>
                            </div>
                            <!-- /.row -->

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer text-right">
            <div class="form-actions col-md-3  offset-md-9 ">
                <button type="submit" onclick="submitForm();" class="btn btn-primary form-control"> Submit</button>
            </div>
        </div>
        {!! Form::close() !!}


    </div>
    </div>
    </div>
    </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.custom.js')}}"></script>
    <script>
        $('.summer_description').summernote({
            height: 200,                 // set editor height
            minHeight: null,             // set minimum height of editor
            maxHeight: null,             // set maximum height of editor
            focus: false,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['fontsize', ['fontsize']],
                ['para', ['ul', 'ol', 'paragraph']],
                ["view", ["fullscreen"]]
            ]
        }); 

        function submitForm(){

            $.easyAjax({
                url: '{{route('admin.gdpr.store')}}',
                container: '#editSettings',
                type: "POST",
                data: $('#editSettings').serialize(),
            })
        }

    </script>
@endpush

