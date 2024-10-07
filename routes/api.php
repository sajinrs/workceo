<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    //'middleware' => ['api-cors']
], function ($router) {
    Route::post('login', 'Api\Auth\LoginController@login');
    Route::post('logout', 'Api\Auth\LoginController@logout');
    Route::post('refresh_token', 'Api\Auth\LoginController@refresh');
});


//Route::middleware('jwt.auth')->get('users', function () {    return auth('api')->user();});

Route::group(
    //['namespace' => 'Api\Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['jwt.auth','api-cors']],
    ['namespace' => 'Api\Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['jwt.auth']],
    function () {
        Route::get('/users', function () {    return auth('api')->user();});

        Route::get('dynamic/{source?}', 'ApiAdminDynamicController@Index');
        Route::get('dynamic/get-category-products/{cat_id?}', 'ApiAdminDynamicController@getCategoryProducts');
        Route::get('dynamic/product-invoice/{id}', 'ApiAdminDynamicController@getProductInvoice'); 

        //Projects
        Route::apiResource('projects', 'ApiAdminManageProjectsController');
        // Route::post('projects/store', 'ApiAdminManageProjectsController@store');
        // Route::get('projects/{projectID}/edit', 'ApiAdminManageProjectsController@edit');
        Route::post('projects/multiple-upload', ['uses' => 'ApiadminManageProjectFilesController@storeMultiple'])->name('projects.multiple-upload');
        Route::get('projects/tasks/{id}', 'ApiAdminManageProjectsController@showProjectTask');
        Route::post('projects/sign/{id}', 'ApiAdminManageProjectsController@projectSign');
        Route::post('projects/create-job-category', 'ApiAdminManageProjectsController@createJobCategory');
        Route::delete('projects/delete-job-category/{id}', 'ApiAdminManageProjectsController@destroyJobCategory');
        Route::post('projects/update-job-status/{id}', 'ApiAdminManageProjectsController@updateJobStatus');
        
        Route::get('dashboard/upcoming_jobs', 'ApiAdminDashboardController@upcomingJobs');
        Route::post('dashboard/update_location', 'ApiAdminDashboardController@updateLocation');

        Route::apiResource('dashboard', 'ApiAdminDashboardController');
        // Route::get('clients/{clientID}/edit', 'ApiAdminManageClientsController@edit');
        // Route::get('clients/create/{clientID?}', 'ApiAdminManageClientsController@create');
        Route::apiResource('clients', 'ApiAdminManageClientsController');
        // Route::post('clients/store', 'ApiAdminManageClientsController@store');

        //Employees
        Route::apiResource('employees', 'ApiAdminManageEmployeesController');
        // Route::post('employees/store', 'ApiAdminManageEmployeesController@store');
        // Route::get('employees/{empID}/edit', 'ApiAdminManageEmployeesController@edit');

        //Events
        Route::apiResource('events', 'ApiAdminEventCalendarController');
        // Route::post('events/store', 'ApiAdminEventCalendarController@store');
        // Route::get('events/{eventID}/edit', 'ApiAdminEventCalendarController@edit');

        //Leads
        Route::apiResource('leads', 'ApiAdminLeadController');
        // Route::post('leads/store', 'ApiAdminLeadController@store');
        // Route::get('leads/{leadID}/edit', 'ApiAdminLeadController@edit');
        Route::post('leads/create-agent', 'ApiAdminLeadController@storeAgent');
        Route::delete('leads/remove-agent/{id}', 'ApiAdminLeadController@removeAgent');
        Route::get('leads/proposals/{id?}', 'ApiAdminProposalController@Index');
        
        //Invoices
        Route::apiResource('invoices', 'ApiManageAllInvoicesController');
        // Route::post('invoices/store', 'ApiManageAllInvoicesController@store');
        // Route::get('invoices/{invoiceID}/edit', 'ApiManageAllInvoicesController@edit');

        //Contracts
        Route::apiResource('contracts', 'ApiAdminContractController');
        // Route::post('contracts/store', 'ApiAdminContractController@store');
        // Route::get('contracts/{contractID}/edit', 'ApiAdminContractController@edit');

        //Payments
        Route::apiResource('payments', 'ApiAdminPaymentsController');
        // Route::post('payments/store', 'ApiAdminPaymentsController@store');
        // Route::get('payments/{paymentID}/edit', 'ApiAdminPaymentsController@edit');

        //Tasks
        Route::apiResource('tasks', 'ApiAdminManageAllTasksController');
        // Route::post('tasks/store', 'ApiAdminManageAllTasksController@store');
        // Route::get('tasks/{taskID}/edit', 'ApiAdminManageAllTasksController@edit');
        Route::apiResource('task-files', 'ApiAdminTaskFilesController');

        //Estimates
        Route::apiResource('estimates', 'ApiAdminManageEstimatesController');
        // Route::post('estimates/store', 'ApiAdminManageEstimatesController@store');
        // Route::get('estimates/{estID}/edit', 'ApiAdminManageEstimatesController@edit');

        //Expenses
        Route::apiResource('expenses', 'ApiAdminManageExpensesController');
        // Route::post('expenses/store', 'ApiAdminManageExpensesController@store');
        // Route::get('expenses/{expID}/edit', 'ApiAdminManageExpensesController@edit');

        //Vehicles
        Route::apiResource('vehicles', 'ApiAdminManageVehiclesController');
        // Route::post('vehicles/store', 'ApiAdminManageVehiclesController@store');
        // Route::get('vehicles/{vehicleID}/edit', 'ApiAdminManageVehiclesController@edit');
        Route::post('vehicles/store-documents', ['uses' => 'ApiAdminManageVehiclesController@storeDocuments'])->name('vehicles.store-documents');

        //Department
        Route::apiResource('teams', 'ApiAdminManageTeamsController');
        // Route::post('teams/store', 'ApiAdminManageTeamsController@store');
        // Route::get('teams/{teamID}/edit', 'ApiAdminManageTeamsController@edit');

        //Tickets
        Route::apiResource('tickets', 'ApiAdminManageTicketsController');
        // Route::post('tickets/store', 'ApiAdminManageTicketsController@store');
        // Route::get('tickets/{ticketID}/edit', 'ApiAdminManageTicketsController@edit');

        Route::apiResource('user-chat', 'ApiAdminChatController');

        //Notices
        Route::resource('notices', 'ApiAdminManageNoticesController');
        // Route::post('notices/store', 'ApiAdminManageNoticesController@store');
        // Route::get('notices/{noticeID}/edit', 'ApiAdminManageNoticesController@edit');

        Route::resource('settings/profile-settings', 'ApiAdminProfileSettingsController');

        Route::get('projects/invoices/download/{id}', 'ApiAdminManageInvoicesController@download');
    }
);
