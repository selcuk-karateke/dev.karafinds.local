<?php

namespace Karatekes;

class Logger
{
    private $logFile;

    public function __construct($filename = "logs/custom.log")
    {
        $this->logFile = $filename;
    }

    /**
     * Schreibt eine Nachricht ins Logfile und optional in den Browser.
     * 
     * @param string $message Die zu loggende Nachricht.
     * @param string $level Das Log-Level (info, warning, error).
     * @param bool $echo Flag, ob die Nachricht auch im Browser ausgegeben werden soll.
     */
    public function log($message, $level = 'info', $echo = false)
    {
        $time = date('Y-m-d H:i:s');
        $logMessage = "[$time] [$level] $message" . PHP_EOL;

        // In Datei schreiben
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);

        // Optional: Im Browser ausgeben
        if ($echo) {
            $this->echoToBrowser($message, $level);
        }
    }

    /**
     * Gibt die Nachricht im Browser aus, nutzt Bootstrap 5 für das Styling.
     * 
     * @param string $message Die Nachricht.
     * @param string $level Das Log-Level (info, warning, error).
     */
    private function echoToBrowser($message, $level)
    {
        $alertType = $this->getAlertType($level);
        echo "<div class='alert {$alertType}'>{$message}</div>";
    }

    /**
     * Ermittelt den Bootstrap-Alert-Typ basierend auf dem Log-Level.
     * 
     * @param string $level Das Log-Level.
     * @return string Der Bootstrap-Alert-Typ.
     */
    private function getAlertType($level)
    {
        switch ($level) {
            case 'error':
                return 'alert-danger';
            case 'warning':
                return 'alert-warning';
            default:
                return 'alert-info';
        }
    }
}
