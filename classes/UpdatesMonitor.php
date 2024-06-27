<?php
// classes/UpdatesMonitor.php
class UpdatesMonitor
{
    private $siteUrl;
    private $username;
    private $password;

    public function __construct($siteUrl, $username, $password)
    {
        $this->siteUrl = $siteUrl;
        $this->username = $username;
        $this->password = $password;
    }

    private function makeRequest($endpoint)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->siteUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $response = curl_exec($ch);
        curl_close($ch);

        $decodedResponse = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['error' => 'Invalid JSON response'];
        }

        return $decodedResponse;
    }

    public function getUpdates()
    {
        $plugins = $this->makeRequest("/wp-json/wp/v2/plugins");
        $themes = $this->makeRequest("/wp-json/wp/v2/themes");

        if (isset($plugins['error']) || isset($themes['error'])) {
            return ['plugins' => [], 'themes' => [], 'error' => 'Failed to fetch updates'];
        }

        return ['plugins' => $plugins, 'themes' => $themes];
    }
}
