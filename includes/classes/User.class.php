<?php
/*
 * @Author: Beatrice Abrahamsson
 * @Email: beaabrahamsson6@gmail.com
 * @Date: 2022-03-16 12:59:48
 * @Last Modified by: Beatrice Abrahamsson
 * @Last Modified time: 2022-03-18 21:02:12
 * @Description: Description
 */

class User {
    //properties
    private $db;
    private $id;
    private $date;
    private $fname;
    private $lname;
    private $email;
    private $uname;
    private $pword;

    //constructor with db connection
    function __construct() {
        $this->db = new mysqli('studentmysql.miun.se', 'beab2100', 'HXRh5FzwsZ', 'beab2100'); //ansluta till databas
        if($this->db->connect_errno > 0){
            die('Fel vid anslutning' . $this->db->connect_error);
        }
    }

      //Add user
      public function registerUser(string $fname, string $lname, string $email, string $uname, string $pword) : bool {
        $hashed_pword = password_hash($pword, PASSWORD_DEFAULT);

        //check with set methods
        if(!$this->setFname($fname)) return false;
        if(!$this->setLname($lname)) return false;
        if(!$this->setEmail($email)) return false;
        if(!$this->setUname($uname)) return false;
        if(!$this->setPassword($pword)) return false;

        //Sanitize input using real_escape_string
        $fname = $this->db->real_escape_string($fname);
        $lname = $this->db->real_escape_string($lname);
        $email = $this->db->real_escape_string($email);
        $uname = $this->db->real_escape_string($uname);
        $pword = $this->db->real_escape_string($pword);

        //Remove any HTML from input
        $fname = strip_tags($fname);
        $lname = strip_tags($lname);
        $email = strip_tags($email);
        $uname = strip_tags($uname);
        $pword = strip_tags($pword);


        //SQL Query
        $sql = "INSERT INTO user(fname, lname, email, uname, pword)VALUES('$fname', '$lname', '$email', '$uname', '$hashed_pword');";
        //Send query
        //return mysqli_query($this->db, $sql);
        $result = $this->db->query($sql);
        return $result;

    }

        //Set-methods
        public function setFname(string $fname) : bool {
            //Check if first name is set
            if(!isset($_POST['fname'])) {
                echo "<p class='error'>Du måste ange förnamn!</p><br>";
                return false;
            } else {
                //Set first name
                $this->fname = $fname;
                return true;
            }
        }
    
        //method to set lastname
        public function setLname(string $lname) : bool {
            //Check if last name is set
            if(!isset($_POST['lname'])) {
                echo "<p class='error'>Du måste ange efternamn!</p><br>";
                return false;
            } else {
                //Set last name
                $this->lname = $lname;
                return true;
            }
        }

        //mehtod to set email
        public function setEmail(string $email) : bool {
            //SQL query to get matching emails from database
            $select = mysqli_query($this->db, "SELECT * FROM user WHERE email = '".$_POST['email']."'");

            //Check if email is set
            if(!isset($_POST['email'])) {
                echo "<p class='error'>Du måste ange e-post!</p><br>";
                return false;
            //Check if email already exists in database
            } elseif (mysqli_num_rows($select)) {
                echo "<p class='error'>E-post finns redan!</p><br>";
                return false; 
            //Validate email format
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<p class='error'>Fel format på e-post</p><br>";
                return false;
            } else {
                //Set email
                $this->email = $email;
                return true;
            }
        }
        //method to set username
        public function setUname(string $uname) : bool{
            //SQL query to get matching emails from database
            $select = mysqli_query($this->db, "SELECT * FROM user WHERE uname = '".$_POST['uname']."'");

            //Check if username is set
            if(!isset($_POST['uname'])) {
                echo "<p class='error'>Du måste ange användarnamn!</p><br>";
                return false;
            //Check if username already exists in database
            } elseif (mysqli_num_rows($select)) {
                echo "<p class='error'>Användarnamn finns redan!</p><br>";
                return false; 
            //Validate username format
            } elseif (!preg_match('/^[A-Za-z][A-Za-z0-9]{4,25}$/', $uname)) {
                echo "<p class='error'>Användarnamn måste börja med en bokstav, vara mellan 5-25 tecken och kan endast innehålla bokstäver och siffror.</p><br>";
                return false;
            } else {
                //Set username
                $this->uname = $uname;
                return true;
            }
        }
        //method to set password
        public function setPassword(string $pword) : bool{
            //Variables
            $uppercase = preg_match('@[A-Z]@', $pword);
            $lowercase = preg_match('@[a-z]@', $pword);
            $number    = preg_match('@[0-9]@', $pword);
            $specialChars = preg_match('@[^\w]@', $pword);

            //Check if password is set
            if(!isset($_POST['pword'])) {
                echo "<p class='error'>Du måste ange lösenord!</p><br>";
                return false;
            //Validate password format
            } elseif (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($pword) < 8) {
                echo "<p class='error'>Lösenord ska vara minst 8 tecken och inkludera minst en stor bokstav, en siffra och ett specialtecken.</p><br>";
                return false;
            } else {
                //Set password
                $this->pword = $pword;
                return true;
            }
        }

