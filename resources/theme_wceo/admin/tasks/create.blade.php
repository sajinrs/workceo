@extends('layouts.app')

@push('head-script')
    {{--<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">--}}
    {{--<link rel="stylesheet"  href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">--}}

    {{--<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/select2.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/summernote.css') }}">--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/date-picker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dropzone.css')}}">

    <style>
        .panel-black .panel-heading a, .panel-inverse .panel-heading a {
            color: unset !important;
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
                            <li class="breadcrumb-item active">@lang('app.addNew')</li>
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
                        <h5>@lang('modules.tasks.newTask')</h5>
                    </div>
                    {!! Form::open(['id'=>'storeTask','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="card-body">

                        <div class="form-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-label-group">
                                            <input placeholder="-" type="text" id="heading" name="heading" class="form-control form-control-lg">
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
                                                        <option
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
                                                    <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
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
                                            <textarea id="description" name="description" rows="12"
                                                      class="form-control form-control-lg"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">

                                            <div class="checkbox checkbox-info">
                                                <input id="dependent-task" name="dependent" value="yes"
                                                       type="checkbox">
                                                <label for="dependent-task">@lang('modules.tasks.dependent')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="dependent-fields" style="display: none">
                                    <div class="col-md-12">
                                        <div class="form-group form-label-group">
                                            <select placeholder="-" class="select2 form-control form-control-lg"
                                                    data-placeholder="@lang('modules.tasks.chooseTask')"
                                                    name="dependent_task_id" id="dependent_task_id">
                                                <option value=""></option>
                                                @foreach($allTasks as $allTask)
                                                    <option value="{{ $allTask->id }}">{{ $allTask->heading }}
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
                                    <div class="col-md-6">
                                        <div class="form-group form-label-group">
                                            <select  placeholder="-" class="select2 select2-multiple " multiple="multiple"
                                                    data-placeholder="@lang('modules.tasks.chooseAssignee')"
                                                    name="user_id[]" id="user_id">
                                                <option value=""></option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                                @endforeach
                                            </select>
                                            <label for="user_id" class="required">@lang('modules.tasks.assignTo')</label>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-3">
                                        <div class="form-group form-label-group">
                                            <input placeholder="-" type="text" name="start_date" id="start_date2" class="form-control form-control-lg"
                                                   autocomplete="off">
                                            <label for="start_date2" class="required">@lang('app.startDate')</label>
                                        </div>
                                    </div>
                                    <!--/span-->

                                    <!--/span-->
                                    <div class="col-md-3">
                                        <div class="form-group form-label-group">
                                            <input  placeholder="-" type="text" name="due_date" id="due_date2" class="form-control form-control-lg"
                                                   autocomplete="off">
                                            <label for="due_date2" class="required">@lang('app.dueDate')</label>
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="checkbox checkbox-info">
                                                <input id="private-task" name="is_private" value="true"  type="checkbox">
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
                                                        <input type="radio" name="priority" id="radio13"
                                                               value="high">
                                                        <label for="radio13" class="text-danger">
                                                            @lang('modules.tasks.high') </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="radio radio-warning">
                                                        <input type="radio" name="priority"
                                                               id="radio14" checked value="medium">
                                                        <label for="radio14" class="text-warning">
                                                            @lang('modules.tasks.medium') </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="radio radio-success">
                                                        <input type="radio" name="priority" id="radio15"
                                                               value="low">
                                                        <label for="radio15" class="text-success">
                                                            @lang('modules.tasks.low') </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">

                                            <div class="checkbox checkbox-info">
                                                <input id="repeat-task" name="repeat" value="yes"
                                                       type="checkbox">
                                                <label for="repeat-task">@lang('modules.events.repeat')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6" id="repeat-fields" style="display: none">
                                        <div class="row">
                                            <div class="col-xs-6 col-md-4 ">
                                                <div class="form-group">
                                                    <label for="repeat_count">@lang('modules.events.repeatEvery')</label>
                                                    <input  type="number" min="1" value="1" name="repeat_count" id="repeat_count"
                                                           class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-4">
                                                <div class="form-group">
                                                    <label for="repeat_type">&nbsp;</label>
                                                    <select  name="repeat_type" id="repeat_type" class="form-control">
                                                        <option value="day">@lang('app.day')</option>
                                                        <option value="week">@lang('app.week')</option>
                                                        <option value="month">@lang('app.month')</option>
                                                        <option value="year">@lang('app.year')</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xs-6 col-md-4">
                                                <div class="form-group">
                                                    <label for="repeat_cycles">@lang('modules.events.cycles')</label>
                                                        <input type="number" name="repeat_cycles" id="repeat_cycles"
                                                           class="form-control">
                                                        {{--<a class="mytooltip"--}}
                                                        {{--href="javascript:void(0)"> <i--}}
                                                        {{--class="fa fa-info-circle"></i><span--}}
                                                        {{--class="tooltip-content5"><span--}}
                                                        {{--class="tooltip-text3"><span--}}
                                                        {{--class="tooltip-inner2">@lang('modules.tasks.cyclesToolTip')</span></span></span></a>--}}
                                                        {{----}}

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">



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
                    <div class="card-footer">
                        <div class="form-actions row">
                            <button type="button" id="store-task" class="btn btn-primary col-md-3 offset-md-9"> @lang('app.save')</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>



    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="taskCategoryModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
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
    {{--<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>--}}
    {{--<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>--}}
    {{--<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>--}}
    {{--<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>--}}
    {{--<script src="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.js') }}"></script>--}}
    <script src="{{ asset('themes/wceo/assets/js/dropzone/dropzone.js')}}"></script>

    {{--<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.js')}}"></script>--}}
    {{--<script src="{{ asset('themes/wceo/assets/js/editor/summernote/summernote.custom.js')}}"></script>--}}
    <script src="{{ asset('themes/wceo/assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/select2/select2-custom.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
    <script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>

    <script>
        Dropzone.autoDiscover = false;
        //Dropzone class
        myDropzone = new Dropzone("div#file-upload-dropzone", {
            url: "{{ route('admin.task-files.store') }}",
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
            }
        });

        myDropzone.on('sending', function (file, xhr, formData) {
            console.log(myDropzone.getAddedFiles().length, 'sending');
            var ids = $('#taskID').val();
            formData.append('task_id', ids);
        });

        myDropzone.on('completemultiple', function () {
            var msgs = "@lang('messages.taskCreatedSuccessfully')";
            $.showToastr(msgs, 'success');
            window.location.href = '{{ route('admin.all-tasks.index') }}'

        });
      

        //    update task
        $('#store-task').click(function () {
            $.easyAjax({
                url: '{{route('admin.all-tasks.store')}}',
                container: '#storeTask',
                type: "POST",
                data: $('#storeTask').serialize(),
                success: function (data) {
                    $('#storeTask').trigger("reset");
                    $('.summernote').summernote('code', '');
                    if (myDropzone.getQueuedFiles().length > 0) {
                        taskID = data.taskID;
                        $('#taskID').val(data.taskID);
                        myDropzone.processQueue();
                    } else {
                        var msgs = "@lang('messages.taskCreatedSuccessfully')";
                        $.showToastr(msgs, 'success');
                        window.location.href = '{{ route('admin.all-tasks.index') }}'
                    }
                }
            })
        });

        jQuery('#due_date2, #start_date2').datepicker({
            autoclose: true,
            todayHighlight: true,
            weekStart: '{{ $global->week_start }}',
            dateFormat: '{{ $global->date_picker_format }}',
            language: 'en'
        });

        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        $('#project_id').change(function () {
            var id = $(this).val();
            var url = '{{route('admin.all-tasks.members', ':id')}}';
            url = url.replace(':id', id);

            $.easyAjax({
                url: url,
                type: "GET",
                redirect: true,
                success: function (data) {
                    $('#user_id').html(data.html);
                }
            })

            // For getting dependent task
            var dependentTaskUrl = '{{route('admin.all-tasks.dependent-tasks', ':id')}}';
            dependentTaskUrl = dependentTaskUrl.replace(':id', id);
            $.easyAjax({
                url: dependentTaskUrl,
                type: "GET",
                success: function (data) {
                    $('#dependent_task_id').html(data.html);
                }
            })
        });

        $('#repeat-task').change(function () {
            if ($(this).is(':checked')) {
                $('#repeat-fields').show();
            } else {
                $('#repeat-fields').hide();
            }
        });

        $('#dependent-task').change(function () {
            if ($(this).is(':checked')) {
                $('#dependent-fields').show();
            } else {
                $('#dependent-fields').hide();
            }
        });

        $('#repeat-task, #dependent-task, #project_id').trigger('change');
    </script>
    <script>
        $('#createTaskCategory').click(function () {
            var url = '{{ route('admin.taskCategory.create-cat')}}';
            $('#modelHeading').html("@lang('modules.taskCategory.manageTaskCategory')");
            $.ajaxModal('#taskCategoryModal', url);
        })
    </script>
@endpush

