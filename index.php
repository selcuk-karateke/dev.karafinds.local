<?php
$title = "Homepage";
$meta_description = "Willkommen bei Webdesign Karateke";
// $additional_head_content = '<link rel="stylesheet" href="index.css">';
include 'parts/head.php';
?>

<body>
    <div class="container">
        <header class="my-4">
            <h1 class="text-center">Home - Webdesign Karateke</h1>
            <?php include 'parts/nav.htm'; ?>
        </header>
        <section>
            <div class="row">
                <div class="col-md-12">
                    <h2>Willkommen bei Webdesign Karateke</h2>
                    <p></p>
                </div>
            </div>
        </section>
    </div>
    <footer class="text-center mt-4">
        &copy; 2023 Webdesign Karateke - Alle Rechte vorbehalten.
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>