        //Login
        public function userLogin(string $uname, string $pword) : bool {
            //SQL query
            $sql = "SELECT * FROM user WHERE uname='$uname';";

            //Send query
            $result = $this->db->query($sql);

            //If the number of rows in result is more than 0
            if($result->num_rows > 0) {
                //fetch as an associative array
                $row = $result->fetch_assoc();
                //set stored_password variable
                $stored_password = $row['pword'];

                //If password matches a hash
                if(password_verify($pword, $stored_password)) {
                    //set session uname
                    $_SESSION['uname'] = $uname;
                    //set session userid
                    $_SESSION['userid'] = $row['userid'];
                    //return true
                    return true;
                } else {
                    //else return false
                    return false;
                }
            } else {
                //else return false
                return false;
            }
        }
        //method to check if user is logged in
        public function checkLogin() : bool {
            //Check is session username is set
            if(isset($_SESSION['uname'])) {
                //retun true
                return true;
            } else {
                //return false
                return false;
            }
        }

        //method to restrict page
        public function restrictPage() {
            //if session username is not set
            if(!isset($_SESSION['uname'])) {
                //send to login page with error message
                header("Location: login.php?error=Du måste logga in!");
                exit;
            }
        }
        //method to get users
        public function getUsers() : array {
            //SQL question
            $sql = "SELECT * FROM user ORDER BY lname DESC";
            //If query not sent -> error
            if(!$result = $this->db->query($sql)){
                die('Fel vid SQL-fråga [' . $db->error . ']');
            }
            //Fetch all as array
            $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
            //return array
            return $array;
        }

        //method to get user by id
        public function getUserById(int $id) : array {
            //get integer of id
            $id = intval($id);
            //SQL query
            $sql = "SELECT * FROM user WHERE userid=$id;";
            //send query
            $result = mysqli_query($this->db, $sql);
            //return as array
            return $result->fetch_assoc();
        }

        //method to check user post id
        public function checkUserPostId(string $postid) : bool{
            //SQL query
            $sql = "SELECT * FROM post WHERE postid=$postid;";
            //If query not sent -> error
            if(!$result = $this->db->query($sql)){
                die('Fel vid SQL-fråga [' . $db->error . ']');
            }    
            //fetch as array
            $row = $result->fetch_assoc();
            //If session userid is the same as userid from database
            if($_SESSION['userid'] == $row['userid']) {
                //return true
                return true;
            } else {
                //else return false
                return false;
            }
        }
        //method to check user comment id
        public function checkUserCommentId(string $commentid) : bool{
            //SQL query
            $sql = "SELECT * FROM comment WHERE commentid=$commentid;";
            //If query not sent -> error
            if(!$result = $this->db->query($sql)){
                die('Fel vid SQL-fråga [' . $db->error . ']');
            }    
            //fetch as array
            $row = $result->fetch_assoc();
            //If session userid is the same as userid from database
            if($_SESSION['userid'] == $row['userid']) {
                //return true
                return true;
            } else {
                //else return false
                return false;
            }
        }

        //method to delete user
        public function deleteUser(int $id) : bool {
            //get integer of id
            $id = intval($id);
            //SQL Query
            $sql = "DELETE FROM user WHERE userid=$id;";
            //Send query
            return mysqli_query($this->db, $sql);
        }

        //Destructor
        function __destruct() {
        mysqli_close($this->db);
        }
    }