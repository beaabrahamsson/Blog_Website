<?php
/*
 * @Author: Beatrice Abrahamsson
 * @Email: beaabrahamsson6@gmail.com
 * @Date: 2022-03-16 13:01:02
 * @Last Modified by: Beatrice Abrahamsson
 * @Last Modified time: 2022-03-18 20:00:38
 * @Description: Description
 */

 //include classes
 include("includes/classes/User.class.php");
 include("includes/classes/Post.class.php");
 include("includes/classes/Comment.class.php");

        //check if id is set
        if(isset($_GET['id'])) {
            $id = intval($_GET['id']);
            //create object and call method
            $post = new Post();
            $details = $post->getPostById($id);
            //set page title
            $page_title = "Uppdatera " . $details['title'];
        }

        //include header
        include("includes/header.php");

        //check if user is logged in
        if(!isset($_SESSION['userid'])) {
            header("Location: login.php?message=Du måste vara inloggad!");
        }

        //Creating object ($user) from class User
        $user = new User();

        //Checking if id is set
        if(isset($_GET['id'])) {
            //Calling method to check if user is allowed to edit the post
            if($user->checkUserPostId($_GET['id'])) {
                echo "<p class='message'>Du är inloggad som " . $_SESSION['uname'] . "!</p>";
            } else {
                header("Location: index.php?error=Du har ej behörighet att ändra detta inlägg då du inte är författaren!");
            }
        }


        //Creating object ($post) from class Post
        $post = new Post();

        //Check if id is sent 
        if(isset($_GET['id'])) {
            $id = intval($_GET['id']);

        //check if form is submitted
        if(isset($_POST['title'])) {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $image = $_FILES["file"]["name"];
            //if set, success is true
            $success = true;
            //default value
            $message = "";

                //if title is not set, error message
                if(!$post->setTitle($title)) {
                    $success = false;
                    $message .= "<p class='error'>Du måste ange en titel!</p>";
                }
                //if content is not set, error message
                if(!$post->setContent($content)) {
                    $success = false;
                    $message .= "<p class='error'>Du måste ange ett innehåll!</p>";
                }
                //check if image is set
                if(!$post->setImage($image)) {
                $success = false;
            }
                //if successfull, update post
                if($success) {
                    $post->updatePost($id, $title, $content, $image);
                    $message .= "<p class='message'>Inlägg uppdaterat!</p>";
                } else {
                    $message .= "<p class='error'>Inlägg ej uppdaterat! Kontrollera värden och försök igen.</p>";
                }
            }
            

            //call method to get post details
            $details = $post->getPostById($id);
            } else {
                header("Location: admin.php");
            }

        //Calling method to get user details
        $user = $user->getUserById($details['userid']);
        ?>


        <?php
            //if message is set, show message
            if(isset($message)) {
                echo $message;
            }

            //Default values
            $title = "";
            $content = "";
        ?><!--Form to edit post-->
        <div class="container">
            <form id="new" method="post" enctype="multipart/form-data" action="edit.php?id=<?= $id; ?>">
                <h3>Uppdatera inlägg: <?= $details['title']; ?></h3>
                <p>Skrivet av: <?= $user['uname']; ?> den <?= $details['postdate']; ?></p>
                <label for="title">Titel:</label><br>
                <input type="text" name="title" id="title" value="<?= $details['title']; ?>"><br>
                <label for="content">Innehåll:</label><br>
                <textarea id="content" name="content" cols="40" rows="5"><?= $details['content']; ?></textarea>
                <label for="file">Välj bild att ladda upp:</label><br>
                <input type="file" name="file">
                <input type="submit" name="submit" value="Uppdatera inlägg" onclick="divide()">
            </form>
        </div>
    <?php
    //include footer
    include("includes/footer.php");
    ?>