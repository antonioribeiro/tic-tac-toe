<?php

use App\Application;

require '../vendor/autoload.php';

(new Application())->run()->send();
