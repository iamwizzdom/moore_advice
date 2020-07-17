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

$application = DB::read("SELECT *, states.name as state_name FROM applications INNER JOIN users ON applications.user_id = users.id
                            INNER JOIN states ON applications.state_of_origin = states.id WHERE applications.user_id = {$_SESSION['user']->id}");

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
    <link href="application/detail.css" rel="stylesheet" type="text/css">
</head>
<body>

<div class="container">

<div style="width: 100%" class="text-center">
<h1>APPLICANT’S DETAILS</h1>
<img src="application/images/{$application->image}" width="200" alt="Image uploaded by user "/>
</div>
<p>Dear "{$application->first_name} {$application->last_name}" , your application details have been received.</p>
<p>Your Access code is "{$application->code}" . Kindly go through the details.</p>

<table>
    <tr>
        <td>First Name</td>
        <td>{$application->first_name}</td>
    </tr>
    <tr>
        <td>Last Name</td>
        <td>{$application->last_name}</td>
    </tr>
    <tr>
        <td>Address</td>
        <td>{$application->address}</td>
    </tr>
    <tr>
        <td>Marital Status</td>
        <td>{$application->marital_status}</td>
    </tr>
    <tr>
        <td>Educational Background</td>
        <td>{$application->education}</td>
    </tr>
    <tr>
        <td>Select Best Subject</td>
        <td>{$subjects}</td>
    </tr>
    <tr>
        <td>Religion</td>
        <td>{$application->religion}</td>
    </tr>
    <tr>
        <td>State of Origin</td>
        <td>{$application->state_name}</td>
    </tr>
    <tr>
        <td>Date of Birth</td>
        <td>{$application->dob}</td>
    </tr>
</table>

</div>

</body>
</html>
HTML;
