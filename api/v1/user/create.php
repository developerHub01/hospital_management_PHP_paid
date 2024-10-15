<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/UserModel.php";

if ($_SERVER['REQUEST_METHOD'] != "POST")
  return response();

$rawData = file_get_contents("php://input");
$body = json_decode($rawData, true);

if (!isset($body))
  return response([
    "statusCode" => 400,
    "success" => false,
    "message" => "Invalid JSON body"
  ]);

$payload = [];

$payload['name'] = isset($body['name']) ? trim($body['name']) : null;
$payload['email'] = isset($body['email']) ? trim($body['email']) : null;
$payload['password'] = isset($body['password']) ? password_hash(trim($body['password']), PASSWORD_BCRYPT) : null;


if (empty($payload['name']) || empty($payload['email']) || empty($payload['password']))
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "Missing required fields",
    ]
  );

$userModel = new UserModel();
$isCreated = $userModel->createUser($payload);

if (!$isCreated)
  return response();


response([
  "statusCode" => 201,
  "success" => true,
  "message" => "user created successfully",
]);