<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/WardModel.php";
require_once "../../model/UserModel.php";
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

$payload['ward_id'] = isset($body['ward_id']) ? $body['ward_id'] : null;
$payload['capacity'] = isset($body['capacity']) ? $body['capacity'] : null;

if (empty($payload['ward_id']) || empty($payload['capacity']))
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "Missing required fields",
    ]
  );

$userModel = new UserModel();
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

if (!$payload['capacity'])
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "ward capacity must be greater than zero",
    ]
  );

$wardData = $wardModel->findWardByWardId($payload['ward_id']);

if ($payload['capacity'] < $wardData['current_patients_count'])
  return response(
    [
      "statusCode" => 403,
      "success" => false,
      "message" => "ward capacity can't be less then word patients count",
    ]
  );


$isUpdated = $wardModel->update($payload['ward_id'], $payload['capacity']);

if (!$isUpdated)
  return response();

response([
  "statusCode" => 200,
  "success" => true,
  "message" => "doctor updated successfully",
]);