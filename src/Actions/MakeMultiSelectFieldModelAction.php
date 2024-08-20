<?php

namespace App\Actions;

use AmoCRM\Collections\CustomFields\CustomFieldEnumsCollection;
use AmoCRM\Models\CustomFields\EnumModel;
use AmoCRM\Models\CustomFields\MultiselectCustomFieldModel;

class MakeMultiSelectFieldModelAction
{
    public static function run(string $name, string $code, array $values): MultiSelectCustomFieldModel
    {
        $options = (new CustomFieldEnumsCollection());

        foreach ($values as $key => $value) {
            $options->add(
                (new EnumModel())
                    ->setValue($value)
                    ->setSort($key)
            );
        }

        return (new MultiSelectCustomFieldModel())
            ->setName($name)
            ->setCode($code)
            ->setEnums(
                $options
            );
    }
}
