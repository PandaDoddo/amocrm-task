<?php

namespace App\Actions;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\ContactModel;
use App\Tasks\MakeSomeEmailModelTask;
use App\Tasks\MakeSomePhoneModelTask;
use Faker\Factory;

class MakeSomeContactSubAction
{
    public static function run(): ContactModel
    {
        $faker = Factory::create('ru_RU');

        return (new ContactModel())
            ->setFirstName($faker->firstName())
            ->setLastName($faker->lastName())
            ->setCustomFieldsValues(
                (new CustomFieldsValuesCollection())
                    ->add(MakeSomePhoneModelTask::run())
                    ->add(MakeSomeEmailModelTask::run())
            );
    }
}
