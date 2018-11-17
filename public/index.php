<?php

use App\Application;

require '../vendor/autoload.php';

$response = (new Application())->run();

$response->send();
