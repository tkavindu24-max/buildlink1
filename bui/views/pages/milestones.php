<?php requireLogin(); $pId = (int)($_GET["id"] ?? 0); global $pdo; $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?"); $stmt->execute([$pId]); $project = $stmt->fetch(); if (!$project) { echo "<div class='container section'><div class='card'><h2>Error 404</h2></div></div>"; } else { ?>
<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Interactive Project Timeline</h2>
            <p>Project: <strong style="color:var(--text-main);"><?= e($project["title"]) ?></strong>. Fund milestones into escrow securely to allow professionals to begin work.</p>
        </div>
        
        <?php $stmt = $pdo->prepare("SELECT * FROM milestones WHERE project_id = ? ORDER BY id"); $stmt->execute([$pId]); $milestones = $stmt->fetchAll(); ?>
        
        <!-- VERTICAL TIMELINE UI -->
        <div class="timeline">
            <?php foreach ($milestones as $milestone): ?>
            <div class="timeline-item <?= $milestone['status'] ?>">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h3 style="font-size:22px; margin-bottom:8px;"><?= e($milestone["title"]) ?></h3>
                    <p style="color:var(--text-muted); margin-bottom:20px; font-size:15px; line-height:1.6;"><?= e($milestone["description"]) ?></p>
                    
                    <div style="display:flex; justify-content:space-between; background:var(--bg-color); padding:14px 20px; border-radius:var(--radius-md); margin-bottom:20px; border:1px solid var(--border);">
                        <p style="font-weight:700; font-size:13px; text-transform:uppercase; letter-spacing:0.05em; color: <?= $milestone['status'] === 'completed' ? 'var(--good)' : 'var(--primary)' ?>">
                            Task: <?= ucfirst(str_replace("_", " ", e($milestone["status"]))) ?>
                        </p>
                        <p style="font-weight:700; font-size:13px; text-transform:uppercase; letter-spacing:0.05em; color: <?= $milestone['payment_status'] === 'released' ? 'var(--good)' : ($milestone['payment_status'] === 'funded' ? 'var(--info)' : 'var(--text-muted)') ?>">
                            Funds: <?= ucfirst(e($milestone["payment_status"])) ?>
                        </p>
                    </div>
                    
                    <h2 style="font-size:28px; color:var(--text-main); margin-bottom:20px; font-family:'Space Grotesk', sans-serif;">Rs. <?= number_format((float)($milestone["amount"] ?? 0), 2) ?></h2>

                    <!-- CLIENT ESCROW ACTIONS -->
                    <?php if ($user["role"] === "client" && $milestone['payment_status'] === 'unfunded'): ?>
                        <form method="POST" action="index.php?page=milestones&id=<?= $pId ?>">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="milestone_id" value="<?= $milestone["id"] ?>">
                            <input type="hidden" name="project_id" value="<?= $pId ?>">
                            <button type="submit" name="fund_escrow" class="btn" style="width:100%; padding:14px; font-size:15px; background:var(--info); box-shadow:none;">🔒 Fund Phase into Escrow (Simulated)</button>
                        </form>
                    <?php elseif ($user["role"] === "client"): ?>
                         <p style="font-size:14px; font-weight:600; color:var(--text-main); text-align:center; padding:12px; background: <?= $milestone['payment_status'] === 'funded' ? 'var(--info-soft)' : 'var(--good-soft)' ?>; border-radius:var(--radius-sm); border:1px solid <?= $milestone['payment_status'] === 'funded' ? 'rgba(59,130,246,0.2)' : 'rgba(16,185,129,0.2)' ?>;">
                            <?= $milestone['payment_status'] === 'funded' ? '🔒 Funds locked in escrow. Awaiting contractor completion.' : '✅ Funds released. Phase complete.' ?>
                         </p>
                    <?php endif; ?>

                    <!-- PROFESSIONAL UPDATE ACTIONS (ONE-BY-ONE PROGRESS) -->
                    <?php if ($user["role"] === "professional" || $user["role"] === "admin"): ?>
                        <?php if ($milestone['status'] === 'pending'): ?>
                            <form method="POST" action="index.php?page=milestones&id=<?= $pId ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="milestone_id" value="<?= $milestone["id"] ?>">
                                <input type="hidden" name="project_id" value="<?= $pId ?>">
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" name="update_milestone" class="btn btn-outline" style="width:100%; padding:14px; font-size:15px;">🚀 Start Working on Phase</button>
                            </form>
                        <?php elseif ($milestone['status'] === 'in_progress'): ?>
                            <form method="POST" action="index.php?page=milestones&id=<?= $pId ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="milestone_id" value="<?= $milestone["id"] ?>">
                                <input type="hidden" name="project_id" value="<?= $pId ?>">
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" name="update_milestone" class="btn" style="width:100%; padding:14px; font-size:15px; background:var(--good); box-shadow:none;">✅ Mark Task as Completed & Request Funds</button>
                            </form>
                        <?php else: ?>
                             <p style="font-size:14px; font-weight:700; color:var(--good); text-align:center; padding:12px; background:var(--good-soft); border-radius:var(--radius-sm);">Phase Successfully Completed</p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php } ?>