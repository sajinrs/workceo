<?php

namespace App\Http\Controllers\Admin;

use App\CreditNotes;
use App\Currency;
use App\Helper\Reply;
use App\Http\Requests\Invoices\StoreInvoice;
use App\Invoice;
use App\InvoiceItems;
use App\InvoiceSetting;
use App\Notifications\NewInvoice;
use App\Project;
use App\Tax;
use App\User;
use App\Company;
use App\OfflineInvoicePayment;
use App\OfflinePaymentMethod;
use App\ClientPayment;
use App\Product;
use App\ProductCategory;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManageInvoicesController extends AdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'Job';
        $this->pageIcon = 'fas fa-list-alt';
        $this->middleware(function ($request, $next) {
            if (!in_array('invoices',$this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->project = Project::findOrFail();
        $this->currencies = Currency::all();
        $this->lastInvoice = Invoice::count()+1;
        $this->invoiceSetting = InvoiceSetting::first();
        $this->taxes = Tax::all();
        $this->zero = '';
        if (strlen($this->lastInvoice) < $this->invoiceSetting->invoice_digit){
            for ($i=0; $i<$this->invoiceSetting->invoice_digit-strlen($this->lastInvoice); $i++){
                $this->zero = '0'.$this->zero;
            }
        }

        return view('admin.projects.invoices.create', $this->data);
    }

    public function createInvoice(Request $request)
    {
        $this->project = Project::findOrFail($request->id);
        $this->currencies = Currency::all();
        $this->lastInvoice = Invoice::count()+1;
        $this->invoiceSetting = InvoiceSetting::first();
        $this->taxes = Tax::all();
        $this->categories = ProductCategory::all();
        $this->zero = '';
        if (strlen($this->lastInvoice) < $this->invoiceSetting->invoice_digit){
            for ($i=0; $i<$this->invoiceSetting->invoice_digit-strlen($this->lastInvoice); $i++){
                $this->zero = '0'.$this->zero;
            }
        }
        return view('admin.projects.invoices.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoice $request)
    {
        $items = $request->input('item_name');
        $itemsSummary = $request->input('item_summary');
        $cost_per_item = $request->input('cost_per_item');
        $quantity = $request->input('quantity');
        $amount = $request->input('amount');
        $tax = $request->input('taxes');

        foreach ($quantity as $qty) {
            if (!is_numeric($qty) && (intval($qty) < 1)) {
                return Reply::error(__('messages.quantityNumber'));
            }
        }

        foreach ($cost_per_item as $rate) {
            if (!is_numeric($rate)) {
                return Reply::error(__('messages.unitPriceNumber'));
            }
        }

        foreach ($amount as $amt) {
            if (!is_numeric($amt)) {
                return Reply::error(__('messages.amountNumber'));
            }
        }

        foreach ($items as $itm) {
            if (is_null($itm)) {
                return Reply::error(__('messages.itemBlank'));
            }
        }

        $invoice = new Invoice();
        $invoice->project_id = $request->project_id ?? null;
        $invoice->invoice_number = Invoice::count() + 1;
        $invoice->issue_date = Carbon::createFromFormat($this->global->date_format, $request->issue_date)->format('Y-m-d');
        $invoice->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
        $invoice->sub_total = $request->sub_total;
        $invoice->total = $request->total;
        $invoice->discount = round($request->discount_value, 2);
        $invoice->discount_type = $request->discount_type;
        $invoice->total = round($request->total, 2);
        $invoice->currency_id = $request->currency_id;
        $invoice->note = $request->note;
        $invoice->save();

        foreach ($items as $key => $item) :
            if (!is_null($item)) {
                InvoiceItems::create(
                    [
                        'invoice_id' => $invoice->id,
                        'item_name' => $item,
                        'item_summary' => $itemsSummary[$key] ? $itemsSummary[$key] : '',
                        'type' => 'item',
                        'quantity' => $quantity[$key],
                        'unit_price' => round($cost_per_item[$key], 2),
                        'amount' => round($amount[$key], 2),
                        'taxes' => $tax ? array_key_exists($key, $tax) ? json_encode($tax[$key]) : null : null
                    ]
                );
            }
        endforeach;

        $this->logSearchEntry($invoice->id, 'Invoice #'.$invoice->invoice_number, 'admin.all-invoices.show', 'invoice');

        $this->project = Project::findOrFail($request->project_id);
        $view = view('admin.projects.invoices.invoice-ajax', $this->data)->render();
        return Reply::successWithData(__('messages.invoiceCreated'), ['html' => $view]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->project = Project::findOrFail($id);
        return view('admin.projects.invoices.show', $this->data);
    }

    public function data($id)
    {
       
        $project = Project::findOrFail($id);
        $invoices = $project->invoices;       
        return DataTables::of($invoices)
            ->addColumn('action', function($row){
               
            return '<a href="'.route('admin.invoices.download', $row->id).'" data-toggle="tooltip" data-original-title="Download" class="btn-md btn-default btn-circle m-l-10"><i class="fa fa-download"></i></a>';

            })
            ->editColumn('invoice_number', function ($row) {
                return $row->invoice_number;
            })
            ->editColumn('total', function ($row) {
                return $row->currency->currency_symbol.currencyFormat($row->total);
            })
            ->editColumn('status', function($row){
                if ($row->credit_note) {
                        return '<label class="badge badge-warning">'.strtoupper(__('app.credit-note')).'</label>';
                } else {
                    if ($row->status == 'unpaid')
                        return '<label class="badge badge-danger">'.strtoupper($row->status).'</label>';
                    elseif ($row->status == 'paid')
                        return '<label class="badge badge-success">'.strtoupper($row->status).'</label>';
                    elseif ($row->status == 'canceled')
                        return '<label class="badge badge-danger">'.strtoupper($row->status).'</label>';
                    else
                        return '<label class="badge badge-info">'.strtoupper(__('modules.invoices.partial')).'</label>';
                 
                }
                
            })          

            ->editColumn('issue_date', function($row) {
                return $row->issue_date->format($this->global->date_format);
            })
            ->rawColumns(['status', 'action'])
            ->removeColumn('project_id')
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        Invoice::destroy($id);
        $this->project = Project::findOrFail($invoice->project_id);
        $view = view('admin.projects.invoices.invoice-ajax', $this->data)->render();
        return Reply::successWithData(__('messages.invoiceDeleted'), ['html' => $view]);
    }

    public function download($id) {
        //header('Content-type: application/pdf');

        $this->invoice = Invoice::findOrFail($id);
        $this->paidAmount = $this->invoice->getPaidAmount();
        $this->creditNote = 0;
        if ($this->invoice->credit_note){
            $this->creditNote = CreditNotes::where('invoice_id', $id)
                ->select('cn_number')
                ->first();
        }

        // Download file uploaded
        if($this->invoice->file != null){
            return response()->download(storage_path('app/public/invoice-files').'/'.$this->invoice->file);
        }

        if($this->invoice->discount > 0){
            if($this->invoice->discount_type == 'percent'){
                $this->discount = (($this->invoice->discount/100)*$this->invoice->sub_total);
            }
            else{
                $this->discount = $this->invoice->discount;
            }
        }
        else{
            $this->discount = 0;
        }

        $taxList = array();

        $items = InvoiceItems::whereNotNull('taxes')
            ->where('invoice_id', $this->invoice->id)
            ->get();

        foreach ($items as $item) {
            foreach (json_decode($item->taxes) as $tax){
                $this->tax = InvoiceItems::taxbyid($tax)->first();
                if ($this->tax){
                    if (!isset($taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'])) {
                        $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = ($this->tax->rate_percent / 100) * $item->amount;
                    } else {
                        $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] + (($this->tax->rate_percent / 100) * $item->amount);
                    }
                }
            }
        }

        $this->taxes = $taxList;

        $this->settings = $this->company;

        $this->invoiceSetting = InvoiceSetting::first();
        //        return view('invoices.'.$this->invoiceSetting->template, $this->data);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('invoices.'.$this->invoiceSetting->template, $this->data);
        $filename = $this->invoice->invoice_number;
        //       return $pdf->stream();
        return $pdf->download($filename . '.pdf');
    }

    public function offlinePayment($id)
    {
        $company = Company::findOrFail(company()->id);
        $this->company_name = $company->company_name;

        $this->invoice = Invoice::findOrFail($id);
        $this->project = Project::findOrFail($this->invoice->project_id);
        $this->methods = OfflinePaymentMethod::activeMethod();

        return view('admin.invoices.offline-payment', $this->data);
    }

    public function offlinePaymentSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'description' => 'required'
        ]);

        $checkAlreadyRequest = Invoice::with(['offline_invoice_payment' => function($q) {
            $q->where('status', 'pending');
        }])->where('id', $request->invoice_id)->first();

        if($checkAlreadyRequest->offline_invoice_payment->count() > 0 ) {
            return Reply::error('You have already raised a request.');
        }

        
        $checkAlreadyRequest->status = 'paid';
        $checkAlreadyRequest->save();

        // create offline payment request
        $offlinePayment                    = new OfflineInvoicePayment();
        $offlinePayment->invoice_id        = $checkAlreadyRequest->id;
        $offlinePayment->client_id         = $request->client_id;
        $offlinePayment->payment_method_id = $request->offline_id;
        $offlinePayment->email             = $request->email;
        $offlinePayment->description       = $request->description;        

        if ($request->hasFile('offline-payment-files')) {
            $offlinePayment->slip = Files::upload($request->slip, 'offline-payment-files');
        }

        //$offlinePayment->slip = $request->slip->hashName();
        $offlinePayment->save();

        $clientPayment = new ClientPayment();
        $clientPayment->currency_id = $checkAlreadyRequest->currency_id;
        $clientPayment->company_id = $checkAlreadyRequest->company_id;
        $clientPayment->invoice_id = $checkAlreadyRequest->id;
        $clientPayment->project_id = $checkAlreadyRequest->project_id;
        $clientPayment->amount = $checkAlreadyRequest->total;
        $clientPayment->offline_method_id = $request->offline_id;
        $clientPayment->transaction_id = Carbon::now()->timestamp;
        $clientPayment->gateway = 'Offline';
        $clientPayment->status = 'complete';
        $clientPayment->paid_on = Carbon::now();
        $clientPayment->user_id  = $this->user->id;
        $clientPayment->added_by = 'admin';
        $clientPayment->save();

        return Reply::redirect(route('admin.projects.show', $checkAlreadyRequest->project_id));
    }
}
