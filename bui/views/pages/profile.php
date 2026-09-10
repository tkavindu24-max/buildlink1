<?php requireLogin(); if ($user["role"] !== "professional") { redirect("index.php?page=dashboard"); } ?>
<div class="form-container" style="max-width:700px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:32px; border-bottom:1px solid var(--border); padding-bottom:16px;">
        <h2 style="margin:0;">My Professional Profile</h2>
        <?php if($user['is_premium']): ?><div class="premium-badge" style="margin:0;">💎 Premium</div><?php endif; ?>
    </div>
    
    <div class="card" style="padding:40px; <?= $user['is_premium'] ? 'border-top:4px solid var(--primary);' : '' ?>">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px; flex-wrap:wrap; gap:16px;">
            <div>
                <h2 style="font-size:32px; margin-bottom:4px; line-height:1.2; color:var(--text-main);">👷 <?= e($user["name"]) ?></h2>
                <p style="color:var(--text-muted); font-size:16px; font-weight:500;"><?= e($user["professional_type"]) ?></p>
            </div>
            <div style="text-align:right;">
                <span class="badge" style="background: <?= $user['verification_status'] === 'verified' ? 'var(--good-soft)' : 'var(--bg-color)' ?>; color: <?= $user['verification_status'] === 'verified' ? 'var(--good)' : 'var(--text-muted)' ?>; border: 1px solid <?= $user['verification_status'] === 'verified' ? 'var(--good)' : 'var(--border)' ?>; font-size:13px; padding:6px 12px;">
                    <?= $user['verification_status'] === 'verified' ? '✓ Verified Account' : 'Status: ' . ucfirst(e($user["verification_status"])) ?>
                </span>
            </div>
        </div>
        
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(150px, 1fr)); gap:20px; background:var(--bg-color); padding:24px; border-radius:var(--radius-md); border:1px solid var(--border); margin-bottom:32px;">
            <div><p style="font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Experience</p><p style="font-size:16px; font-weight:600; margin-top:4px; color:var(--text-main);"><?= e($user["experience_years"]) ?> Years</p></div>
            <div><p style="font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Location</p><p style="font-size:16px; font-weight:600; margin-top:4px; color:var(--text-main);">📍 <?= e($user["location"]) ?></p></div>
            <div><p style="font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Client Rating</p><p style="font-size:16px; font-weight:700; margin-top:4px; color:var(--primary);">⭐ <?= number_format((float)($user["rating"] ?? 0), 1) ?> / 5.0</p></div>
        </div>

        <h4 style="margin-bottom:12px; color:var(--text-main); font-size:16px;">Qualification</h4>
        <p style="color:var(--text-muted); font-size:15px; margin-bottom:24px;"><span style="background:var(--bg-color); padding:6px 12px; border-radius:var(--radius-sm); border:1px solid var(--border);"><?= e($user["qualification"]) ?></span></p>

        <h4 style="margin-bottom:12px; color:var(--text-main); font-size:16px;">Biography &amp; Portfolio</h4>
        <div style="color:var(--text-main); font-size:15px; line-height:1.8; background:var(--bg-color); padding:24px; border-radius:var(--radius-md); border:1px solid var(--border);">
            <?= nl2br(e($user["description"])) ?>
        </div>
    </div>
</div>