<?php
function e($value) { return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8"); }
function redirect($url) { header("Location: " . $url); exit; }
function isLoggedIn() { return isset($_SESSION["user_id"]); }
function requireLogin() { if (!isLoggedIn()) { $_SESSION['error'] = "Please login first."; redirect("index.php?page=login"); } }
function currentUser() {
    global $pdo;
    if (!isset($_SESSION["user_id"])) return null;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION["user_id"]]);
    return $stmt->fetch();
}
function verifyCsrfToken($token) {
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        die("<div style='display:flex;justify-content:center;align-items:center;height:100vh;background:#0f172a;color:#ef4444;font-family:sans-serif;'><h2>Security Alert: Invalid CSRF Token.</h2></div>");
    }
}