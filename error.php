<?php
/**
 * Created by PhpStorm.
 * User: Wisdom Emenike
 * Date: 7/17/2020
 * Time: 2:26 PM
 */

session_start();

$message = $_SESSION['message'] ?? "We're not sure why you came here. Please go back.";

echo <<<HTML

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apply</title>
    <link href="application/error.css" rel="stylesheet" type="text/css">
</head>
<body>

<div class="container">

<h2 style="color: crimson; font-family: sans-serif;">{$message}</h2>
<button class="button" onclick="window.history.back();">Go Back</button>

</div>

</body>
</html>
HTML;
