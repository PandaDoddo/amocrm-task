<?php

namespace App;

use App\Services\MainService;

include_once __DIR__.'/vendor/autoload.php';

$mainService = new MainService();

// create leads with company and contact
$ids = $mainService->scriptPartA();

// create custom field for leads
$field = $mainService->scriptPartB();

// set value in new field in leads
$mainService->scriptPartC($ids, $field);
