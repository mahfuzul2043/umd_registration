<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='stylesheet.css' type='text/css' />
    <link rel="icon" type="image/png" href="images/uofmlogo.jpg">
    <title>CIS435 Reservations</title>
</head>

<body>
    <header class='bg-uofm-blue'>
        <div style='display: flex; align-items: center; flex: 1; padding: 10px'>
            <img src="images/uofmlogo.jpg" height="30" class="d-inline-block align-top" alt="U of M Logo">
            <span style='font-size: 20px; margin-left: 10px; font-weight: bold'>CIS435 Reservations</span>
        </div>
        <nav>
            <div style='display: flex'>
                <a href='register.php'>Register</a>
                <a href='reservations.php'>Reservations</a>
            </div>
        </nav>
    </header>