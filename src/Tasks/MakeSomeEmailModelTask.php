<?php

namespace App\Tasks;

use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use Faker\Factory;

class MakeSomeEmailModelTask
{
    public static function run(): MultitextCustomFieldValuesModel
    {
        return MakeSomeMultitextCustomFieldValuesModelTask::run(
            'EMAIL',
            (Factory::create('ru_RU'))->email()
        );
    }
}
