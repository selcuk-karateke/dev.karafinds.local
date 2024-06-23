<?php
ini_set('max_execution_time', 0);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 1);
ini_set("log_errors", 1); // Enable error logging
error_reporting(-1);
// ini_set("error_log", "/tmp/php-error.log"); // set error path

require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION["logged"]) && !isset($_SESSION["username"])) {
} elseif ((isset($_SESSION["logged"]) && $_SESSION["logged"] == "1" && isset($_SESSION["username"]))) {
    // require_once 'database.php';
}

// Check if a session is not active before starting one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST["login"])) {
    login();
}

if (isset($_POST["logout"])) {
    logout();
}

// Bestimmen, welcher Inhalt basierend auf dem Benutzerstatus angezeigt wird.
$userLogged = isset($_SESSION['logged']) && $_SESSION['logged'] === '1' && isset($_SESSION['username']);
