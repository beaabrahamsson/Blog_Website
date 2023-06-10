<?php
/*
 * @Author: Beatrice Abrahamsson
 * @Email: beaabrahamsson6@gmail.com
 * @Date: 2022-03-16 13:01:37
 * @Last Modified by: Beatrice Abrahamsson
 * @Last Modified time: 2022-03-18 20:03:23
 * @Description: Description
 */
//include classes
include("includes/classes/User.class.php");
include("includes/classes/Post.class.php");
include("includes/classes/Comment.class.php");

//check if id is set
if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    header("Location: index.php");
}
?>

        <div class="blogpost">
            <?php
            //calling function to print
            $db = new Post();
            $row = $db->getPostsByUser();


            //Loop through array to print users name
            foreach($row as $c) {
                //If userid is same as id
                if(isset($c['userid'])) {
                        //If username is set, print fname and lname of user
                        echo "<h2>Alla inlägg av " . $c['fname'] . "&nbsp;" . $c['lname'] . "</h2>";
                        //set page title
                        $page_title = $c['fname'] ."&nbsp;". $c['lname'];
                        break; //Break to only loop once
                }
            }
            //include header
            include("includes/header.php");

            //Check if array is empty
            if(empty($row)) {
                echo "<p class='error'>Denna användare har inte gjort några inlägg ännu!</p>";
            }
                
            $comment = new Comment();

            //Loop thrpugh array and print posts
            foreach($row as $c) {
                echo "<div class='post' <h3><a class='title' href='details.php?id=" . $c['postid'] . "'>" . $c['title'] . "</a></h3>";
                echo "<p class='italics'>Postat av <a href='userpage.php?id=" . $c['userid'] . "'>" . $c['fname'] . "&nbsp;" . $c['lname'] . "</a> den " . $c['postdate'] . "</p>";
                echo "<img src='" . "uploads/" .$c['imagename']."' alt=''>";
                echo nl2br("<p>" . substr($c['content'], 0, 700) . "...</p>"); //Using nl2br() function to to insert HTML line breaks and substr() function to only show first 700 characters.
                echo "<div class='readmore'><a href='details.php?id=" . $c['postid'] . "'>Läs hela inlägget</a></div>";
                //call function to get amount of comments
                $total = $comment->getCommentAmount($c['postid']);
                echo "<div class='readmore'><a href='details.php?id=" . $c['postid'] . "'>Antal kommentarer: " . $total . "</a></div><br>";
                if(isset($_SESSION['userid']) && $c['userid']==$_SESSION['userid']) {
                    echo "<div class='editButtons'><a href='edit.php?id=" . $c['postid'] . "'>Ändra</a>";
                    echo "<a href='admin.php?deleteid=" . $c['postid'] . "'>Ta bort</a></div><br></div>";
                }
            }
            ?>
        
        </div>
    <?php
    //include footer
    include("includes/footer.php");
    ?>


