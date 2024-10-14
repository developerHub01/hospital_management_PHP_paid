<?php

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../config/dotenv.php";
use Firebase\JWT\JWT;

function createToken($payload)
{
   global $config;
   return JWT::encode($payload, $config['JWT_SECRET_KEY'], 'HS256');
}