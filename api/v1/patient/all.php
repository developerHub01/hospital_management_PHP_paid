<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/PatientModel.php";

if ($_SERVER['REQUEST_METHOD'] !== "GET")
  return response();

$patientModel = new PatientModel();
$patientList = $patientModel->index();

if (!$patientList)
  return response();

response([
  "statusCode" => 200,
  "success" => true,
  "message" => "patients found successfully",
  "data" => $patientList,
]);