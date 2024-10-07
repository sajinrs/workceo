<?php

namespace App\Observers;

use App\Invoice;
use App\Project;
use App\User;
use App\Notifications\NewInvoice;
use App\UniversalSearch;
use Illuminate\Support\Facades\File;

class ProjectObserver
{

    public function saving(Project $project)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $project->company_id = company()->id;
        }
    }

    public function deleting(Project $project)
    {
        File::deleteDirectory('user-uploads/project-files/' . $project->id);

        $universalSearches = UniversalSearch::where('searchable_id', $project->id)->where('module_type', 'project')->get();
        if ($universalSearches){
            foreach ($universalSearches as $universalSearch){
                UniversalSearch::destroy($universalSearch->id);
            }
        }
    }

    public function updated(Project $project)
    {        
        if (!isRunningInConsoleOrSeeding()) {            
            if ($project->client_id != null) {
                if($project->job_status == 'invoice')
                {
                    $clientId = $project->client_id;
                    if(!empty($project->invoices))
                    {
                        foreach($project->invoices as $invoice)
                        {
                            // Notify client
                            $notifyUser = User::withoutGlobalScopes(['company', 'active'])->findOrFail($clientId);
                            $notifyUser->notify(new NewInvoice($invoice));
                        }
                    }                    
                }
            }
        }
    }    

}
