<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#ffffff">
<title>BuildLink | Modern SaaS Construction Platform</title>
<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🏗️</text></svg>">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* CSS RESET AND SAAS DESIGN TOKENS */
:root { --primary: #f59e0b; --primary-hover: #d97706; --secondary: #1e293b; --secondary-hover: #0f172a; --bg-color: #f8fafc; --surface: #ffffff; --surface-hover: #f1f5f9; --text-main: #0f172a; --text-muted: #64748b; --text-light: #94a3b8; --border: #e2e8f0; --border-hover: #cbd5e1; --good: #10b981; --good-soft: #d1fae5; --warn: #f59e0b; --warn-soft: #fef3c7; --bad: #ef4444; --bad-soft: #fee2e2; --info: #3b82f6; --info-soft: #dbeafe; --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05); --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05); --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.05), 0 4px 6px -4px rgb(0 0 0 / 0.05); --shadow-hover: 0 20px 25px -5px rgb(0 0 0 / 0.08), 0 8px 10px -6px rgb(0 0 0 / 0.08); --radius-sm: 8px; --radius-md: 12px; --radius-lg: 16px; --radius-full: 9999px; --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
body.dark-mode { --bg-color: #0f172a; --surface: #1e293b; --surface-hover: #334155; --text-main: #f8fafc; --text-muted: #94a3b8; --border: #334155; --border-hover: #475569; --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3); --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4); --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.5); --shadow-hover: 0 20px 25px -5px rgb(0 0 0 / 0.6); --good-soft: rgba(16, 185, 129, 0.15); --warn-soft: rgba(245, 158, 11, 0.15); --bad-soft: rgba(239, 68, 68, 0.15); --info-soft: rgba(59, 130, 246, 0.15); }
* { margin: 0; padding: 0; box-sizing: border-box; } html { scroll-behavior: smooth; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background: var(--bg-color); color: var(--text-main); line-height: 1.6; transition: background-color 0.3s ease, color 0.3s ease; font-size: 15px; }
h1, h2, h3, h4, h5, .brand-logo { font-family: 'Space Grotesk', system-ui, sans-serif; letter-spacing: -0.03em; color: var(--text-main); }
a { text-decoration: none; color: inherit; transition: var(--transition); } p { color: var(--text-muted); } img { max-width: 100%; display: block; border-radius: var(--radius-sm); }
:focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; border-radius: 4px; } .container { width: 92%; max-width: 1200px; margin: auto; }

/* NAVBAR */
.navbar { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); padding: 16px 0; position: sticky; top: 0; z-index: 1000; transition: var(--transition); }
body.dark-mode .navbar { background: rgba(30, 41, 59, 0.85); } .navbar .container { display: flex; justify-content: space-between; align-items: center; }
.brand-logo { font-size: 22px; font-weight: 700; display: flex; align-items: center; gap: 10px; } .brand-logo .mark { width: 36px; height: 36px; border-radius: var(--radius-sm); background: linear-gradient(135deg, var(--primary), var(--primary-hover)); display: inline-flex; align-items: center; justify-content: center; font-size: 18px; color: white; box-shadow: var(--shadow-sm); }
.nav-links { display: flex; gap: 12px; align-items: center; } .nav-links a:not(.btn) { font-weight: 500; font-size: 14px; padding: 8px 16px; border-radius: var(--radius-sm); color: var(--text-muted); } .nav-links a:not(.btn):hover { background: var(--surface); color: var(--text-main); box-shadow: var(--shadow-sm); }
.menu-toggle { display: none; cursor: pointer; font-size: 24px; color: var(--text-main); background: transparent; border: none; }

/* BUTTONS */
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 20px; border: 1px solid transparent; border-radius: var(--radius-md); cursor: pointer; background: var(--primary); color: #ffffff; font-weight: 600; font-family: 'Inter', sans-serif; font-size: 14px; transition: var(--transition); box-shadow: var(--shadow-sm); }
.btn:hover { background: var(--primary-hover); transform: translateY(-1px); box-shadow: var(--shadow-md); color: white; } .btn:active { transform: translateY(0); box-shadow: var(--shadow-sm); }
.btn-blue { background: var(--secondary); } .btn-blue:hover { background: var(--secondary-hover); } .btn-red { background: var(--bad); } .btn-red:hover { background: #dc2626; }
.btn-outline { background: transparent; border-color: var(--border); color: var(--text-main); box-shadow: none; } .btn-outline:hover { border-color: var(--primary); color: var(--primary-hover); background: var(--warn-soft); }

/* SECTIONS & CARDS */
.section { padding: 80px 0; } .section-title { max-width: 600px; margin-bottom: 48px; } .section-title h2 { font-size: 32px; font-weight: 800; margin-bottom: 12px; } .section-title p { color: var(--text-muted); font-size: 16px; } .section-title.center { margin: 0 auto 48px; text-align: center; }
.grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
.card { background: var(--surface); padding: 32px; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm); transition: var(--transition); position: relative; }
.card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); border-color: var(--border-hover); } .card h3 { margin-bottom: 12px; font-size: 18px; font-weight: 700; } .card p { font-size: 14.5px; }
.icon { font-size: 24px; margin-bottom: 20px; display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: var(--warn-soft); border-radius: var(--radius-md); color: var(--warn); }
.icon.purple { background: var(--info-soft); color: var(--info); } .icon.emerald { background: var(--good-soft); color: var(--good); } .icon.blue { background: var(--info-soft); color: var(--info); } .icon.rose { background: var(--bad-soft); color: var(--bad); }

