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

$patientId = $body['patient_id'];

if (empty($patientId))
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "Missing required fields",
    ]
  );

$patientDoctorModel = new PatienDoctortModel();

$doctorList = $patientDoctorModel->findDoctorsByPatientId($patientId);

if (!$doctorList)
  return response();

response([
  "statusCode" => 200,
  "success" => true,
  "message" => "doctors found successfully",
  "data" => $doctorList
]);