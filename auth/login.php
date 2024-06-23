<?php
$title = "Login";
$meta_description = "Login bei Webdesign Karateke";
// $additional_head_content = '<link rel="stylesheet" href="index.css">';
include './parts/head.php';
?>
<div class="container">
    <header class="my-4">
        <h1 class="text-center">Login - Webdesign Karateke</h1>
        <?php include './parts/nav.php'; ?>
    </header>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" name="login">Login</button>
            </form>
        </div>
    </div>
    <footer class="text-center mt-4">
        &copy; 2023 Webdesign Karateke - Alle Rechte vorbehalten.
    </footer>
</div>