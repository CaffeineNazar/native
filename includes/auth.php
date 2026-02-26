<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// kalau belum login, lempar ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
