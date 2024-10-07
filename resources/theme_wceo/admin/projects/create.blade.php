@extends('layouts.app')
@push('head-script')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dropzone.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap-datetimepicker.min.css') }}">
@endpush

@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.addNew')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid product-wrapper">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">

                  {!! Form::open(['id'=>'createProject','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="card-header">
                        <div class="card-title mb-0 h4"> @lang('modules.projects.createTitle')
                            <span class="pull-right">
                                <div class="btn-group m-r-10">
                                    <ul role="menu" class="dropdown-menu">
                                        @forelse($templates as $template)
                                            <li onclick="setTemplate('{{$template->id}}')" role="presentation"><a href="javascript:void(0)" role="menuitem"><i class="icon wb-reply" aria-hidden="true"></i> {{ ucwords($template->project_name) }}</a></li>
                                        @empty
                                            <li class="text-dark">@lang('messages.noRecordFound')</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </span>
                        </div>


                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group">
                                        <input type="text" name="project_name" id="project_name" class="form-control form-control-lg" placeholder="*">
                                        <label for="project_name" class="col-form-label required">@lang('modules.jobs.jobName')</label>
                                        <input type="hidden" name="template_id" id="template_id">
                                    </div>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-5">
                                    <div class="form-label-group form-group">                                        
                                        <select class="select2 form-control form-control-lg" name="client_id" id="client_id" placeholder="*">
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}">{{ ucwords($client->company_name) }}</option>
                                            @endforeach
                                        </select>
                                        <label for="client_id" class="col-form-label required">@lang('modules.projects.selectClient')</label>
                                    </div>
                                </div>

                                <div class="col-md-4 ">
                                    <div class="form-label-group form-group">                                        
                                        <select class="select2 form-control" name="category_id" id="category_id" >
                                            @forelse($categories as $category)
                                                <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noProjectCategoryAdded')</option>
                                            @endforelse
                                        </select>
                                        <label for="category_id" class="col-form-label required">@lang('modules.projects.projectCategory')</label>
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <a href="javascript:;" id="addProjectCategory" class="btn btn-sm btn-outline btn-block btn-primary"><i
                                                        class="fa fa-plus"></i> @lang('modules.projectCategory.addProjectCategory')</a>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="checkbox checkbox-info col-md-10">
                                    <input id="client_view_task" onchange="checkTask()" name="client_view_task" value="true" type="checkbox" checked>
                                    <label for="client_view_task">@lang('modules.projects.clientViewTask')</label>
                                </div>
                                   
                                <div class="checkbox checkbox-info col-md-10" id="clientNotification">
                                    <input id="client_task_notification" name="client_task_notification" value="true" type="checkbox">
                                    <label for="client_task_notification">@lang('modules.projects.clientTaskNotification')</label>
                                </div>
                                
                                <div class="checkbox checkbox-info  col-md-10">
                                    <input id="manual_timelog" name="manual_timelog" value="true" type="checkbox">
                                    <label for="manual_timelog">@lang('modules.projects.manualTimelog')</label>
                                </div>
                                    
                                <div class="checkbox checkbox-info col-md-10">
                                    <input id="default_project_member" name="default_project_member" value="true" type="checkbox">
                                    <label for="default_project_member">@lang('modules.projects.selfAssignAsProjectMember')</label>
                                </div>
                                    
                            </div>

                            <br />

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">
                                                <input type="text" name="start_date" id="start_date" autocomplete="off" class="form-control form-control-lg" placeholder="*">
                                                <label for="start_date" class="col-form-label required">@lang('modules.projects.startDate')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">
                                                <input type="text" name="start_time" id="start_time" autocomplete="off" class="form-control form-control-lg" placeholder="*">
                                                <label for="start_time" class="col-form-label required">@lang('modules.projects.startTime')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group" id="deadlineBox">
                                                <input type="text" name="deadline" id="deadline" autocomplete="off" class="form-control form-control-lg" placeholder="*">
                                                <label for="deadline" class="col-form-label required">@lang('modules.projects.endDate')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">
                                                <input type="text" name="end_time" id="end_time" autocomplete="off" class="form-control form-control-lg" placeholder="*">
                                                <label for="end_time" class="col-form-label required">@lang('modules.projects.endTime')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">
                                                <input type="text" class="form-control form-control-lg" name="project_budget" id="project_budget" placeholder="*">
                                                <label for="project_budget" class="col-form-label required">@lang('modules.projects.projectBudget')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">
                                                <select name="currency_id" id="currency_id" class="form-control form-control-lg hide-search" placeholder="*">
                                                    <option value="">--</option>
                                                    @foreach ($currencies as $item)
                                                        <option @if($item->currency_code == 'USD') selected @endif
                                                            value="{{ $item->id }}">{{ $item->currency_name }} ({{ $item->currency_code }})</option>
                                                    @endforeach
                                                </select>
                                                <label for="currency_id" class="control-label required">@lang('modules.invoices.currency')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">                                                
                                                <input type="text" name="hours_allocated" id="hours_allocated" class="form-control form-control-lg" placeholder="*">
                                                <label for="hours_allocated" class="control-label">@lang('modules.jobs.estHours')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group"> 
                                                <select name="status" id="status" class="form-control form-control-lg hide-search" placeholder="*">
                                                    <option data-percentage="0" data-jobstatus="schedule" value="not started">Schedule</option>
                                                    <option data-percentage="17" data-jobstatus="omw" value="not started">En Route</option>
                                                    <option data-percentage="33" data-jobstatus="start" value="in progress">Start</option>
                                                    <option data-percentage="50" data-jobstatus="finish" value="in progress">Finish</option>
                                                    <option data-percentage="66" data-jobstatus="invoice" value="awaiting invoice">Invoice</option>
                                                    <option data-percentage="83" data-jobstatus="paid" value="awaiting pay">Paid</option>
                                                    <option data-percentage="100" data-jobstatus="closed" value="finished">Closed</option>
                                                    {{--<option
                                                            value="not started">@lang('app.notStarted')
                                                    </option>
                                                    <option
                                                            value="in progress">@lang('app.inProgress')
                                                    </option>
                                                    <option
                                                            value="on hold">@lang('app.onHold')
                                                    </option>
                                                    <option
                                                            value="canceled">@lang('app.canceled')
                                                    </option>
                                                    <option
                                                            value="finished">@lang('app.finished')
                                                    </option>--}}
                                                </select>
                                                <label for="status" class="control-label">@lang('app.project') @lang('app.status')</label>
                                                <input type="hidden" id="jobStatus" name="job_status" value="schedule" />
                                                <input type="hidden" name="completion_percent" id="completion_percent" value="0" />
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                        
                                        <textarea name="project_summary" id="project_summary" rows="10" class="form-control form-control-lg" placeholder="*"></textarea>
                                        <label for="project_summary" class="col-form-label">@lang('modules.jobs.jobdescription')</label>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <br />
                            <h5>@lang('modules.jobs.teamMembers')</h5>

                            <div class="row">
                                <div class="checkbox checkbox-info  col-md-2">
                                    <input id="all_members" name="all_members" value="true" type="checkbox">
                                    <label for="all_members">@lang('modules.jobs.allMembers')</label>
                                </div>
                                
                                <div class="col-md-10">
                                    <div class="form-group" id="user_id">
                                        <select class="select2 m-b-10 select2-multiple form-control " multiple="multiple"
                                                data-placeholder="Choose Members" name="user_id[]" id="team_id">
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->id }}">{{ ucwords($emp->name). ' ['.$emp->email.']' }} @if($emp->id == $user->id)
                                                        (YOU) @endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <br />
                            <h5>Vehicles</h5>

                            <div class="row">
                                <div class="checkbox checkbox-info  col-md-2">
                                    <input id="all_vehicles" name="all_vehicles" value="true" type="checkbox">
                                    <label for="all_vehicles">All Vehicles</label>
                                </div>
                                
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <select class="select2 m-b-10 select2-multiple form-control " multiple="multiple"
                                                data-placeholder="Choose Vehicles" name="vehicle_id[]" id="vehicle_id">
                                            @foreach($vehicles as $vehicle)
                                                <option value="{{ $vehicle->id }}">{{ ucwords($vehicle->vehicle_name). ' ['.$vehicle->license_plate.']' }} 
                                                </option>
                                            @endforeach
                                        </select>                                       
                                    </div>
                                </div>
                            </div>

                            <div class="d-md-block d-lg-block ">
                                <br />
                                <hr />
                                <div class="row">
                                    <div class="col-md-12 m-t-10"><h5>Invoices<span style="color:red">*</span></h5></div>
                                </div>


                                <div id="sortable">
                                    <div class="row item-row margin-top-5">

                                        <div class="col-md-12 invoice-head">
                                            <div class="row">

                                                <div class="col-md-4 d-none d-sm-block font-bold d-none d-sm-block label-block">
                                                    @lang('modules.invoices.item')
                                                </div>

                                                <div class="col-md-1 d-none d-sm-block font-bold d-none d-sm-block label-block">
                                                    @lang('modules.invoices.qty')
                                                </div>

                                                <div class="col-md-2 d-none d-sm-block font-bold d-none d-sm-block label-block">
                                                    @lang('modules.invoices.unitPrice')
                                                </div>

                                                <div class="col-md-2 font-bold d-none d-sm-block d-none d-sm-block label-block">
                                                    @lang('modules.invoices.tax') <a href="javascript:;" class="tax-settings" ><i class="icofont icofont-gear"></i></a>
                                                </div>

                                                <div class="col-md-2 text-right d-none d-sm-block font-bold d-none d-sm-block label-block-last">
                                                    @lang('modules.invoices.amount')
                                                </div>

                                                <div class="col-md-1 d-none d-sm-block d-none d-sm-block">
                                                    &nbsp;
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.item')</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                                                    <input type="text" class="form-control item_name" name="item_name[]">
                                                </div>
                                            </div>                                           
                                        </div>

                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.qty')</label>
                                                <input type="number" min="1" class="form-control quantity" value="1" name="quantity[]" >
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.unitPrice')</label>
                                                <input type="text"  class="form-control cost_per_item" name="cost_per_item[]" value="0" >
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.tax') <a href="javascript:;" id="tax-settings2" ><i class="icofont icofont-gear"></i></a></label>
                                                <select id="multiselect" name="taxes[0][]"  multiple="multiple" class="select2 form-control type">
                                                    @foreach($taxes as $tax)
                                                        <option data-rate="{{ $tax->rate_percent }}" value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 border-dark  text-right invoiceAmount">
                                            <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.amount')</label>
                                            <p class="form-control-static">{{ $global->currency->currency_symbol }}<span class="amount-html">0.00</span></p>
                                            <input type="hidden" class="amount" name="amount[]" value="0">
                                        </div>

                                        <div class="col-md-1 text-right d-md-block d-lg-block d-none">
                                            <button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i></button>
                                        </div>
                                        <div class="col-md-1 m-b-20 d-md-none d-lg-none">
                                                <button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>
                                        </div>

                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-4">
                                        <button type="button" class="btn btn-primary" id="add-item"><i class="fa fa-plus"></i>  Add Item or Service</button>
                                    </div>

                                    <div class="col-md-5">
                                        @if(in_array("products", $modules) )
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group form-label-group">
                                                    <select placeholder="-" class="form-control form-control-lg" name="cat_id" id="cat_id">
                                                        <option value="">--</option>
                                                        @foreach($productCategories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->category_name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="cat_id" class="control-label">Product Category</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group form-label-group">
                                                    <select placeholder="-" class="form-control form-control-lg" id="products">
                                                        <option value="">--</option>
                                                    </select>
                                                    <label for="products" class="control-label">@lang('app.menu.products') </label>
                                                </div>
                                            </div>

                                            <div class="offset-md-6 col-md-6">
                                                <button type="button" class="btn btn-primary pull-right" id="addProduct">Add Product</button>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-xs-6 col-md-4 text-left resTextLeft" >@lang('modules.invoices.subTotal')</div>
                                            <p class="text-right col-xs-6 col-md-8" >
                                                {{ $global->currency->currency_symbol }}<span class="sub-total">0.00</span>
                                            </p>
                                            <input type="hidden" class="sub-total-field" name="sub_total" value="0">
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 text-left resTextLeft p-t-10">
                                                @lang('modules.invoices.discount')
                                            </div>
                                            <div class="form-group col-xs-6 col-md-4" >
                                                <input type="number" min="0" value="0" name="discount_value" class="form-control discount_value">
                                            </div>
                                            <div class="form-group col-xs-6 col-md-4" >
                                                <select class="form-control hide-search" name="discount_type" id="discount_type">
                                                    <option value="percent">%</option>
                                                    <option value="fixed">@lang('modules.invoices.amount')</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" id="invoice-taxes">
                                            <div class="col-md-4 text-left resTextLeft p-b-10">
                                                @lang('modules.invoices.tax')
                                            </div>

                                            <p class="text-right col-xs-6 col-md-8" >
                                                {{ $global->currency->currency_symbol }}<span class="tax-percent">0.00</span>
                                            </p>
                                        </div>

                                        <div class="row font-bold total-amount">
                                            <div class="col-md-4 col-xs-6 text-left resTextLeft" >@lang('modules.invoices.total')</div>

                                            <p class="text-right col-xs-6 col-md-8" >
                                                {{ $global->currency->currency_symbol }}<span class="total">0.00</span>
                                            </p>


                                            <input type="hidden" class="total-field" name="total" value="0">
                                        </div>

                                    </div>

                                </div>

                                
                            </div>                          

                            <div class="row m-t-40 m-b-20">
                                <div class="col-md-12">
                                    <h6 class="text-primary font-600">Images and Files</h6>
                                    <button type="button" class="btn btn-block btn-outline-info btn-sm col-md-2 select-image-button" style="margin-bottom: 10px;display: none "><i class="fa fa-upload"></i> File Select Or Upload</button>
                                    <div id="file-upload-box" >
                                        <div class="row" id="file-dropzone">
                                            <div class="col-md-12">
                                                <div class="dropzone" id="file-upload-dropzone">
                                                    <div class="dz-message needsclick"><i class="icon-cloud-up"></i>
                                                        <h6>Drop files here or click to upload.</h6></span>
                                                    </div>
                                                    {{ csrf_field() }}
                                                    <div class="fallback">
                                                        <input name="file" type="file" multiple/>
                                                    </div>
                                                    <input name="image_url" id="image_url"type="hidden" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="projectID" id="projectID">
                                </div>
                            </div>
                            <!--/span-->

                            <div class="row">
                                @if(isset($fields))
                                    @foreach($fields as $field)
                                        <div class="col-md-6">
                                            <label>{{ ucfirst($field->label) }}</label>
                                            <div class="form-group">
                                                @if( $field->type == 'text')
                                                    <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                @elseif($field->type == 'password')
                                                    <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                @elseif($field->type == 'number')
                                                    <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">

                                                @elseif($field->type == 'textarea')
                                                    <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" id="{{$field->name}}" cols="3">{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>

                                                @elseif($field->type == 'radio')
                                                    <div class="radio-list">
                                                        @foreach($field->values as $key=>$value)
                                                            <label class="radio-inline @if($key == 0) p-0 @endif">
                                                                <div class="radio radio-info">
                                                                    <input type="radio" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" id="optionsRadios{{$key.$field->id}}" value="{{$value}}" @if(isset($editUser) && $editUser->custom_fields_data['field_'.$field->id] == $value) checked @elseif($key==0) checked @endif>>
                                                                    <label for="optionsRadios{{$key.$field->id}}">{{$value}}</label>
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @elseif($field->type == 'select')
                                                    {!! Form::select('custom_fields_data['.$field->name.'_'.$field->id.']',
                                                            $field->values,
                                                             isset($editUser)?$editUser->custom_fields_data['field_'.$field->id]:'',['class' => 'form-control gender'])
                                                     !!}

                                                @elseif($field->type == 'checkbox')
                                                    <div class="mt-checkbox-inline">
                                                        @foreach($field->values as $key => $value)
                                                            <label class="mt-checkbox mt-checkbox-outline">
                                                                <input name="custom_fields_data[{{$field->name.'_'.$field->id}}][]" type="checkbox" value="{{$key}}"> {{$value}}
                                                                <span></span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @elseif($field->type == 'date')
                                                    <input type="text" class="form-control date-picker" size="16" name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                           value="{{ isset($editUser->dob)?Carbon\Carbon::parse($editUser->dob)->format('Y-m-d'):Carbon\Carbon::now()->format($global->date_format)}}">
                                                @endif
                                                <div class="form-control-focus"> </div>
                                                <span class="help-block"></span>

                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>

                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <div class="form-actions col-md-3  offset-md-9 ">
                            <button type="submit" id="save-form" class="btn btn-primary form-control">@lang('app.save')</button>
                        </div>
                    </div>
                  {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>



    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade bs-modal-md in" id="taxModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    @lang('app.loading')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('app.close')</button>
               </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}
@endsection

@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/jquery.ui.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-time-picker/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/dropzone/dropzone.js')}}"></script>
<script>


    /* projectID = '';
    Dropzone.autoDiscover = false;
    //Dropzone class
    myDropzone = new Dropzone("div#file-upload-dropzone", {
        url: "{{ route('admin.files.multiple-upload') }}",
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        paramName: "file",
        maxFilesize: 10,
        maxFiles: 10,
        acceptedFiles: "image/*,application/pdf",
        autoProcessQueue: false,
        uploadMultiple: true,
        addRemoveLinks:true,
        parallelUploads:10,
        init: function () {
            myDropzone = this;
        }
    });
    myDropzone.on('sending', function(file, xhr, formData) {
      //  console.log([formData, 'formData']);
        var ids = $('#projectID').val();
        formData.append('project_id', ids);
    });
    myDropzone.on('completemultiple', function () {
        var msgs = "@lang('modules.projects.projectUpdated')";
        $.showToastr(msgs, 'success');
        window.location.href = '{{ route('admin.projects.index') }}'
    }); */


    Dropzone.autoDiscover = false;
        //Dropzone class
        myDropzone = new Dropzone("div#file-upload-dropzone", {
            url: "{{ route('admin.files.multiple-upload') }}",
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            paramName: "file",
            maxFilesize: 20,
            maxFiles: 10,
            acceptedFiles: "image/*,application/pdf",
            autoProcessQueue: false,
            uploadMultiple: true,
            addRemoveLinks: true,
            parallelUploads: 10,
            init: function () {
                this.on("error", function(file, message) { 
                    if (file.size > 20) {
                        this.removeFile(file); 
                        $.toast({
                            heading: 'Error',
                            text: 'File is too large for destination file system. Maximum Upload Size is 20MB.',
                            position: 'top-right',
                            stack: false,
                            icon: 'error'
                        });                  
                        return message();
                    }
                    myDropzone = this;
                    
                });
                
            }
        });

        myDropzone.on('sending', function (file, xhr, formData) {
            console.log(myDropzone.getAddedFiles().length, 'sending');
            var ids = $('#projectID').val();
            formData.append('project_id', ids);
        });

        myDropzone.on('completemultiple', function () {
            var msgs = "@lang('messages.taskCreatedSuccessfully')";
            $.showToastr(msgs, 'success');
            window.location.href = '{{ route('admin.projects.index') }}'

        });



    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
     //$('#clientNotification').hide();
    checkTask();
    $('#start_date, #deadline').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });

    $('#start_time, #end_time').datetimepicker({
        format: 'LT',
        icons: {
            time: 'fa fa-clock',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-check',
            clear: 'fa fa-trash',
            close: 'fa fa-times'
        },
    });

    // check client view task checked
    function checkTask()
    {
        var chVal = $('#client_view_task').is(":checked") ? true : false;
        if(chVal == true){
            $('#clientNotification').show();
        }
        else{
            $('#clientNotification').hide();
        }
    }

    $('#without_deadline').click(function () {
        var check = $('#without_deadline').is(":checked") ? true : false;
        if(check == true){
            $('#deadlineBox').hide();
        }
        else{
           $('#deadlineBox').show();
        }
    });

    // Set selected Template
    function setTemplate(id){
        var template = {!! $templates !!};
        var selectedTemplate = [];
        if(id != null && id != undefined && id != ""){
            $.each( template, function( key, value ) {
                if(value['id'] == id){
                    selectedTemplate = value;
                }
            });
            $('#project_name').val(selectedTemplate['project_name']);
            $('#category_id').val(selectedTemplate['category_id']);
            $('#category_id').trigger('change');
            $('#project_summary').summernote('code', selectedTemplate['project_summary']);
            $('#notes').val(selectedTemplate['notes']);
            $('#template_id').val(selectedTemplate['id']);

            if(selectedTemplate['client_view_task'] == 'enable'){
                $("#client_view_task").prop('checked', true);
                $('#clientNotification').show();
                if(selectedTemplate['allow_client_notification'] == 'enable'){
                    $("#client_task_notification").prop('checked', 'checked');
                }
                else{
                    $("#client_task_notification").prop('checked', false);
                }
            }
            else{
                $("#client_view_task").prop('checked', false);
                $("#client_task_notification").prop('checked', false);
                $('#clientNotification').hide();
            }
            if(selectedTemplate['manual_timelog'] == 'enable'){
                $("#manual_timelog").prop('checked', true);
            }
            else{
                $("#manual_timelog").prop('checked', false);
            }
        }
    }


    $("#deadline").datepicker({
                autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    }).on('changeDate', function (selected) {
                var maxDate = new Date(selected.date.valueOf());
                $('#start_date').datepicker('setEndDate', maxDate);
            });

    $('#save-form').click(function () {
        is_saving = true;
        $.easyAjax({
            url: '{{route('admin.projects.store')}}',
            container: '#createProject',
            type: "POST",
            redirect: true,
            data: $('#createProject').serialize(),
            success: function(response){
                if(myDropzone.getQueuedFiles().length > 0){
                    projectID = response.projectID;
                    $('#projectID').val(response.projectID);
                    myDropzone.processQueue();
                }
                else{
                   /*  var msgs = "@lang('modules.projects.projectUpdated')";
                    $.showToastr(msgs, 'error'); */
                    //window.location.href = '{{ route('admin.projects.index') }}'
                }
            }
        })
    });
    
    $(':reset').on('click', function(evt) {
        evt.preventDefault()
        $form = $(evt.target).closest('form')
        $form[0].reset()
        $form.find('select').select2()
    });
