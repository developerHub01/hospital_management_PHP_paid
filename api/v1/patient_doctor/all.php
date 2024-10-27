<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/PatienDoctortModel.php";

if ($_SERVER['REQUEST_METHOD'] !== "GET")
  return response();

$patientDoctorModel = new PatienDoctortModel();
$patientDoctorList = $patientDoctorModel->index();

if (!$patientDoctorList)
  return response();


response([
  "statusCode" => 200,
  "success" => true,
  "message" => "users found successfully",
  "data" => $patientDoctorList,
]);