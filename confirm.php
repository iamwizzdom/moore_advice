<?php
/**
 * Created by PhpStorm.
 * User: Wisdom Emenike
 * Date: 7/16/2020
 * Time: 9:36 PM
 */

require 'connection/DB.php';

session_start();

if (empty($_SESSION)) {
    header("Location: login.php");
    exit();
}

$application = DB::read("SELECT * FROM applications INNER JOIN users ON applications.user_id = users.id WHERE applications.user_id = {$_SESSION['user']->id}");

if (empty($application)) {

    header("Location: apply.php");
    exit();
}

$application = $application[0];

echo <<<HTML

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apply</title>
    <link href="application/confirm.css" rel="stylesheet" type="text/css">
</head>
<body>

<div class="container">

<p>Dear "{$application->first_name} {$application->last_name}"</p>
<p>Your application with the access code "{$application->code}" is successful</p>
<p>Kindly print Application status and Application Details by clicking the buttons below.</p>
<a href="status.php" class="button">Application Status</a>
<a href="detail.php" class="button">Application Detail</a>
</div>

</body>
</html>
HTML;

