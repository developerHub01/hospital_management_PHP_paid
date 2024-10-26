<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require_once "../../utils/readToken.php";
require_once "../../model/UserModel.php";


$userModel = new UserModel();
$userModel->readCurrentUserData();

// $data = readToken("access_token");

// print_r($data);



echo json_encode(["message" => "hello world"]);
