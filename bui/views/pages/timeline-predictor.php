<?php requireLogin(); ?>
<div class="form-container">
    <h2>⏳ AI Timeline Predictor</h2>
    <p style="color:var(--text-muted); margin-bottom:32px;">Forecast a realistic completion date based on algorithmic analysis of project size, complexity, contractor history, and environmental factors.</p>
    <div id="timelineFormBox">
        <form method="POST" id="timelineForm" onsubmit="runAiSimulation(event, 'timelineForm')">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="form-group">
                <label>Total build size (sq. ft.)</label>
                <input type="number" name="project_size" step="1" required placeholder="e.g. 2500">
            </div>
            
            <div class="form-group">
                <label>Architectural Complexity</label>
                <select name="complexity">
                    <option value="simple">Simple / Prefabricated / Box-design</option>
                    <option value="standard" selected>Standard Residential Home</option>
                    <option value="complex">Custom / High-end / Complex Architecture</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Contractor Historical Track Record</label>
                <select name="contractor_rating">
                    <option value="5.0">Excellent (5 stars) — highly efficient, no historical delays</option>
                    <option value="4.0" selected>Good (4 stars) — industry standard performance</option>
                    <option value="3.0">Average (3 stars) — occasional logistical delays</option>
                    <option value="2.0">Below average (&lt;3 stars) — high risk of timeline slippage</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Expected Seasonal Weather</label>
                <select name="weather_condition">
                    <option value="dry">Dry / Favorable for concrete curing</option>
                    <option value="mixed" selected>Mixed / Standard seasonal</option>
                    <option value="monsoon">Monsoon / Heavy rain expected</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Material &amp; Labor Market Availability</label>
                <select name="resource_availability">
                    <option value="high">High availability / Surplus</option>
                    <option value="medium" selected>Standard market conditions</option>
                    <option value="low">Low availability / Known material shortages</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Municipal Permit & Regulatory Delays</label>
                <select name="permit">
                    <option value="fast">Fast-track / Pre-approved zone</option>
                    <option value="standard" selected>Standard council processing times</option>
                    <option value="delayed">High-regulation area (e.g., coastal, heritage) / Delays expected</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Terrain & Foundation Difficulty</label>
                <select name="terrain">
                    <option value="easy" selected>Flat plot / Stable solid soil</option>
                    <option value="moderate">Moderate slope / Standard excavation</option>
                    <option value="difficult">Difficult terrain / Rocky / Requires deep piling</option>
                </select>
            </div>

            <input type="hidden" name="calculate_timeline" value="1">
            <button type="submit" class="btn btn-blue" style="width:100%; padding:16px; font-size:15px; margin-top:10px;">Run Monte Carlo Forecast</button>
        </form>
    </div>
    <div id="aiLoader" class="ai-loader">
        <div class="spinner" style="border-top-color: var(--primary); border-right-color: var(--info);"></div>
        <h3 style="color:var(--text-main); margin-bottom:8px;">Calculating projection…</h3>
        <p style="color:var(--text-muted);">Running variables against historical construction data...</p>
    </div>
    
    <?php if (isset($_POST["calculate_timeline"])):
        $size = (float)$_POST["project_size"]; 
        $rating = (float)$_POST["contractor_rating"]; 
        $weather = $_POST["weather_condition"]; 
        $resources = $_POST["resource_availability"];
        $complexity = $_POST["complexity"];
        $permit = $_POST["permit"];
        $terrain = $_POST["terrain"];

        $base_months = max(1, $size / 500);
        
        if ($rating >= 4.5) $rating_modifier = 0.9; elseif ($rating >= 3.5) $rating_modifier = 1.0; elseif ($rating >= 3.0) $rating_modifier = 1.15; else $rating_modifier = 1.4;
        if ($weather === 'monsoon') $weather_modifier = 1.4; elseif ($weather === 'mixed') $weather_modifier = 1.15; else $weather_modifier = 1.0;
        if ($resources === 'low') $resource_modifier = 1.35; elseif ($resources === 'medium') $resource_modifier = 1.1; else $resource_modifier = 1.0;
        
        if ($complexity === 'complex') $comp_modifier = 1.3; elseif ($complexity === 'simple') $comp_modifier = 0.9; else $comp_modifier = 1.0;
        if ($permit === 'delayed') $perm_modifier = 1.2; elseif ($permit === 'fast') $perm_modifier = 0.95; else $perm_modifier = 1.0;
        if ($terrain === 'difficult') $terr_modifier = 1.25; elseif ($terrain === 'moderate') $terr_modifier = 1.1; else $terr_modifier = 1.0;

        $total_months = $base_months * $rating_modifier * $weather_modifier * $resource_modifier * $comp_modifier * $perm_modifier * $terr_modifier;
        
        $total_days = round($total_months * 30); 
        $completion_date = date('F j, Y', strtotime("+$total_days days"));
        
        $color = $total_months <= ($base_months * 1.15) ? "var(--good)" : ($total_months <= ($base_months * 1.5) ? "var(--warn)" : "var(--bad)");
        $status = $total_months <= ($base_months * 1.15) ? "Optimal / On-Track" : ($total_months <= ($base_months * 1.5) ? "Moderate slippage expected" : "Severe delay risk detected");
        $colorSoft = $total_months <= ($base_months * 1.15) ? "var(--good-soft)" : ($total_months <= ($base_months * 1.5) ? "var(--warn-soft)" : "var(--bad-soft)");
    ?>
    <div class="card" style="margin-top:32px; border-color:<?= $color ?>; text-align:center; padding:48px 32px;">
        <span class="badge" style="background:<?= $colorSoft ?>; color:<?= $color ?>; padding:8px 16px; font-size:14px; border:1px solid <?= $color ?>;"><?= $status ?></span>
        
        <h2 style="font-size:16px; margin-top:32px; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:1px;">Estimated Core Duration</h2>
        <h1 style="color:var(--text-main); font-size:64px; margin:8px 0; font-family:'Space Grotesk', sans-serif; line-height:1;"><?= round($total_months, 1) ?> <span style="font-size:24px; color:var(--text-light); font-weight:500;">months</span></h1>
        
        <div style="background:var(--bg-color); padding: 24px; border-radius: var(--radius-md); margin-top: 32px; border:1px solid var(--border);">
            <p style="color:var(--text-muted); font-size:14px; margin-bottom:8px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">Projected Completion Date</p>
            <h3 style="color:var(--primary); font-size:28px;"><?= $completion_date ?></h3>
        </div>
        
        <button class="btn btn-outline" style="margin-top:32px; width:100%;" onclick="window.location.reload();">Run another forecast</button>
    </div>
    <script> document.getElementById('timelineFormBox').style.display='none'; </script>
    <?php endif; ?>
</div>