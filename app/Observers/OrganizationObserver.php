<?php

namespace App\Observers;

use App\Models\Organization;

class OrganizationObserver
{
    public function saved(Organization $organization)
    {
    }

    public function deleted(Organization $organization)
    {
    }
}
