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
        @include('sections.lead_setting_menu')
       </div>
        <div class="col-md-9">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                          <h5>@lang('modules.lead.leadAgent')</h5>
                           
                        </div>
                     <div  class="card-body">
             
                        <div class="form-body" >
                         <div class="vtabs customvtab m-t-10">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="white-box">
                                        <h5>@lang('app.addNew') @lang('modules.lead.leadAgent')</h5>

                                        {!! Form::open(['id'=>'createTypes','class'=>'ajax-form','method'=>'POST']) !!}

                                        <div class="form-body">

                                            <div class="form-label-group form-group" id="user_id">
                                                <select class="form-control form-control-lg" multiple="multiple" placeholder="*" id="user" name="user_id[]"  >
                                                    @foreach($employees as $emp)
                                                        <option value="{{ $emp->id }}">{{ ucwords($emp->name). ' ['.$emp->email.']' }} @if($emp->id == $user->id)
                                                                (YOU) @endif</option>
                                                    @endforeach
                                                </select>
                                                <label for="user" class="control-label required">@lang('modules.tickets.chooseAgents')</label>
                                            </div>

                                            <div class="form-actions">
                                                <button type="submit" id="save-type" class="btn btn-primary"> @lang('app.save')
                                                </button>
                                            </div>
                                        </div>

                                        {!! Form::close() !!}

                                    </div>
                                </div>

                                <div class="col-md-12 m-t-40">
                                    <div class="white-box">
                                        <h5>@lang('modules.lead.leadAgent')</h5>


                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('app.name')</th>
                                                    <th>@lang('app.action')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($leadAgents as $key=>$agents)
                                                    <tr id="leadAgent_{{ $agents->id }}">
                                                        <td width="10%">{{ ($key+1) }}</td>
                                                        <td width="60%">{{ ucwords($agents->user->name) }}</td>
                                                        <td width="30%">
                                                            <a href="javascript:;" data-type-id="{{ $agents->id }}"
                                                                class="btn btn-sm btn-danger btn-rounded btn-outline delete-type"><i
                                                                        class="fa fa-times"></i> @lang('app.remove')</a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <td colspan="3" class="text-center">
                                                        <div class="empty-space" style="height: 200px;">
                                                            <div class="empty-space-inner">
                                                                <div class="icon" style="font-size:30px"><i
                                                                            class="icon-layers"></i>
                                                                </div>
                                                                <div class="title m-b-15"> @lang('messages.noLeadAgentAdded')
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
                         </div>
                        </div>
                     </div>


    </div>
    <!-- .row -->


    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="leadStatusModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
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
    $('#save-type').click(function () {
        $.easyAjax({
            url: '{{route('admin.lead-agent-settings.store')}}',
            container: '#createTypes',
            type: "POST",
            data: $('#createTypes').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    window.location.reload();
                }
            }
        })
    });


    $('body').on('click', '.delete-type', function () {
        var id = $(this).data('type-id');
        swal({

            title: "Are you sure?",
            text: "This will remove the lead status from the list.",
            icon: "{{ asset('img/warning.png')}}",
            buttons: ["CANCEL", "DELETE"],
            dangerMode: true
        })
        .then((willDelete) => {
            if (willDelete) {

                var url = "{{ route('admin.lead-agent-settings.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") 
                        {
                            $.unblockUI();
                            $('#leadAgent_'+id).fadeOut();
                        }
                    }
                });
            }
        });
    });    

</script>


@endpush

