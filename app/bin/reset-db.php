#!/usr/bin/php

<?php
use TestHelper\Db;

include __DIR__ . '/../bootstrap.php';
echo 'Resetting db... ';

unlink(APP_DATABASE_FILE);
Db::initDb(APP_DATABASE_FILE);

echo 'done.' . PHP_EOL;
