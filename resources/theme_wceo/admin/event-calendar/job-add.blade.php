<div class="modal-header">
    <h5 class="modal-title">@lang('app.add') @lang('app.job')</h5>
    <button class="close btn-close-outside" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><img src="{{ asset('img/x-button.png') }}" alt="close" width="30" height="30" /></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            {!! Form::open(['id'=>'createProject','class'=>'ajax-form event-form','method'=>'POST']) !!}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-1 p-r-0">
                        <div class="event-icon"><i data-feather="check"></i></div>
                    </div>
                    <div class="col-md-11">  
                        <div class="row">   
                            <div class="col-md-12">                                
                                <div class="form-group form-label-group">
                                    <input placeholder="-" type="text" name="project_name" id="project_name" class="form-control form-control-lg" />
                                    <label for="project_name" class="required">@lang('modules.jobs.jobName')</label>
                                </div>
                            </div>
                            <div class="col-md-12">   
                                <div class="checkbox checkbox-info m-l-15">
                                    <input style="position: absolute" id="newClient" value="true" type="checkbox" />
                                    <label for="newClient">Check if New Client</label>
                                </div>
                            </div>

                            <div class="col-md-12">                                    
                                <div class="form-label-group form-group">
                                    <select class="select2 form-control form-control-lg" name="client_id" id="client_id" placeholder="*">
                                        <option value=""></option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ ucwords($client->company_name) }}</option>
                                        @endforeach
                                    </select>
                                    <label for="client_id" class="col-form-label required">@lang('modules.projects.selectClient')</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-1 p-r-0">
                        <div class="event-icon"><i data-feather="calendar"></i></div>
                    </div>
                    
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-6 p-r-0">
                                <div class="form-group form-label-group">
                                    <input placeholder="-" type="text" name="start_date" id="job_start_date" class="job_date form-control form-control-lg">
                                    <label for="job_start_date" class="required">@lang('modules.events.startDate')</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="bootstrap-timepicker timepicker form-group form-label-group">
                                    <input placeholder="-" type="text" name="start_time" id="job_start_time" class="job_time form-control form-control-lg">
                                    <label for="job_start_time" class="required">@lang('modules.events.startTime')</label>
                                </div>
                            </div>

                            <div class="col-md-6 p-r-0">
                                <div class="form-group form-label-group">
                                    <input placeholder="-" type="text" name="deadline" id="deadline" class="job_date form-control form-control-lg">
                                    <label for="deadline" class="required">@lang('modules.events.endDate')</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="bootstrap-timepicker timepicker form-group form-label-group">
                                    <input placeholder="-" type="text" name="end_time" id="job_end_time" class="job_time form-control form-control-lg">
                                    <label for="job_end_time" class="required">@lang('modules.events.endTime')</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-1 p-r-0">
                        <div class="event-icon"><i data-feather="align-left"></i></div>
                    </div>
                    
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-label-group">
                                    <textarea placeholder="-" name="project_summary" id="project_summary" class="form-control form-control-lg"></textarea>
                                    <label for="project_summary" class="required">@lang('modules.jobs.jobdescription')</label>
                                </div>
                            </div>

                            <div class="col-md-6 p-r-0">
                                <div class="form-label-group form-group">                                        
                                    <select class="select2 form-control" name="category_id" id="category_id" >
                                    <option value=""></option>
                                        @forelse($categories as $category)
                                            <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                        @empty
                                            <option value="">@lang('messages.noProjectCategoryAdded')</option>
                                        @endforelse
                                    </select>
                                    <label for="category_id" class="col-form-label required">@lang('modules.projects.projectCategory')</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group form-label-group">
                                    <input type="text" class="form-control form-control-lg" name="project_budget" id="project_budget" placeholder="*">
                                    <label for="project_budget" class="col-form-label required">@lang('modules.projects.projectBudget')</label>
                                </div>
                            </div>  

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-1 p-r-0">
                        <div class="event-icon"></div>
                    </div>
                    <div class="col-md-11">                                    
                        <div class="checkbox checkbox-info m-l-15">
                            <input style="position: absolute" id="all_job_members" value="true" type="checkbox">
                            <label for="all_job_members">@lang('modules.jobs.allMembers')</label>
                        </div>
                    </div>
                </div>

                <div class="row" id="attendees">
                    <div class="col-md-1 p-r-0">
                        <div class="event-icon"><i data-feather="users"></i></div>
                    </div>
                    <div class="col-md-11">                                    
                        <div class="form-group">
                            <select class="form-control select2 m-b-10 select2-multiple col-md-12" multiple="multiple" placeholder="_"
                                    data-placeholder="Choose Members" name="user_id[]" id="job_user_id">
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ ucwords($emp->name) }} @if($emp->id == $user->id)
                                            (YOU) @endif</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
               
                <hr />

                <div class="row">
                    <div class="col-md-1 p-r-0">
                        <div class="event-icon"><i data-feather="file"></i></div>
                    </div>
                    
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-6 p-r-0">
                                <div class="form-group form-label-group">
                                    <select placeholder="-" class="form-control form-control-lg" name="cat_id" id="cat_id">
                                        <option value=""></option>
                                        @foreach($productCategories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="cat_id" class="control-label">Category</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group form-label-group">
                                    <select placeholder="-" class="form-control form-control-lg" id="products">
                                        <option value=""></option>
                                    </select>
                                    <label for="products" class="control-label">@lang('app.menu.products') </label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                
                <div id="jobInvoice">
                    <div class="row">
                        <div class="col-md-1 p-r-0">
                            <div class="event-icon"></div>
                        </div>
                        
                        <div class="col-md-11">
                            <div class="form-group form-label-group">
                                <input type="text" class="form-control form-control-lg" id="item_name" name="item_name[]" placeholder="@lang('modules.invoices.item')" />
                                <label for="item_name" class="required">Invoice Item </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-1 p-r-0">
                            <div class="event-icon"><i data-feather="dollar-sign"></i></div>
                        </div>
                        
                        <div class="col-md-11">
                            <div class="row">
                                <div class="col-md-4 p-r-0">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-" type="number" min="1" name="quantity[]" id="quantity" class="quantity form-control form-control-lg" value="1" />
                                        <label for="quantity" class="required">@lang('modules.invoices.qty')</label>
                                    </div>
                                </div>

                                <div class="col-md-4 p-r-0">
                                    <div class="form-group form-label-group">
                                        <input placeholder="-" type="text" name="cost_per_item[]" id="cost_per_item" class="cost_per_item form-control form-control-lg" />
                                        <label for="cost_per_item" class="required">@lang('modules.invoices.unitPrice')</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select id="multiselect" class="form-control select2 m-b-10 select2-multiple col-md-12 type" multiple="multiple" placeholder="_"
                                                data-placeholder="@lang('modules.invoices.tax')" name="taxes[0][]">
                                                @foreach($taxes as $tax)
                                                <option data-rate="{{ $tax->rate_percent }}"
                                                    value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group form-label-group">
                                        <textarea placeholder="-" name="item_summary[]" id="item_summary" class="form-control form-control-lg"></textarea>
                                        <label for="item_summary">@lang('app.description')</label>
                                    </div>
                                    <input type="hidden" class="amount" name="amount[]" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="row">
                    <div class="col-md-12">
                        <h4 class="pull-right" style="font-weight: normal;">Total: {{ $global->currency->currency_symbol }}<span class="total">0</span></h4>
                        <input type="hidden" class="total-field" name="total" value="0">
                        <input type="hidden" name="currency_id" value="{{$currencies[0]->id}}" />
                        <input type="hidden" name="status" value="not started" />
                        <input type="hidden" name="job_status" value="schedule" />
                        <input type="hidden" name="client_view_task" value="true" />
                        <input type="hidden" name="completion_percent" value="0" />
                        <input type="hidden" name="discount_type" value="percent" />
                        <input type="hidden" name="created_from" value="event" />
                    </div>
                </div>
                
            </div>          

            {!! Form::close() !!}
        </div>
    </div>

</div>
<div class="modal-footer p-t-0">
    <a class="btn btn-light active waves-effect" href="{{ route('admin.projects.create') }}">Advanced</a>
    <button type="button" id="saveJob" class="btn btn-primary waves-effect waves-light">@lang('app.save')</button>    
</div>

