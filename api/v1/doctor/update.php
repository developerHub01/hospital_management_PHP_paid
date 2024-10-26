<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/DoctorModel.php";
require_once "../../model/UserModel.php";
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

$payload['id'] = isset($body['id']) ? $body['id'] : null;
$payload['specialization'] = isset($body['specialization']) ? trim($body['specialization']) : null;

if (empty($payload['id']) || empty($payload['specialization']))
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "Missing required fields",
    ]
  );

$userModel = new UserModel();
$doctorModel = new DoctorModel();

$currentUserData = $userModel->readCurrentUserData();

if (!$currentUserData || !in_array($currentUserData['role'], ["admin", "super_admin"]))
  return response(
    [
      "statusCode" => 401,
      "success" => false,
      "message" => "unauthorized access",
    ]
  );

$isUpdated = $doctorModel->update($payload['id'], $payload['specialization']);

if (!$isUpdated)
  return response();

response([
  "statusCode" => 200,
  "success" => true,
  "message" => "doctor updated successfully",
]);