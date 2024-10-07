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
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.edit') Group Task</li>
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
                        <h5>@lang('app.edit') Group Task</h5> 
                    </div>
                    {!! Form::open(['id'=>'createTask','class'=>'ajax-form','method'=>'PUT']) !!}
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
                                                            <div class="form-label-group form-group">                                   
                                                                <input placeholder="-" type="text" id="cat_name" name="cat_name" class="form-control-lg form-control" value="{{$category->title}}">
                                                                <label for="cat_name" class="required">Task Category</label>                                       
                                                            </div>
                                                        </div>
                                                       
                                                    </div>
                                                    <!--/row-->

                                                    <div id="TaskItems" class="d-md-block d-lg-block ">
                                                        <br />
                                                        <h5>Task</h5>
                                                        @if($tasks)
                                                            @foreach($tasks as $key=> $task)
                                                            <div class="row item-row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label required">Title</label>
                                                                        <input type="text" name="title[]" class="form-control item_name" value="{{$task->title}}" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Description</label>
                                                                        <textarea id="description" name="description[]" rows="10" class="form-control">{{$task->description}}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Priority</label>
                                                                        <select name="priority[]" class="hide-search form-control">
                                                                            <option value="high" @if($task->priority == 'high') selected @endif>High</option>
                                                                            <option value="medium" @if($task->priority == 'medium') selected @endif>Medium</option>
                                                                            <option value="low" @if($task->priority == 'low') selected @endif>Low</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                @if($key != 0)
                                                                    <div class="col-md-7 pull-right m-t-30"><button type="button" class="btn pull-right remove-item btn-outline-danger"><i class="fa fa-times"></i></button></div>
                                                                @endif
                                                                <div class="col-md-12"><hr /></div>
                                                            </div>    
                                                            
                                                            @endforeach   
                                                        @endif    
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-4 m-t-5">
                                                            <button type="button" class="btn btn-primary" id="add-item"><i class="fa fa-plus"></i> Add Task</button>
                                                        </div>
                                                    </div>

                                                    
                                                </div>               
                
                                            </div>
                                        </div><!--row-->
                                        <div class="clearfix"></div>
                                    </div><!--vhome3-->
                                </div><!--tab-content-->
                            </div> <!--customvtab-->
                        </div>    <!-- .form-body -->
                    </div> <!--card-body-->
                    <div class="card-footer text-right">
                        <div class="form-actions col-md-6 offset-md-7">
                            <div class="row">
                                <div class="col-md-5 pr-0">
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary form-control">@lang('app.cancel')</a>
                                </div>
                                <div class="col-md-5 pr-0">
                                    <button type="submit" id="save-form" class="btn btn-primary form-control">@lang('app.save')</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div> <!--panel-inverse-->
            </div>
        </div>
    </div>
</div>


@endsection

@push('footer-script')

<script>
    
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.task-groups.update', [$category->id])}}',
            container: '#createTask',
            type: "POST",
            redirect: true,
            data: $('#createTask').serialize()
        })
    });

    
    $('#add-item').click(function () {
        var i = $(document).find('.item_name').length;
        var item = '<div class="row item-row">'
                    +'<div class="col-md-12"><hr />'
                    +'<div class="form-group">'
                    +'<label class="control-label required">Title</label>'
                    +'<input type="text" name="title[]" class="form-control item_name" />'
                    +'</div>'
                    +'</div>'

                    +'<div class="col-md-12">'
                    +'<div class="form-group">'
                    +'<label class="control-label">Description</label>'
                    +'<textarea id="description" name="description[]" rows="10" class="form-control"></textarea>'
                    +'</div>'
                    +'</div>'

                    +'<div class="col-md-5">'
                    +'<div class="form-group">'
                    +'<label class="control-label">Priority</label>'
                    +'<select name="priority[]" class="form-control hide-search">'
                    +'<option value="high">High</option>'
                    +'<option value="medium" selected>Medium</option>'
                    +'<option value="low">Low</option>'
                    +'</select>'
                    +'</div>'
                    +'</div>'
                    +'<div class="col-md-7 pull-right m-t-30">'
                    +'<button type="button" class="btn pull-right remove-item btn-outline-danger"><i class="fa fa-times"></i></button>'
                    +'</div>'
                    +'</div> ';

        $(item).hide().appendTo("#TaskItems").fadeIn(500);
    });

    $('#createTask').on('click','.remove-item', function () {
        $(this).closest('.item-row').fadeOut(300, function() {
            $(this).remove();
            calculateTotal();
        });
    });
</script>
@endpush

