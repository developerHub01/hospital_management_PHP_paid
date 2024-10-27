<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/UserModel.php";
require_once "../../utils/geLoggedInUser.php";

if ($_SERVER['REQUEST_METHOD'] !== "GET")
  return response();

$userId = getLoggedInUser();

$userModel = new UserModel();
$userData = $userModel->findFullUserById($userId);

if (!$userData)
  return response([
    "data" => $userId
  ]);


response([
  "statusCode" => 200,
  "success" => true,
  "message" => "user found successfully",
  "data" => $userData
]);