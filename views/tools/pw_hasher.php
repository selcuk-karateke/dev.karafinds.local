<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';
$title = "PW Hasher with Salt - Karatekos";
$meta_description = "PW Hasher with Salt - Willkommen bei Karatekos";
// $additional_head_content_1 = '<link rel="stylesheet" href="extra.css">';

include $_SERVER['DOCUMENT_ROOT'] . '/parts/head.php';

function pwHasherWithSalt($conn)
{
    $sql = "SELECT id, username, password FROM users";
    $stmt = $conn->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        // Salt generieren
        $salt = bin2hex(random_bytes(16));
        // Passwort hashen
        $passwordHash = password_hash($user['password'] . $salt, PASSWORD_DEFAULT);
        // Update Query
        $updateSql = "UPDATE users SET password = :password, salt = :salt WHERE id = :id";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindParam(':password', $passwordHash);
        $updateStmt->bindParam(':salt', $salt);
        $updateStmt->bindParam(':id', $user['id']);
        $updateStmt->execute();
    }
}
?>

<body class="bg-dark text-white">
    <div class="container">
        <header class="my-4">
            <h1 class="text-center"><?= $title ?></h1>
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/parts/nav.php'; ?>
        </header>
        <section>
            <div class="row">
                <div class="col-md-4">
                    <form method="post">
                        <button class="btn btn-primary" type="submit" name="convertPWs">Passwörter konvertieren</button>
                    </form>
                </div>
                <div class="col-md-8">
                    <?= $message ?>
                </div>
            </div>
        </section>
    </div>
    <footer class="text-center stroked-text mt-4">
        &copy; 2023 Karatekos - Alle Rechte vorbehalten.
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>