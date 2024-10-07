<div class="modal-header">
    <h5 class="modal-title">@lang('app.designation')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> 
</div>

{!! Form::open(['id'=>'createDepartment','class'=>'ajax-form','method'=>'POST']) !!}
<div class="modal-body">
    <div class="portlet-body">        
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>@lang('app.name')</label>
                        <input type="text" name="designation_name" id="designation_name" class="form-control">
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" id="save-department" onclick="saveDesignation()" class="btn btn-primary"> @lang('app.save')</button>
</div>
{!! Form::close() !!}

<script>

    function saveDesignation() {
        var designationName = $('#designation_name').val();
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: '{{route('admin.designations.quick-store')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: { 'designation_name':designationName, '_token':token},
            success: function (response) {
                if(response.status == 'success'){
                    $('#designation').html(response.designationData);
                    $("#designation").select2();
                    $('#departmentModel').modal('hide');
                }
            }
        })
        return false;
    }
</script>