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
                          <h5>{{ __($pageTitle) }} </h5>
                           
                        </div>
                       
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">




                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            
                        <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">

                <div class="vtabs customvtab m-t-10">

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                        <div class="row">
                        <div class="col-md-12">
                            <div class="white-box">

                                <div class="alert alert-info ">
                                    <i class="fa fa-info-circle"></i> @lang('messages.taskSettingNote')
                                </div>
                                {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}

                                <div class="form-group">
                                    <div class="checkbox checkbox-info ">
                                        <input id="self_task" name="self_task" value="yes"
                                                @if($global->task_self == "yes") checked
                                                @endif
                                                type="checkbox">
                                        <label for="self_task">@lang('messages.employeeSelfTask')</label>
                                    </div>
                                </div>

                               
                                {!! Form::close() !!}

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


    </div>
                                
                                  
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
    </div>
    </div>
    </div>
    </div>
@endsection

@push('footer-script')

<script>
    // change task Setting For Setting
    $('input[name=self_task]').click(function () {
        var self_task = $('input[name=self_task]:checked').val();

        $.easyAjax({
            url: '{{route('admin.task-settings.store')}}',
            type: "POST",
            data: {'_token': '{{ csrf_token() }}', 'self_task': self_task}
        })
    });

</script>
@endpush

