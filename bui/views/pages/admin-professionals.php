<?php requireLogin(); if ($user["role"] !== "admin") { redirect("index.php?page=dashboard"); } global $pdo;?>
<section class="section">
    <div class="container">
        <div class="section-title"><h2>Professional credentials</h2><p>Verify or reject professional profiles below.</p></div>
        <div class="table-container">
            <table style="min-width: 800px;">
                <tr><th>Identity</th><th>Discipline</th><th>Location</th><th>Current Status</th><th>Admin Action</th></tr>
                <?php $professionals = $pdo->query("SELECT * FROM users WHERE role='professional' ORDER BY id DESC")->fetchAll(); foreach ($professionals as $p): ?>
                <tr>
                    <td style="font-weight:600; color:var(--text-main);"><?= e($p["name"]) ?><?= $p['is_premium'] ? ' <span style="font-size:12px;">💎</span>' : '' ?></td>
                    <td style="color:var(--text-muted);"><?= e($p["professional_type"]) ?></td>
                    <td style="color:var(--text-muted);">📍 <?= e($p["location"]) ?></td>
                    <td><span class="badge" style="color:<?= $p['verification_status']=='verified'?'var(--good)':($p['verification_status']=='rejected'?'var(--bad)':'var(--warn)') ?>; background:<?= $p['verification_status']=='verified'?'var(--good-soft)':($p['verification_status']=='rejected'?'var(--bad-soft)':'var(--warn-soft)') ?>; border:1px solid currentColor;"><?= e($p["verification_status"]) ?></span></td>
                    <td>
                        <form method="POST" style="display:flex; gap:8px;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="prof_id" value="<?= $p['id'] ?>">
                            <button type="submit" name="update_admin_prof_status" value="pending" class="btn btn-outline" style="padding:6px 12px; font-size:12px; border-radius:var(--radius-sm);">Pending</button>
                            <button type="submit" name="update_admin_prof_status" value="verified" class="btn" style="padding:6px 12px; font-size:12px; background:var(--good); box-shadow:none; border-radius:var(--radius-sm);">Verify</button>
                            <button type="submit" name="update_admin_prof_status" value="rejected" class="btn btn-red" style="padding:6px 12px; font-size:12px; border-radius:var(--radius-sm); box-shadow:none;">Reject</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>