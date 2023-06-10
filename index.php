<?php
/*
 * @Author: Beatrice Abrahamsson
 * @Email: beaabrahamsson6@gmail.com
 * @Date: 2022-03-16 13:01:11
 * @Last Modified by: Beatrice Abrahamsson
 * @Last Modified time: 2022-03-18 20:00:54
 * @Description: Description
 */
//include classes
include("includes/classes/User.class.php");
include("includes/classes/Post.class.php");
include("includes/classes/Comment.class.php");

//set page title
$page_title = "Startsida";
//include header
include("includes/header.php");
?>

        <div id="welcome">
            <h1>Välkommen till Resebloggen</h1>
            <p>Här kan du tillsammans med andra på resande fot dela med dig av dina upplevelser!</p>
        </div>
        <?php
        //If error is set, show error message
        if(isset($_GET['error'])) {
            echo "<p class='error'>" . $_GET['error'] . "</p>";
        }
        //If message is set, show message
        if(isset($_GET['message'])) {
            echo "<p class='message'>" . $_GET['message'] . "</p>";
        }
        ?><div class="flexbox">
            <div class="users">
            <h2>Registrerade användare</h2>
                <div id="hideUsers">
                    <?php
                    //Creating object and calling method to get users
                    $user = new User();
                    $row = $user->getUsers();

                    //Loop through array and print users
                    foreach($row as $c){
                        echo "<a href='userpage.php?id=" . $c['userid'] . "'>" . $c['fname'] . "&nbsp;" . $c['lname'] . "</a><br><br>";
                    }
                    ?>
                
                </div>
                <button id="toggle">Dölj lista</button>
            </div>
            <div class="blogpost">
                <h2>Senaste inläggen</h2>
                <?php
                //calling function to get posts
                $db = new Post();
                $row = $db->getPosts();
                //Use function array_slice to only get first five rows
                $slicedRow = array_slice($row, 0, 5);
                
                //Creating object from class Comment
                $comment = new Comment();

                //Loop thrpugh array and print posts
                foreach($slicedRow as $c) {
                    echo "<div class='post'> <h3><a class='title' href='details.php?id=" . $c['postid'] . "'>" . $c['title'] . "</a></h3>";
                    echo "<p class='italics'>Postat av <a href='userpage.php?id=" . $c['userid'] . "'>" . $c['fname'] . "&nbsp;" . $c['lname'] . "</a> den " . $c['postdate'] . "</p>";
                    echo "<img src='" . "uploads/" .$c['imagename']."' alt=''>";
                    echo nl2br("<p>" . substr($c['content'], 0, 700) . "...</p>"); //Using nl2br() function to to insert HTML line breaks and substr() function to only show first 700 characters.
                    echo "<div class='readmore'><a href='details.php?id=" . $c['postid'] . "'>Läs hela inlägget</a></div>";
                    //call function to get amount of comments
                    $total = $comment->getCommentAmount($c['postid']);
                    echo "<div class='readmore'><a href='details.php?id=" . $c['postid'] . "'>Antal kommentarer: " . $total . "</a></div><br>";
                    //if user is logged in and the post userid is the same as session userid, show edit and delete buttons
                    if(isset($_SESSION['userid']) && $c['userid']==$_SESSION['userid']) {
                        echo "<div class='editButtons'><a href='edit.php?id=" . $c['postid'] . "'>Ändra</a>";
                        echo "<a href='admin.php?deleteid=" . $c['postid'] . "'>Ta bort</a></div><br></div>";
                    } else {
                        echo "</div>";
                    }
                }
                ?>
                
            </div>
        </div>
    <?php
    //include footer
    include("includes/footer.php");
    ?>
