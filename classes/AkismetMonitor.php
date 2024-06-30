<?php

class Akismet
{
    private $api_key;
    private $blog_url;
    private $api_url = 'https://rest.akismet.com/1.1/';

    public function __construct($api_key, $blog_url)
    {
        $this->api_key = $api_key;
        $this->blog_url = $blog_url;
    }

    public function verifyKey()
    {
        $data = [
            'key' => $this->api_key,
            'blog' => $this->blog_url,
        ];
        $response = $this->makeRequest('verify-key', $data);
        return $response == 'valid';
    }

    public function checkComment($comment)
    {
        $data = array_merge([
            'blog' => $this->blog_url,
            'user_ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'referrer' => $_SERVER['HTTP_REFERER'],
            'permalink' => $comment['permalink'],
            'comment_type' => $comment['comment_type'],
            'comment_author' => $comment['comment_author'],
            'comment_author_email' => $comment['comment_author_email'],
            'comment_author_url' => $comment['comment_author_url'],
            'comment_content' => $comment['comment_content'],
        ], $comment);

        $response = $this->makeRequest('comment-check', $data);
        return $response == 'true';
    }

    private function makeRequest($method, $data)
    {
        $url = $this->api_url . $method;
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context  = stream_context_create($options);
        return file_get_contents($url, false, $context);
    }
}
