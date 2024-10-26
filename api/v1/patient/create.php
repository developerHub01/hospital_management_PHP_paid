<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/PatientModel.php";
require_once "../../model/UserModel.php";
require_once "../../model/WardModel.php";
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

$payload = [];

$payload['user_id'] = isset($body['user_id']) ? $body['user_id'] : null;
$payload['ward_id'] = isset($body['ward_id']) ? trim($body['ward_id']) : null;

if (empty($payload['user_id']) || empty($payload['ward_id']))
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "Missing required fields",
    ]
  );

$userModel = new UserModel();
$patientModel = new PatientModel();
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

$wardData = $wardModel->findWardByWardId($payload['ward_id']);

if (!$wardData)
  return response(
    [
      "statusCode" => 404,
      "success" => false,
      "message" => "ward not found",
    ]
  );

if ($wardData['capacity'] - $wardData['current_patients_count'] < 1)
  return response(
    [
      "statusCode" => 403,
      "success" => false,
      "message" => "ward is full",
    ]
  );

$isCreated = $patientModel->create($payload);

if (!$isCreated)
  return response();


response([
  "statusCode" => 201,
  "success" => true,
  "message" => "patient created successfully",
]);