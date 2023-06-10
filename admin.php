<?php
/*
 * @Author: Beatrice Abrahamsson
 * @Email: beaabrahamsson6@gmail.com
 * @Date: 2022-03-16 12:58:39
 * @Last Modified by: Beatrice Abrahamsson
 * @Last Modified time: 2022-03-18 20:00:15
 * @Description: Description
 */

//include classes
include("includes/classes/User.class.php");
include("includes/classes/Post.class.php");
include("includes/classes/Comment.class.php");

//set page title
$page_title = "Nytt inlägg";
//include header
include("includes/header.php");

//create object ($user) from the class News
$user = new User();
//Call method to restrict page
$user->restrictPage();
?>


        <!--Show username and logout button-->
        <div id="username">
            <p>Du är inloggad som: <?= $_SESSION['uname'] ?></p>
            <div id="deleteUser">
                <a class="button" href="admin.php?deleteuser=<?= $_SESSION['userid'] ?>">Ta bort mitt konto</a>
            </div>
        </div>
        <?php

        //create object ($post) from the class post
        $post = new Post();
        

        //If deleteid is set, delete 
        if(isset($_GET['deleteid'])) {
            //Creating object ($user) from class User
            $user = new User();
            //Calling method to check if user is allowed to edit the post
            $user->checkUserPostId($_GET['deleteid']);
            $deleteid = intval($_GET['deleteid']);

            if($post->deletePost($deleteid)) {
                echo "<p class='message'>Inlägg raderat!</p>";
            } else {
                echo "<p class='error'>Fel vid radering av inlägg.</p>"; 
            }
        }

        //If deleteid is set, delete 
        if(isset($_GET['deleteuser'])) {
            //Creating object ($user) from class User
            $user = new User();
            if($_SESSION['userid'] == $_GET['deleteuser']) {
                $deleteuser = intval($_GET['deleteuser']);
                if($user->deleteUser($deleteuser)) {
                    header("Location: index.php?message=Konto borttaget!");
                    //destroy session
                    session_unset();
                    session_destroy();
                } else {
                    echo "<p class='error'>Fel vid radering av konto.</p>"; 
                }
            } else {
                echo "<p class='error'>Du kan ej radera detta konto.</p>"; 
            } 
        }

        //Default values
        $title = "";
        $content = "";

        //check if form is submitted
        if(isset($_POST['title'])) {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $image = $_FILES["file"]["name"];
            //if set, success is true
            $success = true;

            //Check if title is set
            if(!$post->setTitle($title)) {
                $success = false;
                echo "<p class='error'>Du måste ange en titel!</p>";
            }
            //check if contnent is set
            if(!$post->setContent($content)) {
                $success = false;
                echo "<p class='error'>Du måste ange ett innehåll!</p>";
            }

            //check if image is set
            if(!$post->setImage($image)) {
                $success = false;
            }

            //Add new content
            if($success) {
                $post->addPost($title, $content, $image);
                echo "<p class='message'>Inlägg tillagt!</p>"; 
                //Default values
                $title = "";
                $content = "";
            } else {
                echo "<p class='error'>Inlägg ej lagrat! Kontrollera värden och försök igen.</p>";
            } 
        } 
        ?>

        <!--Form for adding new post-->
        <div class="container">
            <h3>Skapa nytt inlägg</h3>
            <form id="new" method="post" enctype="multipart/form-data" action="admin.php">
                <label for="title">Titel:</label><br>
                <input type="text" name="title" id="title" placeholder="Ange titel" value="<?= $title; ?>"><br>
                <label for="content">Innehåll:</label><br>
                <textarea id="content" name="content" cols="40" rows="5" placeholder="Ange innehåll"><?= $content; ?></textarea><br>
                <label for="file">Välj bild att ladda upp:</label><br>
                <input type="file" name="file">
                <input type="submit" name="submit" value="Lägg till" onclick="divide()">
            </form>
        </div>
    <?php
    //include footer
    include("includes/footer.php");
    ?>