<?php
session_start();
require_once 'config/database.php';
require_once 'config/helpers.php';

// Generate CSRF Token globally
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle Global Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    redirect('index.php?page=login');
}

// Route POST Actions (Form Submissions)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'controllers/PostController.php';
    $postController = new PostController($pdo);
    $postController->handle($_POST, $_FILES);
}

// Route GET Views (Page Rendering)
$page = $_GET['page'] ?? 'home';
require_once 'controllers/ViewController.php';
$viewController = new ViewController($pdo);
$viewController->render($page);