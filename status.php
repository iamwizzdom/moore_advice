<?php
/**
 * Created by PhpStorm.
 * User: Wisdom Emenike
 * Date: 7/16/2020
 * Time: 9:37 PM
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

$subjects = implode(", ", json_decode($application->best_subjects, true));

echo <<<HTML


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apply</title>
    <link href="application/status.css" rel="stylesheet" type="text/css">
</head>
<body>

<div class="container">

<div style="width: 100%" class="text-center">
<h1>APPLICANT’S STATUS</h1>
<img src="application/images/{$application->image}" width="200" alt="Image uploaded by user "/>
</div>

<div style="width: 70%; margin: auto">

<p>I "{$application->first_name} {$application->last_name}", applied with the application code "{$application->code}".</p>
<p>I live at "{$application->address}" and I was born on "{$application->dob}"</p>
<p>My favourite subjects are "{$subjects}".</p>
</div>

</div>

</body>
</html>
HTML;
