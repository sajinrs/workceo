<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\Profile\UpdateSuperAdmin;
use App\User;
use App\UserExtra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminTestMapController extends SuperAdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.my_profile';
        $this->pageIcon = 'fa fa-user-circle';
    }

    public function index(){
        $this->userDetail = User::withoutGlobalScope('active')->findOrFail($this->user->id);

        return view('super-admin.test-map.index', $this->data);

    }

    public function getlocs(){
        ini_set('precision', 20);
        ini_set('serialize_precision', 20);
        $admins = User::allAdmins();
        $data = [];
        foreach ($admins as $admin){
            $user_extra = UserExtra::where('user_id',$admin->id)->where('key_name','CURRENT_GPS_LOCATION')->first();
            if($user_extra){
                //echo $user_extra->key_value;
                $geo_loc = json_decode($user_extra->key_value);
                // echo $geo_loc->latitude;
                //print_r($geo_loc);
                foreach ($geo_loc as $key=>$val){
//                    echo $key.' - '.$val;
//                    echo '<br>';
                   if($key === 'latitude'){
                        $data[$admin->id]['lat'] = (string) $val;

                   }elseif($key === 'longitude'){
                        $data[$admin->id]['lng'] = (string) $val;
                   }
                }
              /*  echo '<br>-------------------';
                echo (string)$geo_loc->latitude;echo '-------------<br>';*/
//$lat = (string)$geo_loc->latitude;
//$lng = (string)$geo_loc->longitude;
//                ini_set('precision', 50);
//                $data[$admin->id]['lat'] = $geo_loc->latitude;
//                $data[$admin->id]['lng'] = $geo_loc->longitude;
        //       print_r($data[$admin->id]);

            }
        }
//die;

//        $lat = -34.397;
//        $lng = 150.644;
//        foreach ($admins as $admin){
//            $rand = rand(1,100);
//            $data[$admin->id] = ['lat'=> $lat+$rand, 'lng'=>$lng+$rand];
//        }
        return response()->json($data);
//        echo json_encode($data,true);
//        die;
    }
}
