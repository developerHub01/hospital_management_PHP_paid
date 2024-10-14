<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/respons_builder.php";
require_once "../../model/UserModel.php";
require_once "../../utils/create_token.php";
require_once "../../utils/setTokenCookies.php";

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

$payload['email'] = isset($body['email']) ? trim($body['email']) : null;
$payload['password'] = isset($body['password']) ? trim($body['password']) : null;


if (empty($payload['email']) || empty($payload['password']))
  return response(
    [
      "statusCode" => 400,
      "success" => false,
      "message" => "Missing required fields",
    ]
  );

$userModel = new UserModel();
$userData = $userModel->isCredentialsMatched($payload);

if (!$userData)
  return response([
    "statusCode" => 403,
    "success" => false,
    "message" => "Invalid email or password",
  ]);


unset($userData['password']);

$token = createToken(["id" => $userData['id']]);

setAccessTokenCookies("access_token", $token, 7 * 24 * 60 * 60);

response([
  "statusCode" => 200,
  "success" => true,
  "message" => "logged in successfully",
  "data" => $userData
]);