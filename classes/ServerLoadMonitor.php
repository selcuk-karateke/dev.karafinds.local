<?php
// classes/ServerLoadMonitor.php
class ServerLoadMonitor
{
    private $url;
    private $host;
    private $port;
    private $user;
    private $pass;

    public function __construct($url, $host, $port, $user, $pass)
    {
        $this->url = $url;
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->pass = $pass;
    }
    public function getLoadLocal()
    {
        if (stristr(PHP_OS, 'win')) {
            // Windows system
            $output = shell_exec('wmic cpu get loadpercentage /value');
            if ($output !== null) {
                preg_match('/\d+/', $output, $matches);
                if (isset($matches[0])) {
                    return $matches[0];
                }
            }
            return 'N/A';
        } else {
            // Unix-based system
            $load = sys_getloadavg();
            return $load[0];
        }
    }
    public function getLoad()
    {
        if (!function_exists("ssh2_connect")) {
            die("SSH2 extension is not installed");
        }

        $connection = ssh2_connect($this->host, $this->port);
        if (!$connection) {
            return "Connection failed";
        }

        ssh2_auth_password($connection, $this->user, $this->pass);
        $stream = ssh2_exec($connection, "uptime");
        stream_set_blocking($stream, true);
        $output = stream_get_contents($stream);
        fclose($stream);

        if (preg_match('/load average: ([\d.]+),/', $output, $matches)) {
            return $matches[1];
        }

        return "N/A";
    }
    public function getLoadOverAPI()
    {
        $response = file_get_contents($this->url);
        if ($response === FALSE) {
            return "Unable to fetch data";
        }

        $data = json_decode($response, true);
        return [
            'load' => $data['load'],
            'memory_usage' => $data['memory_usage']
        ];
    }
}
