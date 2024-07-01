<?php
// classes/SecurityMonitor.php
namespace Karatekes;

require '../vendor/autoload.php';

use phpseclib3\Net\SSH2;


class SecurityMonitor
{
    private $ssh;
    private $directory;

    public function __construct($host, $username, $password, $directory, $port = 22)
    {
        // Sicherstellen, dass der Port ein Integer ist
        $port = (int)$port;

        $this->ssh = new SSH2($host, $port);
        if (!$this->ssh->login($username, $password)) {
            throw new \Exception('Login failed');
        }
        $this->directory = $directory;
    }

    public function malwareScan()
    {
        $malwarePatterns = ['eval', 'base64_decode', 'exec', 'shell_exec'];
        $command = "grep -r -n -E '" . implode('|', $malwarePatterns) . "' " . escapeshellarg($this->directory);

        $output = $this->ssh->exec($command);
        if ($output === false) {
            throw new \Exception('Error executing command: ' . $this->ssh->getLastError());
        }

        if (empty($output)) {
            return "No malware detected.";
        } else {
            $detailedOutput = "Malware found:<div class='accordion' id='accordionExample'>";
            $lines = explode("\n", trim($output));
            $index = 0;
            foreach ($lines as $line) {
                $parts = explode(':', $line, 3);
                if (count($parts) === 3) {
                    list($filePath, $lineNumber, $match) = $parts;
                    $fileName = basename($filePath); // Nur den Dateinamen extrahieren
                    if (is_numeric($lineNumber)) {
                        $lineNumber = (int)$lineNumber;
                        $contextCommand = "sed -n '" . ($lineNumber - 2) . "," . ($lineNumber + 2) . "p' " . escapeshellarg($filePath);
                        $context = $this->ssh->exec($contextCommand);
                        if ($context === false) {
                            throw new \Exception('Error executing context command: ' . $this->ssh->getLastError());
                        }
                        $detailedOutput .= "
                <div class='accordion-item'>
                    <h2 class='accordion-header' id='heading{$index}'>
                        <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse{$index}' aria-expanded='false' aria-controls='collapse{$index}'>
                            File: $fileName, Line: $lineNumber
                        </button>
                    </h2>
                    <div id='collapse{$index}' class='accordion-collapse collapse' aria-labelledby='heading{$index}' data-bs-parent='#accordionExample'>
                        <div class='accordion-body'>
                            File: $filePath<br> 
                            <code>Code: $context</code>
                        </div>
                    </div>
                </div>";
                        $index++;
                    }
                }
            }
            $detailedOutput .= "</div>";
            return $detailedOutput;
        }
    }
}
