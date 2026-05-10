<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Explore COD Returns Lanka services including confirmation calls, risk filtering, delivery coordination, returns support, reporting, and Tamil support.">
<meta name="theme-color" content="#7c5cff">
<title>Our Services | COD Returns Lanka</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: radial-gradient(circle at top, #111 0%, #000 100%);
    color: #fff;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: auto;
}

.navbar {
    position: sticky;
    top: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;
    padding: 14px 20px;
    margin-top: 18px;
    border-radius: 18px;
    background: rgba(12, 12, 12, 0.72);
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 18px 50px rgba(0, 0, 0, 0.28);
    backdrop-filter: blur(18px);
    z-index: 1100;
}

.nav-brand {
    display: flex;
    align-items: center;
    gap: 14px;
    color: #fff;
    text-decoration: none;
    min-width: 0;
}

.nav-brand-logo {
    width: 50px;
    height: 50px;
    object-fit: contain;
    background: rgba(255,255,255,0.04);
    padding: 4px;
    border-radius: 14px;
    border: 1px solid rgba(255,255,255,0.1);
}

.nav-brand-copy {
    display: flex;
    flex-direction: column;
}

.nav-brand-title {
    font-size: 17px;
    font-weight: 700;
}

.nav-brand-subtitle {
    color: #9f9f9f;
    font-size: 12px;
}

.nav-menu {
    display: flex;
    align-items: center;
    gap: 18px;
    flex: 1;
}

.nav-links {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex: 1;
}

.nav-links a {
    margin: 0;
    padding: 10px 14px;
    color: #aaa;
    text-decoration: none;
    font-size: 14px;
    border-radius: 999px;
    transition: color 0.25s ease, background 0.25s ease;
}

.nav-links a:hover,
.nav-links a.is-active {
    color: #fff;
    background: rgba(255,255,255,0.07);
}

.nav-btn {
    background: linear-gradient(90deg,#7c5cff,#a855f7);
    padding: 12px 18px;
    border-radius: 12px;
    text-decoration: none;
    color: #fff;
    font-weight: 600;
    white-space: nowrap;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 14px;
}

.nav-toggle {
    display: none;
    width: 46px;
    height: 46px;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px;
    background: rgba(255,255,255,0.04);
    color: #fff;
    cursor: pointer;
}

.nav-toggle-bars {
    display: inline-flex;
    flex-direction: column;
    gap: 5px;
}

.nav-toggle-bars span {
    width: 18px;
    height: 2px;
    border-radius: 999px;
    background: currentColor;
}

.hero {
    padding: 84px 0 36px;
}

.hero-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.05fr) minmax(320px, 0.95fr);
    gap: 30px;
    align-items: stretch;
}

.hero-panel,
.service-card,
.service-detail-card,
.cta-panel {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 24px;
    box-shadow: 0 18px 50px rgba(0,0,0,0.22);
}

.hero-copy h1 {
    margin: 0 0 18px 0;
    font-size: 54px;
    line-height: 1.06;
    letter-spacing: -0.03em;
}

.hero-copy p {
    margin: 0;
    max-width: 620px;
    color: #b4b4b4;
    font-size: 17px;
    line-height: 1.75;
}

.hero-kicker {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 13px;
    border-radius: 999px;
    margin-bottom: 18px;
    background: rgba(124, 92, 255, 0.12);
    border: 1px solid rgba(124, 92, 255, 0.22);
    color: #cbbbff;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.hero-kicker::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #25d366;
}

.hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: 28px;
}

.hero-primary-btn,
.hero-secondary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 52px;
    padding: 0 22px;
    border-radius: 14px;
    text-decoration: none;
    font-weight: 600;
}

