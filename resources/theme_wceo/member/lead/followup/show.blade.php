@extends('layouts.app')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/date-picker.css') }}">
@push('head-script')
    
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} #{{ $lead->id }} - <span class="font-bold">{{ ucwords($lead->company_name) }}</span></h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('member.leads.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('modules.lead.followUp')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="row product-page-main">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs border-tab mb-0" id="top-tab" role="tablist">
                        <li  class="nav-item"><a class="nav-link" href="{{ route('member.leads.show', $lead->id) }}"><span>@lang('modules.lead.profile')</span></a>
                        </li>
                        @if($user->can('edit_lead'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('member.proposals.show', $lead->id) }}"><span>@lang('modules.lead.proposal')</span></a></li>
                        <li  class="nav-item"><a class="nav-link" href="{{ route('member.lead-files.show', $lead->id) }}"><span>@lang('modules.lead.file')</span></a></li>
                        <li class="nav-item"><a class="nav-link active" href="{{ route('member.leads.followup', $lead->id) }}"><span>@lang('modules.lead.followUp')</span></a></li>
                        @endif
                    </ul>
                </div>
                <div class="col-sm-12" id="follow-list-panel">
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade active show" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                            <div class="card-header">
                                <h5>@lang('app.followUp')</h5>
                            </div>
                            <div class="card-body">
                                <div class="row m-b-10">
                                    <div class="col-md-5">
                                        @if($lead->next_follow_up == 'yes')
                                            <a href="javascript:;" id="show-new-follow-panel"
                                               class="btn btn-primary btn-outline"><i class="fa fa-plus"></i> @lang('modules.followup.newFollowUp')</a>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-4">
                                        <select class="select2 form-control"  id="sort-task" data-lead-id="{{ $lead->id }}">
                                            <option value="id">@lang('modules.tasks.lastCreated')</option>
                                            <option value="next_follow_up_date">@lang('modules.tasks.dueSoon')</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="list-group vertical-scroll" id="listGroup">
                                    @forelse($lead->follow as $follow)
                                        <a href="javascript:;" data-follow-id="{{ $follow->id }}" class="list-group-item edit-task">
                                            <h4 class="list-group-item-heading sbold">@lang('app.createdOn'): {{ $follow->created_at->format($global->date_format) }}</h4>
                                            <p class="list-group-item-text">
                                            <div class="row margin-top-5">
                                                <div class="col-md-12">
                                                    @lang('app.remark'): <br>
                                                    {!!  ($follow->remark != '') ? ucfirst($follow->remark) : "<span class='font-red'>Empty</span>" !!}
                                                </div>
                                            </div>
                                            <div class="row margin-top-5">
                                                <div class="col-md-6">
                                                </div>
                                                <div class="col-md-6">
                                                    @lang('app.next_follow_up'): {{ $follow->next_follow_up_date->format($global->date_format) }}
                                                </div>
                                            </div>
                                            </p>
                                        </a>
                                    @empty
                                        <a href="javascript:;" class="list-group-item">
                                            <h4 class="list-group-item-heading sbold">@lang('modules.followup.followUpNotFound')</h4>
                                        </a>
                                    @endforelse
                                </div>
                                <span class="help-block"><b>@lang('app.notice'): </b>  @lang('modules.followup.followUpNote')</span>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-4 d-none" id="new-follow-panel">
                    <div class="">
                        <div class="card-header">
                            <h5><i class="fa fa-plus"></i> @lang('modules.followup.newFollowUp')     <a class="pull-right close" href="javascript:;" id="hide-new-follow-panel"><i class="fa fa-times"></i></a>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="panel-body">
                                {!! Form::open(['id'=>'createFollow','class'=>'ajax-form','method'=>'POST']) !!}

                                {!! Form::hidden('lead_id', $lead->id) !!}

                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">@lang('app.next_follow_up')</label>
                                                <input type="text" name="next_follow_up_date" autocomplete="off" id="next_follow_up_date"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <!--/span-->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">@lang('app.description')</label>
                                                <textarea id="remark" name="remark"
                                                          class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->

                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="save-task" class="btn btn-primary"> @lang('app.save')
                                    </button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 d-none" id="edit-follow-panel">
                </div>

            </div>
        </div>
    </div>


@endsection

@push('footer-script')
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('themes/wceo/assets/js/scrollable/perfect-scrollbar.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/scrollable/scrollable-custom.js')}}"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script type="text/javascript">
    var newTaskpanel = $('#new-follow-panel');
    var taskListPanel = $('#follow-list-panel');
    var editTaskPanel = $('#edit-follow-panel');

    //    save new task
    $('#save-task').click(function () {
        $.easyAjax({
            url: '{{route('admin.leads.follow-up-store')}}',
            container: '#createFollow',
            type: "POST",
            data: $('#createFollow').serialize(),
            formReset: true,
            success: function (data) {
                $('#listGroup').html(data.html);
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            }
        })
    });
    @if($lead->next_follow_up == 'yes')
        //    save new task
        taskListPanel.on('click', '.edit-task', function () {
            var id = $(this).data('follow-id');
            var url = "{{route('admin.leads.follow-up-edit', ':id')}}";
            url = url.replace(':id', id);

            $.easyAjax({
                url: url,
                type: "GET",
                data: {taskId: id},
                success: function (data) {
                    editTaskPanel.html(data.html);
                    taskListPanel.switchClass("col-md-12", "col-md-8", 1000, "easeInOutQuad");
                    newTaskpanel.addClass('d-none').removeClass('d-block');
                    editTaskPanel.switchClass("d-none", "d-block", 300, "easeInOutQuad");
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                }
            })
        });

    @endif

    //    save new task
    $('#sort-task, #hide-completed-tasks').change(function() {
        var sortBy = $('#sort-task').val();
        var id = $('#sort-task').data('lead-id');

        var url = "{{route('admin.leads.follow-up-sort')}}";
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            url: url,
            type: "GET",
            data: {'_token': token, leadId: id, sortBy: sortBy},
            success: function (data) {
                $('#listGroup').html(data.html);
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            }
        })
    });

    $('#show-new-follow-panel').click(function () {
//    taskListPanel.switchClass('col-md-12', 'col-md-8', 1000, 'easeInOutQuad');
        taskListPanel.switchClass("col-md-12", "col-md-8", 1000, "easeInOutQuad");
        editTaskPanel.addClass('d-none').removeClass('d-block');
        newTaskpanel.switchClass("d-none", "d-block", 300, "easeInOutQuad");


    });

    $('#hide-new-follow-panel').click(function () {
        newTaskpanel.addClass('d-none').removeClass('d-block');
        taskListPanel.switchClass("col-md-8", "col-md-12", 1000, "easeInOutQuad");

    });

    editTaskPanel.on('click', '#hide-edit-follow-panel', function () {
        editTaskPanel.addClass('d-none').removeClass('d-block');
        taskListPanel.switchClass("col-md-8", "col-md-12", 1000, "easeInOutQuad");
    });

    jQuery('#next_follow_up_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        dateFormat: '{{ $global->date_picker_format }}',
        language: 'en'
    });

</script>
@endpush