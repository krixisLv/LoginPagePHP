<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 7/11/2015
 * Time: 2:02 PM
 */

try {
    $conn = new PDO('mysql:host=localhost;dbname=login_db', "login_admin", "pass123");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}