.hero-primary-btn {
    background: linear-gradient(90deg,#7c5cff,#a855f7);
    color: #fff;
}

.hero-secondary-btn {
    color: #fff;
    border: 1px solid rgba(255,255,255,0.12);
    background: rgba(255,255,255,0.04);
}

.hero-panel {
    padding: 26px;
}

.hero-panel h2 {
    margin: 0 0 12px 0;
    font-size: 24px;
}

.hero-panel p {
    margin: 0;
    color: #b8b8b8;
    line-height: 1.7;
}

.hero-list {
    margin: 22px 0 0;
    padding: 0;
    list-style: none;
    display: grid;
    gap: 12px;
}

.hero-list li {
    padding: 14px 16px;
    border-radius: 16px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.06);
    color: #ddd;
}

.section {
    padding: 32px 0;
}

.section-head {
    max-width: 760px;
    margin: 0 auto 28px;
    text-align: center;
}

.section-head h2 {
    margin: 0 0 10px;
    font-size: 38px;
}

.section-head p {
    margin: 0;
    color: #a9a9a9;
    font-size: 16px;
    line-height: 1.8;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
}

.service-card {
    padding: 24px;
}

.service-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 54px;
    height: 54px;
    border-radius: 14px;
    margin-bottom: 16px;
    background: linear-gradient(135deg, #7c5cff, #a855f7);
    font-size: 24px;
}

.service-card h3 {
    margin: 0 0 10px;
    font-size: 20px;
}

.service-card p {
    margin: 0 0 16px;
    color: #b6b6b6;
    line-height: 1.75;
}

.service-note {
    display: inline-block;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(124, 92, 255, 0.1);
    color: #cbbbff;
    font-size: 12px;
    font-weight: 600;
}

.service-detail-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.service-detail-card {
    padding: 24px;
}

.service-detail-card h3 {
    margin: 0 0 12px;
    font-size: 22px;
}

.service-detail-card ul {
    margin: 0;
    padding-left: 18px;
    color: #b5b5b5;
    line-height: 1.9;
}

.cta-panel {
    padding: 32px;
    text-align: center;
}

.cta-panel h2 {
    margin: 0 0 12px;
    font-size: 34px;
}

.cta-panel p {
    margin: 0 auto;
    max-width: 640px;
    color: #b5b5b5;
    line-height: 1.8;
}

.cta-actions {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: 24px;
}

.footer {
    margin-top: 70px;
    padding: 24px 0 40px;
    color: #8e8e8e;
    text-align: center;
    font-size: 13px;
}

@media (max-width: 1024px) {
    .hero-layout,
    .services-grid,
    .service-detail-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 768px) {
    .container {
        width: 95%;
    }

    .navbar {
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px;
        top: 10px;
    }

    .nav-brand {
        flex: 1;
    }

    .nav-brand-subtitle {
        display: none;
    }

    .nav-actions {
        margin-left: auto;
    }

    .nav-toggle {
        display: inline-flex;
    }

    .nav-menu {
        width: 100%;
        display: none;
        flex-direction: column;
        gap: 14px;
        padding-top: 6px;
    }

    .nav-menu.is-open {
        display: flex;
    }

    .nav-links {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }

    .nav-links a,
    .nav-btn {
        width: 100%;
        box-sizing: border-box;
        text-align: center;
    }

    .hero {
        padding: 54px 0 24px;
    }

    .hero-layout,
    .services-grid,
    .service-detail-grid {
        grid-template-columns: 1fr;
    }

    .hero-copy h1 {
        font-size: 34px;
    }

    .hero-copy p,
    .section-head p {
        font-size: 14px;
    }

    .section-head h2,
    .cta-panel h2 {
        font-size: 26px;
    }

    .hero-primary-btn,
    .hero-secondary-btn {
        width: 100%;
    }

    .hero-panel,
    .service-card,
    .service-detail-card,
    .cta-panel {
        padding: 22px 18px;
    }
}
</style>
</head>
<body>

