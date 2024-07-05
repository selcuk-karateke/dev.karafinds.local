<?php
// classes/GoogleTrafficMonitor.php
class GoogleTrafficMonitor
{
    private $logFile;

    public function __construct($logFile)
    {
        $this->logFile = $logFile;
    }

    public function checkGoogleTraffic()
    {
        $googleCount = 0;

        if (file_exists($this->logFile)) {
            $file = fopen($this->logFile, 'r');
            while (($line = fgets($file)) !== false) {
                if (strpos($line, 'google.com') !== false) {
                    $googleCount++;
                }
            }
            fclose($file);
        } else {
            return "Log file not found.";
        }

        return $googleCount;
    }
}
