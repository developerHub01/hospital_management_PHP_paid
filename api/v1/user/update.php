<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/responsBuilder.php";
require_once "../../utils/uploadImage.php";
require_once "../../model/UserModel.php";

$avatarFolder = "avatar";

$body = $_POST;

print_r($body);

if (!isset($body['id']))
  return response([
    "statusCode" => 400,
    "success" => false,
    "message" => "Missing user ID",
  ]);

if ($_SERVER['REQUEST_METHOD'] !== "POST")
  return response();

$userModel = new UserModel();

$isAvatarExist = isset($_FILES['avatar']) && !$_FILES['avatar']['error'];

$fieldToUpdate = [];
$params = [];



if ($isAvatarExist) {
  $avatarPath = uploadImage($_FILES['avatar'], $avatarFolder);
  $fieldToUpdate[] = "avatar = :avatar";
  $params[":avatar"] = $avatarPath;
}
if (isset($body['name'])) {
  $fieldToUpdate[] = "name = :name";
  $params[":name"] = $body['name'];
}
if (isset($body['email'])) {
  if ($userModel->isEmailExist($body['email']))
    response([
      "statusCode" => 400,
      "success" => false,
      "message" => "email is already taken",
    ]);

  $fieldToUpdate[] = "email = :email";
  $params[":email"] = $body['email'];
}
if (isset($body['password'])) {
  $fieldToUpdate[] = "password = :password";
  $params[":password"] = password_hash($body['password'], PASSWORD_BCRYPT);
}

if (!count($fieldToUpdate))
  return response([
    "statusCode" => 400,
    "message" => "nothing to update",
  ]);


print_r($fieldToUpdate);
$updatedUserData = $userModel->updateUser($body['id'], $fieldToUpdate, $params);


if (!$updatedUserData)
  return response();

response([
  "statusCode" => 200,
  "success" => true,
  "message" => "user updated successfully",
  "data" => $updatedUserData
]);