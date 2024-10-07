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
        @include('sections.gdpr_settings_menu')
       </div>
        <div class="col-md-9">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                          <h5>Right of access</h5>
                           
                        </div>
                        {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">


                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12">
                               
                                   
                                        <div class="col-md-12">
                                            
                                            <label for="">Allow lead to update their data from public form</label>
                                            <div class="form-group m-checkbox-inline">
                                                    <div class="radio radio-primary">
                                                        <input id="public_lead_edit1" type="radio" name="public_lead_edit" value="1"  @if($gdprSetting->public_lead_edit) checked @endif />
                                                        <label class="mb-0" for="public_lead_edit1"><span class="digits"> Yes</span></label>
                                                    </div>
                                                    <div class="radio radio-primary">
                                                        <input id="public_lead_edit2" type="radio" name="public_lead_edit" value="0" @if($gdprSetting->public_lead_edit==0) checked @endif />
                                                        <label class="mb-0" for="public_lead_edit2"><span class="digits"> No</span></label>
                                                    </div>
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
    <!-- .row -->
    </div>
    </div>
    </div>
    </div>

@endsection

@push('footer-script')
 
    <script>
        
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

