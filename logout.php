<?php
/*
 * @Author: Beatrice Abrahamsson
 * @Email: beaabrahamsson6@gmail.com
 * @Date: 2022-03-16 13:01:23
 * @Last Modified by: Beatrice Abrahamsson
 * @Last Modified time: 2022-03-18 11:53:39
 * @Description: Description
 */

//Include config.php
include("includes/config.php");

//start session
session_start();
//destroy session
session_unset();
session_destroy();

//Go to index.php
header("Location: index.php?message=Du är nu utloggad!");
exit();
?>