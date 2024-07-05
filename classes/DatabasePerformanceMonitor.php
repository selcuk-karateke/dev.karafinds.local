<?php
// classes/DatabasePerformanceMonitor.php
class DatabasePerformanceMonitor
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getQueryCount()
    {
        $stmt = $this->pdo->query("SHOW STATUS LIKE 'Queries'");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['Value'];
    }
}
