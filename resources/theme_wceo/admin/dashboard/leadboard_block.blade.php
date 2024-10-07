<div class="new-users db_leadboard">
    @foreach($employees as $employee)
        <div class="media">
            <img class="rounded-circle image-radius m-r-15" width="58" height="58" src="{{$employee->image_url}}"  alt="">
            <div class="media-body">
                <h7 class="mb-0  f-w-600">{{$employee->name}}
                    <span class="f-z-10 f-w-400">
                        ({{ (!is_null($employee->member) && ($employee->member->count() >0)) ? ($employee->member->count().'+Projects') : 'No Projects' }})
                        {{--(250+Online)--}}
                    </span>
                </h7>
            </div>
            <span class="pull-right user_role"><p class="f-z-10">
                   {{ (!is_null($employee->employeeDetail) && !is_null($employee->employeeDetail->designation)) ? ucwords($employee->employeeDetail->designation->name) : 'NA' }}
                </p></span>
        </div>
    @endforeach
</div>

<script>

    $(document).ready(function () {
        $('.db_leadboard').slimScroll({
            height: '190',
            position: 'right',
            color: '#dcdcdc'
        });
    });

    </script>