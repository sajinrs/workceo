<div class="modal-header">
    <h5 class="modal-title">@if(isset($faqCategory->id)) @lang('app.edit') @else @lang('app.addNew') @endif @lang('app.category')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>

{!! Form::open(['id'=>'addEditFaqCategory','class'=>'ajax-form']) !!}
<div class="modal-body">
    <div class="portlet-body">
        @if(isset($faqCategory->id)) <input type="hidden" name="_method" value="PUT"> @endif
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('app.name')</label>
                        <input type="text" name="name" class="form-control" value="{{ $faqCategory->name ?? '' }}">
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('app.fontAwesomeCode')</label>
                        <input type="text" name="fontawesome_code" class="form-control"
                               value="{{ $faqCategory->fontawesome_code ?? '' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <div class="form-actions">
    <button type="button" class="btn btn-danger" data-dismiss="modal"> @lang('app.cancel')</button>
        <button type="button" id="save-faq-category" onclick="saveCategory({{ $faqCategory->id ?? '' }});return false;"
                class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')</button>
    </div>
</div>
{!! Form::close() !!}