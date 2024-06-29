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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL-Verifizierung deaktivieren
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // SSL-Verifizierung deaktivieren
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return ['error' => 'CURL error: ' . $error_msg];
        }

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

        if (isset($plugins['error'])) {
            return ['error' => 'Failed to fetch plugin updates: ' . $plugins['error']];
        }

        if (isset($themes['error'])) {
            return ['error' => 'Failed to fetch theme updates: ' . $themes['error']];
        }

        return ['plugins' => $plugins, 'themes' => $themes];
    }
}
