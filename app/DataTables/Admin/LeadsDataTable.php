<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Lead;
use App\LeadStatus;
use App\User;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class LeadsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $currentDate = Carbon::today()->format('Y-m-d');
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {

                if ($row->client_id == null || $row->client_id == '') {
                    $follow = '<a href="' . route('admin.clients.create') . '/' . $row->id . '"><i class="fa fa-user"></i> ' . __('modules.lead.changeToClient') . '</a>';
                    if ($row->next_follow_up == 'yes') {
                        $follow .= '<a  onclick="followUp(' . $row->id . ')" href="javascript:;"><i class="fa fa-thumbs-up"></i> ' . __('modules.lead.addFollowUp') . '</a>';
                    }
                } else {
                    $follow = '';
                }
                $action = '
                <div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                            <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">
                              <a href="' . route('admin.leads.show', $row->id) . '"><img src="'.asset('img/icons/search.svg').'" /> '. __('modules.lead.view') . '</a>
                              <a href="' . route('admin.leads.edit', $row->id) . '"><img src="'.asset('img/icons/edit.svg').'" /> ' . __('modules.lead.edit') . '</a>                             
                    ' . $follow . ' 
                            <a href="javascript:;" class="sa-params" data-user-id="' . $row->id . '"><img src="'.asset('img/icons/delete.svg').'" /> '. __('app.delete') . '</a>
                    </div>
                        </div>
                    </div>
                </div>';

                return $action;
            })
            ->addColumn('status', function ($row) {
                $status = LeadStatus::all();
                $statusLi = '';
                foreach ($status as $st) {
                    if ($row->status_id == $st->id) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }
                    $statusLi .= '<option ' . $selected . ' value="' . $st->id . '">' . ucfirst($st->type) . '</option>';
                }

                $action = '<select class="form-control lead-status" name="statusChange" onchange="changeStatus( ' . $row->id . ', this.value)">
                    ' . $statusLi . '
                </select>';


                return $action;
            })
            
            ->addColumn('status_n', function ($row) {
                if(!empty($row->status_id)) {
                    $status = LeadStatus::find($row->status_id);
                    return $status->type;
                } else {
                    return '-';
                }
                
            })

            ->editColumn('client_name', function ($row) {
                if ($row->client_id != null && $row->client_id != '') {
                    $label = '<label class="badge badge-success w-100">' . __('app.convertedtoClient') . '</label>';
                } else {
                    $label = '<label class="badge badge-info w-100">' . __('app.lead') . '</label>';
                }

                return '<a href="' . route('admin.leads.show', $row->id) . '">' . $row->client_name . '</a><div class="clearfix"></div> ' . $label;
            })
            ->editColumn('next_follow_up_date', function ($row) use ($currentDate) {
                if ($row->next_follow_up_date != null && $row->next_follow_up_date != '') {
                    $date = Carbon::parse($row->next_follow_up_date)->format($this->global->date_format);
                } else {
                    $date = '--';
                }
                if ($row->next_follow_up_date < $currentDate && $date != '--') {
                    return $date . ' <label class="badge badge-danger">' . __('app.pending') . '</label>';
                }

                return $date;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format($this->global->date_format);
            })
            ->removeColumn('status_id')
            ->removeColumn('client_id')
            ->removeColumn('source')
            ->removeColumn('next_follow_up')
            ->removeColumn('statusName')
            ->addIndexColumn()
            ->rawColumns(['status', 'action', 'client_name', 'next_follow_up_date']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $lead = Lead::select('leads.id', 'leads.client_id', 'leads.next_follow_up', 'client_name', 'company_name', 'lead_status.type as statusName', 'status_id', 'leads.created_at', 'lead_sources.type as source', \DB::raw("(select next_follow_up_date from lead_follow_up where lead_id = leads.id and leads.next_follow_up  = 'yes' and DATE(next_follow_up_date) >= {$currentDate} ORDER BY next_follow_up_date asc limit 1) as next_follow_up_date"))
            ->leftJoin('lead_status', 'lead_status.id', 'leads.status_id')
            ->leftJoin('lead_sources', 'lead_sources.id', 'leads.source_id');
        if ($this->request()->followUp != 'all' && $this->request()->followUp != '') {
            $lead = $lead->leftJoin('lead_follow_up', 'lead_follow_up.lead_id', 'leads.id');
            if ($this->request()->followUp == 'yes') {
                $lead = $lead->where('leads.next_follow_up', 'yes');
            } else {
                $lead = $lead->where('leads.next_follow_up', 'no');
            }
            // $lead = $lead->where('lead_follow_up.next_follow_up_date', '<', $currentDate);
        }
        if ($this->request()->client != 'all' && $this->request()->client != '') {
            if ($this->request()->client == 'lead') {
                $lead = $lead->whereNull('client_id');
            } else {
                $lead = $lead->whereNotNull('client_id');
            }
        }

        if ($this->request()->sponsor != 'all' && $this->request()->sponsor != '') {
                
            $lead = $lead->where('agent_id', $this->request()->sponsor);            
        }

        if (!is_null($this->request()->status_by) && $this->request()->status_by != 'totalLeads') {
            switch ($this->request()->status_by){
                case('totalClientConverted'):{
                    $lead = $lead->whereNotNull('client_id');
                    break;
                }
                case('totalPendingFollowUps'):{
                    $lead = $lead->leftJoin('lead_follow_up', 'lead_follow_up.lead_id', 'leads.id');
                    $lead = $lead->where(\DB::raw('DATE(next_follow_up_date)'), '<=', Carbon::today()->format('Y-m-d'))
                        ->where('leads.next_follow_up', 'yes');
                    break;
                }
                
            }
        }

        return $lead->GroupBy('leads.id');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('leads-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-10'Bl><'col-md-2'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->parameters([
                'lengthMenu' => [
                    [ 10, 25, 50, 100 ],
                    [ 'Show 10', 'Show 25', 'Show 50', 'Show 100' ]
                ],

                "oLanguage"=> ['sSearchPlaceholder'=>"Search...", 'sSearch'=>'<i class="fa fa-search"></i> _INPUT_',"sLengthMenu"=> "_MENU_"],
                
                'initComplete' => 'function () {
                   window.LaravelDataTables["leads-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });
                
                    $(\'select\').select2({minimumResultsForSearch: -1});
                }',
            ])
            //->columnDefs(['visible' => false, 'targets' => 2])
            ->buttons(Button::make(['text'=> 'Filters<span class="ml-2"><i class="fa fa-angle-down"></i></span>','className'=>'filterBtn'])->action("$('.toggle-data').trigger('click');"))
            ->buttons(Button::make([
                'text'=> '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> Export', 
                'extend'=> 'export',
                'buttons' => ['excel', 'csv','pdf' ]
                ]))
            ->buttons(Button::make([
                    'text'=> '<span class="ml-2"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg></span> Import',
                    'className'=>'importBtn importLeadsList'
            ]));
            

    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            //__('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false ],
            __('app.name') => ['data' => 'client_name', 'name' => 'client_name'],
            __('modules.lead.companyName') => ['data' => 'company_name', 'name' => 'company_name'],
            __('app.created') => ['data' => 'created_at', 'name' => 'created_at'],
            __('modules.lead.nextFollowUp') => ['data' => 'next_follow_up_date', 'name' => 'next_follow_up_date', 'orderable' => false, 'searchable' => false],
            __('Status') => ['data' => 'status', 'name' => 'status', 'exportable' => false, 'printable' => false],
            __('Status ') => ['data' => 'status_n', 'name' => 'status_n', 'orderable' => false, 'visible' => false],
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(150)
                ->addClass('text-center')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'leads_' . date('YmdHis');
    }

    public function pdf()
    {
        set_time_limit(0);
        if ('snappy' == config('datatables-buttons.pdf_generator', 'snappy')) {
            return $this->snappyPdf();
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('datatables::print', ['data' => $this->getDataForPrint()]);

        return $pdf->download($this->getFilename() . '.pdf');
    }
}
