<?php
class PostController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handle($post, $files = []) {
        // AI tool posts are handled directly inside their respective views
        $ignoreCsrfFor = ['calculate_timeline', 'calculate_cost', 'search_materials', 'calculate_risk', 'calculate_reg'];
        foreach ($ignoreCsrfFor as $key) {
            if (isset($post[$key])) return; 
        }

        if (!isset($post['csrf_token'])) return;
        verifyCsrfToken($post['csrf_token']);

        if (isset($post['register'])) $this->register($post);
        elseif (isset($post['login'])) $this->login($post);
        elseif (isset($post['fund_escrow'])) $this->fundEscrow($post);
        elseif (isset($post['create_project'])) $this->createProject($post, $files);
        elseif (isset($post['request_professional'])) $this->requestProfessional($post);
        elseif (isset($post['update_prof_project_status'])) $this->updateProfStatus($post);
        elseif (isset($post['update_admin_prof_status'])) $this->updateAdminProfStatus($post);
        elseif (isset($post['update_milestone'])) $this->updateMilestone($post);
        elseif (isset($post['post_workspace'])) $this->postWorkspace($post, $files);
        elseif (isset($post['submit_review'])) $this->submitReview($post);
    }

    private function register($post) {
        $name = trim($post["name"]); $email = trim($post["email"]); $password = $post["password"];
        $phone = trim($post["phone"]); $role = $post["role"]; $professionalType = $post["professional_type"] ?? "";
        $experience = (int)($post["experience_years"] ?? 0); $location = trim($post["location"] ?? "");
        $qualification = trim($post["qualification"] ?? ""); $description = trim($post["description"] ?? "");

        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION["error"] = "Please fill in all required fields.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["error"] = "Invalid email format.";
        } elseif (strlen($password) < 8) {
            $_SESSION["error"] = "Security: Password must be at least 8 characters.";
        } else {
            $check = $this->pdo->prepare("SELECT id FROM users WHERE email = ?"); $check->execute([$email]);
            if ($check->fetch()) {
                $_SESSION["error"] = "Email already in use.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, phone, role, professional_type, experience_years, location, qualification, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $email, $hashedPassword, $phone, $role, $professionalType, $experience, $location, $qualification, $description]);
                $_SESSION["success"] = "Welcome aboard! Please log in.";
                redirect("index.php?page=login");
            }
        }
    }

    private function login($post) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([trim($post["email"])]);
        $user = $stmt->fetch();
        if ($user && password_verify($post["password"], $user["password"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["id"]; $_SESSION["user_name"] = $user["name"]; $_SESSION["role"] = $user["role"];
            $_SESSION["success"] = "Logged in successfully!";
            redirect("index.php?page=dashboard");
        } else {
            $_SESSION["error"] = "Invalid credentials.";
            redirect("index.php?page=login");
        }
    }

    private function fundEscrow($post) {
        requireLogin();
        $this->pdo->prepare("UPDATE milestones SET payment_status = 'funded' WHERE id = ?")->execute([(int)$post['milestone_id']]);
        $_SESSION['success'] = "Escrow successfully funded! Funds are securely locked.";
        redirect("index.php?page=milestones&id=" . (int)$post['project_id']);
    }

    private function createProject($post, $files) {
        requireLogin();
        if ($_SESSION["role"] !== "client") { $_SESSION["error"] = "Access denied."; redirect("index.php?page=dashboard"); }
        
        $budget = (float)$post["budget"]; $landImage = "";
        if (isset($files["land_image"]) && $files["land_image"]["error"] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/../uploads/land/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $tmpPath = $files["land_image"]["tmp_name"];
            $extension = pathinfo($files["land_image"]["name"], PATHINFO_EXTENSION);
            $fileName = bin2hex(random_bytes(10)) . '_' . time() . '.' . $extension; 
            move_uploaded_file($tmpPath, $uploadDir . $fileName);
            $landImage = "uploads/land/" . $fileName;
        }

        if (empty(trim($post["title"]))) {
            $_SESSION["error"] = "Project title is required.";
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO projects (user_id, title, description, project_type, location, budget, land_area, number_of_floors, construction_type, land_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION["user_id"], trim($post["title"]), trim($post["description"]), trim($post["project_type"]), trim($post["location"]), $budget, (float)$post["land_area"], (int)$post["number_of_floors"], trim($post["construction_type"]), $landImage]);
            $projectId = $this->pdo->lastInsertId();

            $milestones = [
                ["Phase 1: Planning & Blueprinting", "AI-assisted project blueprinting & planning", $budget * 0.10],
                ["Phase 2: Foundation & Base", "Site clearance & foundation laying", $budget * 0.20],
                ["Phase 3: Core Structure", "Main structural frameworks and walls", $budget * 0.40],
                ["Phase 4: Exterior & Interior Finishing", "MEP, plastering, and finishing", $budget * 0.30]
            ];
            foreach ($milestones as $m) {
                $this->pdo->prepare("INSERT INTO milestones (project_id, title, description, amount) VALUES (?, ?, ?, ?)")->execute([$projectId, $m[0], $m[1], $m[2]]);
            }
            $_SESSION["success"] = "Project securely created.";
            redirect("index.php?page=project-details&id=" . $projectId);
        }
    }

    private function requestProfessional($post) {
        requireLogin();
        $projectId = (int)$post["project_id"]; $professionalId = (int)$post["professional_id"];
        $check = $this->pdo->prepare("SELECT id FROM projects WHERE id = ? AND user_id = ?"); $check->execute([$projectId, $_SESSION["user_id"]]);
        if (!$check->fetch()) {
            $_SESSION["error"] = "Unauthorized action.";
        } else {
            $checkReq = $this->pdo->prepare("SELECT id FROM project_professionals WHERE project_id = ? AND professional_id = ?"); $checkReq->execute([$projectId, $professionalId]);
            if ($checkReq->fetch()) { $_SESSION["error"] = "Request already pending."; } 
            else {
                $this->pdo->prepare("INSERT INTO project_professionals (project_id, professional_id, status) VALUES (?, ?, 'requested')")->execute([$projectId, $professionalId]);
                $_SESSION["success"] = "Professional requested successfully.";
            }
        }
        redirect("index.php?page=professionals&project_id=" . $projectId);
    }

    private function updateProfStatus($post) {
        requireLogin();
        if ($_SESSION['role'] === 'professional') {
            $this->pdo->prepare("UPDATE project_professionals SET status = ? WHERE id = ? AND professional_id = ?")->execute([$post["update_prof_project_status"], (int)$post["request_id"], $_SESSION['user_id']]);
            $_SESSION["success"] = "Project request status updated.";
        }
        redirect("index.php?page=professional-projects");
    }

    private function updateAdminProfStatus($post) {
        requireLogin();
        if ($_SESSION['role'] === 'admin') {
            $this->pdo->prepare("UPDATE users SET verification_status = ? WHERE id = ?")->execute([$post["update_admin_prof_status"], (int)$post["prof_id"]]);
            $_SESSION["success"] = "Professional profile status updated.";
        }
        redirect("index.php?page=admin-professionals");
    }

    private function updateMilestone($post) {
        requireLogin();
        $milestoneId = (int)$post["milestone_id"]; $status = $post["status"];
        if (in_array($status, ["pending", "in_progress", "completed"])) {
            if ($status === 'completed') {
                $check = $this->pdo->prepare("SELECT payment_status FROM milestones WHERE id = ?"); $check->execute([$milestoneId]);
                if ($check->fetchColumn() === 'funded') {
                    $this->pdo->prepare("UPDATE milestones SET status = ?, payment_status = 'released' WHERE id = ?")->execute([$status, $milestoneId]);
                    $_SESSION["success"] = "Milestone completed! Escrow funds released.";
                } else {
                    $this->pdo->prepare("UPDATE milestones SET status = ? WHERE id = ?")->execute([$status, $milestoneId]);
                    $_SESSION["success"] = "Milestone timeline updated.";
                }
            } else {
                $this->pdo->prepare("UPDATE milestones SET status = ? WHERE id = ?")->execute([$status, $milestoneId]);
                $_SESSION["success"] = "Milestone timeline updated.";
            }
        }
        redirect("index.php?page=milestones&id=" . $_POST["project_id"]);
    }

    private function postWorkspace($post, $files) {
        requireLogin();
        $projectId = (int)$post["project_id"]; $message = trim($post["message"]); $fileUrl = null; $fileType = null;
        if (isset($files["workspace_file"]) && $files["workspace_file"]["error"] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/../uploads/workspace/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $tmpPath = $files["workspace_file"]["tmp_name"];
            $extension = pathinfo($files["workspace_file"]["name"], PATHINFO_EXTENSION);
            $fileName = bin2hex(random_bytes(10)) . '_' . time() . '.' . $extension; 
            move_uploaded_file($tmpPath, $uploadDir . $fileName);
            $fileUrl = "uploads/workspace/" . $fileName; $fileType = $extension;
        }
        if (!empty($message) || $fileUrl) {
            $this->pdo->prepare("INSERT INTO project_workspace (project_id, user_id, message, file_url, file_type) VALUES (?, ?, ?, ?, ?)")->execute([$projectId, $_SESSION['user_id'], $message, $fileUrl, $fileType]);
            $_SESSION['success'] = "Workspace updated.";
        }
        redirect("index.php?page=workspace&id=" . $projectId);
    }

    private function submitReview($post) {
        requireLogin();
        if ($_SESSION["role"] !== "client") { $_SESSION["error"] = "Only clients can leave reviews."; redirect("index.php?page=dashboard"); }
        $projectId = (int)$post["project_id"]; $professionalId = (int)$post["professional_id"]; $rating = (int)$post["rating"];
        if ($rating >= 1 && $rating <= 5) {
            $check = $this->pdo->prepare("SELECT id FROM reviews WHERE project_id = ? AND professional_id = ?"); $check->execute([$projectId, $professionalId]);
            if ($check->fetch()) { $_SESSION["error"] = "You have already reviewed this professional for this project."; } 
            else {
                $this->pdo->prepare("INSERT INTO reviews (project_id, client_id, professional_id, rating, review_text) VALUES (?, ?, ?, ?, ?)")->execute([$projectId, $_SESSION['user_id'], $professionalId, $rating, trim($post["review_text"])]);
                $this->pdo->prepare("UPDATE users SET rating = (SELECT AVG(rating) FROM reviews WHERE professional_id = ?) WHERE id = ?")->execute([$professionalId, $professionalId]);
                $_SESSION["success"] = "Review submitted successfully!";
            }
        }
        redirect("index.php?page=project-details&id=" . $projectId);
    }
}