<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Files;
use App\Helper\Reply;
use App\Lead;
use App\LeadFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LeadFilesController extends AdminBaseController
{

    private $mimeType = [
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

    /**
     * ManageLeadFilesController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageIcon = 'icofont icofont-people';
        $this->pageTitle = 'app.menu.leadFiles';

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param Request $request
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = new LeadFiles();
            $file->user_id = $this->user->id;
            $file->lead_id = $request->lead_id;
            $file->hashname = Files::uploadLocalOrS3($request->file,'lead-files/'.$request->lead_id);
            $file->filename = $request->file->getClientOriginalName();
            $file->size = $request->file->getSize();
            $file->save();
        }

        $this->lead = Lead::findOrFail($request->lead_id);
        $this->icon($this->lead);
        if($request->view == 'list') {
            $view = view('admin.lead.lead-files.ajax-list', $this->data)->render();
        } else {
            $view = view('admin.lead.lead-files.thumbnail-list', $this->data)->render();
        }
        return Reply::successWithData(__('messages.fileUploaded'), ['html' => $view]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->lead = Lead::findOrFail($id);
        return view('admin.lead.lead-files.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }


    /**
     * @param Request $request
     * @param $id
     * @return array
     * @throws \Throwable
     */
    public function destroy(Request $request, $id)
    {
        $storage = config('filesystems.default');
        $file = LeadFiles::findOrFail($id);
        switch($storage) {
            case 'local':
                File::delete('user-uploads/lead-files/'.$file->lead_id.'/'.$file->hashname);
                break;
            case 's3':
                Storage::disk('s3')->delete('lead-files/'.$file->lead_id.'/'.$file->hashname);
                break;

        }
        LeadFiles::destroy($id);
        $this->lead = Lead::findOrFail($file->lead_id);
        $this->icon($this->lead);
        if($request->view == 'list') {
            $view = view('admin.lead.lead-files.ajax-list', $this->data)->render();
        } else {
            $view = view('admin.lead.lead-files.thumbnail-list', $this->data)->render();
        }
        return Reply::successWithData(__('messages.fileDeleted'), ['html' => $view]);
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download($id) {
        $file = LeadFiles::findOrFail($id);
        return download_local_s3($file, 'lead-files/' . $file->lead_id . '/' . $file->hashname);
    }


    /**
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function thumbnailShow(Request $request)
    {
        $this->lead = Lead::with('files')->findOrFail($request->id);
        $this->icon($this->lead);
        $view = view('admin.lead.lead-files.thumbnail-list', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }


    /**
     * @param $leads
     */
    private function icon($leads) {
        foreach ($leads->files as $lead) {
            $ext = pathinfo($lead->filename, PATHINFO_EXTENSION);
            if ($ext == 'png' || $ext == 'jpe' || $ext == 'jpeg' || $ext == 'jpg' || $ext == 'gif' || $ext == 'bmp' ||
                $ext == 'ico' || $ext == 'tiff' || $ext == 'tif' || $ext == 'svg' || $ext == 'svgz' || $ext == 'psd')
            {
                $lead->icon = 'images';
            } else {
                $lead->icon = $this->mimeType[$ext];
            }
        }
    }
}
