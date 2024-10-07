@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dropzone.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap-datetimepicker.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/range-slider.css') }}">
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
                            <li class="breadcrumb-item active">@lang('modules.jobs.copy')</li>
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

                    {!! Form::open(['id'=>'createProject','class'=>'ajax-form','method'=>'POST','route'=>'admin.projects.store']) !!}

                    <div class="card-header">
                        <div class="card-title mb-0 h4"> @lang('modules.jobs.copyTitle')</div>


                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-label-group form-group">
                                        <input type="text" name="project_name" id="project_name" class="form-control form-control-lg" value="{{ $project->project_name }}" placeholder="*">
                                        <label for="project_name" class="col-form-label required">@lang('modules.jobs.jobName')</label>
                                        <input type="hidden" name="template_id" id="template_id">
                                    </div>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-lg-5">
                                    <div class="form-label-group form-group">                                        
                                        <select class="select2 form-control form-control-lg" name="client_id" id="client_id" placeholder="*">
                                            <option value="null">--</option>
                                                @forelse($clients as $client)
                                                    <option value="{{ $client->id }}"
                                                            @if($project->client_id == $client->id)
                                                            selected
                                                            @endif
                                                    >{{ ucwords($client->company_name) }}</option>
                                                @empty
                                                    <option value="">@lang('modules.projects.selectClient')</option>
                                                @endforelse
                                        </select>
                                        <label for="client_id" class="col-form-label required">@lang('modules.projects.selectClient')</label>
                                    </div>
                                </div>

                                <div class="col-md-4 ">
                                    <div class="form-label-group form-group">                                        
                                        <select class="select2 form-control" name="category_id" id="category_id" >
                                            @forelse($categories as $category)
                                                <option value="{{ $category->id }}"
                                                        @if($project->category_id == $category->id)
                                                        selected
                                                        @endif
                                                >{{ ucwords($category->category_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noProjectCategoryAdded')</option>
                                            @endforelse
                                        </select>
                                        <label for="category_id" class="col-form-label required">@lang('modules.projects.projectCategory')</label>
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <a href="javascript:;" id="addProjectCategory" class="btn btn-sm btn-outline btn-block btn-secondary"><i
                                                        class="fa fa-plus"></i> @lang('modules.projectCategory.addProjectCategory')</a>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="checkbox checkbox-info col-md-10 clientViewTask">
                                    <input id="client_view_task" onchange="checkTask()" name="client_view_task" value="true" type="checkbox"  @if($project->client_view_task == "enable") checked @endif>
                                    <label for="client_view_task">@lang('modules.projects.clientViewTask')</label>
                                </div>
                                   
                                <div class="checkbox checkbox-info col-md-10" id="clientNotification">
                                    <input id="client_task_notification" name="client_task_notification" value="true" type="checkbox" @if($project->allow_client_notification == "enable") checked @endif>
                                    <label for="client_task_notification">@lang('modules.projects.clientTaskNotification')</label>
                                </div>
                                
                                <div class="checkbox checkbox-info  col-md-10">
                                    <input id="manual_timelog" name="manual_timelog" value="true" type="checkbox" @if($project->manual_timelog == "enable") checked @endif>
                                    <label for="manual_timelog">@lang('modules.projects.manualTimelog')</label>
                                </div>                                   
                                
                                    
                            </div>

                            <br />

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">
                                                <input type="text" name="start_date" id="start_date" autocomplete="off" class="form-control form-control-lg" value="{{ $project->start_date->format($global->date_format) }}" placeholder="*">
                                                <label for="start_date" class="col-form-label required">@lang('modules.projects.startDate')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">
                                                <input type="text" name="start_time" id="start_time" autocomplete="off" class="form-control form-control-lg" value="{{ \Carbon\Carbon::createFromFormat('H:i:s', $project->start_time)->format($global->time_format) }}" placeholder="*">
                                                <label for="start_time" class="col-form-label required">@lang('modules.projects.startTime')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group" id="deadlineBox">
                                                <input type="text" name="deadline" id="deadline" autocomplete="off" class="form-control form-control-lg" value="@if($project->deadline) {{ $project->deadline->format($global->date_format) }} @endif" placeholder="*">
                                                <label for="deadline" class="col-form-label required">@lang('modules.projects.endDate')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">
                                                <input type="text" name="end_time" id="end_time" autocomplete="off" class="form-control form-control-lg" value="{{ \Carbon\Carbon::createFromFormat('H:i:s', $project->end_time)->format($global->time_format) }}" placeholder="*">
                                                <label for="end_time" class="col-form-label required">@lang('modules.projects.endTime')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">
                                                <input type="text" class="form-control form-control-lg" name="project_budget" id="project_budget" value="{{ $project->project_budget }}" placeholder="*">
                                                <label for="project_budget" class="col-form-label required">@lang('modules.projects.projectBudget')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">
                                                <select name="currency_id" id="currency_id" class="form-control form-control-lg" placeholder="*">
                                                    <option value="">--</option>
                                                @foreach ($currencies as $item)
                                                    <option
                                                    @if($item->id == $project->currency_id) selected @endif
                                                    value="{{ $item->id }}">{{ $item->currency_name }} ({{ $item->currency_code }})</option>
                                                @endforeach
                                                </select>
                                                <label for="currency_id" class="control-label required">@lang('modules.invoices.currency')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group">                                                
                                                <input type="text" name="hours_allocated" id="hours_allocated" class="form-control form-control-lg" value="{{ $project->hours_allocated }}" placeholder="*">
                                                <label for="hours_allocated" class="control-label">@lang('modules.jobs.estHours')</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-label-group form-group"> 
                                                <select name="status" id="status" class="form-control form-control-lg" placeholder="*">
                                                    <option
                                                        @if($project->status == 'not started') selected @endif
                                                            value="not started">@lang('app.notStarted')
                                                            </option>
                                                            <option
                                                                    @if($project->status == 'in progress') selected @endif
                                                            value="in progress">@lang('app.inProgress')
                                                            </option>
                                                            <option
                                                                    @if($project->status == 'on hold') selected @endif
                                                            value="on hold">@lang('app.onHold')
                                                            </option>
                                                            <option
                                                                    @if($project->status == 'canceled') selected @endif
                                                            value="canceled">@lang('app.canceled')
                                                            </option>
                                                            <option
                                                                    @if($project->status == 'finished') selected @endif
                                                            value="finished">@lang('app.finished')
                                                    </option>
                                                </select>
                                                <label for="status" class="control-label">@lang('app.project') @lang('app.status')</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-label-group form-group">                                        
                                        <textarea name="project_summary" id="project_summary" rows="10" class="form-control form-control-lg" placeholder="*">{{ $project->project_summary }}</textarea>
                                        <label for="project_summary" class="col-form-label">@lang('modules.jobs.jobdescription')</label>
                                    </div>
                                </div>
                            </div>

                            {{--<div class="row">--}}
                            {{----}}
                                {{--<div class="col-md-12">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="control-label">@lang('modules.projects.projectCompletionStatus')</label>--}}
                                        {{--<div id="range_01"></div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<input type="hidden" name="completion_percent" id="completion_percent" value="{{ $project->completion_percent }}">--}}

                            {{--<div class="row">
                                <div class="checkbox checkbox-info  col-md-10">
                                    <input id="calculate-task-progress" name="calculate_task_progress" value="true"
                                            @if($project->calculate_task_progress == "true") checked @endif type="checkbox">
                                    <label for="calculate-task-progress">@lang('modules.projects.calculateTasksProgress')</label>
                                </div>
                            </div>--}}

                            <hr />
                            <br />
                            <h5>@lang('modules.jobs.teamMembers')</h5>

                            <div class="row">
                                <div class="checkbox checkbox-info  col-md-2">
                                    <input id="all_members" name="all_members" value="true" type="checkbox">
                                    <label for="all_members">@lang('modules.jobs.allMembers')</label>
                                </div>                                
                               <?php //print_r($project->members); die('s'); ?>
                               @php $teams[] = ''; @endphp
                                @if(!empty($project->members))
                                    @foreach($project->members as $member)
                                        @php $teams[] = $member->user_id; @endphp
                                    @endforeach
                                @endif

                                <div class="col-md-10">
                                    <div class="form-group" id="user_id">
                                        <select class="select2 m-b-10 select2-multiple form-control " multiple="multiple"
                                                data-placeholder="Choose Members" name="user_id[]" id="team_id">
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->id }}" @if (isset($project->members) && in_array($emp->id, $teams) !== false) selected @endif>{{ ucwords($emp->name). ' ['.$emp->email.']' }} 
                                                    @if($emp->id == $user->id) (YOU) @endif
                                                </option>
                                            @endforeach
                                        </select>                                       
                                    </div>
                                </div>
                            </div>

                            <div class="d-md-block d-lg-block ">

                                <div class="row">
                                    <div class="col-md-12 m-t-30"><h5>Invoices</h5></div>
                                    <div class="col-md-4 font-bold d-none d-sm-block" style="padding: 8px 15px">
                                        @lang('modules.invoices.item')
                                    </div>

                                    <div class="col-md-1 font-bold d-none d-sm-block" style="padding: 8px 15px">
                                        @lang('modules.invoices.qty')
                                    </div>

                                    <div class="col-md-2 font-bold d-none d-sm-block" style="padding: 8px 15px">
                                        @lang('modules.invoices.unitPrice')
                                    </div>

                                    <div class="col-md-2 font-bold d-none d-sm-block" style="padding: 8px 15px">
                                        @lang('modules.invoices.tax') <a href="javascript:;" id="tax-settings" ><i class="icofont icofont-gear"></i></a>
                                    </div>

                                    <div class="col-md-2 text-center font-bold d-none d-sm-block" style="padding: 8px 15px">
                                        @lang('modules.invoices.amount')
                                    </div>

                                    <div class="col-md-1 d-none d-sm-block" style="padding: 8px 15px">
                                        &nbsp;
                                    </div>

                                </div>


                                <div id="sortable">
                                @if(!empty($invoice->items))
                                    @foreach($invoice->items as $key => $item)
                                    <div class="row item-row margin-top-5">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.item')</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                                                    <input type="text" class="form-control item_name" name="item_name[]" value="{{ $item->item_name }}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2">{{ $item->item_summary }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.qty')</label>
                                                <input type="number" min="1" class="form-control quantity" name="quantity[]" value="{{ $item->quantity }}">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.unitPrice')</label>
                                                <input type="text"  class="form-control cost_per_item" name="cost_per_item[]" value="{{ $item->unit_price }}" >
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.type')</label>
                                                <select id="multiselect" name="taxes[0][]"  multiple="multiple" class="select2 form-control type">
                                                    @foreach($taxes as $tax)
                                                        <option data-rate="{{ $tax->rate_percent }}"
                                                            @if (isset($item->taxes) && array_search($tax->id, json_decode($item->taxes)) !== false)
                                                            selected
                                                            @endif
                                                            value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 border-dark  text-center">
                                            <label class="control-label d-md-none d-lg-none">@lang('modules.invoices.amount')</label>
                                            <p class="form-control-static"><span class="amount-html">{{ number_format((float)$item->amount, 2, '.', '') }}</span></p>
                                            <input type="hidden" class="amount" name="amount[]" value="{{ $item->amount }}">
                                        </div>

                                        <div class="col-md-1 text-right d-md-block d-lg-block d-none">
                                            <button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i></button>
                                        </div>
                                        <div class="col-md-1 d-md-none d-lg-none">
                                            <div class="row">
                                                <button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>
                                            </div>
                                        </div>

                                    </div>
                                    @endforeach
                                    @endif
                                </div>

                                
                                <div class="row">
                                    <div class="col-12 m-t-5">
                                        <button type="button" class="btn btn-info" id="add-item"><i class="fa fa-plus"></i> @lang('modules.invoices.addItem')</button>
                                    </div>
                                </div>
                                @if(!empty($invoice->items))
                                <div class="row">
                                    <div class="col-12 ">
                                        <div class="row">
                                            <div class="offset-md-9 col-xs-6 col-md-1 text-right p-t-10 resTextLeft" >@lang('modules.invoices.subTotal')</div>

                                            <p class="form-control-static col-xs-6 col-md-2" >
                                                <span class="sub-total">{{ number_format((float)$invoice->sub_total, 2, '.', '') }}</span>
                                            </p>
                                            <input type="hidden" class="sub-total-field" name="sub_total" value="{{ $invoice->sub_total }}">
                                        </div>

                                        <div class="row">
                                            <div class="offset-md-9 col-md-1 text-right p-t-10 resTextLeft">
                                                @lang('modules.invoices.discount')
                                            </div>
                                            <div class="form-group col-xs-6 col-md-1" >
                                                <input type="number" min="0" value="{{ $invoice->discount }}" name="discount_value" class="form-control discount_value">
                                            </div>
                                            <div class="form-group col-xs-6 col-md-1" >
                                                <select class="form-control" name="discount_type" id="discount_type">
                                                    <option
                                                        @if($invoice->discount_type == 'percent') selected @endif
                                                        value="percent">%</option>
                                                    <option
                                                        @if($invoice->discount_type == 'fixed') selected @endif
                                                        value="fixed">@lang('modules.invoices.amount')</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row m-t-5" id="invoice-taxes">
                                            <div class="offset-md-9 col-md-1 text-right p-t-10 resTextLeft">
                                                @lang('modules.invoices.tax')
                                            </div>

                                            <p class="form-control-static col-xs-6 col-md-2" >
                                                <span class="tax-percent">0.00</span>
                                            </p>
                                        </div>

                                        <div class="row m-t-5 font-bold">
                                            <div class="offset-md-9 col-md-1 col-xs-6 text-right p-t-10 resTextLeft" >@lang('modules.invoices.total')</div>

                                            <p class="form-control-static col-xs-6 col-md-2" >
                                                <span class="total">{{ number_format((float)$invoice->total, 2, '.', '') }}</span>
                                            </p>
                                            <input type="hidden" class="total-field" name="total" value="{{ round($invoice->total, 2) }}">
                                        </div>

                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-12 ">
                                        <div class="row">
                                            <div class="offset-md-9 col-xs-6 col-md-1 text-right p-t-10" >@lang('modules.invoices.subTotal')</div>
                                            <p class="form-control-static col-xs-6 col-md-2" >
                                                <span class="sub-total">0.00</span>
                                            </p>
                                            <input type="hidden" class="sub-total-field" name="sub_total" value="0">
                                        </div>

                                        <div class="row">
                                            <div class="offset-md-9 col-md-1 text-right p-t-10">
                                                @lang('modules.invoices.discount')
                                            </div>
                                            <div class="form-group col-xs-6 col-md-1" >
                                                <input type="number" min="0" value="0" name="discount_value" class="form-control discount_value">
                                            </div>
                                            <div class="form-group col-xs-6 col-md-1" >
                                                <select class="form-control" name="discount_type" id="discount_type">
                                                    <option value="percent">%</option>
                                                    <option value="fixed">@lang('modules.invoices.amount')</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row m-t-5" id="invoice-taxes">
                                            <div class="offset-md-9 col-md-1 text-right p-t-10">
                                                @lang('modules.invoices.tax')
                                            </div>

                                            <p class="form-control-static col-xs-6 col-md-2" >
                                                <span class="tax-percent">0.00</span>
                                            </p>
                                        </div>

                                        <div class="row m-t-5 font-bold">
                                            <div class="offset-md-9 col-md-1 col-xs-6 text-right p-t-10" >@lang('modules.invoices.total')</div>

                                            <p class="form-control-static col-xs-6 col-md-2" >
                                                <span class="total">0.00</span>
                                            </p>


                                            <input type="hidden" class="total-field" name="total" value="0">
                                        </div>

                                    </div>
                                </div>
                                @endif
                            </div>                         

                            <div class="row m-t-40 m-b-20">
                                <div class="col-md-12">
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
                                @foreach($fields as $field)
                                    <div class="col-md-6">
                                        <label>{{ ucfirst($field->label) }}</label>
                                        <div class="form-group">
                                            @if( $field->type == 'text')
                                                <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$project->custom_fields_data['field_'.$field->id] ?? ''}}">
                                            @elseif($field->type == 'password')
                                                <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$project->custom_fields_data['field_'.$field->id] ?? ''}}">
                                            @elseif($field->type == 'number')
                                                <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$project->custom_fields_data['field_'.$field->id] ?? ''}}">

                                            @elseif($field->type == 'textarea')
                                                <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" id="{{$field->name}}" cols="3">{{$project->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>

                                            @elseif($field->type == 'radio')
                                                <div class="radio-list">
                                                    @foreach($field->values as $key=>$value)
                                                        <label class="radio-inline @if($key == 0) p-0 @endif">
                                                            <div class="radio radio-info">
                                                                <input type="radio" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" id="optionsRadios{{$key.$field->id}}" value="{{$value}}" @if(isset($project) && $project->custom_fields_data['field_'.$field->id] == $value) checked @elseif($key==0) checked @endif>>
                                                                <label for="optionsRadios{{$key.$field->id}}">{{$value}}</label>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @elseif($field->type == 'select')
                                                {!! Form::select('custom_fields_data['.$field->name.'_'.$field->id.']',
                                                        $field->values,
                                                         isset($project)?$project->custom_fields_data['field_'.$field->id]:'',['class' => 'form-control gender'])
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
                                                       value="{{ isset($project->custom_fields_data['field_'.$field->id])?Carbon\Carbon::createFromFormat('m/d/Y', $project->custom_fields_data['field_'.$field->id])->format('m/d/Y'):Carbon\Carbon::now()->format($global->date_format)}}">
                                            @endif
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block"></span>

                                        </div>
                                    </div>
                                @endforeach

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
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.close')</button>
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
<script src="{{ asset('themes/wceo/assets/js/range-slider/ion.rangeSlider.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/range-slider/rangeslider-script.js')}}"></script>

<script>

    

    Dropzone.autoDiscover = false;
    //Dropzone class
    myDropzone = new Dropzone("div#file-upload-dropzone", {
        url: "{{ route('admin.files.multiple-upload') }}",
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        paramName: "file",
        maxFilesize: 20,
        maxFiles: 10,
        acceptedFiles: "image/*,application/pdf",
        autoProcessQueue: false,
        uploadMultiple: true,
        addRemoveLinks:true,
        parallelUploads:10,
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

    myDropzone.on('sending', function(file, xhr, formData) {
        console.log(myDropzone.getAddedFiles().length,'sending');
        var ids = '{{ $project->id }}';
        formData.append('project_id', ids);
    });

    myDropzone.on('completemultiple', function () {
        var msgs = "@lang('messages.taskUpdatedSuccessfully')";
        $.showToastr(msgs, 'success');
        window.location.href = '{{ route('admin.projects.index') }}'

    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    checkTask();
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
    @if($project->deadline == null)
        //$('#deadlineBox').hide();
    @endif
    $('#without_deadline').click(function () {
        var check = $('#without_deadline').is(":checked") ? true : false;
        if(check == true){
           // $('#deadlineBox').hide();
        }
        else{
            //$('#deadlineBox').show();
        }
    });

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

    $('#save-form').click(function () {

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
                    var msgs = "@lang('modules.projects.projectUpdated')";
                    $.showToastr(msgs, 'success');
                    window.location.href = '{{ route('admin.projects.index') }}'
                }
            }
        })

    });    

    var completion = $('#completion_percent').val();

    $("#range_01").ionRangeSlider({
        grid: true,
        min: 0,
        max: 100,
        from: parseInt(completion),
        postfix: "%",
        onFinish: saveRangeData
    });

    var slider = $("#range_01").data("ionRangeSlider");

    /*$('#calculate-task-progress').change(function () {
        if($(this).is(':checked')){
            slider.update({"disable": true});
        }
        else{
            slider.update({"disable": false});
        }
    })*/

    function saveRangeData(data) {
        var percent = data.from;
        $('#completion_percent').val(percent);
    }

    $(':reset').on('click', function(evt) {
        evt.preventDefault()
        $form = $(evt.target).closest('form')
        $form[0].reset()
        $form.find('select').select2()
    });

    @if($project->calculate_task_progress == "true")
        slider.update({"disable": true});
    @endif
</script>

<script>
    $('#updateProject').on('click', '#addProjectCategory', function () {
        var url = '{{ route('admin.projectCategory.create-cat')}}';
        $('#modelHeading').html('Manage Project Category');
        $.ajaxModal('#projectCategoryModal', url);
    })
</script>

<script>

$('#all_members').change(function() {
    if($('#all_members').is(":checked")){
        $("#team_id").val("");
        $("#team_id").trigger("change");
    } 
});

$('#tax-settings').click(function () {
        var url = '{{ route('admin.taxes.create')}}';
        $('#modelHeading').html('Manage Project Category');
        $.ajaxModal('#taxModal', url);
    });

    function decimalupto2(num) {
        var amt =  Math.round(num * 100) / 100;
        return parseFloat(amt.toFixed(2));
    }

$(function () {
    $( "#sortable" ).sortable();
});

$('#add-item').click(function () {
        var i = $(document).find('.item_name').length;
        var item = '<div class="row item-row margin-top-5">'

            +'<div class="col-md-4">'
            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.item')</label>'
            +'<div class="input-group">'
            +'<div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>'
            +'<input type="text" class="form-control item_name" name="item_name[]" >'
            +'</div>'
            +'</div>'

            +'<div class="form-group">'
            +'<textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>'
            +'</div>'            

            +'</div>'

            +'<div class="col-md-1">'

            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.qty')</label>'
            +'<input type="number" min="1" class="form-control quantity" value="1" name="quantity[]" >'
            +'</div>'


            +'</div>'

            +'<div class="col-md-2">'
            +'<div class="form-group">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.unitPrice')</label>'
            +'<input type="text" min="0" class="form-control cost_per_item" value="0" name="cost_per_item[]">'
            +'</div>'

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

            +'<div class="col-md-2 text-center resTextLeft">'
            +'<label class="control-label d-md-none d-lg-none">@lang('modules.invoices.amount')</label>'
            +'<p class="form-control-static"><span class="amount-html">0.00</span></p>'
            +'<input type="hidden" class="amount" name="amount[]">'
            +'</div>'

            +'<div class="col-md-1 text-right d-md-block d-lg-block d-none">'
            +'<button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i></button>'
            +'</div>'

            +'<div class="col-md-1 d-md-none d-lg-none m-b-20">'
            +'<button type="button" class="btn remove-item btn-outline-danger"><i class="fa fa-times"></i> @lang('app.remove')</button>'
            +'</div>'

            +'</div>';

        $(item).hide().appendTo("#sortable").fadeIn(500);
        $('#multiselect'+i).select2();
    });

    $('#updateProject').on('click','.remove-item', function () {
        $(this).closest('.item-row').fadeOut(300, function() {
            $(this).remove();
            calculateTotal();
        });
    });

    $('#updateProject').on('keyup change','.quantity,.cost_per_item,.item_name, .discount_value', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount).toFixed(2));
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount).toFixed(2));

        calculateTotal();


    });

    $('#updateProject').on('change','.type, #discount_type', function () {
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
                tax = tax+'<div class="offset-md-8 col-md-2 text-right p-t-10 resTextLeft">'
                    +key
                    +'</div>'
                    +'<p class="form-control-static col-xs-6 col-md-2" >'
                    +'<span class="tax-percent">'+(decimalupto2(value)).toFixed(2)+'</span>'
                    +'</p>';
                taxTotal = taxTotal+decimalupto2(value);
            }
        });

        if(isNaN(subtotal)){  subtotal = 0; }

        $('.sub-total').html(decimalupto2(subtotal).toFixed(2));
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

        $('.total').html(total.toFixed(2));
        $('.total-field').val(total.toFixed(2));

    }

    var curentPercent = $('#completion_percent').val();
    $('#status').change(function(){        
        var status = $('#status option:selected').val();
        if(status == 'finished'){
            $('#completion_percent').val('100');
        } else {
            $('#completion_percent').val(curentPercent);
        }            
    })
</script>
@endpush
