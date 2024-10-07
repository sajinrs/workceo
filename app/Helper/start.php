<?php

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

if (!function_exists('superAdmin')) {
    function superAdmin()
    {
        return auth()->user();
    }
}

if (!function_exists('user')) {
    function user()
    {
        return auth()->user();
    }
}

if (!function_exists('company')) {
    function company()
    {
        if(auth()->user()) {
            $companyId = auth()->user()->company_id;
            $company = \App\Company::find($companyId);
            return $company;
        }
        return false;
    }
}

if (!function_exists('topAds')) {
    function topAds()
    {
        if(auth()->user()) {
            $topAds = \App\AdsSpace::find(1);
            return $topAds;
        }
        return false;
    }
}

if (!function_exists('headerAds')) {
    function headerAds()
    {
        if(auth()->user()) {
            $headerAds = \App\AdsSpace::find(2);
            return $headerAds;
        }
        return false;
    }
}

if (!function_exists('sidebarAds')) {
    function sidebarAds()
    {
        if(auth()->user()) {
            $sidebarAds = \App\AdsSpace::find(3);
            return $sidebarAds;
        }
        return false;
    }
}

if (!function_exists('popupAds')) {
    function popupAds()
    {
        if(auth()->user()) {
            $popupAds = \App\AdsSpace::find(4);
            return $popupAds;
        }
        return false;
    }
}

if (!function_exists('toasterAds')) {
    function toasterAds()
    {
        if(auth()->user()) {
            $toasterAds = \App\AdsSpace::find(5);
            return $toasterAds;
        }
        return false;
    }
}

if (!function_exists('footerAds')) {
    function footerAds()
    {
        if(auth()->user()) {
            $footerAds = \App\AdsSpace::find(6);
            return $footerAds;
            
            
        }
        return false;
    }
}

if (!function_exists('asset_url')) {

    // @codingStandardsIgnoreLine
    function asset_url($path)
    {
        if (config('filesystems.default') == 's3') {
//            return "https://" . config('filesystems.disks.s3.bucket') . ".s3.amazonaws.com/".$path;
        }

        $path = 'user-uploads/' . $path;
        $storageUrl = $path;

        if (!Str::startsWith($storageUrl, 'http')) {
            return url($storageUrl);
        }

        return $storageUrl;

    }

}

if (!function_exists('worksuite_plugins')) {

    function worksuite_plugins()
    {

        if (!session()->has('worksuite_plugins')) {
            $plugins = \Nwidart\Modules\Facades\Module::allEnabled();
            // dd(array_keys($plugins));

            foreach ($plugins as $plugin) {
                Artisan::call('module:migrate', array($plugin, '--force' => true));
            }

            session(['worksuite_plugins' => array_keys($plugins)]);
        }
        return session('worksuite_plugins');

    }
}

if (!function_exists('isSeedingData')) {

    /**
     * Check if app is seeding data
     * @return boolean
     */
    function isSeedingData()
    {
        // We set config(['app.seeding' => true]) at the beginning of each seeder. And check here
        return config('app.seeding');
    }

}
if (!function_exists('isRunningInConsoleOrSeeding')) {

    /**
     * Check if app is seeding data
     * @return boolean
     */
    function isRunningInConsoleOrSeeding()
    {
        // We set config(['app.seeding' => true]) at the beginning of each seeder. And check here
        return app()->runningInConsole() || isSeedingData();
    }

}


if (!function_exists('asset_url_local_s3')) {

    // @codingStandardsIgnoreLine
    function asset_url_local_s3($path)
    {
        if (config('filesystems.default') == 's3') {
            return "https://" . config('filesystems.disks.s3.bucket') . ".s3.amazonaws.com/" . $path;
        }

        $path = 'user-uploads/' . $path;
        $storageUrl = $path;

        if (!Str::startsWith($storageUrl, 'http')) {
            return url($storageUrl);
        }

        return $storageUrl;

    }

}

if (!function_exists('download_local_s3')) {

    // @codingStandardsIgnoreLine
    function download_local_s3($file, $path)
    {
        if (config('filesystems.default') == 's3') {
            $ext = pathinfo($file->filename, PATHINFO_EXTENSION);
            $fs = Storage::getDriver();
            $stream = $fs->readStream($path);

            return Response::stream(function () use ($stream) {
                fpassthru($stream);
            }, 200, [
                "Content-Type" => $ext,
                "Content-Length" => $file->size,
                "Content-disposition" => "attachment; filename=\"" . basename($file->filename) . "\"",
            ]);
        }

        $path = 'user-uploads/'.$path;
        return response()->download($path, $file->filename);

    }

}


if (!function_exists('userChecklistCount')) {
    function userChecklistCount()
    {
        if(auth()->user()) {
            $userID         = auth()->user()->id;
            $checkList = \App\OnBoarding::get();
            $totalCheckList = count($checkList);

            $checklistIDs     = \App\UserExtra::where(['key_name' => 'ONBOARDING_CHECKLIST', 'user_id' => $userID ])->get()->pluck('key_value')->first();
            $checklistIDCount = 0;
            if(!empty($checklistIDs))
            {
                foreach($checkList as $boarding)
                {
                    $boardingID [] = $boarding->id; 
                }

                $checklistIDExplode  = explode(',',$checklistIDs);
                $deletedBoardID      = array_diff($checklistIDExplode, $boardingID);
                $checklistIDs        = array_diff($checklistIDExplode, $deletedBoardID );
                $checklistIDCount    = count($checklistIDs);
            } 

            $result = ['totalCheckList' => $totalCheckList, 'totalChecked' => $checklistIDCount];
            return $result;
        }
        return false;
    }
}


if (!function_exists('currencyFormat')) {
    function currencyFormat($amount)
    {
        $total = number_format($amount, 2, '.', ',');
        //$total = number_format($amount);
        return $total;
    }
}

if (!function_exists('chantUnseenCount')) {
    function chantUnseenCount($from)
    {
        if(auth()->user()) {
            $userID = auth()->user()->id;
            $count = \App\UserChat::chatUnSeen($from,$userID);
            return $count;
        }
        return 10;
    }
}