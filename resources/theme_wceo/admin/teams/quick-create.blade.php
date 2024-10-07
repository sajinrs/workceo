<div class="modal-header">
    <h5 class="modal-title">Department</h5>
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
                        <input type="text" name="department_name" id="department_name" class="form-control">
                    </div>
                </div>
            </div>
        </div>       
        
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" id="save-department" onclick="saveDepartment()" class="btn btn-primary"> @lang('app.save')</button>
</div>
{!! Form::close() !!}

<script>

    function saveDepartment() {
        var departmentName = $('#department_name').val();
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: '{{route('admin.teams.quick-store')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: { 'department_name':departmentName, '_token':token},
            success: function (response) {
                if(response.status == 'success'){
                    $('#department').html(response.teamData);
                    $("#department").select2();
                    $('#departmentModel').modal('hide');
                }
            }
        })
        return false;
    }
</script>