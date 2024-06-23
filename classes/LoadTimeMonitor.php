<?php
// classes/LoadTimeMonitor.php
namespace Karatekes;

class LoadTimeMonitor
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getLoadTime()
    {
        $start = microtime(true);
        $context = stream_context_create([
            'http' => [
                'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'
            ]
        ]);
        @file_get_contents($this->url, false, $context); // Suppress error messages with @
        $end = microtime(true);
        return $end - $start;
    }
}
