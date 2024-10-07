<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\GroupTaskDataTable;
use App\Helper\Reply;
use App\GroupTaskCategory;
use App\GroupTask;
use App\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminTaskGroupController extends AdminBaseController
{
    /**
     * AdminProductController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'Task Group';
        $this->pageIcon = 'fa fa-shopping-cart';
        $this->middleware(function ($request, $next) {
            if (!in_array('products', $this->user->modules)) {
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
    public function index(GroupTaskDataTable $dataTable)
    {
        return $dataTable->render('admin.task-group.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->categories = GroupTaskCategory::all();
        return view('admin.task-group.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'cat_name' => 'required|unique:group_task_categories,title',
        ]);

        $category = new GroupTaskCategory();
        $category->title = $request->cat_name;       
        $category->save();

        $titles = $request->title;

        if($titles)
        {
            foreach ($titles as $title) {
                if (is_null($title)) {
                    return Reply::error("Title can't be blank");
                }
            }

            //request->title[0]
            foreach ($titles as $key => $title) {
                if (!is_null($title)) {
    
                    $task                     = new GroupTask();
                    $task->task_category_id   = $category->id;
                    $task->title              = $title;
                    $task->description        = $request->description[$key];
                    $task->priority           = $request->priority[$key];
                    $task->save();                    
                }
            }
        } 
        
        return Reply::redirect(route('admin.task-groups.index'), 'Task Group Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->category = GroupTaskCategory::find($id);
        $this->tasks = GroupTask::where('task_category_id', $id)->get();

        return view('admin.task-group.edit', $this->data);
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
        $request->validate([
            'cat_name' => 'required|unique:group_task_categories,title,' . $id,
        ]);

        $category = GroupTaskCategory::findOrFail($id);
        $category->title = $request->cat_name;       
        $category->save();

        $titles = $request->title;

        if($titles)
        {
            foreach ($titles as $title) {
                if (is_null($title)) {
                    return Reply::error("Title can't be blank");
                }
            }

            GroupTask::where('task_category_id', $id)->delete();

            foreach ($titles as $key => $title) :
                GroupTask::create(
                    [
                        'task_category_id' => $id,
                        'title'            => $title,
                        'description'      => $request->description[$key],
                        'priority'         => $request->priority[$key],                        
                    ]
                );
            endforeach;            
        }       

        return Reply::redirect(route('admin.task-groups.index'), 'Task Group Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        GroupTaskCategory::destroy($id);
        return Reply::success('Task Group Deleted!');
    }

    public function export() {
        $attributes =  ['tax', 'taxes', 'price'];
        $products = Product::select('id', 'name', 'price')
            ->get()->makeHidden($attributes);

            // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['ID', 'Name', 'Price'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($products as $row) {
            $rowArrayData = $row->toArray();
            $rowArrayData['total_amount'] = $this->global->currency->currency_symbol.$rowArrayData['total_amount'];
            $exportArray[] = $rowArrayData;
        }

        // Generate and return the spreadsheet
        Excel::create('Product', function($excel) use ($exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Product');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('Product file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);

                $sheet->row(1, function($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold'       =>  true
                    ));

                });

            });



        })->download('xlsx');
    }
}
