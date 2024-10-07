
<div class="modal-header">
    <h5 class="modal-title">@lang('modules.tasks.subTask')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

</div>
{!! Form::open(['id'=>'createSubTask','class'=>'ajax-form','method'=>'POST']) !!}
<div class="modal-body">
    <div class="portlet-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-label-group">
                        <input placeholder="-" type="text" name="name" id="name" class="form-control form-control-lg">
                        <label for="name">@lang('app.name')</label>
                        <input type="hidden" name="taskID" id="taskID" value="{{ $taskID }}">
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="form-group form-label-group">
                        <input placeholder="-"  type="text" name="due_date" autocomplete="off" id="due_date3" class="form-control form-control-lg">
                        <label for="due_date3">@lang('app.dueDate')</label>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">

    <div class="form-actions">
        <button type="button" onclick="saveSubTask()" class="btn btn-primary">@lang('app.save')</button>
    </div>

</div>

{!! Form::close() !!}
<script>
    jQuery('#due_date3').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });

</script>
