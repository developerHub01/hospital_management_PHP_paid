<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../utils/uploadImage.php";
require_once "../../utils/geLoggedInUser.php";
require_once "../../model/UserModel.php";


if ($_SERVER['REQUEST_METHOD'] != "POST")
  return response();

$rawData = file_get_contents("php://input");

$body = json_decode($rawData, true);

if (!isset($body['id']))
  return response([
    "statusCode" => 400,
    "success" => false,
    "message" => "Missing user ID",
  ]);

if ($_SERVER['REQUEST_METHOD'] !== "POST")
  return response();

$userModel = new UserModel();
$myId = getLoggedInUser();

$myData = $userModel->findById($myId);

$fieldToUpdate = [];
$params = [];


if (isset($body['name'])) {
  $fieldToUpdate[] = "name = :name";
  $params[":name"] = $body['name'];
}
if (isset($body['dob'])) {
  $fieldToUpdate[] = "dob = :dob";
  $params[":dob"] = $body['dob'];
}

if (isset($body['email'])) {
  $userDataByEmail = $userModel->findUserByEmail($body['email']);
  if ($userDataByEmail && $userDataByEmail['id'] !== $myId)
    response([
      "statusCode" => 400,
      "success" => false,
      "message" => "email is already taken",
    ]);

  $fieldToUpdate[] = "email = :email";
  $params[":email"] = $body['email'];
}

if (isset($body['password']) && !empty($body['password'])) {
  $fieldToUpdate[] = "password = :password";
  $params[":password"] = password_hash($body['password'], PASSWORD_BCRYPT);
}

if (!count($fieldToUpdate))
  return response([
    "statusCode" => 400,
    "message" => "nothing to update",
  ]);

$updatedUserData = $userModel->updateUser($body['id'], $fieldToUpdate, $params);

if (!$updatedUserData)
  return response();

response([
  "statusCode" => 200,
  "success" => true,
  "message" => "user updated successfully",
  "data" => $updatedUserData
]);