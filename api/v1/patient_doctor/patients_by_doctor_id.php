<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/PatientModel.php";
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

$doctorId = $body['doctor_id'];

if (empty($doctorId))
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "Missing required fields",
    ]
  );

$patientDoctorModel = new PatienDoctortModel();

$patientList = $patientDoctorModel->findPatientsByDoctorId($doctorId);

if (!$patientList)
  return response();

response([
  "statusCode" => 200,
  "success" => true,
  "message" => "patients found successfully",
  "data" => $patientList
]);