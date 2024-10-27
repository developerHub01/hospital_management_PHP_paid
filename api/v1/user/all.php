<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/UserModel.php";

if ($_SERVER['REQUEST_METHOD'] !== "GET")
  return response();

$userModel = new UserModel();
$userList = $userModel->index();

if (!$userList)
  return response();


response([
  "statusCode" => 200,
  "success" => true,
  "message" => "users found successfully",
  "data" => $userList,
]);