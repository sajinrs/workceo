<?php

namespace App\DataTables\Member;

use App\DataTables\BaseDataTable;
use App\Lead;
use App\LeadStatus;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
            ->addColumn('action', function($row){
                $follow = '';
                if(($row->client_id == null || $row->client_id == '' || $row->agent_user_id == $this->user->id)){
                    if($this->user->can('add_clients')) {
                        $follow = '<a href="' . route('member.clients.create') . '/' . $row->id . '"><i class="fa fa-user"></i> '.__('modules.lead.changeToClient').'</a>';
                    }
                    if($row->next_follow_up == 'yes' && ($this->user->can('edit_lead') || $row->agent_user_id == $this->user->id)){
                        $follow .= '<a data-lead-id="'.$row->id.'" class="addFollow" href="javascript:;"><i class="fa fa-thumbs-up"></i> '.__('modules.lead.addFollowUp').'</a></li>';
                    }
                }

               
                $edit = '<a href="'.route('member.leads.edit', $row->id).'"><i class="fa fa-edit"></i> '.__('modules.lead.edit').'</a>';
              
               if($this->user->can('delete_lead')){
                    $delete = '<a href="javascript:;" class="sa-params" data-user-id="'.$row->id.'"><i class="fa fa-trash "></i> '.__('app.delete').'</a>';
                }
               else{
                   $delete = '';
               }

               $action = '
                <div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                            <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">
                            <a href="'.route('member.leads.show', $row->id).'"><i class="fa fa-search"></i> '.__('modules.lead.view').'</a>
                            '.$edit.'   
                            '.$follow.'   
                            '.$delete.'
                    </div>
                        </div>
                    </div>
                </div>';
               
               return $action;
            })
            ->addColumn('status', function($row){
                $status = LeadStatus::all();
                $statusLi = '';
                $statusName = '';
                foreach($status as $st) {
                    if($row->status_id == $st->id){
                        $selected = 'selected';
                    }else{
                        $selected = '';
                    }
                    $statusLi .= '<option '.$selected.' value="'.$st->id.'">'.$st->type.'</option>';
                    $statusName =  $st->type;
                }

                $action = '<select class="form-control" name="statusChange" onchange="changeStatus( '.$row->id.', this.value)">
                    '.$statusLi.'
                </select>';

                if (!$this->user->can('view_lead')) {
                    return ucwords($statusName);
                }
                return $action;
            })
            ->editColumn('client_name', function($row){
                if($row->client_id != null && $row->client_id != ''){
                    $label = '<label class="badge badge-success">'.__('app.client').'</label>';
                }
                else{
                    $label = '<label class="badge badge-info">'.__('app.lead').'</label>';
                }

                return $row->client_name.'<div class="clearfix"></div> '.$label;
            })
            ->editColumn('next_follow_up_date', function($row) use($currentDate){
                if($row->next_follow_up_date != null && $row->next_follow_up_date != ''){
                    $date = Carbon::parse($row->next_follow_up_date)->format($this->global->date_format);
                }
                else{
                    $date = '--';
                }
                if($row->next_follow_up_date < $currentDate && $date != '--'){
                    return $date. ' <label class="badge badge-danger">'.__('app.pending').'</label>';
                }

                return $date;
            })
            ->editColumn('created_at', function($row){
                return $row->created_at->format($this->global->date_format);
            })
            ->removeColumn('status_id')
            ->removeColumn('client_id')
            ->removeColumn('source')
            ->removeColumn('next_follow_up')
            ->removeColumn('statusName')
            ->rawColumns(['status','action','client_name','next_follow_up_date'])
            ->addIndexColumn();

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $lead)
    {
        $request = $this->request();
        $currentDate = Carbon::today()->format('Y-m-d');
        $lead = Lead::select('leads.id','leads.client_id','leads.next_follow_up','client_name','company_name','lead_status.type as statusName',
            'status_id', 'leads.created_at', 'lead_sources.type as source','lead_agents.user_id as agent_user_id',
            \DB::raw("(select next_follow_up_date from lead_follow_up where lead_id = leads.id and leads.next_follow_up  = 'yes' and DATE(next_follow_up_date) >= {$currentDate} ORDER BY next_follow_up_date asc limit 1) as next_follow_up_date"))
           ->leftJoin('lead_status', 'lead_status.id', 'leads.status_id')
           ->leftJoin('lead_sources', 'lead_sources.id', 'leads.source_id')
           ->leftJoin('lead_agents', 'lead_agents.id', 'leads.agent_id');
            if($request->followUp != 'all' && $request->followUp != '' && $request->followUp != 'undefined'){
                $lead = $lead->leftJoin('lead_follow_up', 'lead_follow_up.lead_id', 'leads.id')
                    ->where('leads.next_follow_up', 'yes')
                    ->where('lead_follow_up.next_follow_up_date', '<', $currentDate);
            }
            if($request->client != 'all' && $request->client != ''  && $request->client != 'undefined'){
                if($request->client == 'lead'){
                    $lead = $lead->whereNull('client_id');
                }
                else{
                    $lead = $lead->whereNotNull('client_id');
                }
            }

        //if (!$this->user->can('view_lead')) {
            $lead = $lead->where('lead_agents.user_id', $this->user->id);
        //}

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


        $lead = $lead->GroupBy('leads.id');
        
        return $lead;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    
    public function html()
    {
        return $this->builder()
            ->setTableId('users-table')
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
            //->buttons(Button::make(['text'=> 'Filters<span class="ml-2"><i class="fa fa-angle-down"></i></span>','className'=>'filterBtn'])->action("$('.toggle-data').trigger('click');"))
            ->buttons(Button::make(['extend'=> 'export','buttons' => ['excel', 'csv', 'pdf']]))
            ->parameters([
                'lengthMenu' => [
                    [ 10, 25, 50, 100 ],
                    [ 'Show 10', 'Show 25', 'Show 50', 'Show 100' ]
                ],

                "oLanguage"=> ['sSearchPlaceholder'=>"Search...", 'sSearch'=>'<i class="fa fa-search"></i> _INPUT_',"sLengthMenu"=> "_MENU_"],
                
                'initComplete' => 'function () {
                   window.LaravelDataTables["users-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });
                    $(\'select\').select2({minimumResultsForSearch: -1});
                }',
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            __('app.id') => ['data' => 'id', 'name' => 'id', 'exportable' => false, 'printable' => false, 'visible' => false],
            ' #' => ['data' => 'DT_RowIndex', 'orderable' =>false, 'searchable' => false ],
            __('app.clientName') => ['data' => 'client_name', 'name' => 'client_name'],
            __('modules.lead.companyName') => ['data' => 'company_name', 'name' => 'company_name'],
            __('app.createdOn') => ['data' => 'created_at', 'name' => 'created_at'],
            __('modules.lead.nextFollowUp') => ['data' => 'next_follow_up_date', 'name' => 'next_follow_up_date', 'orderable' => false, 'searchable' => false],
            __('app.status') => ['data' => 'status', 'name' => 'status'],
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
        return 'Leads_' . date('YmdHis');
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
