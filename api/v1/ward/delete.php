<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/WardModel.php";
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

$wardId = $body['ward_id'];

if (empty($wardId))
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "Missing required fields",
    ]
  );

$userModel = new UserModel();
$wardModel = new WardModel();

$currentUserData = $userModel->readCurrentUserData();

if (!$currentUserData || !in_array($currentUserData['role'], ["admin", "super_admin"]))
  return response(
    [
      "statusCode" => 401,
      "success" => false,
      "message" => "unauthorized access",
    ]
  );

$wardData = $wardModel->findWardByWardId($wardId);

if ($wardData['current_patients_count'])
  return response(
    [
      "statusCode" => 403,
      "success" => false,
      "message" => "ward can't delete. ward have patients",
    ]
  );

$isDeleted = $wardModel->delete($wardId);

if (!$isDeleted)
  return response();


response([
  "statusCode" => 200,
  "success" => true,
  "message" => "ward deleted successfully",
]);