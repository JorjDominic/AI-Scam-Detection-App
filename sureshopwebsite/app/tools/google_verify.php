<?php
function verifyGoogleToken($token) {
    $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $token;
    $response = file_get_contents($url);
    if (!$response) return null;

    $data = json_decode($response, true);
    return isset($data['email']) ? $data : null;
}
