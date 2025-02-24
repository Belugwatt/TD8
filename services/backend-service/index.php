<?php
header("Content-Type: application/json");

$jwt_secret = getenv("JWT_SECRET");

function validate_jwt($jwt, $secret) {
    $parts = explode(".", $jwt);
    if (count($parts) !== 3) return false;

    [$header, $payload, $signature] = $parts;
    $valid_signature = base64_encode(hash_hmac('sha256', "$header.$payload", $secret, true));

    return $valid_signature === $signature;
}

$headers = apache_request_headers();
$authHeader = isset($headers["Authorization"]) ? $headers["Authorization"] : "";

if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    $jwt = $matches[1];

    if (validate_jwt($jwt, $jwt_secret)) {
        echo json_encode(["message" => "Données sécurisées", "data" => ["foo" => "bar"]]);
    } else {
        http_response_code(403);
        echo json_encode(["error" => "JWT invalide"]);
    }
} else {
    http_response_code(401);
    echo json_encode(["error" => "Aucun token fourni"]);
}
