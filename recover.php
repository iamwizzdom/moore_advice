<?php
/**
 * Created by PhpStorm.
 * User: Wisdom Emenike
 * Date: 7/16/2020
 * Time: 9:36 PM
 */

require 'connection/DB.php';

session_start();

if (!empty($_SESSION)) {
    $application = DB::read("SELECT * FROM applications WHERE user_id = {$_SESSION['user']->id}");
    $location = empty($application) ? 'apply.php' : 'confirm.php';
    header("Location: {$location}");
    exit();
}

$message = "";

if (!empty($_POST)) {

    if (!empty($_POST['code'])) {

        $user = DB::read("SELECT * FROM users WHERE code = '{$_POST['code']}'");

        if (!empty($user)) {
            $_SESSION['user'] = $user[0];

            $application = DB::read("SELECT * FROM applications WHERE user_id = {$_SESSION['user']->id}");
            $location = empty($application) ? 'apply.php' : 'confirm.php';
            header("Location: {$location}");
            exit();
        } else $message = "Sorry, that code is invalid";

    } else $message = "Please enter a code";
}

if ($message) $message = "<span class='error'>{$message}</span>";

echo <<<HTML

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apply</title>
    <link href="application/login.css" rel="stylesheet" type="text/css">
</head>
<body>

<div class="container">

<div class="col-1">
<h1>You can login to this
application once you have
been issued an access code by
the system administrator.</h1>
</div>
<div class="col-2">
<form method="post" action="recover.php">
<div class="input-container">
<label>Access Code:</label>
<input type="text" name="code" placeholder="Enter code"/>
{$message}
</div>
<button type="submit">SUBMIT</button>
</form>
</div>

</body>
</html>
HTML;

