<div class="card">
    <div class="row product-page-main">
        <div class="col-sm-12">

            <ul class="showProjectTabs nav nav-tabs border-tab mb-0" id="top-tab" role="tablist">
                <li class="nav-item projects">
                    <a class="nav-link" href="{{ route('client.projects.show', $project->id) }}"><span>@lang('modules.projects.overview')</span></a>
                </li>
                @if(in_array('employees',$modules))
                    <li class="nav-item projectMembers">
                        <a class="nav-link" href="{{ route('client.project-members.show', $project->id) }}"><span>@lang('modules.projects.members')</span></a>
                    </li>
                @endif
                
                @if($project->client_view_task == 'enable' && in_array('tasks',$modules))
                    <li class="nav-item projectTasks">
                        <a class="nav-link" href="{{ route('client.tasks.edit', $project->id) }}"><span>@lang('app.menu.tasks')</span></a>
                    </li>
                @endif
                <li class="nav-item projectFiles">
                    <a class="nav-link" href="{{ route('client.files.show', $project->id) }}"><span>@lang('modules.projects.files')</span></a>
                </li>
               
                
                    <li class="nav-item projectTimelogs">
                        <a class="nav-link" href="{{ route('client.time-log.show', $project->id) }}"><span>@lang('app.menu.timeLogs')</span></a>
                    </li>
                

                @if(in_array('invoices',$modules))
                    <li class="nav-item projectInvoices">
                        <a class="nav-link" href="{{ route('client.project-invoice.show', $project->id) }}"><span>@lang('app.menu.invoices')</span></a>
                    </li>
                @endif             
            </ul>

        </div>
    </div>
</div>