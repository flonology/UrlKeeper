<?php
use Model\App;
use Model\Request;
use Service\ConfigParams;

include __DIR__ . '/../app/bootstrap.php';

$request = Request::createFromGlobals();
$configParams = new ConfigParams(
    APP_PATH,
    APP_URL_BASE,
    APP_INDEX,
    APP_TEMPLATE_PATH,
    APP_TITLE,
    APP_VERSION,
    APP_DATABASE_FILE
);

$app = new App($configParams);
echo $app->run($request);
