<?php
/*
 * @Author: Beatrice Abrahamsson
 * @Email: beaabrahamsson6@gmail.com
 * @Date: 2022-03-16 13:00:46
 * @Last Modified by: Beatrice Abrahamsson
 * @Last Modified time: 2022-03-18 19:59:59
 * @Description: Description
 */
//include classes
include("includes/classes/User.class.php");
include("includes/classes/Post.class.php");
include("includes/classes/Comment.class.php");

//set page title
$page_title = "Blogg";
//include header
include("includes/header.php");
?>

        <div class="blogpost">
            <h2>Alla blogginlägg</h2>
            <?php
            //creating object ($db) from the class Post
            $db = new Post();
            //calling method to get posts
            $row = $db->getPosts();

            //creat ingobject ($comment) from the class Comment
            $comment = new Comment();

            //Loop thrpugh array and print posts
            foreach($row as $c) {
                echo "<div class='post'> <h3><a class='title' href='details.php?id=" . $c['postid'] . "'>" . $c['title'] . "</a></h3>";
                echo "<p class='italics'>Postat av <a href='userpage.php?id=" . $c['userid'] . "'>" . $c['fname'] . "&nbsp;" . $c['lname'] . "</a> den " . $c['postdate'] . "</p>";
                echo "<img src='" . "uploads/" .$c['imagename']."' alt=''>";
                echo nl2br("<p>" . substr($c['content'], 0, 700) . "...</p>"); //Using nl2br() function to to insert HTML line breaks and substr() function to only show first 700 characters.
                echo "<div class='readmore'><a href='details.php?id=" . $c['postid'] . "'>Läs hela inlägget</a></div>";
                //call method to get amount of comments
                $total = $comment->getCommentAmount($c['postid']);
                echo "<div class='readmore'><a class='readmore' href='details.php?id=" . $c['postid'] . "'>Antal kommentarer: " . $total . "</a></div><br>";
                //if user is logged in and the posts userid is the same as session userid, show edit and delete buttons
                if(isset($_SESSION['userid']) && $c['userid']==$_SESSION['userid']) {
                    echo "<div class='editButtons'><a href='edit.php?id=" . $c['postid'] . "'>Ändra</a>";
                    echo "<a href='admin.php?deleteid=" . $c['postid'] . "'>Ta bort</a></div></div>";
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