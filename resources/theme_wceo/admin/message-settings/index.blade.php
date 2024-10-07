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
                          <h5>@lang('app.menu.messageSettings')</h5>
                           
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

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="allow-client-admin" name="allow_client_admin" value="yes"
                                                               @if($messageSettings->allow_client_admin == 'yes') checked @endif
                                                               type="checkbox">
                                                        <label for="allow-client-admin">@lang('modules.messages.allowClientAdminChat')</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="allow-client-employee" name="allow_client_employee" value="yes"
                                                               @if($messageSettings->allow_client_employee == 'yes') checked @endif
                                                               type="checkbox">
                                                        <label for="allow-client-employee">@lang('modules.messages.allowClientEmployeeChat')</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->

                                        </div>
                                        <!--/row-->
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>

                </div>
            </div>    <!-- .row -->
        </div>

        <div class="card-footer text-right">
            <div class="form-actions col-md-3  offset-md-9 ">
                <button type="submit" id="save-form-2" class="btn btn-primary form-control"> @lang('app.update')</button>
            </div>
        </div>
        {!! Form::close() !!}

    </div>

@endsection

@push('footer-script')
<script>
    $('#save-form-2').click(function () {
        $.easyAjax({
            url: '{{route('admin.message-settings.update', [1])}}',
            container: '#updateProfile',
            type: "POST",
            data: $('#updateProfile').serialize()
        })
    });
</script>
@endpush
