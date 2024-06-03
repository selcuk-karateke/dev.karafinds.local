<?php

/**
 * Funktion zum Starten der OAuth 2.0 Authentifizierung und Abruf des Access Tokens.
 */
function getAccessToken($code, $clientId, $clientSecret, $redirectUri)
{
    $tokenUrl = "https://api.instagram.com/oauth/access_token";

    $postData = http_build_query([
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'grant_type' => 'authorization_code',
        'redirect_uri' => $redirectUri,
        'code' => $code,
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($result, true);
    if (isset($response['access_token'])) {
        return $response['access_token'];
    } else {
        return null;
    }
}

/**
 * Funktion zur Überprüfung der Gültigkeit eines Access Tokens.
 */
function checkAccessTokenValidity($accessToken, $appId, $appSecret)
{
    $url = "https://graph.facebook.com/debug_token?input_token={$accessToken}&access_token={$appId}|{$appSecret}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);
    $result = [
        'is_valid' => false,
        'data' => null
    ];

    if (isset($responseData['data']['is_valid']) && $responseData['data']['is_valid']) {
        $result['is_valid'] = true;
        $result['data'] = $responseData['data'];
    }

    return $result;
}

/**
 * Funktion zum Abrufen von Kommentaren eines Media-Objekts.
 */
function fetchComments($mediaId, $accessToken)
{
    $url = "https://graph.instagram.com/{$mediaId}/comments?access_token={$accessToken}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $comments = curl_exec($ch);
    curl_close($ch);

    return $comments;
}
