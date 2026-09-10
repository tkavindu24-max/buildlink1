<?php
$host = "localhost";
$dbname = "buildlink";
$dbuser = "root";
$dbpass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // AUTO-CREATE TABLES
    $pdo->exec("CREATE TABLE IF NOT EXISTS users ( id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, email VARCHAR(191) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, phone VARCHAR(50), role VARCHAR(50) NOT NULL, professional_type VARCHAR(100), experience_years INT DEFAULT 0, location VARCHAR(255), qualification VARCHAR(255), description TEXT, rating FLOAT, verification_status VARCHAR(50) DEFAULT 'pending', is_premium TINYINT(1) DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP )");
    try { $pdo->exec("ALTER TABLE users ADD COLUMN is_premium TINYINT(1) DEFAULT 0"); } catch (PDOException $e) {}
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS projects ( id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT, project_type VARCHAR(100), location VARCHAR(255), budget DECIMAL(15,2) DEFAULT 0.00, land_area DECIMAL(10,2) DEFAULT 0.00, number_of_floors INT DEFAULT 1, construction_type VARCHAR(100), land_image VARCHAR(255), status VARCHAR(50) DEFAULT 'active', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS milestones ( id INT AUTO_INCREMENT PRIMARY KEY, project_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT, amount DECIMAL(15,2) DEFAULT 0.00, status VARCHAR(50) DEFAULT 'pending', payment_status VARCHAR(50) DEFAULT 'unfunded', FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE )");
    try { $pdo->exec("ALTER TABLE milestones ADD COLUMN payment_status VARCHAR(50) DEFAULT 'unfunded'"); } catch (PDOException $e) {}

    $pdo->exec("CREATE TABLE IF NOT EXISTS project_professionals ( id INT AUTO_INCREMENT PRIMARY KEY, project_id INT NOT NULL, professional_id INT NOT NULL, status VARCHAR(50) DEFAULT 'requested', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE, FOREIGN KEY (professional_id) REFERENCES users(id) ON DELETE CASCADE )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS project_workspace ( id INT AUTO_INCREMENT PRIMARY KEY, project_id INT NOT NULL, user_id INT NOT NULL, message TEXT, file_url VARCHAR(255), file_type VARCHAR(50), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE, FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS reviews ( id INT AUTO_INCREMENT PRIMARY KEY, project_id INT NOT NULL, client_id INT NOT NULL, professional_id INT NOT NULL, rating INT NOT NULL, review_text TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE, FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE, FOREIGN KEY (professional_id) REFERENCES users(id) ON DELETE CASCADE )");

} catch (PDOException $e) {
    die("<div style='font-family:sans-serif; text-align:center; padding:50px;'><h2>Database Error</h2><p>" . htmlspecialchars($e->getMessage()) . "</p></div>");
}