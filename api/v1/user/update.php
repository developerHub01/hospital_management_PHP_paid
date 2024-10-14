<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../utils/uploadImage.php";

$avatarFolder = "/avatar";

if ($_SERVER['REQUEST_METHOD'] !== "POST")
  return response();

$isAvatarExist = isset($_FILES['avatar']) && $_FILES['avatar']['error'];

$avatar = "";

if ($isAvatarExist)
  $avatarPath = uploadImage($_FILES['avatar'], $avatarFolder);


response([
  "statusCode" => 201,
  "success" => true,
  "message" => "user created successfully",
]);