<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/UserModel.php";

if ($_SERVER['REQUEST_METHOD'] !== "POST")
  return response();

$rawData = file_get_contents("php://input");

$body = json_decode($rawData, true);

if (!isset($body))
  return response([
    "statusCode" => 400,
    "success" => false,
    "message" => "Invalid JSON body"
  ]);

$userId = $body['user_id'];


if (empty($userId))
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "Missing required fields",
    ]
  );

$userModel = new UserModel();
$userData = $userModel->findById($userId);

if (!$userData)
  return response();


response([
  "statusCode" => 200,
  "success" => true,
  "message" => "user found successfully",
  "data" => $userData
]);