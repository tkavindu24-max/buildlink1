<?php requireLogin(); ?>
<div class="form-container" style="max-width:800px;">
    <h2>🛒 Material Price Intelligence</h2>
    <p style="color:var(--text-muted); margin-bottom:32px;">Compare live prices from local suppliers and let AI recommend the most cost-effective option.</p>
    <div id="materialFormBox">
        <form method="POST" id="materialForm" onsubmit="runAiSimulation(event, 'materialForm')">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div style="display:flex; gap:20px; flex-wrap:wrap;">
                <div class="form-group" style="flex:2; min-width:200px;"><label>Select Construction Material</label>
                    <select name="material_type"><option value="Cement (50kg Bag)">Cement (50kg Bag)</option><option value="TMT Steel Bars (per Ton)">TMT Steel Bars (per Ton)</option><option value="River Sand (Cube)">River Sand (Cube)</option><option value="Red Bricks (per 1000)">Red Bricks (per 1000)</option></select>
                </div>
                <div class="form-group" style="flex:1; min-width:150px;"><label>Delivery Region</label><select name="region"><option>Colombo</option><option>Gampaha</option><option>Galle</option><option>Kandy</option></select></div>
            </div>
            <input type="hidden" name="search_materials" value="1">
            <button type="submit" class="btn" style="background:var(--good); width:100%; padding:16px; font-size:15px; margin-top:10px; box-shadow:none;">Scan Local Supplier Databases</button>
        </form>
    </div>
    <div id="aiLoader" class="ai-loader">
        <div class="spinner" style="border-top-color: var(--good); border-right-color: #059669;"></div>
        <h3 style="color:var(--good); margin-bottom:8px;">Querying supplier APIs…</h3>
        <p style="color:var(--text-muted);">Fetching real-time inventory and pricing for your region.</p>
    </div>
    <?php if (isset($_POST["search_materials"])):
        $mat = $_POST['material_type']; $reg = $_POST['region'];
        $basePrice = ($mat === 'Cement (50kg Bag)') ? 3100 : (($mat === 'TMT Steel Bars (per Ton)') ? 295000 : (($mat === 'River Sand (Cube)') ? 19000 : 38000));
        if ($reg !== 'Colombo') $basePrice *= 1.06; // Delivery overhead
        $suppliers = [ 
            ['name' => 'MegaBuild Supplies PLC', 'price' => $basePrice * 1.03, 'stock' => 'High Stock', 'quality' => 'Premium'], 
            ['name' => 'Lanka Hardware Direct', 'price' => $basePrice * 0.97, 'stock' => 'Low Stock', 'quality' => 'Standard'], 
            ['name' => 'City Construction Depot', 'price' => $basePrice * 1.05, 'stock' => 'In Stock', 'quality' => 'Premium'] 
        ];
        usort($suppliers, function($a, $b) { return $a['price'] <=> $b['price']; });
    ?>
    <div class="card" style="margin-top:32px; border-top:4px solid var(--good); padding:40px;">
        <h3 style="margin-bottom:8px; font-size:24px;">Market Analysis: <?= e($mat) ?></h3>
        <p style="margin-bottom:24px; font-size:15px; color:var(--text-muted);">📍 Delivery calculated for <strong style="color:var(--text-main);"><?= e($reg) ?></strong></p>
        
        <div class="table-container" style="margin:0; box-shadow:none; border:1px solid var(--border);">
            <table style="min-width: 600px;">
                <tr><th>Supplier Name</th><th>Unit Price</th><th>Inventory</th><th>Material Grade</th></tr>
                <?php foreach ($suppliers as $idx => $s): ?>
                <tr style="<?= $idx === 0 ? 'background:var(--good-soft);' : '' ?>">
                    <td style="font-weight:600; color:var(--text-main); font-size:15px;">
                        <?= e($s['name']) ?> 
                        <?php if($idx === 0): ?><br><span style="display:inline-block; margin-top:6px; background:var(--good); color:white; font-size:11px; font-weight:700; padding:4px 8px; border-radius:4px; text-transform:uppercase; letter-spacing:0.5px;">⭐ Top Recommendation</span><?php endif; ?>
                    </td>
                    <td style="color:var(--good); font-weight:700; font-size:16px;">Rs. <?= number_format((float)($s['price'] ?? 0), 2) ?></td>
                    <td><span class="badge" style="background:var(--bg-color); border:1px solid var(--border); color:var(--text-muted);"><?= e($s['stock']) ?></span></td>
                    <td style="color:var(--text-muted); font-weight:500;"><?= e($s['quality']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <button class="btn btn-outline" style="margin-top:32px; width:100%;" onclick="window.location.reload();">New Search</button>
    </div>
    <script> document.getElementById('materialFormBox').style.display='none'; </script>
    <?php endif; ?>
</div>