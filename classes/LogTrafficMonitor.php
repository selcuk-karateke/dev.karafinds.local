<?php
// classes/LogTrafficMonitor.php
class LogTrafficMonitor
{
    private $logFile;

    public function __construct($logFile)
    {
        $this->logFile = $logFile;
    }

    public function checkLogTraffic()
    {
        $logCount = 0;

        if (file_exists($this->logFile)) {
            $file = fopen($this->logFile, 'r');
            while (($line = fgets($file)) !== false) {
                if (strpos($line, 'google.com') !== false) {
                    $logCount++;
                }
            }
            fclose($file);
        } else {
            return "Log file not found.";
        }

        return $logCount;
    }
}
