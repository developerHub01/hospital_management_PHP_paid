<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/DoctorModel.php";

if ($_SERVER['REQUEST_METHOD'] !== "GET")
  return response();

$doctorModel = new DoctorModel();
$doctorList = $doctorModel->index();

if (!$doctorList)
  return response();

response([
  "statusCode" => 200,
  "success" => true,
  "message" => "patients found successfully",
  "data" => $doctorList,
]);