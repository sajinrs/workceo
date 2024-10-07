@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.leads.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.edit')</li>
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
                    {!! Form::open(['id'=>'updateLead','class'=>'ajax-form','method'=>'PUT']) !!}
                    <div class="card-header">
                        <h4 class="card-title mb-0">@lang('modules.lead.updateTitle')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                        <h5 class="mb-4">@lang('modules.lead.leadInfo')</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <input placeholder="-" type="text" id="company_name" name="company_name" class="form-control-lg form-control"  value="{{ $lead->company_name ?? '' }}">
                                    <label for="company_name" class="required control-label">@lang('modules.lead.companyName')</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <input placeholder="-" type="email" name="client_email" id="client_email" class="form-control-lg form-control" value="{{ $lead->client_email }}">
                                    <label for="client_email" class="required">@lang('modules.lead.clientEmail')</label>
                                    <span class="help-block">@lang('modules.lead.emailNote')</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-label-group form-group">
                                    <input placeholder="-" type="text" name="client_name" id="client_name" class="form-control-lg form-control" value="{{ $lead->client_first_name }}">
                                    <label for="client_name" class="required">@lang('modules.lead.firstName')</label>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-label-group form-group">
                                    <input placeholder="-" type="text" name="client_last_name" id="client_last_name" class="form-control-lg form-control" value="{{ $lead->client_last_name }}">
                                    <label for="client_last_name" class="required">@lang('modules.lead.lastName')</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <input placeholder="-" type="text" id="website" name="website" class="form-control-lg form-control" value="{{ $lead->website ?? '' }}" >
                                    <label for="website" class="control-label">@lang('modules.lead.website')</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <input placeholder="-" type="tel" name="mobile" id="mobile" value="{{ $lead->mobile }}" class="form-control-lg form-control">
                                    <label for="mobile">@lang('modules.lead.mobile')</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-label-group form-group">
                                    <textarea placeholder="-" name="address" id="address" class="form-control-lg form-control" rows="5">{{ $lead->address }}</textarea>
                                    <label for="address" class="required">@lang('app.address')</label>
                                </div>
                            </div>
                        </div>
                        
                        <!--/row-->
                        <hr class="mt-4 mb-4">
                        <h5 class="mb-4">@lang('modules.lead.leadDetails')</h5>

                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-label-group form-group">
                                     <select placeholder="-" class="select2 form-control-lg form-control" data-placeholder="@lang('modules.tickets.chooseAgents')" id="agent_id" name="agent_id">
                                        <option value="">@lang('modules.tickets.chooseAgents')</option>
                                        @foreach($leadAgents as $emp)
                                            <option  @if($emp->id == $lead->agent_id) selected @endif  value="{{ $emp->id }}">{{ ucwords($emp->user->name) }} @if($emp->user->id == $user->id)
                                                    (YOU) @endif</option>
                                        @endforeach
                                    </select>
                                    <label for="agent_id">@lang('app.leadAgent')(@lang('modules.tickets.agent')) </label>
                                </div>
                            </div>
                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <a href="javascript:;" id="addLeadAgent" class="btn btn-sm btn-secondary form-control"><i class="fa fa-plus"></i> @lang('app.add') @lang('app.leadAgent')</a>
                                </div>
                            </div>
                        </div>

                            <div class="row">
                                <!--/span-->
                                <div class="col-md-6">
                                <input type="hidden" name="next_follow_up" value="yes"> 
                                    {{--<div class="col-md-12 p-0">
                                        <div class="form-label-group form-group">
                                            <select placeholder="-" name="next_follow_up" id="next_follow_up" class="select2 form-control-lg form-control">
                                                <option @if($lead->next_follow_up == 'yes') selected
                                                        @endif value="yes"> @lang('app.yes')</option>
                                                <option @if($lead->next_follow_up == 'no') selected
                                                        @endif value="no"> @lang('app.no')</option>
                                            </select>
                                            <label for="next_follow_up">@lang('app.next_follow_up')</label>
                                        </div>
                                    </div>--}}
                                    <div class="col-md-12 p-0">
                                        <div class="form-label-group form-group">
                                            <select placeholder="-" name="status" id="status" class="hide-search form-control-lg form-control">
                                                @forelse($status as $sts)
                                                    <option @if($lead->status_id == $sts->id) selected
                                                            @endif value="{{ $sts->id }}"> {{ ucfirst($sts->type) }}</option>
                                                @empty

                                                @endforelse
                                            </select>
                                            <label for="status">@lang('app.status')</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 p-0">
                                        <div class="form-label-group form-group">
                                            <select placeholder="-" name="source" id="source" class="hide-search form-control-lg form-control">
                                                @forelse($sources as $source)
                                                    <option @if($lead->source_id == $source->id) selected
                                                            @endif value="{{ $source->id }}"> {{ ucfirst($source->type) }}</option>
                                                @empty

                                                @endforelse
                                            </select>
                                            <label for="source">@lang('app.source')</label>
                                        </div>
                                    </div>

                                    

                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group form-group">
                                        <textarea placeholder="-" name="note" id="note" class="form-control-lg form-control" rows="4">{{ $lead->note ?? '' }}</textarea>
                                        <label for="note">@lang('app.note')</label>
                                    </div>
                                </div>

                                <!--/span-->
                            </div>

                        <!--/row-->

                        <div class="row">

                        </div>

                    </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="form-actions row">
                            <div class="col-md-3 offset-md-6">
                                <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-primary gray form-control">@lang('app.cancel')</a>
                                
                            </div>
                            <div class="col-md-3">
                                <button type="submit" id="save-form" class="btn btn-primary form-control">@lang('app.save')</button>
                            </div>
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
                    <button type="button" class="btn  btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}
@endsection

@push('footer-script')
<script type="text/javascript">
    $('#updateLead').on('click', '#addLeadAgent', function () {
        var url = '{{ route('admin.lead-agent-settings.create')}}';
        $('#modelHeading').html('Manage Lead Agent');
        $.ajaxModal('#projectCategoryModal', url);
    })

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.leads.update', [$lead->id])}}',
            container: '#updateLead',
            type: "POST",
            redirect: true,
            data: $('#updateLead').serialize()
        })
    });
</script>
@endpush
