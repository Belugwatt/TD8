<?php
// CORS : autoriser les requêtes depuis localhost:3000
header("Access-Control-Allow-Origin: http://localhost:3000");

// Autoriser les méthodes HTTP spécifiques
header("Access-Control-Allow-Methods: POST, OPTIONS");

// Autoriser les en-têtes spécifiques (Content-Type pour JSON)
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Si la requête est pré-vol (OPTIONS), répondre immédiatement sans traitement
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Autoriser l'envoi de cookies avec les requêtes (si tu veux inclure des cookies comme le JWT)
header("Access-Control-Allow-Credentials: true");

require 'jwt.php'; // Contient les fonctions generate_jwt() et verify_jwt()

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data["username"], $data["password"])) {
    $users = include __DIR__ . '/user.php';

    $username = $data["username"];
    $password = $data["password"];

    if (isset($users[$username]) && $users[$username] === $password) {
        // Génération du JWT
        $token = generate_jwt(["user" => $username, "exp" => time() + 3600]); // Expire dans 1h
        
        // Définition du cookie HttpOnly avec le JWT
        setcookie("token", $token, [
            "expires" => time() + 3600,
            "path" => "/",
            "domain" => "localhost:8080",  // Changer si ton domaine est différent
            "secure" => false,  // Mettre true si HTTPS
            "httponly" => true,
            "samesite" => "None" // Nécessaire pour que le cookie soit envoyé cross-origin
        ]);

        echo json_encode(["message" => "Connexion good"]);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Identifiants incorrects"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Requête invalide"]);
}
?>