/* FORMS */
.form-container { max-width: 560px; margin: 60px auto; background: var(--surface); padding: 48px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--border); }
.form-container h2 { margin-bottom: 8px; font-size: 28px; } .form-group { margin-bottom: 24px; } .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 13.5px; color: var(--text-main); }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 16px; background: var(--bg-color); border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 14px; color: var(--text-main); transition: var(--transition); font-family: 'Inter', sans-serif; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; background: var(--surface); border-color: var(--primary); box-shadow: 0 0 0 4px var(--warn-soft); }

/* BADGES & TABLES */
.badge { padding: 4px 10px; border-radius: var(--radius-full); font-size: 12px; font-weight: 600; letter-spacing: 0.02em; }
.match-badge { position: absolute; top: 20px; right: 20px; background: var(--good-soft); color: var(--good); font-weight: 600; font-size: 12px; padding: 4px 12px; border-radius: var(--radius-full); } .match-medium { color: var(--warn); background: var(--warn-soft); } .match-low { color: var(--bad); background: var(--bad-soft); }
.premium-badge { background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color:#fff; padding: 4px 10px; border-radius: var(--radius-sm); font-size: 11px; font-weight: 700; display: inline-block; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;}
.table-container { overflow-x: auto; background: var(--surface); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--border); margin-top:20px; } table { width: 100%; border-collapse: collapse; min-width: 600px; } th, td { padding: 16px 24px; text-align: left; font-size: 14px; border-bottom: 1px solid var(--border); color: var(--text-main); } th { background: var(--bg-color); font-weight: 600; color: var(--text-muted); font-size: 12.5px; text-transform: uppercase; letter-spacing: 0.05em; } tr:hover td { background: var(--surface-hover); } tr:last-child td { border-bottom: none; }

/* MISC UI */
.progress { width: 100%; height: 8px; background: var(--border); border-radius: var(--radius-full); overflow: hidden; margin-top: 16px; } .progress-bar { height: 100%; background: var(--primary); border-radius: var(--radius-full); transition: width 1s ease-in-out; }
.workspace-feed { display: flex; flex-direction: column; gap: 20px; max-height: 500px; overflow-y: auto; padding: 24px; background: var(--bg-color); border-radius: var(--radius-lg); border: 1px solid var(--border); margin-bottom: 24px; } .ws-msg { display: flex; } .ws-msg.own { justify-content: flex-end; } .ws-msg-box { max-width: 75%; background: var(--surface); padding: 16px 20px; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm); } .ws-msg.own .ws-msg-box { background: var(--secondary); color: white; border: none; } .ws-meta { font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; } .ws-msg.own .ws-meta { color: var(--text-light); }
.file-attachment { display: inline-flex; align-items: center; gap: 8px; margin-top: 12px; padding: 10px 16px; background: var(--bg-color); border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 13px; font-weight: 600; color: var(--text-main); transition: var(--transition);} .file-attachment:hover { border-color: var(--primary); color: var(--primary); } .ws-msg.own .file-attachment { background: rgba(255,255,255,0.1); border-color: transparent; color: white; }
.ai-loader { display: none; text-align: center; padding: 60px 20px; } .spinner { width: 48px; height: 48px; border: 4px solid var(--border); border-top-color: var(--primary); border-right-color: var(--info); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 24px; } @keyframes spin { to { transform: rotate(360deg); } }
.timeline { position: relative; max-width: 700px; margin: 20px auto 40px; padding: 10px 0; } .timeline::before { content: ''; position: absolute; top: 0; bottom: 0; left: 24px; width: 2px; background: var(--border); } .timeline-item { position: relative; margin-bottom: 32px; padding-left: 64px; } .timeline-marker { position: absolute; left: 15px; top: 4px; width: 20px; height: 20px; border-radius: 50%; background: var(--surface); border: 3px solid var(--border); z-index: 1; transition: var(--transition); } .timeline-item.completed .timeline-marker { border-color: var(--good); background: var(--good); } .timeline-item.in_progress .timeline-marker { border-color: var(--primary); background: var(--surface); box-shadow: 0 0 0 4px var(--warn-soft); animation: pulse 2s infinite; } @keyframes pulse { 0% { box-shadow: 0 0 0 0 var(--warn-soft); } 70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); } 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); } } .timeline-content { background: var(--surface); padding: 24px; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm); transition:var(--transition);} .timeline-item.completed .timeline-content { border-left: 4px solid var(--good); } .timeline-item.in_progress .timeline-content { border-left: 4px solid var(--primary); }
.toast { padding: 14px 20px; border-radius: var(--radius-md); color: white; font-weight: 500; box-shadow: var(--shadow-lg); animation: slideInRight 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; display: flex; align-items: center; gap: 10px; font-size: 14px; position:fixed; bottom:20px; right:20px; z-index:9999;} .toast-success { background: var(--secondary); border-left: 4px solid var(--good); } .toast-error { background: var(--secondary); border-left: 4px solid var(--bad); }
@keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } } @keyframes fadeOut { to { opacity: 0; visibility: hidden; } }
.health-box { text-align: center; padding: 24px; border: 1px solid var(--border); border-radius: var(--radius-lg); background: var(--surface); }

