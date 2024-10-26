<?php

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../config/dotenv.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function readToken($cookieName)
{
  global $config;

  if (!isset($_COOKIE[$cookieName])) {
    return ['error' => 'Token not found'];
  }

  try {
    $cookie = $_COOKIE[$cookieName];

    return (array) JWT::decode($cookie, new Key($config['JWT_SECRET_KEY'], 'HS256'));
  } catch (Exception $e) {
    return ['error' => $e->getMessage()];
  }
}