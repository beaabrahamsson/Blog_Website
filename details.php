<?php
/*
 * @Author: Beatrice Abrahamsson
 * @Email: beaabrahamsson6@gmail.com
 * @Date: 2022-03-16 13:00:54
 * @Last Modified by: Beatrice Abrahamsson
 * @Last Modified time: 2022-03-18 20:00:30
 * @Description: Description
 */
//include classes
include("includes/classes/User.class.php");
include("includes/classes/Post.class.php");
include("includes/classes/Comment.class.php");
?>


<?php
//check if id is set
if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    //create object and call method
    $post = new Post();
    $details = $post->getPostById($id);
    //set page title
    $page_title = $details['title'];
}

//include header
include("includes/header.php");

//creating object ($deleteComment) from the class Comment
$deleteComment = new Comment();
//creating object ($newComment) from the class Comment
$newComment = new Comment();
//Creating object ($user) from class User
$user = new User();

//If deleteid is set, delete comment
if(isset($_GET['deleteid'])) {
    //Calling method to check if user is allowed to edit the post
    if($user->checkUserCommentId($_GET['deleteid'])) {
        $deleteid = intval($_GET['deleteid']);
        if($deleteComment->deleteComment($deleteid)) {
            echo "<p class='message'>Kommentar raderad!</p>";
        } else {
            echo "<p class='error'>Fel vid radering av kommentar.</p>"; 
        }
    } else {
        echo "<p class='message'>Kommentar inte raderad, ej behörighet!</p>";
    }
}

//check if form is submitted
if(isset($_POST['comment'])) {
    $comment = $_POST['comment'];

    $success = true;
    //check if comment is set
    if(isset($_SESSION['userid'])) {
        if(!$newComment->setComment($comment)) {
            $success = false;
        }
    } else {
        echo "<p class='error'>Du måste vara inloggad för att kommentera!</p>";
        $success = false;
    }

    //Add new content
    if($success) {
        $newComment->addComment($comment);
        echo "<p class='message'>Kommentar tillagd!</p>"; 
    } else {
        echo "<p class='error'>Kommentar ej tillagd! Kontrollera värden och försök igen.</p>";
    }
}

//creating object ($user) from the class User
$user = new User();
//Calling method to get user info
$user = $user->getUserById($details['userid']);
?>


    <!--Print post-->
    <div class="blogpost">
        <div class="post">
            <h1><?= $details['title'] ?></h1>
            <p class="italics">Postat av <?= $user['fname']?> <?=$user['lname']?> den <?=$details['postdate'] ?></p>
            <img src="uploads/<?= $details['imagename'] ?>" alt="">
            <p><?= nl2br($details['content']) ?></p><!--Using function nl2br() to insert HTML line breaks-->
            <?php
            if(isset($_SESSION['userid']) && $user['userid']==$_SESSION['userid']) {
                        echo "<br><div class='editButtons'><a href='edit.php?id=" . $details['postid'] . "'>Ändra</a>";
                        echo "<a href='admin.php?deleteid=" . $details['postid'] . "'>Ta bort</a></div></div>";
                    } else {
                        echo "</div>";
                    }
            ?>

        <!-- comment form -->
        <div id="commentForm">
            <form method="post" action="details.php?id=<?= $id; ?>">
                <h4>Skriv en kommentar:</h4>
                <textarea name="comment" id="comment" class="form-control" cols="30" rows="5"></textarea>
                <p class="italics">Du kommenterar som: <?= $_SESSION['uname']; ?></p>
                <input type="submit" name="submit" value="Skicka kommentar" onclick="divide()">
            </form>
        </div>
        <button id="toggleComments">Kommentera inlägg</button>
    </div>
    <!-- comments section -->
    <div class="comments">
        <?php
        //creating object ($db) from the class Comment
        $db = new Comment();
        //Calling method to get comments
        $row = $db->getComments();
        //Loop through and print comments
        foreach($row as $c){
            echo "<div class='comment'><p>" . $c['commentdate'] . " -&nbsp;<a href='userpage.php?id=" . $c['userid'] . "'>" . $c['fname'] . "&nbsp;" . $c['lname'] . "</a>:</p>";
            echo "<p>" . $c['comment'] . "</p><br>";
            //if user is logged in and the comments userid is the same as session userid, show delete button
            if(isset($_SESSION['userid']) && $c['userid']==$_SESSION['userid']) {
                echo "<div class='editButtons'><a href='details.php?id=" . $c['postid'] . "&deleteid=" . $c['commentid'] . "'>Ta bort kommentar</a></div></div>";
            } else {
                echo "</div>";
            }
        }
        ?>

    </div>
    <?php
    //include footer
    include("includes/footer.php");
    ?>