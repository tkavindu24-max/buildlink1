<?php requireLogin(); ?>
<div class="form-container" style="max-width:700px;">
    <h2>Start a new project</h2>
    <p style="color:var(--text-muted); margin-bottom:32px;">Provide details about your build so BuildLink can set up your workspace and optimize professional matchmaking.</p>
    
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <div class="form-group">
            <label>Project name *</label>
            <input type="text" name="title" required placeholder="e.g., Skyline Villa Residence">
        </div>
        
        <div style="display:flex; gap:20px; flex-wrap:wrap;">
            <div class="form-group" style="flex:1; min-width:200px;">
                <label>Project type</label>
                <select name="project_type">
                    <option>Residential Home</option>
                    <option>Commercial Facility</option>
                    <option>Industrial Complex</option>
                </select>
            </div>
            <div class="form-group" style="flex:1; min-width:200px;">
                <label>Location (City/District)</label>
                <input type="text" name="location" placeholder="e.g., Colombo 07" required>
            </div>
        </div>
        
        <div style="display:flex; gap:20px; flex-wrap:wrap;">
            <div class="form-group" style="flex:1; min-width:150px;">
                <label>Plot area (perches)</label>
                <input type="number" name="land_area" step="0.01" placeholder="10.5">
            </div>
            <div class="form-group" style="flex:1; min-width:150px;">
                <label>Number of floors</label>
                <input type="number" name="number_of_floors" min="1" value="1">
            </div>
            <div class="form-group" style="flex:1; min-width:150px;">
                <label>Estimated Budget (Rs.)</label>
                <input type="number" name="budget" step="0.01" placeholder="15000000">
            </div>
        </div>
        
        <div class="form-group">
            <label>Construction standard</label>
            <select name="construction_type">
                <option>Standard Finish</option>
                <option>Modern / High-Quality</option>
                <option>Ultra-Luxury Asset</option>
                <option>Sustainable / Eco-Build</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Project brief & Requirements</label>
            <textarea name="description" rows="4" placeholder="Describe your architectural vision, specific engineering needs, or any site constraints..."></textarea>
        </div>
        
        <div class="form-group">
            <label>Land / Topography Photo (Optional)</label>
            <div style="border: 2px dashed var(--border); padding: 20px; border-radius: var(--radius-md); text-align:center; background:var(--bg-color);">
                <input type="file" name="land_image" accept="image/jpeg, image/png, image/webp" style="background:transparent; border:none; box-shadow:none; cursor:pointer;">
                <p style="font-size:12px; margin-top:8px;">Used for AI terrain risk analysis. Max size 5MB.</p>
            </div>
        </div>
        
        <button type="submit" name="create_project" class="btn" style="width:100%; padding:16px; font-size:16px; margin-top:10px;">Initialize Project Workspace</button>
    </form>
</div>