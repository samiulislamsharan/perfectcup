<?php
session_start();

// Open a connection to the database
$mysqli = new mysqli('localhost', `root`, '', 'perfectcup');

// Check for errors
if($mysqli->connect_error) {
    die('Connection error:  . (' .$mysqli->connect_errno. ')' .$mysqli->connect_error);
}

$fname = mysqli_real_escape_string($mysqli, $_POST['fname']);
$lname = mysqli_real_escape_string($mysqli, $_POST['lname']);
$email = mysqli_real_escape_string($mysqli, $_POST['email']);
$password = mysqli_real_escape_string($mysqli, $_POST['password']);

// Validation
if (strlen($fname) < 2) {
    echo 'fname';
} elseif (strlen($lname) < 2) {
    echo 'lname';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'email';
} elseif (strlen($password) <= 4) {
    echo 'password';
} else {
    // Check if email already exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        echo 'taken';
    } else {
        // Encrypt password
        $spassword = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
        $numRow = mysqli_num_rows($result);
        $row = mysqli_fetch_array($result);

        if($numRow < 1){
            $insertRow = $mysqli->query("INSERT INTO users (fname, lname, email, password) VALUES ('$fname', '$lname', '$email', '$spassword')");

            if($insertRow){
                $_SESSION['login'] = $mysqli->insert_id;
                $_SESSION['fname'] = $fname;
                $_SESSION['lname'] = $lname;

                echo 'true';
            } else {
                echo 'false';
            }
        }
    }
}