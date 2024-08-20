<?php

namespace App\Tasks;

use AmoCRM\Models\CompanyModel;
use Faker\Factory;

class MakeSomeCompanyModelTask
{
    public static function run(): CompanyModel
    {
        return (new CompanyModel())->setName(
            Factory::create('ru_RU')->company()
        );
    }
}
