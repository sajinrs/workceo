
<div class="modal-header">
    <h5 class="modal-title">@lang('app.skills')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

</div>
{!! Form::open(['id'=>'createEmpSkill','class'=>'ajax-form','method'=>'POST']) !!}

<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Skill Name</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($skills as $key=>$skill)
                    <tr id="cat-{{ $skill->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($skill->name) }}</td>
                        <td><a href="javascript:;" data-skill-id="{{ $skill->id }}" class="btn btn-sm btn-danger btn-rounded delete-skill">@lang("app.remove")</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No Skills</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <hr>
         <div class="form-body">
            <div class="row">
                <div class="col-xs-12 col-12">
                    <div class="form-group">
                        <label>Skill</label>
                        <input type="text" requird name="skill_name" id="skill_name" class="form-control">
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<div class="modal-footer">
    <div class="form-actions">
        <button type="button" id="save-skill" class="btn btn-primary"> @lang('app.save')</button>
    </div>
</div>
{!! Form::close() !!}
<script>

    $('.delete-skill').click(function () {
        var id = $(this).data('skill-id');
        var url = "{{ route('admin.employees.destroy-skill') }}";
        //url = url.replace(':id', id);

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {id:id, '_token': token, '_method': 'DELETE'},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                    $('#cat-'+id).fadeOut();
                    var options = [];
                    var rData = [];
                    rData = response.data;
                    $.each(rData, function( index, value ) {
                        var selectData = '';
                        if(value.id != id){
                            selectData = '<option value="'+value.id+'">'+value.name+'</option>';
                            options.push(selectData);
                        }
                        
                    });

                    $('#tags').html(options);
                    $('#tags').select2();
                }
            }
        });
    });

    $('#save-skill').click(function () {
        $.easyAjax({
            url: '{{route('admin.employees.store-skill')}}',
            container: '#createEmpSkill',
            type: "POST",
            data: $('#createEmpSkill').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    if(response.status == 'success'){
                       // console.log(response.data);
                        var options = [];
                        var rData = [];
                        rData = response.data;
                        $.each(rData, function( index, value ) {
                            var selectData = '';
                            if(rData.length === index+1)
                                selectData = '<option selected value="'+value.id+'">'+value.name+'</option>';
                                options.push(selectData);
                        });

                        $('#tags').append(options);
                        $('#tags').select2();
                        $('#departmentModel').modal('hide');
                    }
                }
            }
        })
    });
</script>