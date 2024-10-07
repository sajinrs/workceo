<?php

namespace App\Http\Controllers\Admin;

use App\ClientPropertie;
use App\ClientDetails;
use App\Country;
use App\Helper\Reply;
use App\Http\Requests\ClientProperties\StoreProperty;
use App\User;
use Yajra\DataTables\Facades\DataTables;

class ClientPropertiesController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageIcon = 'icofont icofont-people';
        $this->pageTitle = 'app.menu.clients';
        $this->middleware(function ($request, $next) {
            if(!in_array('clients',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });

    }

    public function showProperties($id) {
        $this->countries = Country::all(['id', 'name']);
        $this->client = User::fromQuery('SELECT users.id, client_details.company_id FROM users JOIN client_details ON client_details.user_id = users.id WHERE client_details.user_id = '.$id)->first();
        
        $this->clientDetail = ClientDetails::where('user_id', '=', $id)->first();

        if(!is_null($this->clientDetail)){
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }

        return view('admin.clients.properties', $this->data);
    }

    public function data($id) {
        $timeLogs = ClientPropertie::select('*', 'client_properties.id as property_id', 'countries.name as country')
                                    ->join('countries', 'countries.id', '=', 'client_properties.country_id')
                                    ->where('user_id', $id)->get();

        return DataTables::of($timeLogs)
            ->addColumn('action', function($row){
                return '<a href="javascript:;" class="btn btn-outline-info m-b-5 edit-property"
                      data-toggle="tooltip" data-property-id="'.$row->property_id.'"  data-original-title="Edit"><i class="fa fa-pen" aria-hidden="true"></i></a>

                    <a href="javascript:;" class="btn btn-outline-danger sa-params m-b-5"
                      data-toggle="tooltip" data-property-id="'.$row->property_id.'" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
            })
            ->editColumn('contact_name', function($row){
                return ucwords($row->contact_name);
            })
            ->removeColumn('user_id')
            ->make(true);
    }

    public function store(StoreProperty $request) {
        $property = new ClientPropertie();
        $property->user_id = $request->user_id;
        $property->street = $request->street;
        $property->apt_floor = $request->apt_floor;
        $property->city = $request->city;
        $property->state = $request->state;
        $property->zip = $request->zip;
        $property->country_id = $request->country_id;
        $property->save();

        return Reply::success(__('messages.propertyAdded'));
    }

    public function edit($id) {
        $this->countries = Country::all(['id', 'name']);
        $this->property  = ClientPropertie::findOrFail($id);
        return view('admin.clients.property-edit', $this->data);
    }

    public function update(StoreProperty $request, $id) {
        $property = ClientPropertie::findOrFail($id);
        $property->street = $request->street;
        $property->apt_floor = $request->apt_floor;
        $property->city = $request->city;
        $property->state = $request->state;
        $property->zip = $request->zip;
        $property->country_id = $request->country_id;
        $property->save();

        return Reply::success(__('messages.propertyUpdated'));
    }

    public function destroy($id) {
        ClientPropertie::destroy($id);

        return Reply::success(__('messages.propertyDeleted'));
    }
}
