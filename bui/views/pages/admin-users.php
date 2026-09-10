<?php requireLogin(); if ($user["role"] !== "admin") { redirect("index.php?page=dashboard"); } global $pdo;?>
<section class="section">
    <div class="container">
        <div class="section-title"><h2>User directory</h2></div>
        <div class="table-container">
            <table>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr>
                <?php $users = $pdo->query("SELECT id, name, email, role, is_premium, created_at FROM users ORDER BY id DESC")->fetchAll(); foreach ($users as $u): ?>
                <tr><td style="color:var(--text-light);">#<?= e($u["id"]) ?></td><td style="font-weight:600; color:var(--text-main);"><?= e($u["name"]) ?><?= $u['is_premium'] ? ' <span style="font-size:12px;" title="Premium">💎</span>' : '' ?></td><td style="color:var(--text-muted);"><?= e($u["email"]) ?></td><td><span class="badge" style="background:var(--bg-color); border:1px solid var(--border); color:var(--text-main);"><?= e($u["role"]) ?></span></td><td style="color:var(--text-muted); font-size:13px;"><?= e(date('M j, Y', strtotime($u["created_at"]))) ?></td></tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>