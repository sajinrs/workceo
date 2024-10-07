@extends('layouts.member-app')

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('member.leads.index') }}">{{ __($pageTitle) }}</a></li>
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
                    {!! Form::open(['id'=>'createLead','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.lead.createTitle')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">

                            <h5 class="mb-4">@lang('modules.lead.leadInfo')</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="-" type="text" id="company_name" name="company_name" class="form-control-lg form-control" >
                                        <label for="company_name" class="control-label required">@lang('modules.lead.companyName')</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="-" type="email" name="client_email" id="client_email"  class="form-control-lg form-control">
                                        <label for="client_email" class="required">@lang('modules.lead.clientEmail')</label>
                                        <span class="help-block">@lang('modules.lead.emailNote')</span>
                                    </div>
                                </div>

                            </div>
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="-" type="text" name="client_name" id="client_name"  class="form-control-lg form-control">
                                        <label for="client_name" class="required">@lang('modules.lead.firstName')</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="-" type="text" name="client_last_name" id="client_last_name"  class="form-control-lg form-control">
                                        <label for="client_last_name" class="required">@lang('modules.lead.lastName')</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="-" type="text" id="website" name="website" class="form-control-lg form-control" >
                                        <label for="website" class="control-label">@lang('modules.lead.website')</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <input placeholder="-" type="tel" name="mobile" id="mobile" class="form-control-lg form-control">
                                        <label for="mobile">@lang('modules.lead.mobile')</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                     <div class="form-label-group form-group">
                                        <textarea placeholder="-" name="address" id="address" class="form-control-lg form-control" rows="5"></textarea>
                                        <label for="address" class="required">@lang('app.address')</label>
                                    </div>
                                </div>
                            </div>
                            <!--/row-->


                            <hr class="mt-4 mb-4">

                            <h5 class="mb-4">@lang('modules.lead.leadDetails')</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <select placeholder="-" class="select2 form-control-lg form-control" data-placeholder="Select" id="agent_id" name="agent_id">
                                            @foreach($leadAgents as $emp)
                                                @if($emp->user->id == $user->id)
                                                    <option value="{{ $emp->id }}">{{ ucwords($emp->user->name) }} (YOU) </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <label for="agent_id">@lang('app.leadAgent')(@lang('modules.tickets.agent')) </label>
                                    </div>  
                                </div>

                                <div class="col-md-3">
                                    <div class="form-label-group form-group">
                                        <select placeholder="-" class="select2 form-control-lg form-control" data-placeholder="@lang('modules.lead.leadSource')"  id="source_id" name="source_id">
                                            @foreach($sources as $source)
                                                <option value="{{ $source->id }}">{{ ucwords($source->type) }}</option>
                                            @endforeach
                                        </select>
                                        <label for="source_id">@lang('modules.lead.leadSource') </label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-label-group form-group">
                                        <select placeholder="-" name="next_follow_up" id="next_follow_up" class="select2 form-control-lg form-control">
                                            <option value="yes"> @lang('app.yes')</option>
                                            <option value="no"> @lang('app.no')</option>
                                        </select>
                                        <label for="next_follow_up">@lang('app.next_follow_up')</label>
                                    </div>
                                </div>
                               
                            </div>
                            
                            
                            <div class="row">
                                <div class="col-md-12">
                                     <div class="form-label-group form-group">
                                        <textarea placeholder="-" name="note" id="note" class="form-control-lg form-control" rows="5"></textarea>
                                        <label for="note">@lang('app.note')</label>
                                    </div>
                                </div>

                                
                            </div>


                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions col-md-3 offset-md-9">
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
                    <h5 class="modal-title" id="modelHeading">Modal title</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}
@endsection

@push('footer-script')
<script>

$('#save-form').click(function () {
    $.easyAjax({
        url: '{{route('member.leads.store')}}',
        container: '#createLead',
        type: "POST",
        redirect: true,
        data: $('#createLead').serialize()
    })
});

</script>
@endpush

