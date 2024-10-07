<?php

namespace App\Http\Controllers\Member;

use App\EmployeeDetails;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\User\UpdateProfile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class MemberProfileController extends MemberBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.profileSettings';
        $this->pageIcon = 'icon-user';
    }

    public function index() {
        $this->userDetail = auth()->user();
        $this->employeeDetail = EmployeeDetails::where('user_id', '=', $this->userDetail->id)->first();
        return view('member.profile.edit', $this->data);
    }

    public function update(UpdateProfile $request, $id) {
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

        $this->logUserActivity($user->id, __('messages.updatedProfile'));
        return Reply::redirect(route('member.profile.index'), __('messages.profileUpdated'));
    }

    public function updateOneSignalId(Request $request){
        $user = User::find($this->user->id);
        $user->onesignal_player_id = $request->userId;
        $user->save();
    }
}
