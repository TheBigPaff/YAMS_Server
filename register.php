<?php 
include_once 'jwt_manager.php';
include_once 'email.php';

$conn = new mysqli("localhost", "root", "", "yetanothermobileshooter");

if(mysqli_connect_errno()){
    die("1: Connection Failed"); // error code #1 = connection failed
}

$username = $_POST["username"];
$email = $_POST["email"];
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

// check if email exists
$query = "SELECT username FROM user WHERE email = ?";
$stmt = $conn->prepare($query);

if($stmt === false){
    die("4: " . $mysqli->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if(mysqli_num_rows($result) > 0){
    die("5: Email already exists");
}

// add user to the table
$hash = password_hash($password, PASSWORD_ARGON2I); // https://framework.zend.com/blog/2017-08-17-php72-argon2-hash-password.html
$query = "INSERT INTO user(username, email, password) VALUES (?,?,?)";
$stmt = $conn->prepare($query);
if($stmt === false){
    die("6: " . $mysqli->error);
}
$stmt->bind_param("sss", $username, $email, $hash);
$stmt->execute();
$id=$conn->insert_id;   

// send verification email
$token = encode_token($id);
send_email($email, 'Verify your Yet Another Mobile Shooter account', "Hi! Welcome to Yet Another Mobile Shooter. If it wasn't you that signed up, kindly ignore this email.<br><b>Click <a href=\"http://localhost/yetanothermobileshooter/verification.php?token=".$token."\">this link</a> to verify your user account.</b>");

echo("0");
?>