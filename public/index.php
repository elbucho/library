<?php

require_once(dirname(__DIR__) . '/src/Framework/bootstrap.php');

try {
    require_once(FRAMEWORK_DIR . '/routes.php');
    require_once(FRAMEWORK_DIR . '/security.php');

    $app->run();
} catch (\Exception $e) {

}
