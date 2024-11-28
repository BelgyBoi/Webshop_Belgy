<?php
// auth.php
session_start();

function isAdmin() {
    return isset($_SESSION['admin']) && $_SESSION['admin'] === 1;
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: login.php');
        exit();
    }
}
