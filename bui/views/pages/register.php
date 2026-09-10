<div class="form-container">
    <h2>Create an account</h2>
    <p style="color:var(--text-muted); margin-bottom:28px;">Join the BuildLink network as a client or a verified professional.</p>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <div class="form-group"><label>Full name</label><input type="text" name="name" required></div>
        <div class="form-group"><label>Email address</label><input type="email" name="email" required></div>
        <div class="form-group"><label>Password (min. 8 characters)</label><input type="password" name="password" minlength="8" required></div>
        <div class="form-group"><label>Phone number</label><input type="text" name="phone"></div>
        <div class="form-group"><label>I am registering as a</label>
            <select name="role" id="role" onchange="document.getElementById('profFields').style.display = this.value === 'professional' ? 'block' : 'none'">
                <option value="client">Client / Investor / Homeowner</option>
                <option value="professional">Licensed Professional</option>
            </select>
        </div>
        <div id="profFields" style="display:none; background:var(--bg-color); padding:24px; border-radius:var(--radius-md); margin-bottom:24px; border:1px solid var(--border);">
            <div class="form-group"><label>Primary Discipline</label><select name="professional_type"><option>Architect</option><option>Civil Engineer</option><option>Structural Engineer</option></select></div>
            <div class="form-group"><label>Years of experience</label><input type="number" name="experience_years" min="0"></div>
            <div class="form-group"><label>Base Location</label><input type="text" name="location" placeholder="e.g., Colombo"></div>
            <div class="form-group"><label>Highest Qualification</label><input type="text" name="qualification" placeholder="e.g., BSc Engineering"></div>
            <div class="form-group" style="margin-bottom:0;"><label>Bio &amp; Portfolio Links</label><textarea name="description" rows="3"></textarea></div>
        </div>
        <button type="submit" name="register" class="btn" style="width:100%; padding:14px;">Create account</button>
    </form>
    <p style="margin-top:24px; text-align:center; color:var(--text-muted); font-size:14px;">Already have an account? <a href="index.php?page=login" style="color:var(--primary); font-weight:600;">Log in</a></p>
</div>