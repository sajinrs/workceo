<div class="modal-header">
    <h5 class="modal-title">@lang('app.add') @lang('app.client')</h5>
    <button class="close btn-close-outside" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30" /></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            {!! Form::open(['id'=>'createClient','class'=>'ajax-form event-form','method'=>'POST']) !!}
            <div class="form-body">                

                <div class="row">
                    <div class="col-md-1 p-r-0">
                        <div class="event-icon"><i data-feather="user"></i></div>
                    </div>
                    
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-6 p-r-0">
                                <div class="form-group form-label-group">
                                    <input placeholder="-" type="text" name="company_name" id="company_name" class="form-control form-control-lg">
                                    <label for="company_name" class="required">Client Name</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="bootstrap-timepicker timepicker form-group form-label-group">
                                    <input placeholder="-" type="email" name="email" id="email" class="form-control form-control-lg">
                                    <label for="email" class="required">Client Email</label>
                                </div>
                            </div>

                            <div class="col-md-6 p-r-0">
                                <div class="form-group form-label-group">
                                    <input placeholder="-" type="text" name="name" id="name" class="form-control form-control-lg">
                                    <label for="name" class="required">Contact First Name</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="bootstrap-timepicker timepicker form-group form-label-group">
                                    <input placeholder="-" type="text" name="last_name" id="last_name" class="form-control form-control-lg">
                                    <label for="last_name" class="required">Contact Last Name</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group form-label-group">
                                    <input placeholder="-" type="text" name="mobile" id="mobile" class="form-control form-control-lg">
                                    <label for="mobile">Client Mobile</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-1 p-r-0">
                        <div class="event-icon"><i data-feather="map-pin"></i></div>
                    </div>
                    
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-label-group form-group">
                                    <input placeholder="-" type="text" id="street" name="street" class="form-control form-control-lg" >
                                    <label for="street" class="col-form-label required">@lang('modules.client.streetAddress')</label>
                                </div>
                            </div>

                            <div class="col-md-6 p-r-0">
                                <div class="form-label-group form-group">
                                    <input placeholder="@lang('modules.client.city')" type="text" id="city" name="city" class="form-control form-control-lg" >
                                    <label for="city" class="col-form-label required">@lang('modules.client.city')</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <input placeholder="@lang('modules.client.state')" type="text" name="state" id="state" class="form-control form-control-lg">
                                    <label for="state" class="col-form-label required">@lang('modules.client.state')</label>
                                </div>
                            </div>

                            <div class="col-md-6 p-r-0">
                                <div class="form-label-group form-group">
                                    <input placeholder="@lang('modules.client.zip')" type="text" name="zip" id="zip" class="form-control form-control-lg">
                                    <label for="zip" class="col-form-label required">@lang('modules.client.zip')</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <select placeholder="-" class="select2 form-control form-control-lg" data-placeholder="Country" id="country_id" name="country_id">
                                        <option value="">--</option>
                                        @foreach($countries as $country)
                                            <option @if($country->id == 224) selected @endif value="{{ $country->id }}">{{ ucwords($country->name) }}</option>
                                        @endforeach
                                    </select>
                                    <label for="country_id" class="col-form-label required">@lang('modules.client.country')</label>
                                </div>
                            </div>
                            <input type="hidden" name="sendMail" value="yes" />
                            <input type="hidden" name="created_from" value="event" />
                        </div>
                    </div>
                </div>
                
            </div>          

            {!! Form::close() !!}
        </div>
    </div>

</div>
<div class="modal-footer p-t-0">
<a class="btn btn-light active waves-effect" href="{{ route('admin.clients.create') }}">Advanced</a>
    <button type="button" id="saveClient" class="btn btn-primary waves-effect waves-light">@lang('app.save')</button>   
</div>

