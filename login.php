<?php
/*
 * @Author: Beatrice Abrahamsson
 * @Email: beaabrahamsson6@gmail.com
 * @Date: 2022-03-16 13:01:17
 * @Last Modified by: Beatrice Abrahamsson
 * @Last Modified time: 2022-03-18 20:01:09
 * @Description: Description
 */

//include classes
include("includes/classes/User.class.php");
include("includes/classes/Post.class.php");
include("includes/classes/Comment.class.php");

//set page title
$page_title = "Logga in";
//include header
include("includes/header.php");
?>

        <?php
        //checking if form is set
        if(isset($_POST['uname'])) {
            $uname = $_POST['uname'];
            $pword = $_POST['pword'];

            //Creating new object from class User
            $user = new User();

            //Calling function to login
            if($user->userLogin($uname, $pword)) {
                header("Location: index.php?message=Du är nu inloggad!");
            } else {
                echo "<p class='error'>Fel användarnamn eller lösenord.</p>";
            }
        }

        ?>
<!--Form to log in-->
        <form action="login.php"  id="login" method="post">
            <h2>Logga in</h2>
            <div class="container">
                <label for="uname"><b>Användarnamn:</b></label>
                <input type="text" id="uname" placeholder="Ange användarnamn" name="uname" required>
                <label for="pword"><b>Lösenord:</b></label>
                <input type="password" id="pword" placeholder="Ange lösenord" name="pword" required>
                <input type="submit" value="Logga in">
            </div>
            <div id="alreadyuser">
                <p>Har du inget konto?</p>
                <a href="signup.php">Skapa konto här.</a>
            </div>
        </form>
    <?php
    //include footer
    include("includes/footer.php");
    ?>