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

if ($_SERVER['REQUEST_METHOD'] !== "DELETE")
  return response();

$rawData = file_get_contents("php://input");

$body = json_decode($rawData, true);

if (!isset($body))
  return response([
    "statusCode" => 400,
    "success" => false,
    "message" => "Invalid JSON body"
  ]);

$patientId = $body['patient_id'];

if (empty($patientId))
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "Missing required fields",
    ]
  );

$userModel = new UserModel();
$patientModel = new PatientModel();

$currentUserData = $userModel->readCurrentUserData();

if (!$currentUserData || !in_array($currentUserData['role'], ["admin", "super_admin"]))
  return response(
    [
      "statusCode" => 401,
      "success" => false,
      "message" => "unauthorized access",
    ]
  );

$isDeleted = $patientModel->delete($patientId);

if (!$isDeleted)
  return response();

response([
  "statusCode" => 200,
  "success" => true,
  "message" => "doctor deleted successfully",
]);