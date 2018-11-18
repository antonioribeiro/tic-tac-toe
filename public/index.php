<?php

use App\Services\Application;

require '../vendor/autoload.php';

(new Application())->run()->send();
