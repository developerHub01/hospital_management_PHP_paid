<?php

require_once __DIR__ . "/readToken.php";

function getLoggedInUser()
{
  $userTokenData = readToken("access_token");
  return $userTokenData['id'];
}