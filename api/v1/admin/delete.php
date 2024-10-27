<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/AdminModel.php";
require_once "../../model/UserModel.php";
require_once "../../utils/readToken.php";

if ($_SERVER['REQUEST_METHOD'] != "DELETE")
  return response();

$rawData = file_get_contents("php://input");

$body = json_decode($rawData, true);

if (!isset($body))
  return response([
    "statusCode" => 400,
    "success" => false,
    "message" => "Invalid JSON body"
  ]);

$adminId = $body['admin_id'];

if (empty($adminId))
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

$isDeleted = $adminModel->delete($adminId);

if (!$isDeleted)
  return response();

response([
  "statusCode" => 200,
  "success" => true,
  "message" => "admin deleted successfully",
]);