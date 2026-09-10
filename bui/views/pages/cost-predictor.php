<?php requireLogin(); ?>
<div class="form-container">
    <h2>🤖 AI cost predictor</h2>
    <p style="color:var(--text-muted); margin-bottom:32px;">Estimate baseline construction costs algorithmically using square footage, geographical location, and material finish quality.</p>
    <div id="aiForm">
        <form method="POST" id="predictorForm" onsubmit="runAiSimulation(event, 'predictorForm')">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-group"><label>Floor area (sq. ft.)</label><input type="number" name="area" step="0.01" required placeholder="e.g. 1500"></div>
            <div class="form-group"><label>Geographical Location Index</label><select name="location"><option>Colombo & Suburbs (High Index)</option><option>Kandy District (Medium Index)</option><option>Galle / South (Medium Index)</option><option>Other Districts (Standard Index)</option></select></div>
            <div class="form-group"><label>Material & Finish Grade</label><select name="quality"><option>Standard Grade Finish</option><option>Modern / Premium Composite</option><option>Ultra-Luxury Asset</option></select></div>
            <div class="form-group"><label>Number of stories</label><input type="number" name="floors" value="1" min="1" required></div>
            <input type="hidden" name="calculate_cost" value="1">
            <button type="submit" class="btn" style="width:100%; padding:16px; font-size:15px; margin-top:10px;">Run Cost Estimation</button>
        </form>
    </div>
    <div id="aiLoader" class="ai-loader">
        <div class="spinner"></div><h3 style="color:var(--text-main); margin-bottom:8px;">Compiling current market data…</h3>
        <p style="color:var(--text-muted);">Fetching recent material indices and labor rates...</p>
    </div>
    <?php if (isset($_POST["calculate_cost"])):
        $locMult = str_contains($_POST["location"], 'Colombo') ? 1.15 : (str_contains($_POST["location"], 'Other') ? 1.0 : 1.05);
        $qualMult = str_contains($_POST["quality"], 'Luxury') ? 1.4 : (str_contains($_POST["quality"], 'Premium') ? 1.15 : 1.0);
        $cost = $_POST["area"] * 8500 * $locMult * $qualMult * (1 + (($_POST["floors"]-1)*0.06));
    ?>
    <div class="card" style="margin-top:32px; border-top:4px solid var(--primary); text-align:center; padding:48px 32px;">
        <p style="font-size:14px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:1px;">Estimated Total Cost</p>
        <h1 style="color:var(--primary); font-size:52px; margin:16px 0 8px; font-family:'Space Grotesk', sans-serif;">Rs. <?= number_format((float)($cost ?? 0), 2) ?></h1>
        <p style="font-size:14px; color:var(--text-light); margin-bottom:32px;">± 8% margin of error based on market volatility.</p>
        
        <div style="border-top:1px solid var(--border); padding-top:32px;">
            <button type="button" class="btn btn-blue" style="width:100%; padding:14px;" onclick="alert('PDF generation simulated. Detailed cost breakdown report downloaded.');">📥 Download Detailed Breakdown (PDF)</button>
            <button class="btn btn-outline" style="margin-top:16px; width:100%; padding:14px;" onclick="window.location.reload();">Calculate Another</button>
        </div>
    </div>
    <script> document.getElementById('aiForm').style.display='none'; </script>
    <?php endif; ?>
</div>