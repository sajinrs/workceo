<?php

namespace App\Observers;


use App\GroupTaskCategory;

class GroupTaskCategoryObserver
{

    public function saving(GroupTaskCategory $category)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $category->company_id = company()->id;
        }
    }

}
