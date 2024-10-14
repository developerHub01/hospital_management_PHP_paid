<?php


$dotenv_loader_path = __DIR__ . "/../vendor/autoload.php";
require_once $dotenv_loader_path;

// echo __DIR__ . "/../../vendor/autoload.php";

$dotenv_file_path = __DIR__ . "/../";

$dotenv = Dotenv\Dotenv::createImmutable($dotenv_file_path);
$dotenv->load();
