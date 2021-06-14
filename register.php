<?php 
    $conn = new mysqli("localhost", "root", "", "yetanothermobileshooter");

    if(mysqli_connect_errno()){
        die("1: Connection Failed"); // error code #1 = connection failed
    }

    $username = $_POST["username"];
    $password = $_POST["password"];

    // https://www.codegrepper.com/code-examples/php/errors+in+mysql+php+prepared+statement

    // check if name exists
    $query = "SELECT username FROM user WHERE username = ?";
    $stmt = $conn->prepare($query);

    if($stmt === false){
        die("2: " . $mysqli->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if(mysqli_num_rows($result) > 0){
        die("3: Name already exists");
    }

    // add user to the table
    $hash = password_hash($password, PASSWORD_ARGON2I); // https://framework.zend.com/blog/2017-08-17-php72-argon2-hash-password.html
    $query = "INSERT INTO user(username, password) VALUES (?,?)";
    $stmt = $conn->prepare($query);
    if($stmt === false){
        die("4: " . $mysqli->error);
    }
    $stmt->bind_param("ss", $username, $hash);
    $stmt->execute();
    

    echo("0");
?>