<style>
/* =========================================================
   BUILDLINK MODERN HOMEPAGE
   ========================================================= */

:root {
    --bl-dark: #0b1220;
    --bl-dark-2: #111c2f;
    --bl-orange: #f59e0b;
    --bl-orange-light: #fbbf24;
    --bl-blue: #2563eb;
    --bl-text: #0f172a;
    --bl-muted: #64748b;
    --bl-light: #f8fafc;
    --bl-border: #e2e8f0;
    --bl-white: #ffffff;
}

/* =========================================================
   HERO
   ========================================================= */

.home-hero {
    position: relative;
    min-height: 680px;
    
    background-image:
        linear-gradient(
            90deg,
            rgba(5, 12, 24, 0.82) 0%,
            rgba(5, 12, 24, 0.60) 55%,
            rgba(5, 12, 24, 0.45) 100%
        ),
        url("assets/images/construction-bg.jpg");

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;

    display: flex;
    align-items: center;
}


/* Grid background */
.home-hero::before {
    content: "";
    position: absolute;
    inset: 0;

    background:
        radial-gradient(
            circle at 15% 30%,
            rgba(245, 158, 11, 0.20),
            transparent 35%
        ),
        linear-gradient(
            180deg,
            rgba(0,0,0,0.10),
            rgba(0,0,0,0.35)
        );

    z-index: 1;
    pointer-events: none;
}

.hero-content {
    max-width: 750px;
    margin-left: 8%;
    position: relative;
    z-index: 2;
}

/* Orange glow */
.home-hero::after {
    content: "";
    position: absolute;
    width: 500px;
    height: 500px;
    right: -180px;
    top: -150px;
    border-radius: 50%;
    background: rgba(245,158,11,.12);
    filter: blur(80px);
    pointer-events: none;
}

.hero-content {
    position: relative;
    z-index: 3;
    max-width: 1180px;
    margin: auto;
    display: grid;
    grid-template-columns: 1.15fr .85fr;
    gap: 60px;
    align-items: center;
}

.hero-left {
    max-width: 720px;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 9px 16px;
    margin-bottom: 24px;
    border-radius: 999px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12);
    color: #fbbf24;
    font-size: .82rem;
    font-weight: 700;
    letter-spacing: .04em;
    backdrop-filter: blur(10px);
}

.hero-badge .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22c55e;
    box-shadow: 0 0 12px rgba(34,197,94,.8);
}

.home-hero h1 {
    margin: 0 0 24px;
    font-size: clamp(2.8rem, 6vw, 5.2rem);
    line-height: 1.02;
    letter-spacing: -.045em;
    font-weight: 850;
}

.text-gradient {
    background: linear-gradient(
        90deg,
        #f59e0b,
        #fbbf24,
        #ec4899
    );
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-description {
    max-width: 650px;
    margin-bottom: 34px;
    color: #a9b7ca;
    font-size: 1.08rem;
    line-height: 1.75;
}

.hero-actions {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    align-items: center;
}

.btn-primary-modern {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 15px 25px;
    border-radius: 12px;
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
    color: #111827 !important;
    text-decoration: none;
    font-weight: 800;
    border: 0;
    box-shadow: 0 12px 30px rgba(245,158,11,.28);
    transition: .25s ease;
}

.btn-primary-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 35px rgba(245,158,11,.4);
}

.btn-secondary-modern {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 15px 25px;
    border-radius: 12px;
    color: white !important;
    text-decoration: none;
    font-weight: 700;
    border: 1px solid rgba(255,255,255,.18);
    background: rgba(255,255,255,.06);
    backdrop-filter: blur(10px);
    transition: .25s ease;
}

.btn-secondary-modern:hover {
    background: rgba(255,255,255,.12);
    transform: translateY(-3px);
}

/* Trust row */

.hero-trust {
    display: flex;
    flex-wrap: wrap;
    gap: 22px;
    margin-top: 32px;
    color: #94a3b8;
    font-size: .86rem;
}

.hero-trust span {
    display: flex;
    align-items: center;
    gap: 7px;
}

.hero-trust strong {
    color: #e2e8f0;
}

/* =========================================================
   HERO VISUAL
   ========================================================= */

.hero-visual {
    position: relative;
    min-height: 390px;
}

