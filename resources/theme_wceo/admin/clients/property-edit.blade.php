<div class="modal-header">
    <h5 class="modal-title"><i class="fa fa-clock-o"></i> @lang('app.updatePropertyDetails')</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

</div>
{!! Form::open(['id'=>'updateProperty','class'=>'ajax-form','method'=>'PUT']) !!}

<div class="modal-body">
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-12">


                <div class="form-body">
                    <div class="row m-t-30">
                        <div class="col-md-4 ">
                            <div class="form-label-group form-group">
                                <input placeholder="@lang('modules.client.streetAddress') " type="text" id="street" name="street" value="{{ $property->street ?? '' }}" class="form-control form-control-lg" >
                                <label for="street" class="col-form-label required">@lang('modules.client.streetAddress')</label>
                            </div>
                        </div>

                        <div class="col-md-4 ">
                            <div class="form-label-group form-group">
                                <input placeholder="@lang('modules.client.aptSuiteFloor')" type="tel" name="apt_floor" id="apt_floor" value="{{ $property->apt_floor ?? '' }}" class="form-control form-control-lg">
                                <label for="apt_floor" class="col-form-label">@lang('modules.client.aptSuiteFloor')</label>
                            </div>
                        </div>

                        <div class="col-md-4 ">
                            <div class="form-label-group form-group">
                                <input placeholder="@lang('modules.client.city')" type="text" id="city" name="city" value="{{ $property->city ?? '' }}" class="form-control form-control-lg" >
                                <label for="city" class="col-form-label required">@lang('modules.client.city')</label>
                            </div>
                        </div>

                        <div class="col-md-4 ">
                            <div class="form-label-group form-group">
                                <input placeholder="@lang('modules.client.state')" type="text" name="state" id="state" value="{{ $property->state ?? '' }}" class="form-control form-control-lg">
                                <label for="state" class="col-form-label required">@lang('modules.client.state')</label>
                            </div>
                        </div>

                        <div class="col-md-4 ">
                            <div class="form-label-group form-group">
                                <input placeholder="@lang('modules.client.zip')" type="text" name="zip" id="zip" value="{{ $property->zip ?? '' }}" class="form-control form-control-lg">
                                <label for="zip" class="col-form-label required">@lang('modules.client.zip')</label>
                            </div>
                        </div>

                        <div class="col-md-4 ">
                            <div class="form-label-group form-group">
                                <select placeholder="-" class="select2 form-control form-control-lg" data-placeholder="Country" id="country_id" name="country_id">
                                    <option value="">--</option>
                                    @foreach($countries as $country)
                                        <option @if($property->country_id == $country->id) selected @endif value="{{ $country->id }}">{{ ucwords($country->name) }}</option>
                                    @endforeach
                                </select>
                                <label for="country_id" class="col-form-label">@lang('modules.client.country')</label>
                            </div>
                        </div>
                        
                    </div>

                </div>


            </div>
        </div>

    </div>
</div>
{!! Form::close() !!}
<div class="modal-footer">
    <div class="form-actions  text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

        <button type="button" id="update-form" class="btn btn-primary">Save</button>
    </div>
</div>

<script>

$('#update-form').click(function () {
    $.easyAjax({
        url: '{{route('admin.properties.update', $property->id)}}',
        container: '#updateProperty',
        type: "POST",
        data: $('#updateProperty').serialize(),
        success: function (response) {
            $('#editPropertyModal').modal('hide');
            table._fnDraw();
        }
    })
});
</script>