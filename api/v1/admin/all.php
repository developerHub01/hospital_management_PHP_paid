<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/AdminModel.php";

if ($_SERVER['REQUEST_METHOD'] !== "GET")
  return response();

$adminModel = new AdminModel();
$adminList = $adminModel->index();

if (!$adminList)
  return response();

response([
  "statusCode" => 200,
  "success" => true,
  "message" => "admins found successfully",
  "data" => $adminList,
]);