.dashboard-preview {
    position: relative;
    width: 100%;
    max-width: 480px;
    margin: auto;
    padding: 20px;
    border-radius: 24px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.14);
    box-shadow:
        0 30px 80px rgba(0,0,0,.35),
        inset 0 1px rgba(255,255,255,.12);
    backdrop-filter: blur(20px);
    transform: rotate(2deg);
    transition: .4s ease;
}

.dashboard-preview:hover {
    transform: rotate(0deg) translateY(-8px);
}

.preview-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
}

.preview-title {
    font-weight: 700;
    font-size: .9rem;
}

.preview-status {
    padding: 6px 10px;
    border-radius: 999px;
    background: rgba(34,197,94,.14);
    color: #4ade80;
    font-size: .7rem;
    font-weight: 700;
}

.preview-main {
    background: #ffffff;
    color: #0f172a;
    border-radius: 18px;
    padding: 22px;
}

.project-label {
    font-size: .72rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .08em;
    font-weight: 700;
}

.project-name {
    margin: 5px 0 20px;
    font-size: 1.2rem;
    font-weight: 800;
}

.progress-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: .75rem;
    font-weight: 700;
}

.progress-bar {
    width: 100%;
    height: 9px;
    border-radius: 999px;
    background: #e2e8f0;
    overflow: hidden;
}

