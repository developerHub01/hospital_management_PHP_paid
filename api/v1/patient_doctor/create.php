<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/PatientModel.php";
require_once "../../model/UserModel.php";
require_once "../../model/PatienDoctortModel.php";
require_once "../../utils/readToken.php";

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

$payload = [];

$payload['patient_id'] = isset($body['patient_id']) ? $body['patient_id'] : null;
$payload['doctor_id'] = isset($body['doctor_id']) ? trim($body['doctor_id']) : null;

if (empty($payload['patient_id']) || empty($payload['doctor_id']))
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "Missing required fields",
    ]
  );

$userModel = new UserModel();
$patientDoctorModel = new PatienDoctortModel();

$currentUserData = $userModel->readCurrentUserData();

if (!$currentUserData || !in_array($currentUserData['role'], ["admin", "super_admin"]))
  return response(
    [
      "statusCode" => 401,
      "success" => false,
      "message" => "unauthorized access",
    ]
  );

$isCreated = $patientDoctorModel->create($payload);

if (!$isCreated)
  return response();

response([
  "statusCode" => 201,
  "success" => true,
  "message" => "patient doctor connection created successfully",
]);