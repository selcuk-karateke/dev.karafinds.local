<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $meta_description; ?>">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">

    <?php if (isset($additional_head_content_1)) echo $additional_head_content_1; ?>
    <?php if (isset($additional_head_content_2)) echo $additional_head_content_2; ?>
    <script>
        // Sofortiges Setzen des Dark Mode basierend auf localStorage oder Systemeinstellung
        (function() {})();
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-XXXXX-Y"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-XXXXX-Y');
    </script>
</head>