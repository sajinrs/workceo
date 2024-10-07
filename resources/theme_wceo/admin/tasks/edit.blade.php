@extends('layouts.app')

 @push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">
{{--<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/date-picker.css') }}">--}}
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dropzone.css')}}">

<style>
    .panel-black .panel-heading a,
    .panel-inverse .panel-heading a {
        color: unset!important;
    }
</style>

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
                            <li class="breadcrumb-item"><a  href="{{ route('admin.all-tasks.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.edit')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header">
                        <h5> @lang('modules.tasks.updateTask')</h5>
                    </div>
                    {!! Form::open(['id'=>'updateTask','class'=>'ajax-form','method'=>'PUT']) !!}
                    <div class="card-body">

                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-" type="text" id="heading" name="heading" class="form-control form-control-lg"  value="{{ $task->heading }}">
                                        <label for="heading" class="required">@lang('app.title')</label>
                                    </div>
                                </div>
                                @if(in_array('projects', $modules))
                                    <div class="col-md-6">
                                        <div class="form-group form-label-group">
                                            <select placeholder="-" class="select2 form-control form-control-lg"
                                                    data-placeholder="@lang("app.selectProject")" id="project_id"
                                                    name="project_id">
                                                <option value=""></option>
                                                @foreach($projects as $project)
                                                    <option @if($project->id == $task->project_id) selected @endif
                                                            value="{{ $project->id }}">{{ ucwords($project->project_name) }}</option>
                                                @endforeach
                                            </select>
                                            <label for="project_id" class="control-label">@lang('app.project')</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-label-group">
                                        <select placeholder="-" class="select2 form-control form-control-lg" name="category_id"
                                                id="category_id"
                                                data-style="form-control form-control-lg">
                                            @forelse($categories as $category)
                                                <option @if($task->task_category_id == $category->id)
                                                        selected
                                                        @endif value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noTaskCategoryAdded')</option>
                                            @endforelse
                                        </select>
                                        <label for="category_id" class="control-label">@lang('modules.tasks.taskCategory')  </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <a
                                                href="javascript:;"
                                                id="createTaskCategory"
                                                class="btn btn-sm btn-outline btn-primary"><i
                                                    class="fa fa-plus"></i> @lang('modules.taskCategory.addTaskCategory')
                                        </a>

                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <!--/span-->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.description')</label>
                                        <textarea id="description" name="description"
                                                  class="form-control form-control-lg" rows="12">{{ $task->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">

                                        <div class="checkbox checkbox-info">
                                            <input id="dependent-task" name="dependent" value="yes"  @if($task->dependent_task_id != '') checked @endif  type="checkbox">
                                            <label for="dependent-task">@lang('modules.tasks.dependent')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="dependent-fields" @if($task->dependent_task_id == null) style="display: none" @endif>
                                <div class="col-md-12">
                                    <div class="form-group form-label-group">
                                        <select placeholder="-" class="select2 form-control form-control-lg"
                                                data-placeholder="@lang('modules.tasks.chooseTask')"
                                                name="dependent_task_id" id="dependent_task_id">
                                            <option value=""></option>
                                            @foreach($allTasks as $allTask)
                                                <option value="{{ $allTask->id }}"  @if($allTask->id == $task->dependent_task_id) selected @endif>{{ $allTask->heading }}
                                                    (@lang('app.dueDate'): {{ $allTask->due_date->format($global->date_format) }}
                                                    )
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="dependent_task_id" class="control-label">@lang('modules.tasks.dependentTask')</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!--/span-->
                                <div class="col-md-4">
                                    <div class="form-group form-label-group">
                                        <select  placeholder="-" class="select2 select2-multiple " multiple="multiple"
                                                 data-placeholder="@lang('modules.tasks.chooseAssignee')"
                                                 name="user_id[]" id="user_id">
                                            @if(is_null($task->project_id))
                                                @foreach($employees as $employee)

                                                    @php
                                                        $selected = '';
                                                    @endphp

                                                    @foreach ($task->users as $item)
                                                        @if($item->id == $employee->id)
                                                            @php
                                                                $selected = 'selected';
                                                            @endphp
                                                        @endif

                                                    @endforeach

                                                    <option {{ $selected }}
                                                            value="{{ $employee->id }}">{{ ucwords($employee->name) }}
                                                    </option>

                                                @endforeach
                                            @else
                                                @foreach($task->project->members as $member)
                                                    @php
                                                        $selected = '';
                                                    @endphp

                                                    @foreach ($task->users as $item)
                                                        @if($item->id == $member->user->id)
                                                            @php
                                                                $selected = 'selected';
                                                            @endphp
                                                        @endif

                                                    @endforeach

                                                    <option {{ $selected }}
                                                            value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <label for="user_id" class="required">@lang('modules.tasks.assignTo')</label>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-" type="text" name="start_date" id="start_date2" class="form-control form-control-lg" autocomplete="off"  value="@if($task->start_date != '-0001-11-30 00:00:00' && $task->start_date != null) {{ $task->start_date->format($global->date_format) }} @endif">
                                        <label for="start_date2" class="required">@lang('app.startDate')</label>
                                    </div>
                                </div>
                                <!--/span-->

                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group form-label-group">
                                        <input  placeholder="-" type="text" name="due_date" id="due_date2" class="form-control form-control-lg" autocomplete="off" value="@if($task->due_date != '-0001-11-30 00:00:00') {{ $task->due_date->format($global->date_format) }} @endif">
                                        <label for="due_date2" class="required">@lang('app.dueDate')</label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-label-group">
                                        <select placeholder="-" name="status" id="status" class="form-control form-control-lg">
                                            @foreach($taskBoardColumns as $taskBoardColumn)
                                                <option @if($task->board_column_id == $taskBoardColumn->id) selected @endif value="{{$taskBoardColumn->id}}">{{ $taskBoardColumn->column_name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="status">@lang('app.status')</label>
                                    </div>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="checkbox checkbox-info">
                                            <input id="private-task" name="is_private" value="true"  type="checkbox" @if ($task->is_private) checked  @endif>
                                            <label for="private-task">@lang('modules.tasks.makePrivate')
                                                {{--<a class="mytooltip font-12" href="javascript:void(0)"> <i--}}
                                                {{--class="fa fa-info-circle"></i><span--}}
                                                {{--class="tooltip-content5"><span--}}
                                                {{--class="tooltip-text3"><span--}}
                                                {{--class="tooltip-inner2">@lang('modules.tasks.privateInfo')</span></span></span></a>--}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label m-t-10">@lang('modules.tasks.priority')</label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="radio radio-danger">
                                                    <input type="radio" name="priority" id="radio13" @if($task->priority == 'high') checked
                                                           @endif
                                                           value="high">
                                                    <label for="radio13" class="text-danger">
                                                        @lang('modules.tasks.high') </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="radio radio-warning">
                                                    <input type="radio" name="priority"
                                                           id="radio14" @if($task->priority == 'medium') checked
                                                           @endif value="medium">
                                                    <label for="radio14" class="text-warning">
                                                        @lang('modules.tasks.medium') </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="radio radio-success">
                                                    <input type="radio" name="priority" id="radio15" @if($task->priority == 'low') checked
                                                           @endif
                                                           value="low">
                                                    <label for="radio15" class="text-success">
                                                        @lang('modules.tasks.low') </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row m-b-20">
                                <div class="col-md-12">
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
                                    <input type="hidden" name="taskID" id="taskID">
                                </div>
                            </div>



                        </div>


                    </div>

                    <div class="card-footer text-right">
                        <div class="form-actions col-md-6 offset-md-7">
                            <div class="row">
                                <div class="col-md-5 pr-0">
                                    <a href="{{ route('admin.all-tasks.index') }}" class="btn btn-outline-primary gray form-control" >@lang('app.cancel')</a>
                                </div>
                                <div class="col-md-5 pr-0">
                                <button type="submit" id="update-task" class="btn btn-primary form-control">@lang('app.save')</button>
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
<div class="modal fade bs-modal-md in" id="taskCategoryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" id="modal-data-application">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelHeading"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->.
</div>
{{--Ajax Modal Ends--}}
@endsection
 @push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/dropzone/dropzone.js')}}"></script>

{{--<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.custom.js')}}"></script>--}}
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>

<script>
    Dropzone.autoDiscover = false;
    //Dropzone class
    myDropzone = new Dropzone("div#file-upload-dropzone", {
        url: "{{ route('admin.task-files.store') }}",
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        paramName: "file",
        maxFilesize: 10,
        maxFiles: 10,
        acceptedFiles: "image/*,application/pdf",
        autoProcessQueue: false,
        uploadMultiple: true,
        addRemoveLinks:true,
        parallelUploads:10,
        init: function () {
            myDropzone = this;
        }
    });

    myDropzone.on('sending', function(file, xhr, formData) {
        console.log(myDropzone.getAddedFiles().length,'sending');
        var ids = '{{ $task->id }}';
        formData.append('task_id', ids);
    });

    myDropzone.on('completemultiple', function () {
        var msgs = "@lang('messages.taskUpdatedSuccessfully')";
        $.showToastr(msgs, 'success');
        window.location.href = '{{ route('admin.all-tasks.index') }}'

    });

    //    update task
    $('#update-task').click(function () {

        var status = '{{ $task->board_column->slug }}';
        var currentStatus =  $('#status').val();

        if(status == 'incomplete' && currentStatus == 'completed'){

            $.easyAjax({
                url: '{{route('admin.tasks.checkTask', [$task->id])}}',
                type: "GET",
                data: {},
                success: function (data) {
                    console.log(data.taskCount);
                    if(data.taskCount > 0){
                        swal({
                            title: "Are you sure?",
                            text: "There is a incomplete sub-task in this task do you want to mark complete!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, complete it!",
                            cancelButtonText: "No, cancel please!",
                            closeOnConfirm: true,
                            closeOnCancel: true
                        }, function (isConfirm) {
                            if (isConfirm) {
                                updateTask();
                            }
                        });
                    }
                    else{
                        updateTask();
                    }

                }
            });
        }
        else{
            updateTask();
        }

    });

    function updateTask(){
        $.easyAjax({
            url: '{{route('admin.all-tasks.update', [$task->id])}}',
            container: '#updateTask',
            type: "POST",
            data: $('#updateTask').serialize(),
            success: function(response){
                if(myDropzone.getQueuedFiles().length > 0){
                    taskID = response.taskID;
                    $('#taskID').val(response.taskID);
                    myDropzone.processQueue();
                }
                else{
                    var msgs = "@lang('messages.taskCreatedSuccessfully')";
                    $.showToastr(msgs, 'success');
                    window.location.href = '{{ route('admin.all-tasks.index') }}'
                }
            }
        })
    }

    jQuery('#due_date2, #start_date2').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language:'en'
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });


    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('file-id');
        var deleteView = $(this).data('pk');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "DELETE",
            cancelButtonText: "CANCEL",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.task-files.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE', 'view': deleteView},
                    success: function (response) {
                        console.log(response);
                        if (response.status == "success") {
                            $.unblockUI();
                            $('#list ul.list-group').html(response.html);

                        }
                    }
                });
            }
        });
    });

    $('#project_id').change(function () {
        var id = $(this).val();

        // For getting dependent task
        var dependentTaskUrl = '{{route('admin.all-tasks.dependent-tasks', [':id', ':taskId'])}}';
        dependentTaskUrl = dependentTaskUrl.replace(':id', id);
        dependentTaskUrl = dependentTaskUrl.replace(':taskId', '{{ $task->id }}');
        $.easyAjax({
            url: dependentTaskUrl,
            type: "GET",
            success: function (data) {
                $('#dependent_task_id').html(data.html);
            }
        })
    });

</script>
<script>
    $('#createTaskCategory').click(function(){
        var url = '{{ route('admin.taskCategory.create-cat')}}';
        $('#modelHeading').html("@lang('modules.taskCategory.manageTaskCategory')");
        $.ajaxModal('#taskCategoryModal', url);
    })

</script>

@endpush
