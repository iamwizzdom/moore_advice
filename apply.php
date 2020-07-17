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

$errors = [];

if (!empty($_POST)) {

    if (empty($_POST['first_name'])) $errors['first_name'] = 'Please enter your first name';
    if (empty($_POST['last_name'])) $errors['last_name'] = 'Please enter your last name';
    if (empty($_POST['address'])) $errors['address'] = 'Please enter your address';
    if (empty($_POST['marital_status'])) $errors['marital_status'] = 'Please select your marital status';
    if (empty($_POST['education'])) $errors['education'] = 'Please enter your educational background';
    if (empty($_POST['best_subjects'])) $errors['best_subjects'] = 'Please select your best subjects';
    if (empty($_POST['religion'])) $errors['religion'] = 'Please select your religion';
    if (empty($_POST['state_of_origin'])) $errors['state_of_origin'] = 'Please select your state of origin';
    if (empty($_POST['dob'])) $errors['dob'] = 'Please select your date of birth';

    if (empty($_FILES)) $errors['image'] = "Please select an image";
    else {

        if (!isset($_FILES['image']['name'])) $errors['image'] = "Can't find uploaded file";
        elseif (($_FILES['image']['size'] ?? 0) <= 0) $errors['image'] = "Can't find uploaded file";
        elseif (($_FILES['image']['size'] ?? 0) > (1 * (1024 * 1024))) $errors['image'] = "Image is too large. It must not be greater than 1MB";
        elseif (!in_array($ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION), ['jpg', 'png', 'jpeg']))
            $errors['image'] = "Can't find uploaded file";
        else {
            if (!move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/application/images/' .
                ($filename = (sha1($_SESSION['user']->id ?? $_FILES['image']['name']) . ".{$ext}")))) $errors['image'] = "Sorry we couldn't upload your image at this time";
        }
    }

    if (empty($errors)) {

        $bestSubjects = json_encode($_POST['best_subjects']);

        $write = DB::write("INSERT INTO applications 
                        (user_id, first_name, last_name, address, marital_status, education, best_subjects, religion, state_of_origin, dob, image) VALUES 
                        ('{$_SESSION['user']->id}', '{$_POST['first_name']}', '{$_POST['last_name']}', '{$_POST['address']}', '{$_POST['marital_status']}','{$_POST['education']}', 
                        '{$bestSubjects}', '{$_POST['religion']}', '{$_POST['state_of_origin']}', '{$_POST['dob']}', '{$filename}' )");

        if (!$write) {
            $_SESSION['message'] = "Sorry we could not take your application at this time, please try again later";
            header("Location: error.php");
            exit();
        } else {
            $_SESSION['message'] = "Congratulations, you have successfully applied";
            header("Location: confirm.php");
            exit();
        }

    } else {
        $_SESSION['message'] = "Please fill all the application fields.";
        header("Location: error.php");
        exit();
    }
}

$states = "";

foreach (DB::read("SELECT * FROM states") as $state) {
    $states .= "<option value='{$state->id}'>{$state->name}</option>";
}

echo <<<HTML

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apply</title>
    <link href="application/apply.css" rel="stylesheet" type="text/css">
</head>
<body>

<form method="post" action="apply.php" enctype="multipart/form-data">

<table>
<h1>Online Application</h1>
    <tr>
        <td>First Name</td>
        <td><input type="text" name="first_name" placeholder="Enter first name"></td>
    </tr>
    <tr>
        <td>Last Name</td>
        <td><input type="text" name="last_name" placeholder="Enter last name"></td>
    </tr>
    <tr>
        <td>Address</td>
        <td><input type="text" name="address" placeholder="Enter address"></td>
    </tr>
    <tr>
        <td>Marital Status</td>
        <td>
            <input type="radio" name="marital_status" value="Married"> Married
            <input type="radio" name="marital_status" value="Single"> Single
        </td>
    </tr>
    <tr>
        <td>Educational Background</td>
        <td><input type="text" name="education" placeholder="Enter educational background"></td>
    </tr>
    <tr>
        <td>Select Best Subject</td>
        <td>
            <input type="checkbox" name="best_subjects[]" value="Mathematics"> Mathematics
            <input type="checkbox" name="best_subjects[]" value="English"> English
            <input type="checkbox" name="best_subjects[]" value="Science"> Science
            <input type="checkbox" name="best_subjects[]" value="Government"> Government
            <input type="checkbox" name="best_subjects[]" value="Art"> Art
            <input type="checkbox" name="best_subjects[]" value="Civic"> Civic
            <input type="checkbox" name="best_subjects[]" value="Computer"> Computer
            <input type="checkbox" name="best_subjects[]" value="History"> History
            <input type="checkbox" name="best_subjects[]" value="Agriculture"> Agriculture
        </td>
    </tr>
    <tr>
        <td>Religion</td>
        <td>
            <input type="radio" name="religion" value="Islam"> Islam
            <input type="radio" name="religion" value="Christianity"> Christianity
            <input type="radio" name="religion" value="Traditional"> Traditional
        </td>
    </tr>
    <tr>
        <td>State of Origin</td>
        <td>
            <select name="state_of_origin">
                <option value="" selected disabled>Please select state</option>
                {$states}
            </select>
        </td>
    </tr>
    <tr>
        <td>Date of Birth</td>
        <td><input type="date" name="dob" placeholder="Enter dob"></td>
    </tr>
    <tr>
        <td>Image Upload</td>
        <td><input type="file" name="image"></td>
    </tr>
</table>

<button type="submit">Submit Application</button>

</form>

</body>
</html>

HTML;
