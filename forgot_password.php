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

        $username = $result->fetch_assoc()["username"];

        // send email 
        $token = encode_token_email($email);
        send_email($email, 'Change your Yet Another Mobile Shooter account password', 
        "Hi, user \"$username\"! <br>You're receiving this email because you asked to change your 'Yet Another Mobile Shooter' account password. 
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
            echo "Not authorized."; // TODO: check if JWT has already been used once
            exit;
        }
        else{
            session_start();
            echo '
            <html>
                <head>
                    <link rel="stylesheet" href="styles/form.css">
                    <script src="scripts/forgot_password.js"></script>
                </head>
                <body>
                    <div class="signupSection">
                        <div class="info">
                            <h2>Yet Another Mobile Shooter</h2>
                            <img src="assets/logo.jpeg" alt="Logo" id="logo">
                            <p>Secure form to change your password</p>
                        </div>
                    <form action="#" method="POST" class="signupForm" name="signupform">
                        <input type="hidden" name="token" value="'.$_GET["token"].'" />
                        <h2>Change your password</h2>
                        <ul class="noBullet">
                            <li>
                                <label for="password"></label>
                                <input type="password" class="inputFields" id="password" name="password" placeholder="Password" value="" oninput="return passwordValidation(this.value)" required/>
                            </li>
                            <li>
                                <label for="confirm_password"></label>
                                <input type="password" class="inputFields" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="" oninput="return confirmPasswordValidation(this.value)" required/>
                            </li>
                            <li id="center-btn">
                            <input type="submit" id="join-btn" name="join" alt="Join" value="Join">
                            </li>
                        </ul>
                    </form>
                    </div>

                    <!--<form method="POST">
                        <input type="hidden" name="token" value="'.$_GET["token"].'" />
                        <label>New password:</label><br>
                        <input type="password"  name="password"><br>
                        <label>Confirm new passowrd:</label><br>
                        <input type="password" name="confirm_password"><br><br>
                        <input type="submit" value="Submit">
                    </form>-->
                </body>
            </html>
            ';
        }
    }
    else{
        echo "Not authorized.";
    }

    if(isset($_POST["password"]) && !empty($_POST["password"]) && isset($_POST["confirm_password"]) && !empty($_POST["confirm_password"]) && isset($_GET["token"])){
        $decoded = decode_token($_GET["token"]);
        $now = new DateTimeImmutable();
        if ($decoded->iss !== $serverName ||
        $decoded->nbf > $now->getTimestamp() ||
        $decoded->exp < $now->getTimestamp())
        {
            echo "Not authorized."; // TODO: check if JWT has already been used once
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