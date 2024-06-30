<?php
if (isset($_GET['url'])) {
    $site_url = $_GET['url'];
    $comments_url = $site_url . '/wp-json/wp/v2/comments';
    $response = file_get_contents($comments_url);
    if ($response === FALSE) {
        $comments = [];
    } else {
        $comments =  json_decode($response, true);
    }
    echo json_encode($comments);
} else {
    echo json_encode([]);
}