</script>

<script>
    $('#createProject').on('click', '#addProjectCategory', function () {
        var url = '{{ route('admin.projectCategory.create-cat')}}';
        $('#modelHeading').html('Manage Project Category');
        $.ajaxModal('#projectCategoryModal', url);
    });


    $("body").on("click",".tax-settings, #tax-settings2", function(){
        var url = '{{ route('admin.taxes.create')}}';
        $('#modelHeading').html('Manage Project Category');
        $.ajaxModal('#taxModal', url);
    });

    function decimalupto2(num) {
        var amt =  Math.round(num * 100) / 100;
        return parseFloat(amt.toFixed(2));
    }
</script>

<script>
    var is_saving = false;
$(function () {
    $( "#sortable" ).sortable();
});

    $('#all_members').change(function() {
        if($('#all_members').is(":checked")){
            $("#team_id > option").prop("selected","selected");
            $("#team_id").trigger("change");
        } else {
            $("#team_id").val(null).trigger("change");
        }
    });

$('#add-item').click(function () {
        var i = $(document).find('.item_name').length;
        var item = '<div class="row item-row margin-top-5">'

            +'<div class="col-md-12 invoice-head">'
            +'<div class="row">'

            +'<div class="col-md-4 d-none d-sm-block font-bold d-none d-sm-block label-block">'
            +'@lang('modules.invoices.item')'
            +'</div>'

            +'<div class="col-md-1 d-none d-sm-block font-bold d-none d-sm-block label-block">'
            +'@lang('modules.invoices.qty')'
            +'</div>'

            +'<div class="col-md-2 d-none d-sm-block font-bold d-none d-sm-block label-block">'
            +'@lang('modules.invoices.unitPrice')'
            +'</div>'

            +'<div class="col-md-2 font-bold d-none d-sm-block d-none d-sm-block label-block">'
            +'@lang('modules.invoices.tax') <a href="javascript:;" class="tax-settings" ><i class="icofont icofont-gear"></i></a>'
            +'</div>'

            +'<div class="col-md-2 text-right d-none d-sm-block font-bold d-none d-sm-block label-block-last">'
            +'@lang('modules.invoices.amount')'
            +'</div>'

            +'<div class="col-md-1 d-none d-sm-block d-none d-sm-block">&nbsp;</div>'

            +'</div>'
            +'</div>'

            +'<div class="col-md-4">'
           // +'<div class="row">'
            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.item')</label>'
            +'<div class="input-group">'
            +'<div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>'
            +'<input type="text" class="form-control item_name" name="item_name[]" >'
            +'</div>'
            +'</div>'           
          //  +'</div>'

            +'</div>'

            +'<div class="col-md-1">'

            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.qty')</label>'
            +'<input type="number" min="1" class="form-control quantity" value="1" name="quantity[]" >'
            +'</div>'


            +'</div>'

            +'<div class="col-md-2">'
          //  +'<div class="row">'
            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.unitPrice')</label>'
            +'<input type="text" min="0" class="form-control cost_per_item" value="0" name="cost_per_item[]">'
            +'</div>'
           // +'</div>'

            +'</div>'


            +'<div class="col-md-2">'

            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.tax')</label>' 
            +'<select id="multiselect'+i+'" name="taxes['+i+'][]"  multiple="multiple" class="select2 form-control type">'
                @foreach($taxes as $tax)
            +'<option data-rate="{{ $tax->rate_percent }}" value="{{ $tax->id }}">{{ $tax->tax_name.': '.$tax->rate_percent }}%</option>'
                @endforeach
            +'</select>'
            +'</div>'


            +'</div>'

            +'<div class="col-md-2 text-right invoiceAmount">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.amount')</label>'
            +'<p class="form-control-static">{{ $global->currency->currency_symbol }}<span class="amount-html">0.00</span></p>'
            +'<input type="hidden" class="amount" name="amount[]">'
            +'</div>'

            +'<div class="col-md-1 text-right d-md-block d-lg-block d-none">'
            +'<button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i></button>'
            +'</div>'

            +'<div class="col-md-1 m-b-20 d-md-none d-lg-none">'
            +'<button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>'
            +'</div>'

            +'<div class="col-md-9">'           
            +'<div class="form-group">'
            +'<textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>'
            +'</div>'            
            +'</div>'

            +'</div>';
            

        $(item).hide().appendTo("#sortable").fadeIn(500);
        $('#multiselect'+i).select2();
    });

    $('#createProject').on('click','.remove-item', function () {
        $(this).closest('.item-row').fadeOut(300, function() {
            $(this).remove();
            calculateTotal();
        });
    });

    $('#createProject').on('keyup change','.quantity,.cost_per_item,.item_name, .discount_value', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount).toFixed(2));
        $(this).closest('.item-row').find('.amount-html').html(amount.toLocaleString());
        //$(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount).toFixed(2));

        calculateTotal();


    });

    $('#createProject').on('change','.type, #discount_type', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount).toFixed(2));
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount).toFixed(2));

        calculateTotal();


    });

    function calculateTotal(){
        var subtotal = 0;
        var discount = 0;
        var tax = '';
        var taxList = new Object();
        var taxTotal = 0;
        $(".quantity").each(function (index, element) {
            var itemTax = [];
            var itemTaxName = [];
            $(this).closest('.item-row').find('select.type option:selected').each(function (index) {
                itemTax[index] = $(this).data('rate');
                itemTaxName[index] = $(this).text();
            });
            var itemTaxId = $(this).closest('.item-row').find('select.type').val();

            var amount = parseFloat($(this).closest('.item-row').find('.amount').val());

            if(isNaN(amount)){ amount = 0; }

            subtotal = (parseFloat(subtotal)+parseFloat(amount)).toFixed(2);

            if(itemTaxId != ''){
                for(var i = 0; i<=itemTaxName.length; i++)
                {
                    if(typeof (taxList[itemTaxName[i]]) === 'undefined'){
                        taxList[itemTaxName[i]] = ((parseFloat(itemTax[i])/100)*parseFloat(amount));
                    }
                    else{
                        taxList[itemTaxName[i]] = parseFloat(taxList[itemTaxName[i]]) + ((parseFloat(itemTax[i])/100)*parseFloat(amount));
                    }
                }
            }
        });

        $.each( taxList, function( key, value ) {
            if(!isNaN(value)){
                tax = tax+'<div class="col-md-4 text-left p-b-10">'
                    +key
                    +'</div>'
                    +'<div class="text-right col-xs-6 col-md-8" >'
                    +'{{ $global->currency->currency_symbol }}<span class="tax-percent">'+(decimalupto2(value)).toFixed(2)+'</span>'
                    +'</div>';
                taxTotal = taxTotal+decimalupto2(value);
            }
        });

        if(isNaN(subtotal)){  subtotal = 0; }

        //$('.sub-total').html(decimalupto2(subtotal).toFixed(2));
        $('.sub-total').html(decimalupto2(subtotal).toLocaleString(2));
        $('.sub-total-field').val(decimalupto2(subtotal));

        var discountType = $('#discount_type').val();
        var discountValue = $('.discount_value').val();

        if(discountValue != ''){
            if(discountType == 'percent'){
                discount = ((parseFloat(subtotal)/100)*parseFloat(discountValue));
            }
            else{
                discount = parseFloat(discountValue);
            }

        }

    //show tax
    $('#invoice-taxes').html(tax);

    //calculate total
        var totalAfterDiscount = decimalupto2(subtotal-discount);

        totalAfterDiscount = (totalAfterDiscount < 0) ? 0 : totalAfterDiscount;

        var total = decimalupto2(totalAfterDiscount+taxTotal);

        //$('.total').html(total.toFixed(2));
        $('.total').html(total.toLocaleString());
        $('.total-field').val(total.toFixed(2));

    }

    $('#status').change(function(){        
        var jobStatus = $('#status option:selected').data('jobstatus');
        var percentage = $('#status option:selected').data('percentage');   
        $('#jobStatus').val(jobStatus);
        $('#completion_percent').val(percentage);
    });

    $(window).bind('beforeunload', function(){
        if(!is_saving){
            return 'Are you sure you want to leave?';
        }
    });

    $('#addProduct').click(function(){
        var id = $('#products option:selected').val();        
        $.easyAjax({
            url:'{{ route('admin.all-invoices.update-item') }}',
            type: "GET",
            data: { id: id },
            success: function(response) {
                $(response.view).hide().appendTo("#sortable").fadeIn(500);
                var noOfRows = $(document).find('#sortable .item-row').length;
                var i = $(document).find('.item_name').length-1;
                var itemRow = $(document).find('#sortable .item-row:nth-child('+noOfRows+') select.type');
                itemRow.attr('id', 'multiselect'+i);
                itemRow.attr('name', 'taxes['+i+'][]');
                $(document).find('#multiselect'+i).select2();
                calculateTotal();
                $("#products")[0].selectedIndex = 0;
            }
        });
    });

    $('#cat_id').change(function (e) {
        var cat_id = $(this).val();        
        var url = "{{ route('admin.all-invoices.get-category-products',':id') }}";
        url = url.replace(':id', cat_id);
        $.easyAjax({
            type: 'GET',
            dataType: 'JSON',
            url: url,
            success: function (data) {
                $('#products').html('');
                $('#products').append('<option value="">--</option>');
                $.each(data, function (index, data) {
                    $('#products').append('<option value="' + data.id + '">' + data.name + '</option>');
                });
            }
        });
    });

    $('#all_vehicles').change(function() {
        if($('#all_vehicles').is(":checked")){
            $("#vehicle_id > option").prop("selected","selected");
            $("#vehicle_id").trigger("change");
        } else {
            $("#vehicle_id").val(null).trigger("change");
        }
    });
</script>
@endpush

