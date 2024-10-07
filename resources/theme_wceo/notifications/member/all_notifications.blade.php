


<div class="modal-content allNotifications">
    <div class="modal-header">
        <h5 class="modal-title">{{ count($user->unreadNotifications) }} Unread Notifications</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <div class="portlet-body">
            <div class="col-md-12">
                <div class="notificationList">
                    <div class="notification-dropdown">
                        @foreach ($user->unreadNotifications as $notification)
                            
                            @if(view()->exists('notifications.member.detail_'.snake_case(class_basename($notification->type))))
                                @include('notifications.member.detail_'.snake_case(class_basename($notification->type)))
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>