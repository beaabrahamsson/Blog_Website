
<?php
/*
 * @Author: Beatrice Abrahamsson
 * @Email: beaabrahamsson6@gmail.com
 * @Date: 2022-03-16 13:00:35
 * @Last Modified by: Beatrice Abrahamsson
 * @Last Modified time: 2022-03-18 17:55:11
 * @Description: Description
 */

include("includes/config.php");
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title><?= $site_title . $divider . $page_title ?></title>
</head>
<body>
    <header>
        <div class="navbar">
            <a id="logo" href="index.php">Resebloggen</a>
            <a href="index.php">Startsida</a>
            <a href="blog.php">Blogg</a>
            <a href="admin.php">Nytt inlägg</a>
            <div id="loginLink">
                <?php
                if(!isset($_SESSION['uname'])) {
                    echo "<a href='signup.php'>Skapa konto</a>";
                }
                if(!isset($_SESSION['uname'])) {
                    echo "<a href='login.php'>Logga in</a>";
                } else {
                    echo "<a href='logout.php'>Logga ut</a>";
                    echo "<a href='userpage.php?id=" . $_SESSION['userid'] . "'>" . $_SESSION['uname'] . "</a>";
                }
                ?> 
            </div>
        </div>
        <!-- Top Navigation Menu -->
        <div class="mobilemenu">
            <a href="index.php" class="active">Resebloggen</a>
            <!-- Navigation links (hidden by default) -->
            <div id="links">
                <a href="index.php">Startsida</a>
                <a href="blog.php">Blogg</a>
                <a href="admin.php">Nytt inlägg</a>
                <?php
                if(!isset($_SESSION['uname'])) {
                    echo "<a href='signup.php'>Skapa konto</a>";
                }
                if(!isset($_SESSION['uname'])) {
                    echo "<a href='login.php'>Logga in</a>";
                } else {
                    echo "<a href='logout.php'>Logga ut</a>";
                    echo "<a href='userpage.php?id=" . $_SESSION['userid'] . "'>" . $_SESSION['uname'] . "</a>";
                }
                ?> 
            </div>
            <!-- "Hamburger menu" / "Bar icon" to toggle the navigation links -->
            <a href="javascript:void(0);" class="icon" onclick="mobileMenu()">
            <i class="fa fa-bars"></i>
            </a>
        </div>
    </header>
    <main>