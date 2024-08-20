<?php

namespace App\Tasks;

use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use Faker\Factory;

class MakeSomePhoneModelTask
{
    public static function run(): MultitextCustomFieldValuesModel
    {
        return MakeSomeMultitextCustomFieldValuesModelTask::run(
            'PHONE',
            (Factory::create('ru_RU'))->phoneNumber()
        );
    }
}
