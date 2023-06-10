<?php
/*
 * @Author: Beatrice Abrahamsson
 * @Email: beaabrahamsson6@gmail.com
 * @Date: 2022-03-16 13:01:30
 * @Last Modified by: Beatrice Abrahamsson
 * @Last Modified time: 2022-03-18 20:01:33
 * @Description: Description
 */

//include classes
include("includes/classes/User.class.php");
include("includes/classes/Post.class.php");
include("includes/classes/Comment.class.php");

//set page title
$page_title = "Skapa konto";
//include header
include("includes/header.php");
?>
        <?php
        //create object ($user) from the class User
        $user = new User();

        //If user is logged in, display error
        if($user->checkLogin()) {
            echo "<div class='container'><p class='error'>Du är redan inloggad!</p><br>";
        }

        //Default values
        $fname = "";
        $lname = "";
        $email = "";
        $uname = "";
        $pword = "";

        //check if form is submitted
        if(isset($_POST['fname'])) {
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $uname = $_POST['uname'];
            $pword = $_POST['pword'];

            //check if first name is set
            $success = true;
            if(!$user->setFname($fname)) {
                $success = false;
            }
            //check if last name is set
            if(!$user->setLname($lname)) {
                $success = false;
            }

            //check if email is set
            $success = true;
            if(!$user->setEmail($email)) {
                $success = false;
            }
            //check if username is set
            if(!$user->setUname($uname)) {
                $success = false;
            }

            //check if password is set
            if(!$user->setPassword($pword)) {
                $success = false;
            }

            //Add new user
            if($success) {
                if($user->registerUser($fname, $lname, $email, $uname, $pword)) {
                    echo "<div class='container'><p class='message'>Ditt konto har skapats!</p><br>";
                    echo "<a class='button' href='login.php'>Logga in</a></div>";

                    //Default values
                    $fname = "";
                    $lname = "";
                    $email = "";
                    $uname = "";
                    $pword = "";
                } else {
                    echo "<p class='error'>Fel vid skapande av konto!</p>";
                }    
            } else {
                echo "<p class='error'>Konto ej skapat! Kontrollera värden och försök igen.</p>";
            }

        }

        ?>

        <!--Form to sign up-->
        <form id="login" method="post">
            <h2>Skapa ett konto</h2>
            <div class="container">
                <label for="fname"><b>Förnamn:</b></label>
                <input type="text" id="fname" placeholder="Ange förnamn" name="fname" >
                <label for="lname"><b>Efternamn:</b></label>
                <input type="text" id="lname" placeholder="Ange efternamn" name="lname" >
                <label for="email"><b>E-mail:</b></label>
                <input type="text" id="email" placeholder="Ange e-mail" name="email" >
                <label for="uname"><b>Användarnamn:</b></label>
                <input type="text" id="uname" placeholder="Ange användarnamn" name="uname" >
                <label for="pword"><b>Lösenord:</b></label>
                <input type="password" id="pword" placeholder="Ange lösenord" name="pword" >
                <input type="checkbox" id="checkbox" name="checkbox" required>
                <label for="checkbox">Jag godkänner att mina personuppgifter lagras</label><br><br>
                <input type="submit" value="Skapa konto">
            </div>
            <div id="alreadyuser">
                <p>Har du redan ett konto?</p>
                <a href="login.php">Logga in</a>
            </div>
        </form>
    <?php
    //include footer
    include("includes/footer.php");
    ?>


