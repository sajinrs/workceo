<?php


namespace App;

use Froiden\RestAPI\ApiModel;
use Illuminate\Support\Facades\Schema;

class BaseModel extends ApiModel
{

    protected $mimeType = [
        'txt' => 'fa-file-alt',
        'htm' => 'fa-file-code',
        'html' => 'fa-file-code',
        // 'php' => 'fa-file-code',
        'css' => 'fa-file-code',
        'js' => 'fa-file-code',
        'json' => 'fa-file-code',
        'xml' => 'fa-file-code',
        'swf' => 'fa-file',
        'CR2' => 'fa-file',
        'flv' => 'fa-file-video',

        // images
        'png' => 'fa-file-image',
        'jpe' => 'fa-file-image',
        'jpeg' => 'fa-file-image',
        'jpg' => 'fa-file-image',
        'gif' => 'fa-file-image',
        'bmp' => 'fa-file-image',
        'ico' => 'fa-file-image',
        'tiff' => 'fa-file-image',
        'tif' => 'fa-file-image',
        'svg' => 'fa-file-image',
        'svgz' => 'fa-file-image',

        // archives
        'zip' => 'fa-file',
        'rar' => 'fa-file',
        'exe' => 'fa-file',
        'msi' => 'fa-file',
        'cab' => 'fa-file',

        // audio/video
        'mp3' => 'fa-file-audio',
        'qt' => 'fa-file-video',
        'mov' => 'fa-file-video',
        'mp4' => 'fa-file-video',
        'mkv' => 'fa-file-video',
        'avi' => 'fa-file-video',
        'wmv' => 'fa-file-video',
        'mpg' => 'fa-file-video',
        'mp2' => 'fa-file-video',
        'mpeg' => 'fa-file-video',
        'mpe' => 'fa-file-video',
        'mpv' => 'fa-file-video',
        '3gp' => 'fa-file-video',
        'm4v' => 'fa-file-video',

        // adobe
        'pdf' => 'fa-file-pdf',
        'psd' => 'fa-file-image',
        'ai' => 'fa-file',
        'eps' => 'fa-file',
        'ps' => 'fa-file',

        // ms office
        'doc' => 'fa-file-alt',
        'rtf' => 'fa-file-alt',
        'xls' => 'fa-file-excel',
        'ppt' => 'fa-file-powerpoint',
        'docx' => 'fa-file-alt',
        'xlsx' => 'fa-file-excel',
        'pptx' => 'fa-file-powerpoint',


        // open office
        'odt' => 'fa-file-alt',
        'ods' => 'fa-file-alt',
    ];

    public function getIconAttribute($value) {

        $isColExist = Schema::hasColumn($this->getTable(),'icon');

        if($isColExist){
            return $value;
        }
        if (is_null($this->external_link) && !$isColExist) {
            $ext = pathinfo($this->filename, PATHINFO_EXTENSION);
            if ($ext == 'png' || $ext == 'jpe' || $ext == 'jpeg' || $ext == 'jpg' || $ext == 'gif' || $ext == 'bmp' ||
                $ext == 'ico' || $ext == 'tif' || $ext == 'svg' || $ext == 'svgz' || $ext == 'psd' || $ext == 'csv')
            {
                return 'images';
            }
            else{
                return $this->mimeType[$ext];
            }
        }
    }

}
