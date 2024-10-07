@forelse($project->invoices as $invoice)
    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-5 col-xs-12">
                {{ $invoice->invoice_number }}
            </div>
            <div class="col-sm-2">
                {{ $invoice->currency->currency_symbol }} {{ $invoice->total }}
            </div>
            <div class="col-sm-2 col-xs-12">
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
            </div>
            <div class="col-sm-3 col-xs-12">
                <span class="">{{ $invoice->issue_date->format($global->date_format) }}</span>
                <a href="{{ route('admin.invoices.download', $invoice->id) }}" data-toggle="tooltip" data-original-title="Download" class="btn btn-default btn-circle m-l-10"><i class="fa fa-download"></i></a>
            </div>
        </div>
    </li>
@empty
    <li class="list-group-item">
        <div class="row">
            <div class="col-md-7">
                @lang('messages.noInvoice')
            </div>
        </div>
    </li>
@endforelse
