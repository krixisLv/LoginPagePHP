<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 7/11/2015
 * Time: 2:04 PM
 */

session_start();

if(!isset($_SESSION['user']))
{
    header("Location: index.php");
}
else if(isset($_SESSION['user'])!="")
{
    header("Location: user_page.php");
}

if(isset($_GET['logout']))
{
    session_destroy();
    unset($_SESSION['user']);
    header("Location: index.php");
    exit();
}
