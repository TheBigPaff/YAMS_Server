<?php 
require_once('vendor/autoload.php');
use Firebase\JWT\JWT;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$secret_key = $_ENV['JWT_SECRET_KEY'];
$serverName = "myanothermobileshooter";                                    

function encode_token($id){
    $issuedAt   = new DateTimeImmutable();
    $expire     = $issuedAt->modify('+6 minutes')->getTimestamp();      // Add 60 seconds
    global $secret_key, $serverName;

    $data = [
        'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
        'iss'  => $serverName,                       // Issuer
        'nbf'  => $issuedAt->getTimestamp(),         // Not before
        'exp'  => $expire,                           // Expire
        'id' => $id,                     // Id
    ];

    // Encode the array to a JWT string.
    $token = JWT::encode(
        $data,
        $secret_key
    );

    return $token;
}

function decode_token($token){
    global $secret_key;
    return JWT::decode($token, $secret_key, array('HS256'));
}
?>