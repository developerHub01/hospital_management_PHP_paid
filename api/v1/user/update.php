<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

include_once "../../utils/responsBuilder.php";

if ($_SERVER['REQUEST_METHOD'] != "PATCH")
  return response();

response([
  "statusCode" => 201,
  "success" => true,
  "message" => "user created successfully",
]);