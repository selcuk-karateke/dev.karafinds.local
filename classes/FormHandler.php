<?php

class FormHandler
{
    private $conn;
    private $logger;
    private $userManager;

    public function __construct($userManager, $logger)
    {
        $this->userManager = $userManager;
        $this->logger = $logger;
        $dsn = 'mysql:host=localhost;dbname=karatekos;charset=utf8mb4';
        $username = 'root';
        $password = '';

        try {
            $this->conn = new \PDO($dsn, $username, $password);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            error_log('Connection failed: ' . $e->getMessage());
            throw new \Exception('Database connection error');
        }
    }

    public function handleRequest()
    {
        $message = "";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['convertPWs'])) {
                $message = $this->handlePWs();
            }
            if (isset($_POST['backup'])) {
                $message = $this->handleBackup();
            }
            if (isset($_POST["login"])) {
                $message = $this->handleLogin();
            }
            if (isset($_POST["logout_nav"])) {
                $message = $this->handleLogout();
                // $this->logger->log('logout_nav', 'info');
            }
            if (isset($_POST["register"])) {
                $message = $this->handleRegister();
            }
        }

        return $message;
    }

    private function handleBackup()
    {
        // Pfad zum Quell- und Zielverzeichnis
        $sourceDirectory = __DIR__;
        $targetDirectory = __DIR__ . '/backup';
        $fileStructure = [];

        // Funktion ausführen
        saveDirectoryAsText($sourceDirectory, $targetDirectory, $fileStructure);
        // Speichern der Dateistruktur als JSON
        file_put_contents($targetDirectory . '/file_structure.json', json_encode($fileStructure, JSON_PRETTY_PRINT));
        return "Backup wurde erfolgreich erstellt!";
    }

    private function handlePWs()
    {
        // Funktion ausführen
        $dsn = 'mysql:host=localhost;dbname=karatekos;charset=utf8mb4';
        $username = 'root';
        $password = '';
        $conn = new \PDO($dsn, $username, $password);
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        pwHasherWithSalt($this->conn);
        // 
        return "Passwörter wurden erfolgreich konvertiert!";
    }

    private function handleLogin()
    {
        try {
            $this->userManager->login($_POST['username'], $_POST['password']);
            return "";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function handleLogout()
    {
        $this->logger->log('logout', 'info');
        $this->userManager->logout();
        return "";
    }

    private function handleRegister()
    {
        try {
            $this->userManager->register($_POST['username'], $_POST['password'], $_POST['birthdate']);
            return "";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
