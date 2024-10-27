<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/PatienDoctortModel.php";
require_once "../../model/UserModel.php";
require_once "../../utils/readToken.php";

if ($_SERVER['REQUEST_METHOD'] != "DELETE")
  return response();

$rawData = file_get_contents("php://input");

$body = json_decode($rawData, true);

$patient_id = $body['patient_id'];
$doctor_id = $body['doctor_id'];

if (!isset($body))
  return response([
    "statusCode" => 400,
    "success" => false,
    "message" => "Invalid JSON body"
  ]);

if (empty($patient_id) || empty($doctor_id))
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

$isDeleted = $patientDoctorModel->delete($patient_id, $doctor_id);

if (!$isDeleted)
  return response();

response([
  "statusCode" => 200,
  "success" => true,
  "message" => "doctor deleted successfully",
]);