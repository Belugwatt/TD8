<?php
require_once 'config.php'; // Charge les variables d'environnement

function base64url_encode($data) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
}

function base64url_decode($data) {
    return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
}

function generate_jwt($payload) {
    $header = base64url_encode(json_encode(["alg" => "HS256", "typ" => "JWT"]));
    $payload = base64url_encode(json_encode($payload));
    $signature = hash_hmac('sha256', "$header.$payload", JWT_SECRET, true);
    $signature = base64url_encode($signature);
    
    return "$header.$payload.$signature";
}

function verify_jwt($token) {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return false;

    $signature = base64url_encode(hash_hmac('sha256', "$parts[0].$parts[1]", JWT_SECRET, true));
    
    return hash_equals($signature, $parts[2]);
}
