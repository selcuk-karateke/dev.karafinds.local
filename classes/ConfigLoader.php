<?php
// classes/ConfigLoader.php
namespace Karatekes;

class ConfigLoader
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getWebsites()
    {
        $stmt = $this->pdo->query('SELECT * FROM websites');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getDatabases($website_id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM databases WHERE website_id = :website_id');
        $stmt->execute(['website_id' => $website_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getFtpAccounts($website_id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM ftp_accounts WHERE website_id = :website_id');
        $stmt->execute(['website_id' => $website_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getApiAccounts($website_id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM api_accounts WHERE website_id = :website_id');
        $stmt->execute(['website_id' => $website_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getApiAccount($website_id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM api_accounts WHERE website_id = :website_id LIMIT 1');
        $stmt->execute(['website_id' => $website_id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
