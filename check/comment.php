<?php
require_once '../classes/AkismetMonitor.php';

$api_key = $_POST['api_key'];
$blog_url = $_POST['blog_url'];
$comment = $_POST['comment'];

$akismet = new Akismet($api_key, $blog_url);

if ($akismet->verifyKey()) {
    if ($akismet->checkComment($comment)) {
        echo 'true';
    } else {
        echo 'false';
    }
} else {
    echo 'Invalid API key.';
}
