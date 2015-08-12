<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 7/11/2015
 * Time: 6:33 PM
 */

include_once 'dbconnect.php';

$email_registered = false;

if(isset($_POST['email'])){
    $email = $_POST['email'];
    $email = $conn->quote($email);
    $query = "SELECT id FROM users WHERE email = " . $email;

    $stmt = $conn->prepare($query);
    $stmt = $conn->query($query);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(isset($row['id'])){
        $email_registered = true;
    }
}

$output = array("email", $email_registered);
echo json_encode($output);