<div class="modal-header">
    <h5 class="modal-title">@if(isset($faqCategory->id)) @lang('app.edit') @else @lang('app.addNew') @endif @lang('app.module')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>

{!! Form::open(['id'=>'addEditModule','class'=>'ajax-form']) !!}
<div class="modal-body">
    <div class="portlet-body">
        @if(isset($module->id)) <input type="hidden" name="_method" value="PUT"> @endif
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('app.name')</label>
                        <input type="text" name="name" class="form-control" value="{{ $module->name ?? '' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <div class="form-actions">
        <button type="button" id="save-faq-category" onclick="saveModule({{ $module->id ?? '' }});return false;"
                class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"> @lang('app.cancel')</button>
    </div>
</div>
{!! Form::close() !!}