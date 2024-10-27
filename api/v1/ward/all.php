<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/WardModel.php";

if ($_SERVER['REQUEST_METHOD'] !== "GET")
  return response();

$wardModel = new WardModel();
$wardList = $wardModel->index();

if (!$wardList)
  return response();


response([
  "statusCode" => 200,
  "success" => true,
  "message" => "wards found successfully",
  "data" => $wardList,
]);