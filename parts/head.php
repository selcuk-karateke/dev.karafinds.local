<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $meta_description; ?>">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php if (isset($additional_head_content)) echo $additional_head_content; ?>
</head>