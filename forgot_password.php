<?php 
    include_once 'jwt_manager.php';
    include_once 'email.php';

    if(isset($_POST["email"])){
        $email = $_POST["email"];

        //check if email exists
        $conn = new mysqli("localhost", "root", "", "yetanothermobileshooter");
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if(mysqli_num_rows($result) == 0){
            die("1: No user with that email");
        }

        // send email 
        $token = encode_token_email($email);
        send_email($email, 'Change your Yet Another Mobile Shooter account password', 
        "Hi! You're receiving this email because you asked to change your 'Yet Another Mobile Shooter' account password. 
        If it wasn't you that asked for this, kindly ignore this email.<br><b>Click <a href=\"http://localhost/yetanothermobileshooter/forgot_password.php?token=".$token."\">this link</a> to change your password.</b>");
        echo "0";
    }

    if(isset($_GET["token"])){
        $decoded = decode_token($_GET["token"]);
        $now = new DateTimeImmutable();
        
        if ($decoded->iss !== $serverName ||
            $decoded->nbf > $now->getTimestamp() ||
            $decoded->exp < $now->getTimestamp())
        {
            echo "Not authorized."; // TODO: check is JWT has already been used once
            exit;
        }
        else{
            session_start();
            echo '
            <form method="POST">
                <input type="hidden" name="token" value="'.$_GET["token"].'" />
                <label>New password:</label><br>
                <input type="password"  name="password"><br>
                <label>Confirm new passowrd:</label><br>
                <input type="password" name="confirm_password"><br><br>
                <input type="submit" value="Submit">
            </form> 
            ';
        }
    }

    if(isset($_POST["password"]) && !empty($_POST["password"]) && isset($_POST["confirm_password"]) && !empty($_POST["confirm_password"]) && isset($_GET["token"])){
        $decoded = decode_token($_GET["token"]);
        $now = new DateTimeImmutable();
        if ($decoded->iss !== $serverName ||
        $decoded->nbf > $now->getTimestamp() ||
        $decoded->exp < $now->getTimestamp())
        {
            echo "Not authorized."; // TODO: check is JWT has already been used once
            exit;
        }


        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        if($password != $confirm_password){
            echo "Passwords are not the same.";
            return;
        }
        
        $hash = password_hash($password, PASSWORD_ARGON2I);
        // change password
        $conn = new mysqli("localhost", "root", "", "yetanothermobileshooter");
        $sql = "UPDATE user SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hash, $decoded->email);
        $stmt->execute();
    
        echo "Password changed.";
    }
?>