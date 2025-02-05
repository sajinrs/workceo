<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\Reply;
use App\StorageSetting;
use Illuminate\Http\Request;

class StorageSettingsController extends SuperAdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.storageSettings';
        $this->pageIcon = 'fa fa-gear';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->awsCredentials = StorageSetting::where('filesystem', 'aws')->first();
        $this->localCredentials = StorageSetting::where('filesystem', 'local')->first();

        if(!is_null($this->awsCredentials)){
            $authKeys = json_decode($this->awsCredentials->auth_keys);
            $this->awsCredentials->driver = $authKeys->driver;
            $this->awsCredentials->key = $authKeys->key;
            $this->awsCredentials->secret = $authKeys->secret;
            $this->awsCredentials->region = $authKeys->region;
            $this->awsCredentials->bucket = $authKeys->bucket;
        }


        return view('super-admin.storage-settings.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storageData = StorageSetting::all();
        if(count($storageData) > 0) {
            foreach ($storageData as $data) {
                $data->status = 'disabled';
                $data->save();
            }
        }

        $storage = StorageSetting::firstorNew(['filesystem' => $request->storage]);

        switch ($request->storage) {
            case 'local':
                $storage->filesystem = $request->storage;
                $storage->status = 'enabled';
                break;
            case 'aws':
                $storage->filesystem = $request->storage;
                $json = '{"driver": "s3", "key": "'.$request->aws_key.'", "secret": "'.$request->aws_secret.'", "region": "'.$request->aws_region.'", "bucket": "'.$request->aws_bucket.'"}';
                $storage->auth_keys = $json;
                $storage->status = 'enabled';
                break;
        }
        $storage->save();

        return Reply::success(__('messages.settingsUpdated'));
    }

}
