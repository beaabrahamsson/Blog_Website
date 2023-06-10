<?php
/*
 * @Author: Beatrice Abrahamsson
 * @Email: beaabrahamsson6@gmail.com
 * @Date: 2022-03-16 12:59:38
 * @Last Modified by: Beatrice Abrahamsson
 * @Last Modified time: 2022-03-18 20:49:15
 * @Description: Description
 */

class Post {
    //properties
    private $db;
    private $title;
    private $content;
    private $postdate;
    private $image;

    //constructor with db connection
    function __construct() {
        $this->db = new mysqli('studentmysql.miun.se', 'beab2100', 'HXRh5FzwsZ', 'beab2100'); 
        if($this->db->connect_errno > 0){
            die('Fel vid anslutning' . $this->db->connect_error);
        }
    }

    //method to get posts
    public function getPosts() : array {
        //SQL query
        $sql = "SELECT * FROM post JOIN user ON post.userid=user.userid ORDER BY postdate DESC";
        //Send query
        $result = $this->db->query($sql);
        //Fetch all rows and return the result-set as an associative array
        $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
        //return array
        return $array;
    }

    //method to get posts by specific user
    public function getPostsByUser() : array {
        //set id
        $id = $_GET['id'];
        //SQL question
        $sql = "SELECT * FROM post JOIN user ON post.userid=user.userid WHERE post.userid=$id ORDER BY postdate DESC";
        //send query
        $result = $this->db->query($sql);
        //fetch all as array
        $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
        //return array
        return $array;
    }

    //method to get post my id
    public function getPostById(int $id) : array {
        //get integer of id
        $id = intval($id);
        //SQL query
        $sql = "SELECT * FROM post WHERE postid=$id;";
        //send query
        $result = mysqli_query($this->db, $sql);
        //return as array
        return $result->fetch_assoc();
    }


    //Set-methods
    public function setTitle(string $title) : bool {
        //If not empty
        if($title != "") {
            //set title
            $this->title = $title;
            return true;
        } else {
            return false;
        }
    }

    //Set Content
    public function setContent(string $content) : bool {
        //If not empty
        if($content != "") {
            //set content
            $this->content = $content;
            return true;
        } else {
            return false;
        }
    }

    //Set image
    public function setImage(string $image) : bool {
        //variables
        $targetDir = "uploads/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
        $allowTypes = array('jpg','png','jpeg','gif','pdf');

        //If image is not empty
        if($image != "") {
            //Only allow certain filetypes
            if(!in_array($fileType, $allowTypes)){
                echo "<p class='error'>Endast filer av sorterna JPG, JPEG, PNG, GIF, & PDF går att ladda upp.</p>";
                return false;
            } else {
                //set image
                $this->image = $image;
                return true;
            }
        } else {
            return false;
        }
    }

        //Add post
    public function addPost(string $title, string $content, string $image) {
    //check with set methods
    if(!$this->setTitle($title)) return false;
    if(!$this->setContent($content)) return false;
    
    //Remove any HTML from input
    $this->title = strip_tags($title);
    $this->content = strip_tags($content);

    // Image file upload path
    $targetDir = "uploads/";
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    //If form is submited and image file upload is not empty
    if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])){
        //if uploaded file is moved to target filepath
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert into database using prapared statement
            $stmt = $this->db->prepare("INSERT INTO post (userid, title, content, imagename) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $_SESSION['userid'], $this->title, $this->content, $fileName);
            $insert = $stmt->execute();
            //if insert is successful
            if($insert){
                echo "<p class='message'>Inlägget och bilden (".$fileName. ") har lagrats!</p>";
            }else{
                echo "<p class='error'>Bilden laddades inte upp, försök igen.</p>";
            } 
        }else{
            echo "<p class='error'>Det uppstod ett fel vid filuppladdningen.</p>";
        }
    }else{
        echo "<p class='error'>Vänligen välj en bild att ladda upp.</p>";
    }
    }
    //method to update post
    public function updatePost(int $id, string $title, string $content, string $image) {
        //check with set methods
        if(!$this->setTitle($title)) return false;
        if(!$this->setContent($content)) return false;
    
        //Remove any HTML from input
        $this->title = strip_tags($title);
        $this->content = strip_tags($content);

        // Image file upload path
        $targetDir = "uploads/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])){
            // Upload file to server
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
                // Insert into database using prapared statement
                $stmt = $this->db->prepare("UPDATE post SET userid=?, title=?, content=?, imagename=? WHERE postid=?");
                $stmt->bind_param("isssi", $_SESSION['userid'], $this->title, $this->content, $fileName, $id);
                $insert = $stmt->execute();
                if($insert){
                    echo "<p class='message'>Inlägget och bilden (".$fileName. ") har lagrats!</p>";
                }else{
                    echo "<p class='error'>Bilden laddades inte upp, försök igen.</p>";
                } 
            }else{
                echo "<p class='error'>Det uppstod ett fel vid filuppladdningen.</p>";
            }
        }else{
            echo "<p class='error'>Vänligen välj en bild att ladda upp.</p>";
        }
    }

    //method to Delete post
    public function deletePost(int $id) : bool {
        $id = intval($id);
        //SQL Query
        $sql = "DELETE FROM post WHERE postid=$id;";
        //Send query
        return mysqli_query($this->db, $sql);
    }

    //Destructor
    function __destruct() {
        mysqli_close($this->db);
    }
    
}
?>