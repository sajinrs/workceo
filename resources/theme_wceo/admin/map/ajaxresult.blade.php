
    @if($projects)
        @foreach($projects as $project)
            <li id="jobs_{{$project->id}}" class="OpenJobsInfowindow">
                <h4 class="job_title">{{$project->project_name}}</h4>
                <p class="date">
                {{ \Carbon\Carbon::parse($project->deadline)->format('d')}}
                <span>{{ \Carbon\Carbon::parse($project->deadline)->format('M')}}</span></p>
                <span class="client_name">{{ str_limit($project->client->company_name, 30) }}</span>
                <span class="address">{{ str_limit($project->client->address, 55) }}</span>
            </li>
        @endforeach
    @endif

    @if($clients)
        @foreach($clients as $client)
            <li id="clients_{{$client->id}}" class="OpenClientsInfowindow">
                <h4>{{$client->company_name}}</h4>
                <span class="client_name">{{ str_limit($client->name, 30) }}</span>
                <span class="address">{{ str_limit($client->address, 55) }}</span>
            </li>
        @endforeach
    @endif

    @if($leads)
        @foreach($leads as $lead)
        @php $leadName = $lead->client_first_name.' '.$lead->client_last_name; @endphp
            <li id="leads_{{$lead->id}}" class="OpenLeadsInfowindow">
                <h4 class="lead">{{$lead->company_name}}</h4>
                <span class="client_name">{{ str_limit($leadName, 30) }}</span>
                <span class="address">{{ str_limit($lead->address, 55) }}</span>
            </li>
        @endforeach
    @endif

    @if($employees)
        @foreach($employees as $emp)
            <li id="employees_{{$emp->id}}" class="OpenEmployeesInfowindow">
                <div class="media">
                    <img class="rounded-circle image-radius m-r-15" src="{{ $emp->image_url }}" alt="user" data-original-title="" title="" width="58" height="58">
                    <div class="media-body">
                        <h4 class="lead">{{ str_limit($emp->name, 30) }}</h4>
                        <span class="address">{{($emp->employeeDetail->designation->name)??''}}</span>
                        <span class="address">{{($emp->employeeDetail->department->team_name)??''}}</span>
                    </div>
                </div>
            </li>
        @endforeach
    @endif

    @if($vehicles)
        @foreach($vehicles as $vehicle)
            <li id="vehicles_{{$vehicle->id}}" class="OpenVehiclesInfowindow">
                <div class="media">
                    <img class="image-radius m-r-15" src="{{ $vehicle->image_url }}" alt="user" data-original-title="" title="" width="58" height="58">
                    <div class="media-body">
                        <h4 class="lead">{{ str_limit($vehicle->vehicle_name, 30) }}</h4>
                        <span class="address">{{($vehicle->license_plate )??''}}</span>
                        <span class="address">{{($vehicle->operator->name)??''}}</span>
                    </div>
                </div>
            </li>
        @endforeach
    @endif



