@extends('layouts.app')

@section('page-title')
   <div class="col-md-12">
        <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a  href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>

@endsection
@section('content')


 <div class="container-fluid">
   <div class="row">
        <div class="col-md-3">
        @include('sections.ticket_setting_menu')
       </div>
        <div class="col-md-9">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                          <h5>@lang('app.menu.ticketAgents') </h5>
                           
                        </div>
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">



                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="white-box">
                                        <h5>@lang('app.addNew') @lang('modules.tickets.agents')</h5>

                                        {!! Form::open(['id'=>'createAgents','class'=>'ajax-form','method'=>'POST']) !!}

                                        <div class="form-body">

                                            
                                            <div class="form-label-group form-group" id="user_id">                                                
                                                <select class="form-control form-control-lg" multiple="multiple" placeholder="*" id="user" name="user_id[]"  data-placeholder="@lang('modules.tickets.chooseAgents')" >
                                                    @foreach($employees as $emp)
                                                        <option value="{{ $emp->id }}">{{ ucwords($emp->name). ' ['.$emp->email.']' }} @if($emp->id == $user->id)
                                                                (YOU) @endif</option>
                                                    @endforeach
                                                </select>
                                                <label for="user" class="control-label required">@lang('modules.tickets.chooseAgents')</label>
                                            </div>

                                            <div class="row">

                                                <div class="col-md-8">
                                                    <div class="form-label-group form-label-group form-group">
                                                        
                                                        <select class="form-control form-control-lg" name="group_id" id="group_id">
                                                            <option value="">@lang('modules.tickets.noGroupAssigned')</option>
                                                        @foreach($groups as $group)
                                                                <option value="{{ $group->id }}">{{ ucwords($group->group_name) }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="group_id" class="control-label">@lang('modules.tickets.assignGroup')</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                <a href="javascript:;" class="btn btn-primary btn-sm btn-outline" id="manage-groups"><i class="fa fa-cog"></i> @lang('modules.tickets.manageGroups')</a>

                                                </div>
                                            </div>

                                            

                                            <div class="form-actions">
                                                <div class=" text-left">
                                    <button type="submit" id="save-members" class="btn btn-primary waves-effect waves-light m-r-10">
                                      @lang('app.save')
                                    </button>
                                            </div>

                                            </div>
                                        </div>

                                        {!! Form::close() !!}

                                    </div>
                                </div>

                                <div class="col-md-12 m-t-40">
                                    <div class="white-box">
                                        <h5>@lang('modules.tickets.agents') </h5>

                                        <div class="table-responsive">
                                            <table class="table" id="agents-table">
                                                <thead>
                                                <tr>
                                                    <th>@lang('app.name')</th>
                                                    <th>@lang('modules.tickets.group')</th>
                                                    <th>@lang('app.status')</th>
                                                    <th>@lang('app.action')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($agents as $agent)
                                                    <tr>
                                                        <td>
                                                            <a href="javascript:void(0)">
                                                                {!!  '<img src="'.$agent->user->image_url.'" alt="user" class="img-circle" width="40" height="40">' !!}

                                                                {{ ucwords($agent->user->name) }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <select class="change-agent-group" data-agent-id="{{ $agent->id }}">
                                                                <option value="">No group assigned</option>
                                                                @foreach($groups as $group)
                                                                    <option
                                                                            @if($group->id == $agent->group_id) selected @endif
                                                                    value="{{ $group->id }}">{{ $group->group_name }}</option>
                                                                @endforeach

                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="change-agent-status" data-agent-id="{{ $agent->id }}">
                                                                <option @if($agent->status == 'enabled') selected @endif>enabled</option>
                                                                <option @if($agent->status == 'disabled') selected @endif>disabled</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:;" class="btn btn-outline-danger  btn-circle
                sa-params delete-agents"
                      data-toggle="tooltip" data-agent-id="{{ $agent->id }}"

                      data-original-title="Delete"><span class="icon-trash" aria-hidden="true"></span></a>

                                                        </td>
                                                    </tr>
                                                @empty
                                                    <td colspan="4" class="text-center">
                                                        <div class="empty-space" style="height: 200px;">
                                                            <div class="empty-space-inner">
                                                                <div class="icon" style="font-size:30px"><i
                                                                            class="icon-layers"></i>
                                                                </div>
                                                                <div class="title m-b-15"> @lang('messages.noAgentAdded')
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>

                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
    <!-- .row -->


    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="ticketGroupModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('modules.tickets.manageGroups')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
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



    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    //    save project members
    $('#save-members').click(function () {
        $.easyAjax({
            url: '{{route('admin.ticket-agents.store')}}',
            container: '#createAgents',
            type: "POST",
            data: $('#createAgents').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    window.location.reload();
                }
            }
        })
    });

    $('body').on('click', '.delete-agents', function () {
        var id = $(this).data('agent-id');
            swal({
                title: "Are you sure?",
                text: "This will remove the agent from the list.",
                icon: "{{ asset('img/warning.png')}}",
                buttons: ["CANCEL", "DELETE"],
                dangerMode: true
        })
        .then((willDelete) => {
                if (willDelete) {
                    var url = "{{ route('admin.ticket-agents.destroy',':id') }}";
                    url = url.replace(':id', id);
                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                window.location.reload();
                            }
                        }
                    });
                }
            });
        }); 

    $('.change-agent-status').change(function () {
        var agentId = $(this).data('agent-id');
        var status = $(this).val();
        var token = '{{ csrf_token() }}';
        var url = '{{ route("admin.ticket-agents.update", ':id') }}';
        url = url.replace(':id', agentId);

        $.easyAjax({
            type: 'PUT',
            url: url,
            data: {'_token': token, 'status': status}
        });

    })

    $('.change-agent-group').change(function () {
        var agentId = $(this).data('agent-id');
        var groupId = $(this).val();
        var token = '{{ csrf_token() }}';
        var url = '{{ route("admin.ticket-agents.update-group", ':id') }}';
        url = url.replace(':id', agentId);

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token, 'groupId': groupId}
        });

    })

    $('#manage-groups').click(function () {
        var url = '{{ route("admin.ticket-groups.create")}}';
        $('#modelHeading').html("{{  __('modules.tickets.manageGroups') }}");
        $.ajaxModal('#ticketGroupModal', url);
    })


</script>


@endpush

