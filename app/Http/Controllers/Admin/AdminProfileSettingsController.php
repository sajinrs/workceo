<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\EmployeeDetails;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\User\UpdateAdminProfile;
use App\Http\Requests\User\UpdateProfile;
use App\Libraries\WceoZohoSubscriptions;
use App\User;
use App\UserExtra;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminProfileSettingsController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageIcon = 'icon-user';
        $this->pageTitle = 'app.menu.profileSettings';
    }

    public function index(){
        $this->userDetail = $this->user;
        $this->employeeDetail = EmployeeDetails::where('user_id', '=', $this->userDetail->id)->first();

        return view('admin.profile.index', $this->data);
    }

    public function update(UpdateAdminProfile $request, $id) {

        config(['filesystems.default' => 'local']);

        $user = User::withoutGlobalScope('active')->findOrFail($id);
        $fullName = $request->input('first_name').' '.$request->input('last_name');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->name = $fullName;
        $user->email = $request->input('email');
        $user->gender = $request->input('gender');
        if($request->password != ''){
            $user->password = Hash::make($request->input('password'));
        }
        $user->mobile = $request->input('mobile');

        if ($request->hasFile('image')) {
            Files::deleteFile($user->image,'avatar');

            $crop = [
                'width' => $request->input('width'),
                'height'=> $request->input('height'),
                'x'     => $request->input('x'),
                'y'     => $request->input('y')
            ];
            $user->image = Files::upload($request->image, 'avatar',300, false, $crop);
        }

        $user->save();

        $validate = Validator::make(['address' => $request->address], [
            'address' => 'required'
        ]);

        if($validate->fails()){
            return Reply::formErrors($validate);
        }

        $employee = EmployeeDetails::where('user_id', '=', $user->id)->first();
        if(empty($employee)){
            $employee = new EmployeeDetails();
            $employee->user_id = $user->id;
        }
        $employee->address = $request->address;
        $employee->save();

        // update zoho customer
        $customer_id = WceoZohoSubscriptions::getCustomerId($user->id);
        $customer_data = $user;
        $company = Company::where('id', '=', $user->company_id)->first();
        $customer_data->company_name = $company->company_name;
        $zoho_customer_resp = WceoZohoSubscriptions::updateCustomer($customer_id,$customer_data);
        $user_extra = UserExtra::where('user_id',$user->id)->where('key_name','ZOHO_CUSTOMER_DATA')->first();

        if($zoho_customer_resp['status'] == 'success' && $user_extra) {
            // save customer to db
            $user_extra->key_value = json_encode($zoho_customer_resp['data']);
            $user_extra->save();
        }

        $this->logUserActivity($user->id, __('messages.updatedProfile'));
        return Reply::redirect(route('admin.profile-settings.index'), __('messages.profileUpdated'));
    }


}
