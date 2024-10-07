<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="checklist-holder">
                <div class="card-body">
                {!! Form::open(['id'=>'adminChecklist','class'=>'ajax-form f1','method'=>'POST']) !!}
                <input type="hidden" name="user_id" value="{{$user->id}}" />
                    <div class="media">
                        <img class="rounded-circle image-radius m-r-15" src="{{ asset('img/onboard.png') }}" alt="" width="58" height="58">
                        <div class="media-body">
                            <h1>Hi {{ucwords($user->first_name)}}!</h1>
                            <p>Get the most out of your WorkCEO service business software by completing the tasks below.</p>
                        </div>
                        <span class="pull-right checklist-count">
                            <span class="checkedCount">{{count($checklistIDs)}}</span>/{{count($checklists)}}
                        </span>
                    </div>
                    @if($checklists)
                    
                    <div class="white-bg">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{$checklistPercentage}}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <span class="text-complete">Completed</span>
                            </div>
                        </div>

                        <div class="checklist-items">
                            @foreach($checklists as $checklist)
                            <div class="row item-wrap @if (in_array($checklist->id, $checklistIDs ) ) active @endif">
                                <div class="col-md-9">
                                    <div class="media">
                                        <i class="{{$checklist->icon_code}}"></i>
                                        <div class="media-body">
                                            <h4>{{ str_limit($checklist->title, 26) }}</h4>
                                            <p>{{ str_limit($checklist->description, 74) }}</p>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-md-3 pl-0">
                                    <a class="go-btn" href="javascript:;"  onclick="viewChecklistDetails({{$checklist->id}})">Go</a>
                                    <div class="checkbox checkbox-primary">
                                        <input id="board-checkbox-{{$checklist->id}}" name="checklist_id[]" type="checkbox" value="{{$checklist->id}}" @if (in_array($checklist->id, $checklistIDs ) ) checked @endif />
                                        <label for="board-checkbox-{{$checklist->id}}"></label>
                                    </div>
                                </div>
                            </div>
                            @endforeach                         

                        </div>

                    </div>
                    @endif
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var checklistCheckbox = '.checklist-items input[type="checkbox"]';
$(checklistCheckbox).click(function(){
    
    if($(this).is(":checked")) {
        $(this).closest('.row').addClass('active');
    } else {
        $(this).closest('.row').removeClass('active');
    }
            
    $.easyAjax({
        url: '{{route('admin.dashboard.add-checklist')}}',
        container: '#adminChecklist',
        type: "POST",
        redirect: false,
        data: $('#adminChecklist').serialize(),
        success: function (response) {
            //console.log(response);
            $('.checkedCount').html(response.count);
            $('.white-bg .progress-bar').css('width', response.percentage+'%')

            if(response.percentage == '100')
                $('#checklist-percentage').hide();
            else
                $('#checklist-percentage').show();
        }
    })
});
</script>