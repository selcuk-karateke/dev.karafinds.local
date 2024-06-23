<?php
// classes/SecurityMonitor.php
namespace Karatekes;

require 'vendor/autoload.php';

use phpseclib3\Net\SSH2;


class SecurityMonitor
{
    private $ssh;
    private $directory;

    public function __construct($host, $port, $username, $password, $directory)
    {
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

        if (empty($output)) {
            return "No malware detected.";
        } else {
            $detailedOutput = "Malware found:\n";
            $lines = explode("\n", trim($output));
            foreach ($lines as $line) {
                list($filePath, $lineNumber, $match) = explode(':', $line, 3);
                $contextCommand = "sed -n '" . ($lineNumber - 2) . "," . ($lineNumber + 2) . "p' " . escapeshellarg($filePath);
                $context = $this->ssh->exec($contextCommand);
                $detailedOutput .= "\nFile: $filePath\nLine: $lineNumber\n<code>Code:\n$context\n</code>";
            }
            return $detailedOutput;
        }
    }
}
