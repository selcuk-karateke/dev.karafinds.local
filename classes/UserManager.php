<?php

class UserManager
{
    private $conn;
    private $logger;

    public function __construct($logger)
    {
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

    public function register($username, $password, $birthdate)
    {
        try {
            // Salt generieren
            $salt = bin2hex(random_bytes(16));
            $passwordHash = password_hash($password . $salt, PASSWORD_DEFAULT);

            // IDs berechnen
            $generation = $this->getGeneration($birthdate);
            $lifePathNumber = $this->calculateLifePathNumber($birthdate);
            $westernZodiacId = $this->getWesternZodiacId($birthdate);
            $chineseZodiacId = $this->getChineseZodiacId($birthdate);
            $uniqueSignId = $this->getUniqueSignId($birthdate);
            $indianZodiacId = $this->getIndianZodiacId($birthdate);
            $celticZodiacId = $this->getCelticZodiacId($birthdate);
            $mayaZodiacId = $this->getMayaZodiacId($birthdate);
            $egyptianZodiacId = $this->getEgyptianZodiacId($birthdate);
            $destinyNumber = $this->getDestinyNumber($birthdate);
            $personalityNumber = $this->getPersonalityNumber($birthdate);
            $soulNumber = $this->getSoulNumber($birthdate);
            $birthNumber = $this->getBirthNumber($birthdate);

            // Daten in die Personen-Tabelle einfügen
            $sqlPerson = "INSERT INTO Personen (name, birthdate, generation, life_path_number, western_zodiac_id, chinese_zodiac_id, unique_sign_id, indian_zodiac_id, celtic_zodiac_id, maya_zodiac_id, egyptian_zodiac_id, destiny_number, personality_number, soul_number, birth_number) VALUES (:name, :birthdate, :generation, :life_path_number, :western_zodiac_id, :chinese_zodiac_id, :unique_sign_id, :indian_zodiac_id, :celtic_zodiac_id, :maya_zodiac_id, :egyptian_zodiac_id, :destiny_number, :personality_number, :soul_number, :birth_number)";
            $stmtPerson = $this->conn->prepare($sqlPerson);
            $stmtPerson->bindParam(':name', $username);
            $stmtPerson->bindParam(':birthdate', $birthdate);
            $stmtPerson->bindParam(':generation', $generation);
            $stmtPerson->bindParam(':life_path_number', $lifePathNumber);
            $stmtPerson->bindParam(':western_zodiac_id', $westernZodiacId);
            $stmtPerson->bindParam(':chinese_zodiac_id', $chineseZodiacId);
            $stmtPerson->bindParam(':unique_sign_id', $uniqueSignId);
            $stmtPerson->bindParam(':indian_zodiac_id', $indianZodiacId);
            $stmtPerson->bindParam(':celtic_zodiac_id', $celticZodiacId);
            $stmtPerson->bindParam(':maya_zodiac_id', $mayaZodiacId);
            $stmtPerson->bindParam(':egyptian_zodiac_id', $egyptianZodiacId);
            $stmtPerson->bindParam(':destiny_number', $destinyNumber);
            $stmtPerson->bindParam(':personality_number', $personalityNumber);
            $stmtPerson->bindParam(':soul_number', $soulNumber);
            $stmtPerson->bindParam(':birth_number', $birthNumber);
            $stmtPerson->execute();
            $personId = $this->conn->lastInsertId();

            // Daten in die Users-Tabelle einfügen
            $sqlUser = "INSERT INTO Users (username, password, salt, person_id) VALUES (:username, :password, :salt, :person_id)";
            $stmtUser = $this->conn->prepare($sqlUser);
            $stmtUser->bindParam(':username', $username);
            $stmtUser->bindParam(':password', $passwordHash);
            $stmtUser->bindParam(':salt', $salt);
            $stmtUser->bindParam(':person_id', $personId);

            if ($stmtUser->execute()) {
                header("Location: login.php");
                exit();
            } else {
                throw new \Exception("Fehler bei der Registrierung.");
            }
        } catch (\PDOException $e) {
            error_log('Error during registration: ' . $e->getMessage());
            throw new \Exception("Error during registration");
        }
    }

    public function login($username, $password)
    {
        try {
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            $this->logger->log(print_r($user, true), 'info', false);

            if ($user && password_verify($password . $user['salt'], $user['password'])) {
                $_SESSION["username"] = $user['username'];
                $_SESSION["logged"] = "1";
                header("Location: /");
                exit();
            } else {
                throw new \Exception("Invalid username or password.");
            }
        } catch (\PDOException $e) {
            error_log('Error during login: ' . $e->getMessage());
            throw new \Exception("Error during login");
        }
    }

    public function logout()
    {
        $this->logger->log('logout', 'info');
        session_start();
        $_SESSION = [];
        session_destroy();
        header("Location: /");
        exit();
    }

    function getPersonByUsername($username)
    {
        try {
            $sql =
                "SELECT p.*, 
                       wez.name AS western_zodiac, wez.description AS western_description, 
                       chz.name AS chinese_zodiac, chz.description AS chinese_description, 
                       us.name AS unique_sign, us.description AS unique_sign_description, 
                       iz.name AS indian_zodiac, iz.description AS indian_description, 
                       cz.name AS celtic_zodiac, cz.description AS celtic_description, 
                       mz.name AS maya_zodiac, mz.description AS maya_description, 
                       ez.name AS egyptian_zodiac, ez.description AS egyptian_description 
                FROM persons p
                JOIN users u ON p.id = u.person_id
                LEFT JOIN WesternZodiac wez ON p.western_zodiac_id = wez.id
                LEFT JOIN ChineseZodiac chz ON p.chinese_zodiac_id = chz.id
                LEFT JOIN UniqueSigns us ON p.unique_sign_id = us.id
                LEFT JOIN IndianZodiac iz ON p.indian_zodiac_id = iz.id
                LEFT JOIN CelticZodiac cz ON p.celtic_zodiac_id = cz.id
                LEFT JOIN MayaZodiac mz ON p.maya_zodiac_id = mz.id
                LEFT JOIN EgyptianZodiac ez ON p.egyptian_zodiac_id = ez.id
                WHERE u.username = :username";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
    // Add the helper methods here, ensuring they are accessible within this class
    private function getGeneration($birthdate)
    {
        $year = (int)substr($birthdate, 0, 4);
        if ($year >= 1946 && $year <= 1964) return 'Baby Boomer';
        if ($year >= 1965 && $year <= 1980) return 'Generation X';
        if ($year >= 1981 && $year <= 1996) return 'Millennial';
        if ($year >= 1997 && $year <= 2012) return 'Generation Z';
        if ($year >= 2013) return 'Generation Alpha';
        return 'Unknown';
    }

    private function calculateLifePathNumber($birthdate)
    {
        $digits = str_replace('-', '', $birthdate);
        while (strlen($digits) > 1) {
            $digits = array_sum(str_split($digits));
        }
        return $digits;
    }

    private function getWesternZodiacId($birthdate)
    {
        $date = new DateTime($birthdate);
        $day = $date->format('j');
        $month = $date->format('n');
        $zodiacSigns = [
            ['start' => [1, 20], 'end' => [2, 18], 'id' => 1],   // Wassermann
            ['start' => [2, 19], 'end' => [3, 20], 'id' => 2],   // Fische
            ['start' => [3, 21], 'end' => [4, 19], 'id' => 3],   // Widder
            ['start' => [4, 20], 'end' => [5, 20], 'id' => 4],   // Stier
            ['start' => [5, 21], 'end' => [6, 20], 'id' => 5],   // Zwillinge
            ['start' => [6, 21], 'end' => [7, 22], 'id' => 6],   // Krebs
            ['start' => [7, 23], 'end' => [8, 22], 'id' => 7],   // Löwe
            ['start' => [8, 23], 'end' => [9, 22], 'id' => 8],   // Jungfrau
            ['start' => [9, 23], 'end' => [10, 22], 'id' => 9],  // Waage
            ['start' => [10, 23], 'end' => [11, 21], 'id' => 10], // Skorpion
            ['start' => [11, 22], 'end' => [12, 21], 'id' => 11], // Schütze
            ['start' => [12, 22], 'end' => [1, 19], 'id' => 12]  // Steinbock
        ];
        foreach ($zodiacSigns as $sign) {
            if (($month == $sign['start'][0] && $day >= $sign['start'][1]) || ($month == $sign['end'][0] && $day <= $sign['end'][1])) {
                return $sign['id'];
            }
        }
        return null;
    }

    private function getChineseZodiacId($birthdate)
    {
        $year = (int)substr($birthdate, 0, 4);
        $zodiacStartYear = 4; // 1900 ist ein Jahr der Ratte
        $zodiacCycleLength = 12;
        $zodiacId = (($year - $zodiacStartYear) % $zodiacCycleLength) + 1;
        return $zodiacId;
    }

    private function getUniqueSignId($birthdate)
    {
        return 1; // Beispielwert
    }

    private function getIndianZodiacId($birthdate)
    {
        return 1; // Beispielwert
    }

    private function getCelticZodiacId($birthdate)
    {
        return 1; // Beispielwert
    }

    private function getMayaZodiacId($birthdate)
    {
        return 1; // Beispielwert
    }

    private function getEgyptianZodiacId($birthdate)
    {
        return 1; // Beispielwert
    }

    private function getDestinyNumber($birthdate)
    {
        return $this->calculateLifePathNumber($birthdate);
    }

    private function getPersonalityNumber($birthdate)
    {
        return $this->calculateLifePathNumber($birthdate);
    }

    private function getSoulNumber($birthdate)
    {
        return $this->calculateLifePathNumber($birthdate);
    }

    private function getBirthNumber($birthdate)
    {
        return $this->calculateLifePathNumber($birthdate);
    }
}
