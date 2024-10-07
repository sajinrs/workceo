<div class="modal-header">
    <h5 class="modal-title"> @lang('app.update') @lang('modules.projects.milestones')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
{!! Form::open(['id'=>'updateTime','class'=>'ajax-form','method'=>'PUT']) !!}

<div class="modal-body">
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-body">
                    <div class="row">
                            <div class="col-md-12">
                                {!! Form::hidden('project_id', $milestone->project_id) !!}

                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <div class="form-group form-label-group">
                                                        <input placeholder="-" id="milestone_title" name="milestone_title" type="text" class="form-control form-control-lg" value="{{ $milestone->milestone_title }}">
                                                        <label for="milestone_title" class="required">@lang('modules.projects.milestoneTitle')</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 ">
                                                        <div class="form-group form-label-group">
                                                            <select placeholder="-" name="status" id="status" class="form-control form-control-lg hide-search">
                                                                <option
                                                                @if($milestone->status == 'incomplete') selected @endif
                                                                value="incomplete">@lang('app.incomplete')</option>
                                                                <option
                                                                @if($milestone->status == 'complete') selected @endif
                                                                value="complete">@lang('app.complete')</option>
                                                            </select>
                                                            <label for="status">@lang('app.status')</label>
                                                        </div>
                                                </div>
                                                <div class="col-md-6 ">
                                                        <div class="form-group form-label-group">
                                                            <select placeholder="-" name="currency_id" id="currency_id" class="form-control form-control-lg hide-search">
                                                                <option value="">--</option>
                                                                @foreach ($currencies as $item)
                                                                    <option
                                                                    @if($item->id == $milestone->currency_id) selected @endif
                                                                    value="{{ $item->id }}">{{ $item->currency_code.' ('.$item->currency_symbol.')' }}</option>
                                                                @endforeach
                                                            </select>
                                                            <label for="currency_id">@lang('modules.invoices.currency')</label>
                                                        </div>
                                                </div>
                                                <div class="col-md-6 ">
                                                    <div class="form-group form-label-group">
                                                        <input placeholder="-" id="cost" name="cost" type="number" value="{{ $milestone->cost }}" class="form-control form-control-lg" value="0" min="0" step=".01">
                                                        <label for="cost">@lang('modules.projects.milestoneCost')</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <div class="col-md-6 ">
                                        <div class="form-group form-label-group">
                                            <textarea placeholder="-" name="summary" id="" rows="7" class="form-control form-control-lg">{{ $milestone->summary }}</textarea>
                                            <label for="summary">@lang('modules.projects.milestoneSummary')</label>
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
<div class="modal-footer">
    <div class="form-actions">
        <button type="button" id="update-form" class="btn btn-primary">Save
        </button>
    </div>

</div>

{!! Form::close() !!}


<script>
$('#update-form').click(function () {
    $.easyAjax({
        url: '{{route('admin.milestones.update', $milestone->id)}}',
        container: '#updateTime',
        type: "POST",
        data: $('#updateTime').serialize(),
        success: function (response) {
            $('#editTimeLogModal').modal('hide');
            table._fnDraw();
        }
    })
});
</script>