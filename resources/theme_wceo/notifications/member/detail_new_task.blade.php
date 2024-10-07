
<div class="media">
    <div class="media-body">
        <h6 class="mt-0 mb-0"><span class="badge badge-primary"><i class="fas fa-list-alt"></i> </span>{{ __('email.newTask.subject') }} - {{ ucfirst($notification->data['heading']) }}</h6>
        <small class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</small>
        <p class="mb-0 m-l-40">@if(isset($notification->data['description'])) {!! ucwords($notification->data['description']) !!} @endif</p>
    </div>
</div>