.progress-value {
    width: 68%;
    height: 100%;
    border-radius: inherit;
    background: linear-gradient(90deg,#f59e0b,#ec4899);
}

.preview-grid {
    display: grid;
    grid-template-columns: repeat(2,1fr);
    gap: 12px;
    margin-top: 20px;
}

.preview-card {
    padding: 14px;
    border-radius: 13px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.preview-card small {
    display: block;
    color: #64748b;
    font-size: .7rem;
    margin-bottom: 4px;
}

.preview-card strong {
    font-size: .95rem;
}

.floating-card {
    position: absolute;
    padding: 14px 17px;
    border-radius: 14px;
    background: white;
    color: #0f172a;
    box-shadow: 0 15px 35px rgba(0,0,0,.25);
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: .78rem;
    font-weight: 700;
}

.floating-card .float-icon {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #fef3c7;
}

.float-one {
    top: 5%;
    right: -5%;
}

.float-two {
    bottom: 5%;
    left: -8%;
}

.float-green .float-icon {
    background: #dcfce7;
}

/* =========================================================
   STATS
   ========================================================= */

.stats-section {
    position: relative;
    z-index: 10;
    margin-top: -55px;
    padding: 0 20px;
}

.stats-wrapper {
    max-width: 1120px;
    margin: auto;
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 1px;
    overflow: hidden;
    border-radius: 22px;
    background: #e2e8f0;
    box-shadow: 0 25px 50px rgba(15,23,42,.10);
}

.stat-card {
    padding: 28px 20px;
    text-align: center;
    background: rgba(255,255,255,.96);
    transition: .25s ease;
}

.stat-card:hover {
    background: #fff;
    transform: translateY(-3px);
}

.stat-number {
    display: block;
    margin-bottom: 5px;
    color: #0f172a;
    font-size: 2rem;
    font-weight: 850;
}

.stat-label {
    color: #64748b;
    font-size: .74rem;
    text-transform: uppercase;
    letter-spacing: .06em;
    font-weight: 700;
}

/* =========================================================
   GENERAL SECTIONS
   ========================================================= */

.section {
    padding: 100px 20px;
}

.section-light {
    background: #f8fafc;
}

.section-container {
    max-width: 1120px;
    margin: auto;
}

.section-heading {
    max-width: 720px;
    margin: 0 auto 55px;
    text-align: center;
}

.section-kicker {
    display: inline-block;
    margin-bottom: 12px;
    color: #d97706;
    font-size: .78rem;
    font-weight: 800;
    letter-spacing: .1em;
    text-transform: uppercase;
}

.section-heading h2 {
    margin: 0 0 15px;
    color: #0f172a;
    font-size: clamp(2rem,4vw,3rem);
    line-height: 1.15;
    font-weight: 850;
    letter-spacing: -.03em;
}

.section-heading p {
    margin: 0;
    color: #64748b;
    line-height: 1.7;
}

/* =========================================================
   FEATURES
   ========================================================= */

.feature-grid {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 22px;
}

.feature-card {
    position: relative;
    padding: 30px;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    background: white;
    overflow: hidden;
    transition: .3s ease;
}

.feature-card::after {
    content: "";
    position: absolute;
    width: 120px;
    height: 120px;
    right: -60px;
    bottom: -60px;
    border-radius: 50%;
    background: #f8fafc;
    transition: .3s ease;
}

.feature-card:hover {
    transform: translateY(-7px);
    border-color: #cbd5e1;
    box-shadow: 0 20px 40px rgba(15,23,42,.09);
}

.feature-card:hover::after {
    transform: scale(1.5);
}

.feature-icon {
    position: relative;
    z-index: 2;
    width: 54px;
    height: 54px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 22px;
    border-radius: 15px;
    font-size: 24px;
}

.feature-card h3 {
    position: relative;
    z-index: 2;
    margin: 0 0 10px;
    color: #0f172a;
    font-size: 1.15rem;
}

.feature-card p {
    position: relative;
    z-index: 2;
    margin: 0;
    color: #64748b;
    font-size: .9rem;
    line-height: 1.65;
}

.icon-purple { background:#f3e8ff; }
.icon-green { background:#dcfce7; }
.icon-blue { background:#dbeafe; }
.icon-rose { background:#ffe4e6; }
.icon-yellow { background:#fef3c7; }
.icon-indigo { background:#e0e7ff; }

/* =========================================================
   HOW IT WORKS
   ========================================================= */

.steps-grid {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 25px;
    position: relative;
}

.step-card {
    text-align: center;
    position: relative;
}

.step-number {
    width: 54px;
    height: 54px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    border-radius: 50%;
    background: #0f172a;
    color: #fbbf24;
    font-weight: 800;
    box-shadow: 0 8px 20px rgba(15,23,42,.15);
}

.step-card h3 {
    margin: 0 0 9px;
    font-size: 1rem;
    color: #0f172a;
}

.step-card p {
    margin: 0;
    color: #64748b;
    font-size: .88rem;
    line-height: 1.6;
}

/* =========================================================
   TRUST SECTION
   ========================================================= */

.trust-box {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    align-items: center;
    padding: 55px;
    border-radius: 28px;
    background: linear-gradient(135deg,#0f172a,#1e293b);
    overflow: hidden;
    position: relative;
}

.trust-box h2 {
    color: white;
    margin: 0 0 15px;
    font-size: 2.2rem;
}

.trust-box p {
    color: #94a3b8;
    line-height: 1.7;
}

.trust-list {
    display: grid;
    gap: 14px;
}

.trust-item {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 15px;
    border-radius: 14px;
    background: rgba(255,255,255,.06);
    color: #e2e8f0;
    font-size: .9rem;
    border: 1px solid rgba(255,255,255,.08);
}

.trust-check {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(34,197,94,.15);
    color: #4ade80;
}

/* =========================================================
   CTA
   ========================================================= */

.final-cta {
    padding: 100px 20px;
    text-align: center;
    background:
        radial-gradient(circle at center,rgba(245,158,11,.12),transparent 35%),
        #fff;
}

.final-cta h2 {
    max-width: 700px;
    margin: auto auto 16px;
    color: #0f172a;
    font-size: clamp(2rem,4vw,3.2rem);
    font-weight: 850;
    letter-spacing: -.03em;
}

.final-cta p {
    max-width: 620px;
    margin: 0 auto 30px;
    color: #64748b;
    line-height: 1.7;
}

/* Remove BuildLink Project preview from homepage */
.hero-visual,
.dashboard-preview,
.dashboard-card,
.project-preview {
    display: none !important;
}

/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 900px) {

    .hero-content {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .hero-left {
        margin: auto;
    }

    .hero-description {
        margin-left: auto;
        margin-right: auto;
    }

    .hero-actions,
    .hero-trust {
        justify-content: center;
    }

    .hero-visual {
        min-height: 350px;
    }

    .feature-grid {
        grid-template-columns: repeat(2,1fr);
    }

    .steps-grid {
        grid-template-columns: repeat(2,1fr);
        gap: 40px 25px;
    }

    .trust-box {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 650px) {

    .home-hero {
        padding: 70px 18px 100px;
        min-height: auto;
    }

    .home-hero h1 {
        font-size: 2.65rem;
    }

    .hero-description {
        font-size: .98rem;
    }

    .hero-actions {
        flex-direction: column;
        width: 100%;
    }

    .btn-primary-modern,
    .btn-secondary-modern {
        width: 100%;
    }

    .hero-visual {
        min-height: 300px;
    }

    .dashboard-preview {
        transform: none;
    }

    .floating-card {
        display: none;
    }

    .stats-wrapper {
        grid-template-columns: repeat(2,1fr);
    }

    .stat-card {
        padding: 22px 10px;
    }

    .stat-number {
        font-size: 1.7rem;
    }

    .section {
        padding: 75px 18px;
    }

    .feature-grid {
        grid-template-columns: 1fr;
    }

    .steps-grid {
        grid-template-columns: 1fr;
    }

    .trust-box {
        padding: 32px 25px;
    }

    .trust-box h2 {
        font-size: 1.8rem;
    }
}
</style>


<!-- =========================================================
     HERO SECTION
     ========================================================= -->

<section class="home-hero">

    <div class="hero-content">

        <div class="hero-left">

            <div class="hero-badge">
                <span class="dot"></span>
                AI-Powered Construction Platform
            </div>

            <h1>
                Build smarter.<br>
                Build with
                <span class="text-gradient">confidence.</span>
            </h1>

            <p class="hero-description">
                BuildLink connects Sri Lankan homeowners and developers
                with verified architects and engineers, helps predict
                construction costs, and keeps your entire project organized
                from planning to completion.
            </p>

            <div class="hero-actions">

                <?php if (!isset($_SESSION['user_id'])): ?>

                    <a href="index.php?page=register"
                       class="btn-primary-modern">
                        🚀 Start Your Project
                    </a>

                    <a href="#features"
                       class="btn-secondary-modern">
                        Explore BuildLink →
                    </a>

                <?php else: ?>

                    <a href="index.php?page=dashboard"
                       class="btn-primary-modern">
                        📊 Open Dashboard
                    </a>

                    <a href="#features"
                       class="btn-secondary-modern">
                        Explore Tools →
                    </a>

                <?php endif; ?>

            </div>

            <div class="hero-trust">
                <span>✓ <strong>Verified Professionals</strong></span>
                <span>✓ <strong>AI Assistance</strong></span>
                <span>✓ <strong>Secure Projects</strong></span>
            </div>

        </div>


        <!-- Dashboard preview -->

        <div class="hero-visual">

            <div class="dashboard-preview">

                <div class="preview-header">
                    <div class="preview-title">
                        BuildLink Project
                    </div>

                    <div class="preview-status">
                        ● On Track
                    </div>
                </div>

                <div class="preview-main">

                    <div class="project-label">
                        Active Project
                    </div>

                    <div class="project-name">
                        Modern Family Home
                    </div>

                    <div class="progress-label">
                        <span>Project Progress</span>
                        <span>68%</span>
                    </div>

                    <div class="progress-bar">
                        <div class="progress-value"></div>
                    </div>

                    <div class="preview-grid">

                        <div class="preview-card">
                            <small>Estimated Cost</small>
                            <strong>Rs. 18.5M</strong>
                        </div>

                        <div class="preview-card">
                            <small>AI Match Score</small>
                            <strong>94%</strong>
                        </div>

                        <div class="preview-card">
                            <small>Milestones</small>
                            <strong>7 / 10</strong>
                        </div>

                        <div class="preview-card">
                            <small>Timeline</small>
                            <strong>On Schedule</strong>
                        </div>

                    </div>

                </div>

            </div>

            <div class="floating-card float-one">
                <span class="float-icon">🤖</span>
                AI Professional Match
            </div>

            <div class="floating-card float-two float-green">
                <span class="float-icon">✓</span>
                Verified Professional
            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     STATS
     ========================================================= -->

<section class="stats-section">

    <div class="stats-wrapper">

        <div class="stat-card">
            <span class="stat-number">10+</span>
            <span class="stat-label">AI-Assisted Tools</span>
        </div>

        <div class="stat-card">
            <span class="stat-number">5–8%</span>
            <span class="stat-label">Booking Commission</span>
        </div>

        <div class="stat-card">
            <span class="stat-number">3</span>
            <span class="stat-label">Professional Disciplines</span>
        </div>

        <div class="stat-card">
            <span class="stat-number">100%</span>
            <span class="stat-label">Secure Escrow</span>
        </div>

    </div>

</section>


<!-- =========================================================
     FEATURES
     ========================================================= -->

<section id="features" class="section section-light">

    <div class="section-container">

        <div class="section-heading">

            <span class="section-kicker">
                Powerful tools
            </span>

            <h2>
                Everything your construction project needs
            </h2>

            <p>
                From finding the right professional to tracking milestones,
                BuildLink brings the important parts of your construction
                journey together in one simple workspace.
            </p>

        </div>


        <div class="feature-grid">

            <div class="feature-card">

                <div class="feature-icon icon-purple">
                    🤖
                </div>

                <h3>Smart Matchmaking</h3>

                <p>
                    Find suitable architects, engineers and construction
                    professionals using project location, budget, scope
                    and compatibility factors.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon icon-green">
                    📋
                </div>

                <h3>Regulation Checker</h3>

                <p>
                    Identify important zoning, setback and planning
                    considerations before construction begins.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon icon-blue">
                    ⏱️
                </div>

                <h3>Timeline Predictor</h3>

                <p>
                    Estimate realistic project completion timelines based
                    on project size, milestones and expected conditions.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon icon-indigo">
                    ☁️
                </div>

                <h3>Digital Workspace</h3>

                <p>
                    Keep project documents, blueprints, updates and
                    communication organized in one centralized workspace.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon icon-yellow">
                    🛡️
                </div>

                <h3>Escrow Milestones</h3>

                <p>
                    Manage project payments through milestone-based
                    releases and improve transparency between clients
                    and professionals.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon icon-rose">
                    🛒
                </div>

                <h3>Material Intelligence</h3>

                <p>
                    Compare material options and supplier prices to help
                    make more informed construction purchasing decisions.
                </p>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     HOW IT WORKS
     ========================================================= -->

<section class="section">

    <div class="section-container">

        <div class="section-heading">

            <span class="section-kicker">
                Simple process
            </span>

            <h2>
                Start building in four simple steps
            </h2>

            <p>
                BuildLink simplifies the journey from an initial idea
                to a professionally managed construction project.
            </p>

        </div>


        <div class="steps-grid">

            <div class="step-card">

                <div class="step-number">01</div>

                <h3>Create Your Project</h3>

                <p>
                    Tell us about your location, land, budget,
                    requirements and preferred house style.
                </p>

            </div>


            <div class="step-card">

                <div class="step-number">02</div>

                <h3>Get AI Insights</h3>

                <p>
                    Use AI-assisted tools to analyze your project
                    and understand potential costs and requirements.
                </p>

            </div>


            <div class="step-card">

                <div class="step-number">03</div>

                <h3>Meet Professionals</h3>

                <p>
                    Discover verified professionals who match your
                    project requirements and location.
                </p>

            </div>


            <div class="step-card">

                <div class="step-number">04</div>

                <h3>Track & Build</h3>

                <p>
                    Manage milestones, documents, communication and
                    project progress from one workspace.
                </p>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     TRUST / SECURITY
     ========================================================= -->

<section class="section section-light">

    <div class="section-container">

        <div class="trust-box">

            <div>

                <span class="section-kicker">
                    Built for trust
                </span>

                <h2>
                    More transparency.<br>
                    Less construction stress.
                </h2>

                <p>
                    Construction projects involve many decisions,
                    professionals and payments. BuildLink brings these
                    elements together so clients can make informed
                    decisions while professionals can manage their
                    projects more efficiently.
                </p>

            </div>


            <div class="trust-list">

                <div class="trust-item">
                    <span class="trust-check">✓</span>
                    Verified professional profiles
                </div>

                <div class="trust-item">
                    <span class="trust-check">✓</span>
                    Transparent project milestones
                </div>

                <div class="trust-item">
                    <span class="trust-check">✓</span>
                    Centralized project documents
                </div>

                <div class="trust-item">
                    <span class="trust-check">✓</span>
                    AI-assisted decision support
                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     FINAL CTA
     ========================================================= -->

<section class="final-cta">

    <h2>
        Your construction journey starts here.
    </h2>

    <p>
        Stop managing your project through scattered messages,
        spreadsheets and guesswork. Bring everything together
        with BuildLink.
    </p>

    <?php if (!isset($_SESSION['user_id'])): ?>

        <a href="index.php?page=register"
           class="btn-primary-modern">
            🚀 Create Your Free Account
        </a>

    <?php else: ?>

        <a href="index.php?page=dashboard"
           class="btn-primary-modern">
            📊 Go to Dashboard
        </a>

    <?php endif; ?>

</section>