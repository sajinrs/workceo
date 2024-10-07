<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helper\Reply;
use App\Http\Requests\ChatStoreRequest;
use App\MessageSetting;
use App\Notifications\NewChat;
use App\User;
use App\UserChat;
use App\Helper\Files;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Input;

/**
 * Class MemberChatController
 * @package App\Http\Controllers\Member
 */
class ApiAdminChatController extends ApiAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.messages';
        $this->pageIcon = 'icofont icofont-ui-text-loading';
        $this->middleware(function ($request, $next) {
            if(!in_array('messages',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });

    }

    public function index()
    {
        $data['userList'] = $this->userListLatest();

        $userID = request()->get('userID');
        $id     = $userID;
        $name   = '';

        if(count($data['userList']) != 0)
        {
            if(($userID == '' || $userID == null)){
                $id   = $data['userList'][0]->id;
                $name = $data['userList'][0]->name;

            }else{
                $id = $userID;
                $name = User::findOrFail($userID)->name;
            }

            $updateData = ['message_seen' => 'yes'];
            UserChat::messageSeenUpdate($this->user->id, $id, $updateData);
        }

        $data['dpData'] = $id;
        $data['dpName'] = $name;

        $data['chatDetails'] = UserChat::chatDetail($id, $this->user->id);

        if (request()->ajax()) {
            return $this->userChatData($data['chatDetails'], 'user');
        }

        return new UserResource($data);


    }

    /**
     * @param $chatDetails
     * @param $type
     * @return string
     */
    public function userChatData($chatDetails)
    {
        $chatMessage = '';

        $this->chatDetails = $chatDetails;

        $chatMessage .= view('admin.user-chat.ajax-chat-list', $this->data)->render();

        $chatMessage .= '<li id="scrollHere"></li>';

        return Reply::successWithData(__('messages.fetchChat'), ['chatData' => $chatMessage]);

    }

    /**
     * @return mixed
     */
    public function postChatMessage(ChatStoreRequest $request)
    {
        $this->user = auth()->user();

       

        $message = $request->get('message');

        if($request->user_type == 'client'){
            $userID = $request->get('client_id');
        }
        else{
            $userID = $request->get('user_id');
        }

        $allocatedModel = new UserChat();
        $allocatedModel->message         = htmlentities($message);
        $allocatedModel->user_one        = $this->user->id;
        $allocatedModel->user_id         = $userID;
        $allocatedModel->from            = $this->user->id;
        $allocatedModel->to              = $userID;

        if ($request->hasFile('attachment')) {
            $allocatedModel->attachment = Files::uploadLocalOrS3($request->attachment, 'attachment');
        }

        $allocatedModel->save();

        $this->userLists = $this->userListLatest();

        $this->userID = $userID;

        $users = view('admin.user-chat.ajax-user-list', $this->data)->render();

        $lastLiID = '';
        return Reply::successWithData(__('messages.fetchChat'), ['chatData' => $this->index(), 'dataUserID' => $this->user->id, 'userList' => $users, 'liID' => $lastLiID]);
    }

    /**
     * @return mixed
     */
    public function userListLatest($term = null)
    {
        $result = User::userListLatest($this->user->id, $term );

        return $result;
    }

    public function getUserSearch()
    {
        $term = request()->get('term');
        $this->userLists = $this->userListLatest($term);

        $users = '';

        $users = view('admin.user-chat.ajax-user-list', $this->data)->render();

        return Reply::dataOnly(['userList' => $users]);
    }

    public function create() {
        $this->members = User::allEmployees($this->user->id);
        $this->clients = User::allClients();
        $this->messageSetting = MessageSetting::first();
        return view('admin.user-chat.create', $this->data);
    }

    /**
     * @return mixed
     */
    public function postChatEmoji(ChatStoreRequest $request)
    {
        $this->user = auth()->user();



        $message = $request->get('message');

        if($request->user_type == 'client'){
            $userID = $request->get('client_id');
        }
        else{
            $userID = $request->get('user_id');
        }

        $allocatedModel = new UserChat();
        $allocatedModel->message         = $message;
        $allocatedModel->user_one        = $this->user->id;
        $allocatedModel->user_id         = $userID;
        $allocatedModel->from            = $this->user->id;
        $allocatedModel->to              = $userID;
        $allocatedModel->message_type    = 'emoji';

        if ($request->hasFile('attachment')) {
            $allocatedModel->attachment = Files::upload($request->attachment, 'attachment', 300);
        }

        $allocatedModel->save();

        $this->userLists = $this->userListLatest();

        $this->userID = $userID;

        $users = view('admin.user-chat.ajax-user-list', $this->data)->render();

        $lastLiID = '';
        return Reply::successWithData(__('messages.fetchChat'), ['chatData' => $this->index(), 'dataUserID' => $this->user->id, 'userList' => $users, 'liID' => $lastLiID]);
    }


    public function memberMedia($id, ChatStoreRequest $request)
    {
        
        $this->member  = User::findOrFail($id);
        $this->chatDetails = UserChat::chatMedia($id, $this->user->id);
        $this->mediaCount = count($this->chatDetails);
        if(isset($request->media))
            return view('admin.user-chat.ajax-media-list', $this->data);
        else
            return view('admin.user-chat.ajax-member-info', $this->data);
    }

    public function chatStatus(ChatStoreRequest $request)
    {
        $userID = $this->user->id;
        $employee = User::findOrFail($userID);
        $employee->chat_status = $request->status;
        $employee->save();
    }


}
