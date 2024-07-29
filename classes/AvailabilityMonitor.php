<?php
// classes/AvailabilityMonitor.php
namespace Karatekes;

class AvailabilityMonitor
{
    private $url;
    private $cacertPath;

    public function __construct($url, $cacertPath)
    {
        $this->url = $url;
        $this->cacertPath = $cacertPath;
    }

    public function check()
    {
        // cURL-Handle initialisieren
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CAINFO, $this->cacertPath);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Redirects folgen
        curl_setopt($ch, CURLOPT_VERBOSE, true); // Detailed output
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); // User-Agent setzen

        $response = curl_exec($ch);

        // Fehlerprüfung
        if ($response === false) {
            return "cURL: " . curl_error($ch);
        } else {
            // HTTP-Statuscode extrahieren
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            switch ($statusCode) {
                case 200:
                    return "UP";
                case 403:
                    return "DOWN (403)";
                default:
                    return "DOWN ($statusCode)";
            }
        }
    }
}
