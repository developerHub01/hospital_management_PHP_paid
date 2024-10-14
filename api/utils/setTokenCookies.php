<?php

function setAccessTokenCookies($tokenName = "access_token", $token, $expiryInSeconds = 3600)
{
  $expiryTime = time() + $expiryInSeconds;

  setcookie(
    $tokenName,
    $token,
    $expiryTime,
    '/',
    null,
    true,
  );
}