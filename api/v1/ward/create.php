<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../utils/readToken.php";
require_once "../../model/UserModel.php";
require_once "../../model/WardModel.php";

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

$capacity = isset($body['capacity']) ? trim($body['capacity']) : null;

if (empty($capacity))
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "Missing required fields",
    ]
  );

if (!$capacity)
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "ward capacity must be greater than zero",
    ]
  );

$userModel = new UserModel();

$currentUserData = $userModel->readCurrentUserData();

if (!$currentUserData || !in_array($currentUserData['role'], ["admin", "super_admin"]))
  return response(
    [
      "statusCode" => 401,
      "success" => false,
      "message" => "unauthorized access",
    ]
  );

$wardModel = new WardModel();

$isCreated = $wardModel->create($capacity);

if (!$isCreated)
  return response();


response([
  "statusCode" => 201,
  "success" => true,
  "message" => "ward created successfully",
]);