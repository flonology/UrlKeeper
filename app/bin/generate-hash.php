#!/usr/bin/php

<?php
if ($argc < 2) {
    echo 'Syntax: generate-hash.php <string>' . PHP_EOL . PHP_EOL;
    exit();
}

$given_string = $argv[1];
printf(
    'Generated hash for %s: %s%s',
    $given_string, password_hash($given_string, PASSWORD_DEFAULT),
    PHP_EOL
);
