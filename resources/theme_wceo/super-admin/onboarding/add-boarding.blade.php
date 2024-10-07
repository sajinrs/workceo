<div class="modal-header">
    <h5 class="modal-title"> @lang('app.add') @lang('app.menu.onBoarding')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

{!! Form::open(['id'=>'boardingForm','class'=>'ajax-form']) !!}
<div class="modal-body">
    <div class="portlet-body">

       
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('app.boarding.taskTitle')</label>
                        <input type="text" name="title" class="form-control" />
                    </div>
                </div>

                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('app.boarding.taskDescription')</label>
                        <input type="text" name="description" class="form-control" />
                    </div>
                </div>

                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('app.fontAwesomeCode')</label>
                        <input type="text" name="icon_code" class="form-control" />
                    </div>
                </div>

                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('app.boarding.popupTitle')</label>
                        <input type="text" name="popup_title" class="form-control" />
                    </div>
                </div>

                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('app.boarding.popupDescription')</label>
                        <textarea name="popup_description" class="form-control"></textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <label>@lang('app.boarding.popupImage') (600x300) </label>
                    <div class="form-group">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 100px;">
                                
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail"
                                    style="max-width: 200px; max-height: 100px;"></div>
                            <div>
                        <span class="btn btn-sm btn-success btn-file">
                        <span class="fileinput-new"> @lang('app.selectImage') </span>
                        <span class="fileinput-exists"> @lang('app.change') </span>
                        <input type="file" name="image" id="boardImage"> </span>
                                <a href="javascript:;" class="btn btn-sm btn-danger fileinput-exists"
                                    data-dismiss="fileinput"> @lang('app.remove') </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 ">
                    <div class="radio-list">
                        <label class="radio-inline p-0">
                            <div class="radio radio-info">
                                <input type="radio" name="type" checked="" id="inApp" value="inapp" />
                                <label for="inApp">In-App</label>
                            </div>
                        </label>&nbsp;&nbsp;&nbsp;
                        <label class="radio-inline">
                            <div class="radio radio-info">
                                <input type="radio" name="type" id="external" value="external" />
                                <label for="external">External Link</label>
                            </div>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Link</label>
                        <input type="text" name="popup_link" class="form-control" />
                    </div>
                </div>            
            </div>
            
        </div>
        
    </div>
</div>

<div class="modal-footer">
    <div class="form-actions">
        <button type="button" onclick="saveBoarding();return false;" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>       
    </div>
</div>
{!! Form::close() !!}

