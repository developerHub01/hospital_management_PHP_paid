<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../model/UserModel.php";
require_once "../../utils/createToken.php";
require_once "../../utils/setTokenCookies.php";

if ($_SERVER['REQUEST_METHOD'] != "GET")
  return response();


setAccessTokenCookies("access_token", "", -7 * 24 * 60 * 60);

response([
  "statusCode" => 200,
  "success" => true,
  "message" => "logout successfully",
]);