<?php
/*
 * @Author: Beatrice Abrahamsson
 * @Email: beaabrahamsson6@gmail.com
 * @Date: 2022-03-16 12:59:30
 * @Last Modified by: Beatrice Abrahamsson
 * @Last Modified time: 2022-03-18 20:52:27
 * @Description: Description
 */

class Comment {
    //properties
    private $db;
    private $comment;

    //constructor with db connection
    function __construct() {
        $this->db = new mysqli('studentmysql.miun.se', 'beab2100', 'HXRh5FzwsZ', 'beab2100'); //ansluta till databas
        if($this->db->connect_errno > 0){
            die('Fel vid anslutning' . $this->db->connect_error);
        }
    }
    //Method to set comment
    public function setComment(string $comment) : bool {
        //Check if last name is set
        if(!isset($_POST['comment'])) {
            echo "<p class='error'>Du måste ange kommentar!</p><br>";
            return false;
        } else {
            //Set last name
            $this->comment = $comment;
            return true;
        }
    }

    //Method to add comment
    public function addComment(string $comment) : bool{
        //Check set method
        if(!$this->setComment($comment)) return false;
        //Remove any HTML from input
        $this->comment = strip_tags($comment);

        //Interto into comment using prepared statements
        $stmt = $this->db->prepare("INSERT INTO comment (userid, postid, comment) VALUES (?, ?, ?)");
        //blind parameters
        $stmt->bind_param("iis", $_SESSION['userid'], $_GET['id'], $this->comment);
        //execute
        $stmt->execute();
        //return true
        return true;
    } 
    //Method to get comments
    public function getComments() : array {
        //set id
        $id = $_GET['id'];
        //SQL query
        $sql = "SELECT * FROM comment JOIN user ON comment.userid=user.userid WHERE comment.postid=$id ORDER BY commentdate DESC";
        //send query
        $result = $this->db->query($sql);
        //fetch all as array
        $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
        //return array
        return $array;
    }

    //Method to get amount of comments for a post
    public function getCommentAmount(string $postid) {
        //SQL query
        $result = mysqli_query($this->db, "SELECT COUNT(*) AS total FROM comment WHERE postid=$postid");
        //fetch as array
		$data = mysqli_fetch_assoc($result);
        //return total
		return $data['total'];
    }

    //Method to delete comment
    public function deleteComment(int $id) : bool {
        $id = intval($id);
        //SQL Query
        $sql = "DELETE FROM comment WHERE commentid=$id;";
        //Send query
        return mysqli_query($this->db, $sql);
    }

    //Destructor
    function __destruct() {
        mysqli_close($this->db);
    }
        
}  