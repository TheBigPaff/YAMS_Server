<?php 
include_once 'jwt_manager.php';
$token = $_GET["token"];
$decoded = decode_token($token);
$now = new DateTimeImmutable();

if ($decoded->iss !== $serverName ||
    $decoded->nbf > $now->getTimestamp() ||
    $decoded->exp < $now->getTimestamp())
{
    echo "Not authorized."; // TODO: check is JWT has already been used once
    exit;
}
else{
    // verify user
    $conn = new mysqli("localhost", "root", "", "yetanothermobileshooter");
    $sql = "UPDATE user SET verified = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $decoded->id);
    $stmt->execute();

    echo "You're now verified.";
}
?>