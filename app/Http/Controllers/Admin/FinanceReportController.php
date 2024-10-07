<?php

namespace App\Http\Controllers\Admin;

use App\Currency;
use App\DataTables\Admin\PaymentsReportDataTable;
use App\Helper\Reply;
use App\Payment;
use App\Project;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceReportController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.reports';
        $this->pageIcon = 'icofont icofont-chart-histogram';

        $this->middleware(function ($request, $next) {
            if (!in_array('reports', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(PaymentsReportDataTable $dataTable) {

        $graphData = [];
        $this->currencies = Currency::all();
        $this->currentCurrencyId = $this->global->currency_id;

        $this->fromDate = Carbon::today()->subDays(180);
        $this->toDate = Carbon::today();
        $incomes = [];
        $invoices = Payment::join('currencies', 'currencies.id', '=', 'payments.currency_id')
            //->where(DB::raw('DATE(`paid_on`)'), '>=', $this->fromDate)
            //->where(DB::raw('DATE(`paid_on`)'), '<=', $this->toDate)
            ->whereMonth(DB::raw('DATE(payments.`paid_on`)'), Carbon::now()->month)
            ->where('payments.status', 'complete')
            // ->groupBy('year', 'month')
            ->orderBy('paid_on', 'ASC')
            ->get([
                DB::raw('DATE_FORMAT(paid_on,"%M/%y") as date'),
                DB::raw('YEAR(paid_on) year, MONTH(paid_on) month'),
                DB::raw('amount as total'),
                'currencies.id as currency_id',
                'currencies.exchange_rate'
            ]);


        foreach ($invoices as $invoice) {
            if (!isset($incomes[$invoice->date])) {
                $incomes[$invoice->date] = 0;
            }

            if ($invoice->currency_id != $this->global->currency->id) {
                $incomes[$invoice->date] += floor($invoice->total / $invoice->exchange_rate);
            } else {
                $incomes[$invoice->date] += round($invoice->total, 2);
            }
        }

        $dates = array_keys($incomes);

        foreach ($dates as $date) {
            $graphData[] = [
                'date' =>  $date,
                'total' =>  isset($incomes[$date]) ? round($incomes[$date], 2) : 0,
            ];
        }

        usort($graphData, function ($a, $b) {
            $t1 = strtotime($a['date']);
            $t2 = strtotime($b['date']);
            return $t1 - $t2;
        });

        $this->chartData = json_encode($graphData);
        $this->projects = Project::all();
        $this->clients = User::allClients();
        // return view('admin.reports.finance.index', $this->data);
        //return $dataTable->render('admin.reports.finance.index', $this->data);

        return $dataTable->render('admin.reports.financial.revenue', $this->data);
        //$view = view('admin.reports.revenue', $this->data)->render();
        //return Reply::dataOnly(['status' => 'success', 'view' => $view]);
        
    }

    public function store(Request $request) {
        $this->currentCurrencyId = $request->currencyId;   

        $incomes = [];
        $graphData = [];
        $invoices = Payment::join('currencies', 'currencies.id', '=', 'payments.currency_id')
            ->leftJoin('projects', 'projects.id', '=', 'payments.project_id')           
            ->where('payments.status', 'complete')
            // ->groupBy('year', 'month')
            ->orderBy('paid_on', 'ASC');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $fromDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $invoices = $invoices->where(DB::raw('DATE(`paid_on`)'), '>=', $fromDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $toDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $invoices = $invoices->where(DB::raw('DATE(`paid_on`)'), '<=', $toDate);
        }

        if ($request->project != 'all' && !is_null($request->project)) {
            $invoices = $invoices->where('payments.project_id', '=', $request->project);
        }

        if ($request->client != 'all' && !is_null($request->client)) {
            $invoices = $invoices->where('projects.client_id', '=', $request->client);
        }

        if ($request->period !== null && $request->period != 'null' && $request->period != '' && $request->period == 'this_week') {            
            $invoices = $invoices->whereBetween(DB::raw('DATE(payments.`paid_on`)'), [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }

        if ($request->period !== null && $request->period != 'null' && $request->period != '' && $request->period == 'this_month') {            
            $invoices = $invoices->whereMonth(DB::raw('DATE(payments.`paid_on`)'), Carbon::now()->month);
        }

        if ($request->period !== null && $request->period != 'null' && $request->period != '' && $request->period == 'last_12_month') {            
            $invoices = $invoices->whereMonth(DB::raw('DATE(payments.`paid_on`)'), '<=', Carbon::now()->subMonth(12));
        }

        if ($request->period !== null && $request->period != 'null' && $request->period != '' && $request->period == 'last_30_days') {            
            $invoices = $invoices->whereMonth(DB::raw('DATE(payments.`paid_on`)'), '>=', Carbon::now()->subDays(30));
        }

        if ($request->period !== null && $request->period != 'null' && $request->period != '' && $request->period == 'this_year') {        
            $startYear =  Carbon::now()->startOfYear();
            $endYear   =  Carbon::now()->endOfYear();
            $invoices  = $invoices->whereBetween(DB::raw('DATE(payments.`paid_on`)'), [$startYear, $endYear]);
        }

        $invoices = $invoices->get([
            DB::raw('DATE_FORMAT(paid_on,"%M/%y") as date'),
            DB::raw('YEAR(paid_on) year, MONTH(paid_on) month'),
            DB::raw('amount as total'),
            'currencies.id as currency_id',
            'currencies.exchange_rate'
        ]);

        foreach ($invoices as $invoice) {
            if (!isset($incomes[$invoice->date])) {
                $incomes[$invoice->date] = 0;
            }

            if ($invoice->currency_id != $this->global->currency->id) {
                $incomes[$invoice->date] += floor($invoice->total / $invoice->exchange_rate);
            } else {
                $incomes[$invoice->date] += round($invoice->total, 2);
            }
        }

        $dates = array_keys($incomes);

        foreach ($dates as $date) {
            $graphData[] = [
                'date' =>  $date,
                'total' =>  isset($incomes[$date]) ? round($incomes[$date], 2) : 0,
            ];
        }

        usort($graphData, function ($a, $b) {
            $t1 = strtotime($a['date']);
            $t2 = strtotime($b['date']);
            return $t1 - $t2;
        });

        $chartData = json_encode($graphData);

        return Reply::successWithData(__('messages.reportGenerated'), ['chartData' => $chartData]);
    }

}
