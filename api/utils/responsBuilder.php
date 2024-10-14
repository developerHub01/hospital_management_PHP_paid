<?php
function response($payload = [])
{
  $payload['statusCode'] ??= 500;
  $payload['success'] ??= false;
  $payload['message'] ??= "something went wrong";
  
  http_response_code($payload['statusCode']);
  echo json_encode($payload);
  return;
}