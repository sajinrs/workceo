@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/datatable-extension.css')}}">
@endpush
 
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                    <h4 class="m-b-0" style="color: #1d61d2;"><i class="{{ $pageIcon }}"></i>  <span class="font-bold">{{  ucwords($lead->company_name) }}</span></h4>
                        <!--<h3><i class="{{ $pageIcon }}"></i> <span
                                    class="font-bold">{{ ucwords($lead->company_name) }}</span></h3>-->
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.leads.index') }}">{{ __($pageTitle) }}</a></li>
                            {{--<li class="breadcrumb-item active">@lang('modules.lead.proposal')</li>--}}
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
                    <ul class="showProjectTabs nav nav-tabs border-tab mb-0" id="top-tab" role="tablist">
                        <li  class="nav-item"><a class="nav-link" href="{{ route('admin.leads.show', $lead->id) }}"><span>@lang('modules.lead.profile')</span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link active" href="{{ route('admin.proposals.show', $lead->id) }}"><span>@lang('modules.lead.proposal')</span></a></li>
                        <li  class="nav-item"><a class="nav-link" href="{{ route('admin.lead-files.show', $lead->id) }}"><span>@lang('modules.lead.file')</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.leads.followup', $lead->id) }}"><span>@lang('modules.lead.followUp')</span></a></li>
                        @if($gdpr->enable_gdpr)
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.leads.gdpr', $lead->id) }}"><span>GDPR</span></a></li>
                        @endif
                    </ul>
                </div>
                </div>
        </div>
            <div class="col-sm-12 p-0">
                <div class="tab-content" id="top-tabContent">
                    <div class="tab-pane fade active show" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                            <h4 class="col-6  p-l-20">@lang('modules.proposal.title')</h4>
                            <span class="col-6 text-right p-r-20"><a href="{{ route('admin.proposals.create') }}/{{$lead->id}}" class="btn btn-primary btn-sm">@lang('modules.proposal.createTitle') <i class="fa fa-plus" aria-hidden="true"></i></a></span>
                        </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="proposal-table">
                                    <thead>
                                    <tr>
                                        <th>@lang('app.id')</th>
                                        <th>@lang('app.lead')</th>
                                        <th>@lang('modules.invoices.total')</th>
                                        <th>@lang('modules.estimates.validTill')</th>
                                        <th>@lang('app.status')</th>
                                        <th>@lang('app.action')</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>



@endsection

@push('footer-script')
<script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/custom.js')}}"></script>    
<script>

    $("body").tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    var table = $('#proposal-table').dataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: '{!! route('admin.proposals.data', [$lead->id]) !!}',
        "order": [[ 0, "desc" ]],
        language: {
            "url": "<?php echo __("app.datatable") ?>"
        },
        "fnDrawCallback": function( oSettings ) {
            $("body").tooltip({
                selector: '[data-toggle="tooltip"]'
            });
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'company_name', name: 'company_name' },
            { data: 'total', name: 'total' },
            { data: 'valid_till', name: 'valid_till' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', width: '5%' }
        ]
    });

    $('body').on('click', '.sa-params', function(){
        var id = $(this).data('proposal-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted proposal!",
            icon: "warning",
            buttons: ["No, cancel please!", "Yes, delete it!"],
            dangerMode: true
        }).then((willDelete) => {
            if (willDelete) {

                var url = "{{ route('admin.proposals.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            table._fnDraw();
                        }
                    }
                });
            }
        });
    });

</script>
@endpush