<div class="container">
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <a href="{{ route('spin.form') }}" class="nav-brand" aria-label="COD Returns Lanka home">
            <img src="{{ asset('/img/cod.jpeg') }}" alt="COD Returns Lanka logo" class="nav-brand-logo">
            <span class="nav-brand-copy">
                <span class="nav-brand-title">COD Returns Lanka</span>
                <span class="nav-brand-subtitle">Reduce failed deliveries. Save more on every COD order.</span>
            </span>
        </a>
        <div class="nav-actions">
            <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav-menu" aria-label="Open navigation menu">
                <span class="nav-toggle-bars" aria-hidden="true">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </button>
        </div>
        <div class="nav-menu" id="mobile-nav-menu">
            <div class="nav-links">
                <a href="{{ route('spin.services') }}" class="is-active">Our Services</a>
                <a href="{{ route('spin.form') }}#how">How it Works</a>
                <a href="{{ route('spin.form') }}#clients">Clients</a>
                <a href="{{ route('spin.form') }}#pricing">Pricing</a>
            </div>
            <a href="{{ route('spin.form') }}#pricing" class="nav-btn">Start Free Trial</a>
        </div>
    </nav>

    <main>
        <section class="hero">
            <div class="hero-layout">
                <div class="hero-copy">
                <div class="hero-kicker">Our Services</div>
                    <h1>Services we provide for COD verification, delivery support, and return reduction.</h1>
                    <p>This page explains the main services we offer to help Sri Lankan eCommerce businesses manage COD orders more carefully from intake to delivery follow-up.</p>

                    <div class="hero-actions">
                        <a href="{{ route('spin.form') }}#pricing" class="hero-primary-btn">Start Free Trial</a>
                        <a href="https://wa.me/94729708209?text=Hi%20COD%20Returns%20Lanka%2C%20I%20want%20to%20learn%20more%20about%20your%20services." class="hero-secondary-btn" target="_blank" rel="noopener noreferrer">Ask on WhatsApp</a>
                    </div>
                </div>

                <aside class="hero-panel">
                    <h2>Main service scope</h2>
                    <p>Our work is focused on the operational parts of COD order handling that usually affect return rates, courier costs, and delivery quality.</p>

                    <ul class="hero-list">
                        <li>Order intake, validation, and daily upload support</li>
                        <li>Customer confirmation, address checks, and availability checks</li>
                        <li>Risk filtering, delivery coordination, and return follow-up</li>
                        <li>Weekly reporting, bilingual support, and operational updates</li>
                    </ul>
                </aside>
            </div>
        </section>

        <section class="section">
            <div class="section-head">
                <h2>Core services</h2>
                <p>These are the main services we provide for stores that want better control over COD order quality, dispatch decisions, and delivery outcomes.</p>
            </div>

            <div class="services-grid">
                <article class="service-card">
                    <div class="service-icon">📥</div>
                    <h3>Order Intake & Validation</h3>
                    <p>We accept daily order lists through Excel, CSV, or a simple operational workflow and check the information before confirmation begins.</p>
                    <span class="service-note">Daily order preparation</span>
                </article>

                <article class="service-card">
                    <div class="service-icon">☎️</div>
                    <h3>Order Confirmation Calls</h3>
                    <p>We contact customers before dispatch to confirm purchase intent, delivery readiness, and address accuracy so weak orders are filtered early.</p>
                    <span class="service-note">Pre-dispatch support</span>
                </article>

                <article class="service-card">
                    <div class="service-icon">📍</div>
                    <h3>Address & Availability Verification</h3>
                    <p>We confirm the customer can receive the parcel, is reachable, and has shared a workable delivery address before dispatch.</p>
                    <span class="service-note">Delivery readiness</span>
                </article>

                <article class="service-card">
                    <div class="service-icon">🚨</div>
                    <h3>Risk Filtering</h3>
                    <p>Orders that show suspicious or low-quality signals can be flagged before they create courier costs, return pressure, or unnecessary team follow-up.</p>
                    <span class="service-note">Loss prevention</span>
                </article>

                <article class="service-card">
                    <div class="service-icon">🚚</div>
                    <h3>Delivery Coordination</h3>
                    <p>We help bridge communication between courier and customer during the delivery stage, improving the chance of a successful handover.</p>
                    <span class="service-note">Execution support</span>
                </article>

                <article class="service-card">
                    <div class="service-icon">🔄</div>
                    <h3>Re-delivery & Return Follow-up</h3>
                    <p>When deliveries fail, we help review the reason, support re-delivery attempts where possible, and improve visibility into returned orders.</p>
                    <span class="service-note">Recovery support</span>
                </article>

                <article class="service-card">
                    <div class="service-icon">📦</div>
                    <h3>Courier & Status Updates</h3>
                    <p>We keep operational communication clearer by following delivery status changes and helping your team stay informed on active COD cases.</p>
                    <span class="service-note">Workflow visibility</span>
                </article>

                <article class="service-card">
                    <div class="service-icon">📊</div>
                    <h3>Reporting & Insights</h3>
                    <p>Weekly reporting gives you a clearer picture of return trends, order quality, and how much revenue is being protected through better operations.</p>
                    <span class="service-note">Performance tracking</span>
                </article>

                <article class="service-card">
                    <div class="service-icon">🌐</div>
                    <h3>Tamil Support Add-on</h3>
                    <p>For brands that serve wider customer segments, Tamil-language confirmation support can be added to improve communication quality.</p>
                    <span class="service-note">Optional add-on</span>
                </article>
            </div>
        </section>

        <section class="section">
            <div class="section-head">
                <h2>Additional service support</h2>
                <p>Alongside the core services above, these are the extra support areas many stores need during daily COD operations.</p>
            </div>

            <div class="service-detail-grid">
                <div class="service-detail-card">
                    <h3>Operational support services</h3>
                    <ul>
                        <li>Daily order upload handling through Excel or CSV</li>
                        <li>Simple coordination support for teams without a full in-house call desk</li>
                        <li>Follow-up communication for unclear or incomplete order details</li>
                        <li>Shared WhatsApp coordination for quick service updates</li>
                    </ul>
                </div>

                <div class="service-detail-card">
                    <h3>Performance and language support</h3>
                    <ul>
                        <li>Weekly reporting on delivery outcomes and return-related issues</li>
                        <li>Identification of recurring delivery or customer-quality problems</li>
                        <li>Tamil-language confirmation support where needed</li>
                        <li>Service guidance based on order volume and operational needs</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="cta-panel">
                <h2>Need a service plan for your store?</h2>
                <p>Send us your order volume and current COD challenges, and we can guide you on which services are most relevant for your operation.</p>

                <div class="cta-actions">
                    <a href="{{ route('spin.form') }}#pricing" class="hero-primary-btn">View Pricing</a>
                    <a href="https://wa.me/94729708209?text=Hi%20COD%20Returns%20Lanka%2C%20I%20want%20to%20discuss%20your%20services%20for%20my%20store." class="hero-secondary-btn" target="_blank" rel="noopener noreferrer">Chat on WhatsApp</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        © 2026 COD Returns Lanka. Built for Sri Lanka's eCommerce growth.
    </footer>
</div>

<script>
const navToggle = document.querySelector('.nav-toggle');
const navMenu = document.getElementById('mobile-nav-menu');

if (navToggle && navMenu) {
    navToggle.addEventListener('click', () => {
        const isOpen = navMenu.classList.toggle('is-open');
        navToggle.setAttribute('aria-expanded', String(isOpen));
        navToggle.setAttribute('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');
    });

    navMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                navMenu.classList.remove('is-open');
                navToggle.setAttribute('aria-expanded', 'false');
                navToggle.setAttribute('aria-label', 'Open navigation menu');
            }
        });
    });
}
</script>

</body>
</html>
