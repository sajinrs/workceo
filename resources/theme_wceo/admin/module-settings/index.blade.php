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


@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush

@section('content')
 <div class="container-fluid">
   <div class="row">
        <div class="col-md-3">
        @include('sections.module_setting_menu')
       </div>
        <div class="col-md-9">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                          <h5>{{ ucfirst($type) }} @lang("modules.moduleSettings.moduleSetting")</h5>
                           
                        </div>
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">


                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="white-box">
                                 <!--        <h3 class="box-title m-b-0">{{ ucfirst($type) }} @lang("modules.moduleSettings.moduleSetting")</h3>
 -->
                                        <p class="text-muted m-b-10 font-13">
                                            @lang("modules.moduleSettings.employeeSubTitle") {{ ucfirst($type) }} @lang("modules.moduleSettings.section")
                                        </p>

                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12 b-t p-t-20">
                                                {!! Form::open(['id'=>'editSettings','class'=>'ajax-form form-horizontal','method'=>'PUT']) !!}

                                                @foreach($modulesData as $setting)
                                                    <div class="form-group col-md-4">
                                                        <label class="control-label col-xs-6" >@lang('modules.module.'.$setting->module_name)</label>
                                                        <div class="col-xs-6">
                                                            <div class="switchery-demo">
                                                                <input type="checkbox" @if($setting->status == 'active') checked @endif class="js-switch change-module-setting" data-setting-id="{{ $setting->id }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- .row -->
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
    <!-- .row -->



@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
<script>

    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());

    });

    $('.change-module-setting').change(function () {
        var id = $(this).data('setting-id');

        if($(this).is(':checked'))
            var moduleStatus = 'active';
        else
            var moduleStatus = 'deactive';

        var url = '{{route('admin.module-settings.update', ':id')}}';
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            type: "POST",
            data: { 'id': id, 'status': moduleStatus, '_method': 'PUT', '_token': '{{ csrf_token() }}' }
        })
    });
</script>
@endpush