<?php requireLogin(); ?>
<div class="form-container">
    <h2>📋 Regulation &amp; zoning checker</h2>
    <p style="color:var(--text-muted); margin-bottom:32px;">Verify if your architectural parameters comply with standard municipal council regulations and zoning laws.</p>
    <div id="regForm">
        <form method="POST" id="regulationForm" onsubmit="runAiSimulation(event, 'regulationForm')">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-group"><label>Zoning classification</label><select name="zone"><option value="Residential">Residential Zone</option><option value="Commercial">Commercial Zone</option><option value="Industrial">Industrial Zone</option></select></div>
            <div class="form-group"><label>Proposed Building Type</label><select name="b_type"><option value="House">Single/Multi-family House</option><option value="Apartment">Apartment Complex</option><option value="Factory">Manufacturing/Factory</option><option value="Shop">Retail Space</option></select></div>
            
            <div style="display:flex; gap:20px; flex-wrap:wrap;">
                <div class="form-group" style="flex:1;"><label>Total Stories (Floors)</label><input type="number" name="floors" value="1" min="1" required></div>
                <div class="form-group" style="flex:1;"><label>Road Setback (Meters)</label><input type="number" name="setback" step="0.1" value="3.0" required></div>
            </div>
            
            <input type="hidden" name="calculate_reg" value="1">
            <button type="submit" class="btn" style="background:var(--secondary); color:white; width:100%; padding:16px; font-size:15px; margin-top:10px; box-shadow:none;">Run Compliance Matrix</button>
        </form>
    </div>
    <div id="aiLoader" class="ai-loader">
        <div class="spinner" style="border-top-color: var(--secondary); border-right-color: var(--border);"></div>
        <h3 style="color:var(--text-main); margin-bottom:8px;">Verifying municipal protocols…</h3>
        <p style="color:var(--text-muted);">Cross-referencing zoning databases and structural limits...</p>
    </div>
    <?php if (isset($_POST["calculate_reg"])):
        $zone = $_POST['zone']; $b_type = $_POST['b_type']; $floors = (int)$_POST['floors']; $setback = (float)$_POST['setback'];
        $score = 100; $alerts = [];
        if ($b_type === 'Factory' && $zone !== 'Industrial') { $score -= 50; $alerts[] = "Zoning Violation: Industrial facilities are strictly prohibited in {$zone} zones."; }
        if ($b_type === 'Apartment' && $zone === 'Industrial') { $score -= 40; $alerts[] = "Zoning Violation: Residential complexes cannot be built in designated Industrial zones."; }
        if ($setback < 3.0) { $score -= 35; $alerts[] = "Setback Violation: A minimum 3.0m street setback line is legally required."; }
        if ($floors > 3 && $setback < 5.0) { $score -= 20; $alerts[] = "Height/Width Ratio Warning: Buildings over 3 stories typically require a >5.0m access road/setback."; }
        
        $score = max(0, min(100, $score));
        $color = $score === 100 ? "var(--good)" : ($score >= 60 ? "var(--warn)" : "var(--bad)");
        $statusText = $score === 100 ? "Fully Compliant" : ($score >= 60 ? "Conditional Compliance / Flags Found" : "Severe Violations Detected");
    ?>
    <div class="card" style="margin-top:32px; border-top:4px solid <?= $color ?>; padding:48px 32px;">
        <div style="text-align:center;">
            <p style="font-size:14px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Compliance Rating</p>
            <h1 style="font-size:64px; margin:0 0 12px; color:var(--text-main); font-family:'Space Grotesk', sans-serif; line-height:1;"><?= $score ?>%</h1>
            <p style="color:<?= $color ?>; font-weight:700; font-size:16px;"><?= $statusText ?></p>
        </div>
        
        <?php if(!empty($alerts)): ?>
            <div style="margin-top:32px; border-top:1px solid var(--border); padding-top:24px;">
                <h4 style="margin-bottom:16px; font-size:16px; color:var(--text-main);">Identified Regulatory Flags:</h4>
                <ul style="color:var(--text-main); font-size:14.5px; background:var(--bad-soft); padding:20px 20px 20px 40px; border-radius:var(--radius-md); border:1px solid rgba(239, 68, 68, 0.2); line-height:1.6;">
                    <?php foreach($alerts as $alert): ?> 
                        <li style="margin-bottom:8px; color:var(--bad); font-weight:500;"><?= e($alert) ?></li> 
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <button class="btn btn-outline" style="margin-top:32px; width:100%;" onclick="window.location.reload();">Test Another Configuration</button>
    </div>
    <script> document.getElementById('regForm').style.display='none'; </script>
    <?php endif; ?>
</div>