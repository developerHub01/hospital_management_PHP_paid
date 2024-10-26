<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/AdminModel.php";
require_once "../../model/UserModel.php";
require_once "../../utils/readToken.php";

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

$userId = isset($body['user_id']) ? $body['user_id'] : null;

if (empty($userId))
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "Missing required fields",
    ]
  );

$userModel = new UserModel();
$adminModel = new AdminModel();

$currentUserData = $userModel->readCurrentUserData();

if (!$currentUserData || $currentUserData['role'] !== "super_admin")
  return response(
    [
      "statusCode" => 401,
      "success" => false,
      "message" => "unauthorized access",
    ]
  );

$isCreated = $adminModel->create($userId);

if (!$isCreated)
  return response();


response([
  "statusCode" => 201,
  "success" => true,
  "message" => "admin created successfully",
]);