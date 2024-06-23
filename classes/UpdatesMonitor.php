<?php
// classes/UpdatesMonitor.php
class UpdatesMonitor
{
    private $siteUrl;
    private $cookie;

    public function __construct($siteUrl, $cookie)
    {
        $this->siteUrl = $siteUrl;
        $this->cookie = $cookie;
    }

    private function makeRequest($endpoint)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->siteUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Cookie: ' . $this->cookie
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    public function getUpdates()
    {
        $plugins = $this->makeRequest("/wp-json/wp/v2/plugins");
        $themes = $this->makeRequest("/wp-json/wp/v2/themes");
        return ['plugins' => $plugins, 'themes' => $themes];
    }
}
