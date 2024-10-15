<?php

require_once "responsBuilder.php";

function uploadImage($image, $directory_name)
{
  $mainPath = "uploads/$directory_name";
  $path = __DIR__ . "/../../public/$mainPath";
  /* if path not exist */
  if (!is_dir($path))
    mkdir($path, 0777, true);

  $allowedExtensions = ["png", "jpg", "jpeg", "gif"];

  $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

  if (!in_array($fileExtension, $allowedExtensions)) {
    response([
      "statusCode" => 400,
      "message" => "Invalid file type",
    ]);
    exit();
  }

  $newFile = uniqid() . "." . $fileExtension;
  $filePath = $path . "/" . $newFile;

  if (!move_uploaded_file($image['tmp_name'], $filePath)) {
    response([
      "statusCode" => 500,
      "message" => "error uploading file",
    ]);
    exit();
  }

  print_r(move_uploaded_file($image['tmp_name'], $filePath));

  return "$mainPath/$newFile";
}