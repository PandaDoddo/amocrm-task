<?php

namespace App\Helpers;

use AmoCRM\Collections\Leads\LeadsCollection;
use App\Actions\MakeSomeLeadModelAction;

class MakeSomeLeadCollectionHelper
{
    public static function run(int $count): LeadsCollection
    {
        $collection = new LeadsCollection();
        for ($i = 0; $i < $count; $i++) {
            $collection->add(MakeSomeLeadModelAction::run());
        }

        return $collection;
    }
}
