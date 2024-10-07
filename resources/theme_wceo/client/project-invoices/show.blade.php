@extends('layouts.client-app')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} #{{ $project->id }} - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('client.dashboard.index') }}">@lang('app.menu.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('client.projects.index') }}">{{ __($pageTitle) }}</a></li>
                            <li class="breadcrumb-item active">@lang('app.menu.invoices')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        @include('client.projects.show_project_menu')

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="pull-left">@lang('app.menu.invoices')</h5>
                    </div>
                    <div class="card-body" id="invoices-list-panel">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice #</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="timer-list">
                                @forelse($project->invoices as $key=>$invoice)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->currency_symbol }}{{ currencyFormat($invoice->total) }}</td>
                                        <td>
                                            @if ($invoice->credit_note)
                                                <label class="badge badge-warning">
                                                    {{ strtoupper(__('app.credit-note')) }}
                                                </label>
                                            @else
                                                @if ($invoice->status == 'unpaid')
                                                    <label class="badge badge-danger">
                                                        {{ strtoupper($invoice->status) }}
                                                    </label>
                                                @elseif ($invoice->status == 'paid')
                                                    <label class="badge badge-success">
                                                        {{ strtoupper($invoice->status) }}
                                                    </label>
                                                @elseif ($invoice->status == 'canceled')
                                                    <label class="badge badge-danger">
                                                        {{ strtoupper($invoice->status) }}
                                                    </label>
                                                @else
                                                    <label class="badge badge-info">
                                                        {{ strtoupper(__('modules.invoices.partial')) }}
                                                    </label>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $invoice->issue_date->format('d M, y') }}</td>
                                        <td>
                                            <a href="{{ route('client.invoices.download', $invoice->id) }}" data-toggle="tooltip" data-original-title="Download" class="btn-md btn-default btn-circle m-l-10"><i class="fa fa-download"></i></a>
                                        </td>
                                                                                    
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"> @lang('messages.noInvoice')</td>
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



@endsection
@push('footer-script')


<script>
    $('#show-invoice-modal').click(function(){
        var url = '{{ route('admin.invoices.createInvoice', $project->id)}}';
        $('#modelHeading').html('Add Invoice');
        $.ajaxModal('#add-invoice-modal',url);
    })

    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('invoice-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted invoice!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.invoices.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                            $('#invoices-list-panel ul.list-group').html(response.html);

                        }
                    }
                });
            }
        });
    });
    $('ul.showProjectTabs .projectInvoices .nav-link').addClass('active');
</script>
@endpush
