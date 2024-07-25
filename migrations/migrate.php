<?php
// Datenbankverbindung herstellen
$pdo = new PDO('mysql:host=localhost;dbname=db_main', 'root', '');

// JSON-Daten laden
$jsonData = file_get_contents(__DIR__ . '/../config.json');
$data = json_decode($jsonData, true);

foreach ($data['websites'] as $website) {
    // Webseite einfügen
    $stmt = $pdo->prepare('INSERT INTO websites (name, url, mail, hash, host, port, user, pass, path, updates, type, spam_api) 
                           VALUES (:name, :url, :mail, :hash, :host, :port, :user, :pass, :path, :updates, :type, :spam_api)');
    $stmt->execute([
        ':name' => $website['name'],
        ':url' => $website['url'],
        ':mail' => $website['mail'],
        ':hash' => $website['hash'],
        ':host' => $website['host'],
        ':port' => $website['port'],
        ':user' => $website['user'],
        ':pass' => $website['pass'],
        ':path' => $website['path'],
        ':updates' => $website['updates'],
        ':type' => $website['type'],
        ':spam_api' => $website['spam_api']
    ]);
    $websiteId = $pdo->lastInsertId();

    // Datenbanken einfügen
    foreach ($website['db'] as $db) {
        $stmt = $pdo->prepare('INSERT INTO db_accounts (website_id, name, host, user, pass) VALUES (:website_id, :name, :host, :user, :pass)');
        $stmt->execute([
            ':website_id' => $websiteId,
            ':name' => $db['name'],
            ':host' => $db['host'],
            ':user' => $db['user'],
            ':pass' => $db['pass']
        ]);
    }

    // FTP-Zugangsdaten einfügen
    foreach ($website['ftp'] as $ftp) {
        $stmt = $pdo->prepare('INSERT INTO ftp_accounts (website_id, user, pass) VALUES (:website_id, :user, :pass)');
        $stmt->execute([
            ':website_id' => $websiteId,
            ':user' => $ftp['user'],
            ':pass' => $ftp['pass']
        ]);
    }

    // API-Zugangsdaten einfügen
    foreach ($website['api'] as $api) {
        $stmt = $pdo->prepare('INSERT INTO api_accounts (website_id, user, pass) VALUES (:website_id, :user, :pass)');
        $stmt->execute([
            ':website_id' => $websiteId,
            ':user' => $api['user'],
            ':pass' => $api['pass']
        ]);
    }
}
