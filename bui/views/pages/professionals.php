<?php requireLogin(); if ($user["role"] !== "client") { redirect("index.php?page=dashboard"); } ?>
<section class="section">
    <div class="container">
        <?php
        global $pdo;
        $pId = (int)($_GET["project_id"] ?? 0);
        $projectData = null;
        if ($pId > 0) {
            $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
            $stmt->execute([$pId, $_SESSION["user_id"]]);
            $projectData = $stmt->fetch();
        }

        $professionals = $pdo->query("SELECT * FROM users WHERE role = 'professional'")->fetchAll();

        // --- SMART MATCHMAKING ALGORITHM ---
        if ($projectData) {
            foreach ($professionals as &$prof) {
                $score = 0;
                $projLoc = strtolower(trim($projectData['location']));
                $profLoc = strtolower(trim($prof['location']));
                if (!empty($projLoc) && !empty($profLoc)) {
                    if ($projLoc === $profLoc) { $score += 25; }
                    elseif (str_contains($projLoc, $profLoc) || str_contains($profLoc, $projLoc)) { $score += 15; }
                }

                $exp = (int)$prof['experience_years'];
                $budget = (float)$projectData['budget'];
                if ($budget > 10000000) {
                    if ($exp >= 10) $score += 20; elseif ($exp >= 5) $score += 10; else $score += 5;
                } else {
                    if ($exp >= 5) $score += 20; elseif ($exp >= 2) $score += 10; else $score += 5;
                }

                $rating = (float)($prof['rating'] ?? 0);
                $score += ($rating / 5.0) * 20;

                $projDesc = strtolower($projectData['description'] . ' ' . $projectData['title']);
                $profType = strtolower($prof['professional_type']);
                if (str_contains($projDesc, $profType)) { $score += 15; } else { $score += 5; }

                if ($prof['is_premium']) { $score += 20; }

                $prof['match_score'] = min(99, round($score));
            }
            unset($prof);
            usort($professionals, function($a, $b) { return $b['match_score'] <=> $a['match_score']; });
        } else {
            usort($professionals, function($a, $b) { return ($b['is_premium'] <=> $a['is_premium']) ?: (($b['rating'] ?? 0) <=> ($a['rating'] ?? 0)); });
        }
        ?>

        <?php if ($projectData): ?>
            <div id="aiLoaderMatch" class="ai-loader" style="display:block; margin: 60px auto;">
                <div class="spinner"></div>
                <h3 style="color:var(--text-main); margin-bottom:8px;">Running Smart Matchmaking Algorithm…</h3>
                <p style="color:var(--text-muted);">Comparing location, budget requirements, and professional disciplines against <strong style="color:var(--text-main);"><?= e($projectData['location']) ?></strong>…</p>
            </div>
        <?php endif; ?>

        <div id="professionalGridWrapper" style="<?= $projectData ? 'display:none;' : 'display:block;' ?>">
            <div class="section-title">
                <?php if ($projectData): ?>
                    <h2>Recommended professionals</h2>
                    <p>AI-sorted by compatibility with your project: <strong style="color:var(--text-main)"><?= e($projectData["title"]) ?></strong></p>
                <?php else: ?>
                    <h2>Verified Professional Network</h2>
                    <p>Connect with Sri Lanka's top vetted architects and engineers.</p>
                <?php endif; ?>
            </div>

            <div class="grid">
                <?php foreach ($professionals as $prof): ?>
                <div class="card" style="<?= $prof['is_premium'] ? 'border-top:4px solid var(--primary);' : '' ?>">
                    <?php if (isset($prof['match_score'])): ?>
                        <?php
                            if ($prof['match_score'] >= 80) $badgeClass = 'match-badge';
                            elseif ($prof['match_score'] >= 50) $badgeClass = 'match-badge match-medium';
                            else $badgeClass = 'match-badge match-low';
                        ?>
                        <div class="<?= $badgeClass ?>">⚡ <?= $prof['match_score'] ?>% Match</div>
                    <?php endif; ?>

                    <div class="icon blue" style="margin-top: 6px;">👷</div>
                    <h3 style="margin-right: 90px; font-size:20px;"><?= e($prof["name"]) ?></h3>

                    <?php if($prof['is_premium']): ?>
                        <div class="premium-badge">💎 Premium Partner</div><br>
                    <?php endif; ?>

                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
                        <span class="badge" style="background: <?= $prof['verification_status'] === 'verified' ? 'var(--good-soft)' : 'var(--bg-color)' ?>; color: <?= $prof['verification_status'] === 'verified' ? 'var(--good)' : 'var(--text-muted)' ?>; border: 1px solid <?= $prof['verification_status'] === 'verified' ? 'var(--good)' : 'var(--border)' ?>;">
                            <?= $prof["verification_status"] === "verified" ? "✓ Verified License" : "Pending Verification" ?>
                        </span>
                        <span style="font-weight:700; color:var(--primary); font-size:14px;">⭐ <?= number_format((float)($prof["rating"] ?? 0), 1) ?></span>
                    </div>

                    <div style="font-size:14px; line-height:2; color:var(--text-main); margin-bottom:16px;">
                        <p style="color:var(--text-main);"><strong style="color:var(--text-muted);">Discipline:</strong> <?= e($prof["professional_type"]) ?></p>
                        <p style="color:var(--text-main);"><strong style="color:var(--text-muted);">Experience:</strong> <?= e($prof["experience_years"]) ?> years</p>
                        <p style="color:var(--text-main);"><strong style="color:var(--text-muted);">Based in:</strong> <?= e($prof["location"]) ?></p>
                    </div>
                    
                    <?php if($prof["description"]): ?>
                        <p style="font-size:13.5px; color:var(--text-muted); background:var(--bg-color); padding:12px; border-radius:var(--radius-md); border:1px solid var(--border); margin-bottom:24px; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;"><?= e($prof["description"]) ?></p>
                    <?php endif; ?>

                    <?php if ($pId > 0): ?>
                    <form method="POST" action="index.php?page=professionals">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="project_id" value="<?= $pId ?>">
                        <input type="hidden" name="professional_id" value="<?= $prof["id"] ?>">
                        <button type="submit" name="request_professional" class="btn" style="width:100%">Send Project Request</button>
                    </form>
                    <?php else: ?>
                    <a href="index.php?page=professionals&project_id=0" class="btn btn-outline" style="width:100%">View Full Profile</a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($projectData): ?>
        <script> setTimeout(() => { document.getElementById('aiLoaderMatch').style.display = 'none'; document.getElementById('professionalGridWrapper').style.display = 'block'; }, 1800); </script>
        <?php endif; ?>
    </div>
</section>