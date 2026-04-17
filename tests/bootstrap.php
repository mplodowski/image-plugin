<?php

require __DIR__.'/../../../../modules/system/tests/bootstrap.php';

$pestConfigFile = __DIR__.'/Pest.php';

if (class_exists('Pest\TestSuite', false) && file_exists($pestConfigFile)) {
    require_once $pestConfigFile;
}
