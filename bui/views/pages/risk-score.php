<?php requireLogin(); ?>
<div class="form-container">
    <h2>⚠️ Site Risk Analytics</h2>
    <p style="color:var(--text-muted); margin-bottom:32px;">Input your site's physical parameters to generate a composite risk score for construction delays and structural integrity.</p>
    <div id="riskForm">
        <form method="POST" id="riskAnalyzerForm" onsubmit="runAiSimulation(event, 'riskAnalyzerForm')">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-group"><label>Topological Slope</label><select name="slope"><option value="low">Low variance / Flat</option><option value="medium">Moderate angle (requires light retaining)</option><option value="high">Steep gradient (high landslide risk)</option></select></div>
            <div class="form-group"><label>Soil Stability Index</label><select name="soil"><option value="stable">Solid / Bedrock / Laterite</option><option value="medium">Mixed aggregate / Sandy</option><option value="unstable">Loose / Clay / Peat (requires deep piling)</option></select></div>
            <div class="form-group"><label>Hydrological Flood Risk</label><select name="flood"><option value="low">Negligible / High elevation</option><option value="medium">Historical median / Occasional waterlogging</option><option value="high">High-risk flood zone / Wetland proximity</option></select></div>
            <div class="form-group"><label>Logistical Site Access</label><select name="access"><option value="good">Good (Wide road for heavy machinery)</option><option value="medium">Restricted (Narrow lane)</option><option value="poor">Severe bottleneck (Manual labor transfer needed)</option></select></div>
            <input type="hidden" name="calculate_risk" value="1">
            <button type="submit" class="btn btn-red" style="width:100%; padding:16px; font-size:15px; margin-top:10px;">Run Vulnerability Assessment</button>
        </form>
    </div>
    <div id="aiLoader" class="ai-loader">
        <div class="spinner" style="border-top-color: var(--bad); border-right-color: #b91c1c;"></div>
        <h3 style="color:var(--bad); margin-bottom:8px;">Analyzing topological matrices…</h3>
        <p style="color:var(--text-muted);">Evaluating variables against geological data models...</p>
    </div>
    <?php if (isset($_POST["calculate_risk"])):
        $riskScore = 0;
        if ($_POST["slope"] === "high") $riskScore += 30; elseif ($_POST["slope"] === "medium") $riskScore += 15;
        if ($_POST["soil"] === "unstable") $riskScore += 30; elseif ($_POST["soil"] === "medium") $riskScore += 15;
        if ($_POST["flood"] === "high") $riskScore += 25; elseif ($_POST["flood"] === "medium") $riskScore += 12;
        if ($_POST["access"] === "poor") $riskScore += 15; elseif ($_POST["access"] === "medium") $riskScore += 8;
        $color = $riskScore <= 25 ? "var(--good)" : ($riskScore <= 55 ? "var(--warn)" : "var(--bad)");
        $colorSoft = $riskScore <= 25 ? "var(--good-soft)" : ($riskScore <= 55 ? "var(--warn-soft)" : "var(--bad-soft)");
        $status = $riskScore <= 25 ? "Low Risk Plot" : ($riskScore <= 55 ? "Moderate Vulnerability" : "High Risk / Complex Build");
    ?>
    <div class="card" style="margin-top:32px; border-color:<?= $color ?>; text-align:center; padding:48px 32px;">
        <span class="badge" style="background:<?= $colorSoft ?>; color:<?= $color ?>; padding:8px 16px; font-size:14px; border:1px solid <?= $color ?>; text-transform:uppercase; letter-spacing:0.05em;"><?= $status ?></span>
        <h2 style="font-size:16px; margin-top:32px; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:1px;">Composite Risk Score</h2>
        <h1 style="font-size:72px; margin:8px 0; color:var(--text-main); font-family:'Space Grotesk', sans-serif; line-height:1;"><?= $riskScore ?><span style="font-size:24px;color:var(--text-light); font-weight:500;">/100</span></h1>
        
        <?php if($riskScore > 55): ?>
            <div style="background:var(--bad-soft); padding:16px; border-radius:var(--radius-md); border:1px solid rgba(239, 68, 68, 0.3); margin-top:24px;">
                <p style="color:var(--bad); font-size:14px; font-weight:600;">Recommendation: Highly suggest matching with a specialized Structural Engineer before proceeding to blueprinting.</p>
            </div>
        <?php endif; ?>
        
        <button class="btn btn-outline" style="margin-top:32px; width:100%;" onclick="window.location.reload();">Run new analysis</button>
    </div>
    <script> document.getElementById('riskForm').style.display='none'; </script>
    <?php endif; ?>
</div>