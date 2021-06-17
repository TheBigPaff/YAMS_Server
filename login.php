<?php 
    $conn = new mysqli("localhost", "root", "", "yetanothermobileshooter");
    if(mysqli_connect_errno()){
        die("1: Connection Failed"); // error code #1 = connection failed
    }

    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // check if name exists
    $query = "SELECT username, password FROM user WHERE username = ?";
    $stmt = $conn->prepare($query);

    if($stmt === false){
        die("2: " . $mysqli->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if(mysqli_num_rows($result) == 0){
        die("3: No user with that name");
    }
    else if(mysqli_num_rows($result) > 1){
        die("3: More than one user with that name... Weird");
    }

    // get login info from query 
    $user_info = mysqli_fetch_assoc($result);

    // verify user -> https://stackoverflow.com/questions/47602044/how-do-i-use-the-argon2-algorithm-with-password-hash
        
    if(!password_verify($password, $user_info["password"])) {
        die("6: Incorrect password");
    }

    echo "0";
?>