/* MOBILE RESPONSIVE */
@media(max-width: 860px) {
    .menu-toggle { display: block; }
    .nav-links { display: flex; flex-direction: column; align-items: stretch; position: absolute; top: 100%; left: 0; width: 100%; background: var(--surface); padding: 0; border-top: 1px solid var(--border); max-height: 0; overflow: hidden; transition: max-height 0.35s ease-out; box-shadow: var(--shadow-md); }
    body.dark-mode .nav-links { background: var(--secondary); }
    .nav-links.active { max-height: 420px; padding: 10px 0; }
    .nav-links a:not(.btn) { width: 100%; padding: 14px 20px; text-align: left; border-radius: 0; }
    .nav-links .btn { width: calc(100% - 40px); margin: 8px 20px; }
    .theme-switch { align-self: flex-start; margin: 10px 20px; }
    .form-container { width: 94%; padding: 30px 20px; margin: 30px auto; }
}
.theme-switch { cursor: pointer; padding: 8px; border-radius: var(--radius-full); background: var(--bg-color); color: var(--text-main); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 16px; transition: var(--transition); } .theme-switch:hover { border-color: var(--primary); color: var(--primary); }

.hero { position: relative; padding: 100px 20px 80px; text-align: center; background: radial-gradient(circle at top, var(--surface) 0%, var(--bg-color) 100%); border-bottom: 1px solid var(--border); }
.hero h1 { font-size: 56px; font-weight: 800; margin-bottom: 24px; line-height: 1.1; color: var(--text-main); } .hero h1 em { font-style: normal; color: var(--primary); }
.hero p { font-size: 18px; max-width: 650px; margin: 0 auto 40px; color: var(--text-muted); font-weight: 400; }
.stat-strip { background: var(--surface); border-bottom: 1px solid var(--border); } .stat-strip .container { display: grid; grid-template-columns: repeat(4, 1fr); }
.stat-item { text-align: center; padding: 30px 10px; border-left: 1px solid var(--border); } .stat-item:first-child { border-left: none; }
.stat-item .num { font-family: 'Space Grotesk', sans-serif; font-size: 32px; font-weight: 700; color: var(--primary); } .stat-item .label { font-size: 14px; font-weight: 500; color: var(--text-muted); margin-top: 6px; }

</style>
<script>
    function initTheme() { 
        if(localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode'); 
        }
    }
    
    function toggleTheme() { 
        document.body.classList.toggle('dark-mode'); 
        localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light'); 
    }
    
    // Wait for the DOM to load before applying the theme to the body
    document.addEventListener('DOMContentLoaded', initTheme);
    
    function runAiSimulation(e, formId) { 
        e.preventDefault(); 
        document.getElementById(formId).parentElement.style.display = 'none'; 
        document.querySelectorAll('.ai-loader').forEach(l => l.style.display = 'block'); 
        setTimeout(() => { document.getElementById(formId).submit(); }, 1600); 
    }
</script>
</head>
<body>

<!-- TOAST SYSTEM -->
<div class="toast-container" id="toastBox">
    <?php if (isset($_SESSION["success"])): ?>
        <div class="toast toast-success" role="status" style="animation: slideInRight 0.4s ease-out forwards, fadeOut 0.5s ease-out 4s forwards;">✅ <?= e($_SESSION["success"]) ?></div>
        <?php unset($_SESSION["success"]); endif; ?>
    <?php if (isset($_SESSION["error"])): ?>
        <div class="toast toast-error" role="alert" style="animation: slideInRight 0.4s ease-out forwards, fadeOut 0.5s ease-out 5s forwards;">⚠️ <?= e($_SESSION["error"]) ?></div>
        <?php unset($_SESSION["error"]); endif; ?>
</div>

<nav class="navbar">
    <div class="container">
        <a href="index.php" class="brand-logo"><span class="mark">🏗️</span>BuildLink</a>
        <button class="menu-toggle" onclick="document.querySelector('.nav-links').classList.toggle('active')" aria-label="Toggle menu">☰</button>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <?php if (!$user): ?>
                <a href="index.php?page=login">Log in</a>
                <a href="index.php?page=register" class="btn">Get started</a>
            <?php else: ?>
                <a href="index.php?page=dashboard">Dashboard</a>
                <?php if ($user["role"] === "client"): ?>
                    <a href="index.php?page=professionals">Network</a>
                <?php endif; ?>
                <a href="index.php?action=logout">Log out</a>
            <?php endif; ?>
            <button class="theme-switch" onclick="toggleTheme()" title="Toggle dark mode" aria-label="Toggle dark mode">🌓</button>
        </div>
    </div>
</nav>