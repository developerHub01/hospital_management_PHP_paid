<?php
$site_name = "Prime Hospital";
$page_title = $site_name;

$base_name = basename($_SERVER['PHP_SELF']);

switch ($base_name) {
  case "about.php":
    $page_title = "About Us - " . $site_name;
    break;
  default:
    $page_title = $site_name;
    break;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title ?></title>
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link rel="stylesheet" href="../css/styles.css">
</head>

<body>