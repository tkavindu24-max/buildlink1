<?php
class ViewController {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function render($page) {
        global $pdo; // Make PDO available to views
        $user = currentUser();
        
        $validPages = ['home', 'register', 'login', 'dashboard', 'create-project', 'professionals', 'project-details', 'workspace', 'milestones', 'timeline-predictor', 'cost-predictor', 'materials', 'risk-score', 'regulation-checker', 'profile', 'professional-projects', 'admin-users', 'admin-professionals', 'admin-projects'];
        
        if (!in_array($page, $validPages)) {
            $page = '404';
        }

        include __DIR__ . '/../views/layouts/header.php';
        include __DIR__ . '/../views/pages/' . $page . '.php';
        include __DIR__ . '/../views/layouts/footer.php';
    }
}