<?php

namespace App\Actions;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Models\LeadModel;
use App\Tasks\MakeSomeCompanyModelTask;
use Faker\Factory;

class MakeSomeLeadModelAction
{
    public static function run(): LeadModel
    {
        $faker = Factory::create('ru_RU');

        return (new LeadModel())
            ->setName($faker->words(2, true))
            ->setPrice($faker->numberBetween(100, 1000))
            ->setContacts(
                (new ContactsCollection())
                    ->add(MakeSomeContactSubAction::run())
            )
            ->setCompany(
                MakeSomeCompanyModelTask::run()
            )
            ->setRequestId(
                uniqid(
                    'lead_model',
                    true
                )
            );
    }
}
