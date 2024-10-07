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
                          <h5>@lang('app.menu.leadStatus')</h5>
                           
                        </div>
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">


     

                    <div class="row">

                        <div class="col-md-12">
                            <div class="white-box">
                                <h5>@lang('app.addNew') @lang('modules.lead.leadStatus')</h5>

                                {!! Form::open(['id'=>'createTypes','class'=>'ajax-form','method'=>'POST']) !!}

                                <div class="form-body">

                                    <div class="form-label-group form-group">                           
                                        <input type="text" class="form-control form-control-lg" name="type" id="type" placeholder="-">
                                        <label for="type" class="control-label required">@lang('modules.lead.leadStatus')</label>
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
                                <h5>@lang('app.menu.leadStatus')</h5>


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
                                        @forelse($leadStatus as $key=>$status)
                                            <tr>
                                                <td>{{ ($key+1) }}</td>
                                                <td>{{ ucwords($status->type) }}</td>
                                                <td>
                                                    <a href="javascript:;" data-type-id="{{ $status->id }}"
                                                        class="btn btn-sm btn-info btn-rounded btn-outline edit-type"><i
                                                                class="fa fa-edit"></i> @lang('app.edit')</a>
                                                    <a href="javascript:;" data-type-id="{{ $status->id }}"
                                                        class="btn btn-sm btn-danger btn-rounded btn-outline delete-type"><i
                                                                class="fa fa-times"></i> @lang('app.remove')</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td>
                                                    @lang('messages.noLeadStatusAdded')
                                                </td>
                                            </tr>
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


    //    save project members
    $('#save-type').click(function () {
        $.easyAjax({
            url: '{{route('admin.lead-status-settings.store')}}',
            container: '#createTypes',
            type: "POST",
            data: $('#createTypes').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    $('#createTypes')[0].reset();
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

                var url = "{{ route('admin.lead-status-settings.destroy',':id') }}";
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


    $('.edit-type').click(function () {
        var typeId = $(this).data('type-id');
        var url = '{{ route("admin.lead-status-settings.edit", ":id")}}';
        url = url.replace(':id', typeId);

        $('#modelHeading').html("{{  __('app.edit')." ".__('modules.lead.leadStatus') }}");
        $.ajaxModal('#leadStatusModal', url);
    })


</script>


@endpush

