<?php requireLogin(); ?>
<section class="dashboard section">
    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 40px; flex-wrap:wrap; gap:16px;">
            <div>
                <h1 style="font-size:32px; margin-bottom: 4px;">Welcome back, <?= e($user["name"]) ?></h1>
                <p style="color:var(--text-muted); font-size:15px; font-weight:500; text-transform:uppercase; letter-spacing:0.05em;"><?= e($user["role"]) ?> Account</p>
            </div>
            <span class="badge" style="background:var(--good-soft); color:var(--good); padding: 6px 14px;">● All systems operational</span>
        </div>

        <?php if ($user["role"] === "client"): ?>
            <div class="grid" style="margin-bottom: 40px;">
                <div class="card" style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
                    <h3 style="font-size:48px; color:var(--primary); margin:0; line-height:1; font-family:'Space Grotesk', sans-serif;">
                        <?= number_format((float)($pdo->query("SELECT COUNT(*) FROM projects WHERE user_id = {$_SESSION['user_id']}")->fetchColumn() ?? 0)) ?>
                    </h3>
                    <p style="font-weight:600; margin-top:12px; color:var(--text-main);">Active Projects</p>
                </div>
                <div class="card" style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
                    <h3 style="font-size:48px; color:var(--info); margin:0; line-height:1; font-family:'Space Grotesk', sans-serif;">
                        <?= number_format((float)($pdo->query("SELECT COUNT(*) FROM project_professionals pp JOIN projects p ON pp.project_id = p.id WHERE p.user_id = {$_SESSION['user_id']}")->fetchColumn() ?? 0)) ?>
                    </h3>
                    <p style="font-weight:600; margin-top:12px; color:var(--text-main);">Professionals Contacted</p>
                </div>
            </div>

            <div class="grid">
                <div class="card"><div class="icon">🏗️</div><h3>Start a project</h3><p>Describe your build and let BuildLink set up your workspace.</p><br><a href="index.php?page=create-project" class="btn btn-outline" style="width:100%;">New project</a></div>
                <div class="card"><div class="icon blue">👷</div><h3>Find professionals</h3><p>Search verified architects, civil and structural engineers.</p><br><a href="index.php?page=professionals" class="btn btn-outline" style="width:100%;">Browse network</a></div>
                <div class="card"><div class="icon emerald">🛒</div><h3>Material Prices</h3><p>Compare local supplier prices and AI recommendations.</p><br><a href="index.php?page=materials" class="btn btn-outline" style="width:100%;">Check Prices</a></div>
                <div class="card"><div class="icon purple">⏳</div><h3>Timeline predictor</h3><p>Get a realistic estimate of your completion date.</p><br><a href="index.php?page=timeline-predictor" class="btn btn-outline" style="width:100%;">Predict timeline</a></div>
                <div class="card"><div class="icon">🤖</div><h3>Cost predictor</h3><p>Estimate your total build cost from a few inputs.</p><br><a href="index.php?page=cost-predictor" class="btn btn-outline" style="width:100%;">Estimate cost</a></div>
                <div class="card"><div class="icon rose">⚠️</div><h3>Site risk analytics</h3><p>Screen your plot for slope, soil and flood risk.</p><br><a href="index.php?page=risk-score" class="btn btn-outline" style="width:100%;">Assess risk</a></div>
            </div>

            <h2 style="margin: 60px 0 24px; font-size:24px;">Recent Professional Responses</h2>
            <?php 
            $myReqs = $pdo->prepare("SELECT pp.status, p.title as project_title, u.name as prof_name, u.professional_type FROM project_professionals pp JOIN projects p ON pp.project_id = p.id JOIN users u ON pp.professional_id = u.id WHERE p.user_id = ? ORDER BY pp.created_at DESC LIMIT 5");
            $myReqs->execute([$_SESSION['user_id']]);
            $clientReqs = $myReqs->fetchAll();
            if($clientReqs): ?>
            <div class="table-container" style="margin-bottom: 60px;">
                <table>
                    <tr><th>Project</th><th>Professional</th><th>Discipline</th><th>Response Status</th></tr>
                    <?php foreach($clientReqs as $r): 
                        if ($r['status'] === 'accepted' || $r['status'] === 'verified') { $sc = 'var(--good)'; $bg = 'var(--good-soft)'; $displayStatus = 'ACCEPTED'; } 
                        elseif ($r['status'] === 'rejected') { $sc = 'var(--bad)'; $bg = 'var(--bad-soft)'; $displayStatus = 'REJECTED'; } 
                        else { $sc = 'var(--warn)'; $bg = 'var(--warn-soft)'; $displayStatus = 'PENDING'; }
                    ?>
                    <tr>
                        <td style="font-weight:600; color:var(--text-main);"><?= e($r['project_title']) ?></td>
                        <td><?= e($r['prof_name']) ?></td>
                        <td><?= e($r['professional_type']) ?></td>
                        <td><span class="badge" style="color:<?= $sc ?>; background:<?= $bg ?>;"><?= $displayStatus ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <?php else: ?>
                <div class="card" style="text-align:center; padding:40px; margin-bottom: 60px; background:transparent; border:1px dashed var(--border); box-shadow:none;">
                    <p style="color:var(--text-muted);">You haven't requested any professionals yet.</p>
                </div>
            <?php endif; ?>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 24px;">
                <h2 style="font-size:24px; margin:0;">Your Projects</h2>
                <a href="index.php?page=create-project" class="btn" style="padding: 8px 16px; font-size:13px;">+ New Project</a>
            </div>
            
            <?php $projects = $pdo->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC"); $projects->execute([$_SESSION["user_id"]]); $projs = $projects->fetchAll(); ?>
            <?php if ($projs): ?>
                <div class="grid">
                    <?php foreach ($projs as $p): ?>
                        <div class="card" style="border-top: 4px solid var(--primary);">
                            <h3 style="font-size:20px;"><?= e($p["title"]) ?></h3>
                            <p style="color:var(--text-muted); font-size:13.5px; margin-bottom:16px; font-weight:500; display:flex; gap:8px; align-items:center;">
                                <span class="badge" style="background:var(--bg-color); border:1px solid var(--border); color:var(--text-main); font-weight:500;"><?= e($p["project_type"]) ?></span> 
                                <span>📍 <?= e($p["location"]) ?></span>
                            </p>
                            <h2 style="color:var(--text-main); margin-bottom:24px; font-size:28px; font-family:'Space Grotesk', sans-serif;">Rs. <?= number_format((float)($p["budget"] ?? 0), 2) ?></h2>
                            <a href="index.php?page=project-details&id=<?= $p["id"] ?>" class="btn btn-outline" style="width:100%">Manage Project & Workspace</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card" style="text-align:center; padding:60px;"><h3 style="color:var(--text-main); font-weight:600; margin-bottom:16px;">You haven't started a project yet.</h3><a href="index.php?page=create-project" class="btn">Start your first project</a></div>
            <?php endif; ?>

        <?php elseif ($user["role"] === "professional"): ?>

            <div class="grid" style="margin-bottom: 40px;">
                <div class="card" style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
                    <h3 style="font-size:48px; color:var(--info); margin:0; line-height:1; font-family:'Space Grotesk', sans-serif;">
                    <?= number_format((float)($pdo->query("SELECT COUNT(*) FROM project_professionals WHERE professional_id = {$_SESSION['user_id']} AND status != 'rejected'")->fetchColumn() ?? 0)) ?></h3>
                    <p style="font-weight:600; margin-top:12px; color:var(--text-main);">Project Requests</p>
                </div>
                <div class="card" style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
                    <h3 style="font-size:48px; color:var(--good); margin:0; line-height:1; font-family:'Space Grotesk', sans-serif;">
                    ⭐ <?= number_format((float)($user["rating"] ?? 0), 1) ?></h3>
                    <p style="font-weight:600; margin-top:12px; color:var(--text-main);">Client Rating</p>
                </div>
            </div>
            
            <div class="grid">
                <div class="card"><div class="icon">👤</div><h3>My profile</h3><p>Manage how clients see your professional profile.</p><br><a href="index.php?page=profile" class="btn btn-outline" style="width:100%;">View profile</a></div>
                <div class="card"><div class="icon blue">🏗️</div><h3>Project requests</h3><p>Accept/Reject client requests and access files.</p><br><a href="index.php?page=professional-projects" class="btn btn-blue" style="width:100%;">Manage Workspaces</a></div>
                <div class="card"><div class="icon emerald">📋</div><h3>Regulation check</h3><p>Verify zoning rules for a client's proposal.</p><br><a href="index.php?page=regulation-checker" class="btn btn-outline" style="width:100%;">Check rules</a></div>
                <div class="card"><div class="icon purple">⏳</div><h3>Timeline predictor</h3><p>Forecast a build's completion date.</p><br><a href="index.php?page=timeline-predictor" class="btn btn-outline" style="width:100%;">Predict timeline</a></div>
            </div>

        <?php elseif ($user["role"] === "admin"): ?>
            <div class="grid" style="margin-bottom: 40px;">
                <div class="card" style="text-align:center;"><h3 style="font-size:48px; color:var(--text-main); margin:0; font-family:'Space Grotesk', sans-serif;"><?= number_format((float)($pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?? 0)) ?></h3><p style="font-weight:600; margin-top:12px;">Total Users</p></div>
                <div class="card" style="text-align:center;"><h3 style="font-size:48px; color:var(--info); margin:0; font-family:'Space Grotesk', sans-serif;"><?= number_format((float)($pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn() ?? 0)) ?></h3><p style="font-weight:600; margin-top:12px;">Total Projects</p></div>
                <div class="card" style="text-align:center;"><h3 style="font-size:48px; color:var(--warn); margin:0; font-family:'Space Grotesk', sans-serif;"><?= number_format((float)($pdo->query("SELECT COUNT(*) FROM project_professionals WHERE status='requested'")->fetchColumn() ?? 0)) ?></h3><p style="font-weight:600; margin-top:12px;">Pending Matchings</p></div>
            </div>
            
            <div class="grid">
                <div class="card"><div class="icon">👥</div><h3>Manage users</h3><br><a href="index.php?page=admin-users" class="btn btn-outline" style="width:100%;">View directory</a></div>
                <div class="card"><div class="icon blue">👷</div><h3>Verify professionals</h3><br><a href="index.php?page=admin-professionals" class="btn btn-blue" style="width:100%;">Review credentials</a></div>
                <div class="card"><div class="icon emerald">🏗️</div><h3>All projects</h3><br><a href="index.php?page=admin-projects" class="btn btn-outline" style="width:100%;">Monitor projects</a></div>
            </div>
        <?php endif; ?>
    </div>
</section>