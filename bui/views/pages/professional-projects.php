<?php requireLogin(); if ($user["role"] !== "professional") { redirect("index.php?page=dashboard"); } ?>
<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Project Requests & Workspaces</h2>
            <p>Review incoming requests from clients. Accept requests to unlock project data rooms and collaborate.</p>
        </div>
        <?php
        global $pdo;
        $stmt = $pdo->prepare("SELECT pp.*, p.title, p.project_type, p.location, p.budget, u.name AS client_name FROM project_professionals pp JOIN projects p ON pp.project_id = p.id JOIN users u ON p.user_id = u.id WHERE pp.professional_id = ? AND pp.status != 'rejected' ORDER BY pp.created_at DESC");
        $stmt->execute([$_SESSION["user_id"]]); $requests = $stmt->fetchAll();
        ?>
        <?php if ($requests): ?>
        <div class="table-container" style="border:1px solid var(--border); box-shadow:var(--shadow-sm);">
            <table style="min-width: 900px;">
                <tr><th>Project Details</th><th>Location</th><th>Client Budget</th><th>Status</th><th>Actions</th><th>Data Room</th></tr>
                <?php foreach ($requests as $req): 
                    if ($req['status'] === 'accepted' || $req['status'] === 'verified') { $sc = 'var(--good)'; $bg = 'var(--good-soft)'; $displayStatus = 'ACCEPTED'; } 
                    elseif ($req['status'] === 'rejected') { $sc = 'var(--bad)'; $bg = 'var(--bad-soft)'; $displayStatus = 'REJECTED'; } 
                    else { $sc = 'var(--primary)'; $bg = 'var(--warn-soft)'; $displayStatus = 'PENDING'; }
                ?>
                <tr>
                    <td>
                        <a href="index.php?page=project-details&id=<?= $req['project_id'] ?>" style="color:var(--text-main); font-weight:700; font-size:16px; text-decoration:none; display:block; margin-bottom:4px;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-main)'">
                            <?= e($req["title"]) ?> <span style="font-size:12px; color:var(--text-light);">↗</span>
                        </a>
                        <div style="font-size:13px; color:var(--text-muted); font-weight:500;">Client: <span style="color:var(--text-main);"><?= e($req['client_name']) ?></span></div>
                    </td>
                    <td style="color:var(--text-muted); font-weight:500;">📍 <?= e($req["location"]) ?></td>
                    <td style="color:var(--text-main); font-weight:700; font-size:15px; font-family:'Space Grotesk', sans-serif;">Rs. <?= number_format((float)($req["budget"] ?? 0), 2) ?></td>
                    <td><span class="badge" style="color:<?= $sc ?>; background:<?= $bg ?>; border:1px solid <?= $sc ?>; padding:6px 12px;"><?= $displayStatus ?></span></td>
                    <td>
                        <form method="POST" style="display:flex; gap:8px; align-items:center;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                            <?php if ($req['status'] !== 'accepted' && $req['status'] !== 'verified'): ?>
                                <button type="submit" name="update_prof_project_status" value="accepted" class="btn" style="padding:6px 14px; font-size:12px; border-radius:var(--radius-sm); background:var(--good); box-shadow:none;">Accept</button>
                                <button type="submit" name="update_prof_project_status" value="rejected" class="btn btn-outline" style="padding:6px 14px; font-size:12px; border-radius:var(--radius-sm); color:var(--bad); border-color:var(--bad-soft);">Decline</button>
                            <?php else: ?>
                                <span style="color:var(--text-light); font-size:13px; font-style:italic;">Responded</span>
                            <?php endif; ?>
                        </form>
                    </td>
                    <td>
                        <?php if($req['status'] === 'accepted' || $req['status'] === 'verified'): ?>
                            <a href="index.php?page=workspace&id=<?= $req['project_id'] ?>" class="btn btn-blue" style="padding: 8px 16px; font-size: 13px; box-shadow:none;">☁️ Enter Workspace</a>
                        <?php else: ?>
                            <span class="badge" style="color:var(--text-muted); font-size:12px; font-weight:500; padding: 6px 12px; border:1px dashed var(--border); background:var(--bg-color);">Accept to unlock</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php else: ?>
        <div class="card" style="text-align:center; padding:60px;"><p style="font-size:16px; color:var(--text-muted); font-weight:500;">You have no active project requests.</p></div>
        <?php endif; ?>
    </div>
</section>