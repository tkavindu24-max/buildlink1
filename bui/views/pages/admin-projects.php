<?php requireLogin(); if ($user["role"] !== "admin") { redirect("index.php?page=dashboard"); } global $pdo;?>
<section class="section">
    <div class="container">
        <div class="section-title"><h2>Platform Projects Master View</h2></div>
        <div class="table-container">
            <table>
                <tr><th>ID</th><th>Project Title</th><th>Location</th><th>Total Budget</th><th>Status</th></tr>
                <?php $projects = $pdo->query("SELECT * FROM projects ORDER BY id DESC")->fetchAll(); foreach ($projects as $p): ?>
                <tr>
                    <td style="color:var(--text-light); font-size:14px;">#<?= e($p["id"]) ?></td>
                    <td style="font-weight:600; color:var(--text-main);"><?= e($p["title"]) ?></td>
                    <td style="color:var(--text-muted);">📍 <?= e($p["location"]) ?></td>
                    <td style="color:var(--text-main); font-weight:700; font-family:'Space Grotesk', sans-serif;">Rs. <?= number_format((float)($p["budget"] ?? 0), 2) ?></td>
                    <td><span class="badge" style="background:var(--bg-color); border:1px solid var(--border); color:var(--text-muted);"><?= e($p["status"]) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>