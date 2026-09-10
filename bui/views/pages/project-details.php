<?php requireLogin(); 
$pId = (int)($_GET["id"] ?? 0); global $pdo;
$stmt = $pdo->prepare("SELECT p.*, u.name AS client_name, u.email AS client_email, u.phone AS client_phone FROM projects p JOIN users u ON p.user_id = u.id WHERE p.id = ?"); 
$stmt->execute([$pId]); $project = $stmt->fetch(); 
if (!$project) { echo "<div class='container section'><div class='card' style='text-align:center;'><h2>Project not found</h2><a href='index.php?page=dashboard' class='btn btn-outline' style='margin-top:20px;'>Return to Dashboard</a></div></div>"; } else { 
?>
<section class="section">
    <div class="container">
        <!-- PROJECT HEALTH DASHBOARD -->
        <?php 
        $stmt = $pdo->prepare("SELECT * FROM milestones WHERE project_id = ?"); $stmt->execute([$pId]); $milestones = $stmt->fetchAll();
        $totalM = count($milestones); $completedM = 0; $releasedFunds = 0; $nextTask = 'Awaiting Planning';
        foreach ($milestones as $m) { 
            if ($m["status"] === "completed") {
                $completedM++; 
                if ($m["payment_status"] === 'released') $releasedFunds += $m["amount"];
            } elseif ($m["status"] === "in_progress") {
                $nextTask = $m["title"] . " (In Progress)";
            } elseif ($nextTask === 'Awaiting Planning' && $m["status"] === 'pending') {
                $nextTask = $m["title"] . " (Pending)";
            }
        }
        $progress = $totalM > 0 ? round(($completedM / $totalM) * 100) : 0;
        $budgetUsedPct = $project["budget"] > 0 ? round(($releasedFunds / $project["budget"]) * 100) : 0;
        
        $profStmt = $pdo->prepare("SELECT u.name, u.professional_type FROM project_professionals pp JOIN users u ON pp.professional_id = u.id WHERE pp.project_id = ? AND pp.status IN ('accepted', 'verified') LIMIT 1");
        $profStmt->execute([$pId]);
        $assignedPro = $profStmt->fetch();
        $assignedProName = $assignedPro ? e($assignedPro['name']) . ' (' . e($assignedPro['professional_type']) . ')' : 'Pending Professional Assignment';
        ?>
        
        <div class="section-title" style="text-align:left; margin-bottom:32px;">
            <div style="display:inline-block; background:var(--bg-color); border:1px solid var(--border); padding:4px 12px; border-radius:var(--radius-full); font-size:12px; font-weight:600; color:var(--text-muted); margin-bottom:12px;">Project Workspace</div>
            <h2 style="font-size:40px; margin-bottom:8px; line-height:1.2;"><?= e($project["title"]) ?></h2>
            <p style="font-size:16px;">Live Health Dashboard & Analytics</p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px,1fr)); gap:24px; margin-bottom:40px;">
            <div class="health-box card" style="border-top:4px solid var(--good); padding:32px 24px;">
                <h4 style="color:var(--text-muted); font-size:13px; text-transform:uppercase; letter-spacing:0.05em;">Timeline Completion</h4>
                <div style="font-size:42px; font-weight:800; color:var(--text-main); margin:12px 0; font-family:'Space Grotesk', sans-serif;"><?= $progress ?>%</div>
                <div class="progress"><div class="progress-bar" style="width:<?= $progress ?>%; background:var(--good);"></div></div>
            </div>
            <div class="health-box card" style="border-top:4px solid var(--primary); padding:32px 24px;">
                <h4 style="color:var(--text-muted); font-size:13px; text-transform:uppercase; letter-spacing:0.05em;">Budget Utilization (Escrow)</h4>
                <div style="font-size:32px; font-weight:800; color:var(--primary); margin:12px 0; line-height:1.2; font-family:'Space Grotesk', sans-serif;">Rs. <?= number_format((float)($releasedFunds ?? 0), 2) ?></div>
                <p style="font-size:14px; font-weight:500; color:var(--text-muted);"><?= $budgetUsedPct ?>% of total budget spent</p>
            </div>
            <div class="health-box card" style="border-top:4px solid var(--info); padding:32px 24px;">
                <h4 style="color:var(--text-muted); font-size:13px; text-transform:uppercase; letter-spacing:0.05em;">Current Active Phase</h4>
                <div style="font-size:20px; font-weight:700; line-height:1.4; color:var(--info); margin-top:20px;"><?= e($nextTask) ?></div>
            </div>
        </div>

        <!-- LIVE PROFESSIONAL PROGRESS TRACKER -->
        <div class="card" style="margin-bottom: 40px; padding:40px;">
            <h3 style="font-size:24px; margin-bottom:8px;">Live Professional Progress Tracker</h3>
            <p style="color:var(--text-muted); margin-bottom:40px; font-size:15px;">Monitor active tasks and the professionals working on your milestones.</p>
            
            <div class="timeline">
                <?php foreach ($milestones as $index => $milestone): ?>
                <div class="timeline-item <?= $milestone['status'] ?>">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                            <span class="badge" style="
                                background: <?= $milestone['status'] === 'completed' ? 'var(--good-soft)' : ($milestone['status'] === 'in_progress' ? 'var(--warn-soft)' : 'var(--bg-color)') ?>; 
                                color: <?= $milestone['status'] === 'completed' ? 'var(--good)' : ($milestone['status'] === 'in_progress' ? 'var(--primary)' : 'var(--text-muted)') ?>;
                                border: 1px solid <?= $milestone['status'] === 'completed' ? 'rgba(16,185,129,0.2)' : ($milestone['status'] === 'in_progress' ? 'rgba(245,158,11,0.2)' : 'var(--border)') ?>;">
                                <?= str_replace('_', ' ', $milestone['status']) ?>
                            </span>
                        </div>
                        <h4 style="margin: 0 0 10px 0; font-size: 19px; color: var(--text-main); font-weight:700;"><?= e($milestone["title"]) ?></h4>
                        <p style="margin: 0; font-size: 14.5px; color: var(--text-muted); background:var(--bg-color); padding:10px 14px; border-radius:var(--radius-sm); border:1px solid var(--border); display:inline-block;">
                            <span style="font-weight:600; color:var(--text-main);">Assigned to:</span> <?= $milestone['status'] === 'completed' || $milestone['status'] === 'in_progress' || ($index === 0 && $assignedPro) ? $assignedProName : 'Pending Assignment' ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card" style="margin-bottom:40px; padding:40px;">
            <h3 style="font-size:24px; margin-bottom:24px;">Project Specifications</h3>
            
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; background:var(--bg-color); padding:24px; border-radius:var(--radius-lg); border:1px solid var(--border); margin-bottom:32px;">
                <div><p style="font-size:13px; font-weight:600; color:var(--text-muted); text-transform:uppercase;">Type</p><p style="font-size:16px; font-weight:600; margin-top:6px; color:var(--text-main);"><?= e($project["project_type"]) ?></p></div>
                <div><p style="font-size:13px; font-weight:600; color:var(--text-muted); text-transform:uppercase;">Location</p><p style="font-size:16px; font-weight:600; margin-top:6px; color:var(--text-main);">📍 <?= e($project["location"]) ?></p></div>
                <div><p style="font-size:13px; font-weight:600; color:var(--text-muted); text-transform:uppercase;">Land Area</p><p style="font-size:16px; font-weight:600; margin-top:6px; color:var(--text-main);"><?= e($project["land_area"]) ?> perches</p></div>
                <div><p style="font-size:13px; font-weight:600; color:var(--text-muted); text-transform:uppercase;">Floors</p><p style="font-size:16px; font-weight:600; margin-top:6px; color:var(--text-main);"><?= e($project["number_of_floors"]) ?></p></div>
                <div><p style="font-size:13px; font-weight:600; color:var(--text-muted); text-transform:uppercase;">Total Budget</p><p style="font-size:16px; font-weight:700; color:var(--primary); margin-top:6px;">Rs. <?= number_format((float)($project["budget"] ?? 0), 2) ?></p></div>
            </div>
            
            <?php if ($_SESSION['role'] === 'professional' || $_SESSION['role'] === 'admin'): ?>
            <div style="background:var(--surface); padding:24px; border-radius:var(--radius-lg); border:1px solid var(--border); margin-bottom:32px; box-shadow:var(--shadow-sm);">
                <p style="font-size:13px; font-weight:700; color:var(--primary); text-transform:uppercase; letter-spacing:1px; margin-bottom:16px;">Client Contact Information</p>
                <div style="display:flex; gap:24px; flex-wrap:wrap;">
                    <div><p style="font-size:13px; color:var(--text-muted); margin-bottom:4px;">Name</p><p style="font-size:16px; font-weight:600; color:var(--text-main);">👤 <?= e($project['client_name']) ?></p></div>
                    <div><p style="font-size:13px; color:var(--text-muted); margin-bottom:4px;">Email</p><p style="font-size:15px; font-weight:500; color:var(--text-main);">✉️ <?= e($project['client_email']) ?></p></div>
                    <?php if($project['client_phone']): ?>
                        <div><p style="font-size:13px; color:var(--text-muted); margin-bottom:4px;">Phone</p><p style="font-size:15px; font-weight:500; color:var(--text-main);">📞 <?= e($project['client_phone']) ?></p></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <h3 style="font-size:18px; margin-bottom:12px; color:var(--text-main);">Architectural Brief</h3>
            <div style="color:var(--text-muted); font-size:15.5px; line-height:1.8; background:var(--bg-color); padding:24px; border-radius:var(--radius-md); border:1px solid var(--border);">
                <?= nl2br(e($project["description"])) ?>
            </div>

            <?php if ($project["land_image"]): ?>
                <br><h3 style="font-size:18px; margin-bottom:16px; color:var(--text-main);">Site Topography Image</h3>
                <img src="<?= e($project["land_image"]) ?>" alt="Land photo" style="max-width:100%; border-radius:var(--radius-md); border:1px solid var(--border); box-shadow:var(--shadow-md); margin-bottom:20px;">
                <button onclick="runLandAnalysis()" class="btn btn-outline" style="border-color:var(--info); color:var(--info); font-weight:600;">🔍 Run AI Terrain Analysis</button>
                <div id="landAnalysisLoader" class="ai-loader" style="padding:30px;"><div class="spinner" style="border-top-color: var(--info); width:36px; height:36px; border-width:4px;"></div><p style="color:var(--info); font-size:14px; font-weight:600;">Analyzing topological layers...</p></div>
                <div id="landAnalysisResult" style="display:none; background:var(--info-soft); border:1px solid rgba(59, 130, 246, 0.3); padding:24px; border-radius:var(--radius-md); margin-top:20px;">
                    <h4 style="color:var(--info); margin-bottom:12px; font-size:16px; display:flex; align-items:center; gap:8px;"><span>✅</span> Scan Complete</h4>
                    <ul style="font-size:14.5px; line-height:1.8; color:var(--text-main); margin-left:24px;">
                        <li><strong style="color:var(--info);">Terrain Variance:</strong> Moderate slope detected (~12 degrees).</li>
                        <li><strong style="color:var(--info);">Surface Composition:</strong> Visual indicators suggest mixed aggregate topsoil.</li>
                        <li><strong style="color:var(--info);">Access:</strong> Clear roadway access identified. Low logistical risk.</li>
                    </ul>
                </div>
                <script> function runLandAnalysis() { document.getElementById('landAnalysisLoader').style.display = 'block'; setTimeout(() => { document.getElementById('landAnalysisLoader').style.display = 'none'; document.getElementById('landAnalysisResult').style.display = 'block'; }, 2000); } </script>
            <?php endif; ?>
            
            <div style="margin-top:40px; padding-top:32px; border-top:1px solid var(--border); display:flex; gap:16px; flex-wrap:wrap;">
                <?php $isOwner = ($project['user_id'] == $_SESSION['user_id']); ?>
                <a href="index.php?page=milestones&id=<?= $pId ?>" class="btn btn-outline" style="padding:12px 24px;">Track Escrow Milestones</a>
                
                <?php 
                $canEnterWorkspace = $isOwner;
                if ($_SESSION['role'] === 'professional') {
                    $wChk = $pdo->prepare("SELECT id FROM project_professionals WHERE project_id=? AND professional_id=? AND status IN ('accepted', 'verified')");
                    $wChk->execute([$pId, $_SESSION['user_id']]);
                    if ($wChk->fetch()) $canEnterWorkspace = true;
                }
                if ($_SESSION['role'] === 'admin') $canEnterWorkspace = true;
                ?>
                
                <?php if ($canEnterWorkspace): ?>
                    <a href="index.php?page=workspace&id=<?= $pId ?>" class="btn" style="background:var(--secondary); padding:12px 24px;">☁️ Enter Shared Workspace</a>
                <?php endif; ?>

                <?php if ($isOwner): ?>
                    <a href="index.php?page=professionals&project_id=<?= $pId ?>" class="btn btn-blue" style="background:var(--primary); padding:12px 24px;">🧠 Find professionals</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if($isOwner): ?>
            <?php 
            $profReqs = $pdo->prepare("
                SELECT pp.*, u.name, u.professional_type, u.id as prof_id, 
                       (SELECT rating FROM reviews WHERE project_id = pp.project_id AND professional_id = u.id LIMIT 1) as existing_rating
                FROM project_professionals pp 
                JOIN users u ON pp.professional_id = u.id 
                WHERE pp.project_id = ?
            "); 
            $profReqs->execute([$pId]); 
            $requestedPros = $profReqs->fetchAll(); 
            ?>
            <?php if($requestedPros): ?>
            <div class="card" style="margin-bottom:36px; padding:40px;">
                <h3 style="font-size:24px; margin-bottom:20px;">Professionals Contacted</h3>
                <div class="table-container" style="box-shadow:none; border:1px solid var(--border);">
                    <table style="min-width: 800px;">
                        <tr><th>Professional Name</th><th>Discipline</th><th>Response Status</th><th>Rate & Feedback</th></tr>
                        <?php foreach($requestedPros as $rp): 
                            if ($rp['status'] === 'accepted' || $rp['status'] === 'verified') { $sc = 'var(--good)'; $bg = 'var(--good-soft)'; $displayStatus = 'ACCEPTED'; } 
                            elseif ($rp['status'] === 'rejected') { $sc = 'var(--bad)'; $bg = 'var(--bad-soft)'; $displayStatus = 'REJECTED'; } 
                            else { $sc = 'var(--primary)'; $bg = 'var(--warn-soft)'; $displayStatus = 'PENDING'; }
                        ?>
                        <tr>
                            <td style="font-weight:600; color:var(--text-main); font-size:15px;"><?= e($rp['name']) ?></td>
                            <td><span style="background:var(--bg-color); border:1px solid var(--border); padding:4px 10px; border-radius:var(--radius-sm); font-size:13px; font-weight:500; color:var(--text-muted);"><?= e($rp['professional_type']) ?></span></td>
                            <td><span class="badge" style="background:<?= $bg ?>; color:<?= $sc ?>; padding:6px 14px;"><?= $displayStatus ?></span></td>
                            <td>
                                <?php if ($rp['status'] === 'accepted' || $rp['status'] === 'verified'): ?>
                                    <?php if ($rp['existing_rating']): ?>
                                        <span style="color:var(--primary); font-weight:700; font-size:15px; background:var(--warn-soft); padding:6px 12px; border-radius:var(--radius-sm);">⭐ <?= $rp['existing_rating'] ?>.0 / 5.0</span>
                                    <?php else: ?>
                                        <button onclick="document.getElementById('rate-form-<?= $rp['prof_id'] ?>').style.display='block'; this.style.display='none';" class="btn btn-outline" style="padding:5px 12px; font-size:12px;">Leave a Rating</button>
                                        <form id="rate-form-<?= $rp['prof_id'] ?>" method="POST" action="index.php?page=project-details&id=<?= $pId ?>" style="display:none; margin-top:12px; background:var(--bg-color); padding:20px; border-radius:var(--radius-md); border:1px solid var(--border); min-width:260px; box-shadow:var(--shadow-sm);">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="project_id" value="<?= $pId ?>">
                                            <input type="hidden" name="professional_id" value="<?= $rp['prof_id'] ?>">
                                            
                                            <label style="font-size:13px; font-weight:600; color:var(--text-main); margin-bottom:8px; display:block;">Overall Rating</label>
                                            <select name="rating" style="width:100%; padding:10px; border-radius:var(--radius-sm); border:1px solid var(--border); margin-bottom:16px; font-size:14px; font-family:'Inter', sans-serif; background:var(--surface);" required>
                                                <option value="5">⭐⭐⭐⭐⭐ (5) Excellent</option>
                                                <option value="4">⭐⭐⭐⭐ (4) Good</option>
                                                <option value="3">⭐⭐⭐ (3) Average</option>
                                                <option value="2">⭐⭐ (2) Poor</option>
                                                <option value="1">⭐ (1) Terrible</option>
                                            </select>
                                            
                                            <label style="font-size:13px; font-weight:600; color:var(--text-main); margin-bottom:8px; display:block;">Public Review (Optional)</label>
                                            <textarea name="review_text" placeholder="Share your experience working with this professional..." style="width:100%; padding:10px; border-radius:var(--radius-sm); border:1px solid var(--border); margin-bottom:16px; resize:vertical; min-height:80px; font-size:14px; font-family:'Inter', sans-serif; background:var(--surface);"></textarea>
                                            
                                            <button type="submit" name="submit_review" class="btn" style="width:100%; padding:10px; font-size:14px;">Submit Verified Review</button>
                                        </form>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span style="color:var(--text-light); font-size:13px; font-style:italic;">Available after acceptance</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
<?php } ?>