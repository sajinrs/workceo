<div class="">
    <div class="card-header ">
        <h5><i class="fa fa-edit"></i> @lang('modules.followup.updateFollow')     <a class="pull-right close" href="javascript:;" id="hide-edit-follow-panel"><i class="fa fa-times"></i></a>
        </h5>


    </div>
    <div class="card-body">
        <div class="panel-body">
            {!! Form::open(['id'=>'updateFollow','class'=>'ajax-form']) !!}
            {!! Form::hidden('lead_id', $follow->lead_id) !!}
            {!! Form::hidden('id', $follow->id) !!}

            <div class="form-body">
                <div class="row">
                    <!--/span-->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">@lang('app.next_follow_up')</label>
                            <input type="text" autocomplete="off" name="next_follow_up_date" id="next_follow_up_date2" class="form-control" value="{{ $follow->next_follow_up_date->format($global->date_format) }}">
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">@lang('app.remark')</label>
                            <textarea id="remark" name="remark" class="form-control">{{ $follow->remark }}</textarea>
                        </div>
                    </div>
                </div>
                <!--/row-->

            </div>
            <div class="form-actions">
                <button type="button" id="update-follow" class="btn btn-primary"> @lang('app.save')</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script>
    //    update task
    $('#update-follow').click(function () {
        $.easyAjax({
            url: '{{route('admin.leads.follow-up-update')}}',
            container: '#updateFollow',
            type: "POST",
            data: $('#updateFollow').serialize(),
            success: function (data) {
                $('#follow-list-panel .list-group').html(data.html);
            }
        })
    });

    jQuery('#next_follow_up_date2').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });
</script>
