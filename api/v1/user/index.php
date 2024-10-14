<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../../config/dotenv.php";
$JWT_SECRET = $_ENV['JWT_SECRET_KEY'];

echo json_encode(["message" => "hello world"]);
