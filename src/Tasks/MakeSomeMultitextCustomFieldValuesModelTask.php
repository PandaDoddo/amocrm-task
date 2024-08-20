<?php

namespace App\Tasks;

use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;

class MakeSomeMultitextCustomFieldValuesModelTask
{
    public static function run(string $code, mixed $value): MultitextCustomFieldValuesModel
    {
        return (new MultitextCustomFieldValuesModel())
            ->setFieldCode($code)
            ->setValues(
                (new MultitextCustomFieldValueCollection())
                    ->add(
                        (new MultitextCustomFieldValueModel())
                            ->setValue($value)
                    )
            );
    }
}
