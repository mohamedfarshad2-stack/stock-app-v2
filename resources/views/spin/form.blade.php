<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="Reduce COD returns and save Rs 50,000+ monthly. Trusted by Sri Lankan eCommerce brands. Confirmation calls, courier coordination & returns management.">
<meta name="keywords" content="COD returns, delivery verification, eCommerce Sri Lanka">
<meta name="author" content="COD Returns Lanka">
<meta property="og:title" content="COD Returns Lanka - Reduce Returns & Save Money">
<meta property="og:description" content="Stop losing money on COD returns. Start controlling your deliveries today.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/') }}">
<meta name="theme-color" content="#7c5cff">
<title>COD Returns Lanka - Reduce COD Returns & Save Rs 50,000+</title>

<style>
body {
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background: radial-gradient(circle at top, #111 0%, #000 100%);
    color:#fff;
}

/* SKIP LINK */
.skip-link {
    position: absolute;
    top: -40px;
    left: 0;
    background: #7c5cff;
    color: white;
    padding: 8px 12px;
    text-decoration: none;
    z-index: 100;
}

.skip-link:focus {
    top: 0;
}

.container {
    width:90%;
    max-width:1200px;
    margin:auto;
}

/* NAVBAR */
.navbar {
    position: sticky;
    top: 16px;
    display:flex;
    justify-content:space-between;
    align-items:center;
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
    box-shadow: 0 12px 30px rgba(124, 92, 255, 0.22);
}

.nav-brand-copy {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.nav-brand-title {
    margin: 0;
    font-size: 17px;
    font-weight: 700;
    letter-spacing: 0.01em;
}

.nav-brand-subtitle {
    color: #9f9f9f;
    font-size: 12px;
    line-height: 1.4;
}

.nav-links {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex: 1;
}

.nav-menu {
    display: flex;
    align-items: center;
    gap: 18px;
    flex: 1;
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
    transition: background 0.25s ease, border-color 0.25s ease, transform 0.25s ease;
}

.nav-toggle:hover {
    background: rgba(255,255,255,0.08);
    border-color: rgba(124, 92, 255, 0.4);
    transform: translateY(-1px);
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

.nav-links a {
    margin: 0;
    padding: 10px 14px;
    color:#aaa;
    text-decoration:none;
    font-size: 14px;
    border-radius: 999px;
    transition: color 0.25s ease, background 0.25s ease, transform 0.25s ease;
}

.nav-links a:hover {
    color:#fff;
    background: rgba(255,255,255,0.07);
    transform: translateY(-1px);
}

.nav-btn {
    background:linear-gradient(90deg,#7c5cff,#a855f7);
    padding:12px 18px;
    border-radius:12px;
    text-decoration:none;
    color:white;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: 0 12px 28px rgba(124, 92, 255, 0.28);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.nav-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 34px rgba(124, 92, 255, 0.36);
}

/* HERO */
.hero {
    position: relative;
    padding: 88px 0 72px;
}

.hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 15% 20%, rgba(124, 92, 255, 0.22), transparent 32%),
        radial-gradient(circle at 85% 15%, rgba(37, 211, 102, 0.14), transparent 24%);
    pointer-events: none;
}

.hero-layout {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
    gap: 36px;
    align-items: stretch;
}

.hero-content {
    text-align: left;
    max-width: 680px;
}

.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    margin-bottom: 18px;
    border-radius: 999px;
    background: rgba(124, 92, 255, 0.1);
    border: 1px solid rgba(124, 92, 255, 0.24);
    color: #cbbbff;
    font-size: 13px;
    font-weight: 600;
}

.hero-eyebrow::before {
    content: '';
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #25d366;
    box-shadow: 0 0 0 6px rgba(37, 211, 102, 0.14);
}

.hero h1 {
    font-size: 54px;
    line-height: 1.08;
    letter-spacing: -0.03em;
    margin: 0 0 18px 0;
}

.hero-title-accent {
    background: linear-gradient(90deg,#7c5cff,#a855f7);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.hero p {
    color:#b4b4b4;
    margin:0;
}

.hero-lead {
    max-width: 620px;
    font-size: 17px;
    line-height: 1.75;
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
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.hero-primary-btn {
    background: linear-gradient(90deg,#7c5cff,#a855f7);
    color: #fff;
    box-shadow: 0 14px 34px rgba(124, 92, 255, 0.28);
}

.hero-primary-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 18px 40px rgba(124, 92, 255, 0.36);
}

.hero-secondary-btn {
    color: #fff;
    border: 1px solid rgba(255,255,255,0.12);
    background: rgba(255,255,255,0.04);
}

.hero-secondary-btn:hover {
    transform: translateY(-2px);
    border-color: rgba(37, 211, 102, 0.35);
}

.hero-trust-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 24px;
}

.hero-trust-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    border-radius: 999px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    color: #d0d0d0;
    font-size: 13px;
}

.hero-trust-chip strong {
    color: #fff;
}

.hero-panel {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 18px;
    padding: 26px;
    border-radius: 24px;
    background: linear-gradient(180deg, rgba(255,255,255,0.06) 0%, rgba(255,255,255,0.03) 100%);
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 18px 50px rgba(0,0,0,0.25);
}

.hero-panel-label {
    display: inline-flex;
    width: fit-content;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(37, 211, 102, 0.12);
    color: #7ff0a7;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.hero-panel h3 {
    margin: 0;
    font-size: 24px;
    line-height: 1.3;
}

.hero-panel p {
    color: #b9b9b9;
    font-size: 14px;
    line-height: 1.7;
}

.hero-stats {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
}

.hero-stat {
    padding: 18px;
    border-radius: 18px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
}

.hero-stat-value {
    display: block;
    margin-bottom: 6px;
    font-size: 28px;
    font-weight: 700;
    color: #fff;
}

.hero-stat-label {
    display: block;
    color: #a5a5a5;
    font-size: 13px;
    line-height: 1.5;
}

.hero-proof {
    padding-top: 4px;
    border-top: 1px solid rgba(255,255,255,0.08);
}

.hero-proof-title {
    display: block;
    margin-bottom: 8px;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
}

.hero-proof p {
    margin: 0;
    font-size: 13px;
}

/* SECTION */
.section {
    padding:80px 0;
    text-align:center;
}

/* SERVICE STRIP */
.service-strip {
    padding: 18px 0 10px;
}

.service-strip-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
}

.service-strip-card {
    padding: 22px 22px 20px;
    border-radius: 20px;
    background: linear-gradient(180deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.025) 100%);
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.18);
    text-align: left;
}

.service-strip-title {
    margin: 0 0 8px 0;
    color: #fff;
    font-size: 17px;
    line-height: 1.35;
}

.service-strip-copy {
    margin: 0;
    color: #b9b9b9;
    font-size: 14px;
    line-height: 1.7;
}

/* HOW IT WORKS - COMPACT */
#how.section {
    padding: 60px 0;
}

/* CARDS */
.grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:25px;
    margin-top:40px;
}
.card {
    background:rgba(255,255,255,0.03);
    backdrop-filter: blur(10px);
    border-radius:15px;
    padding:25px;
    border:1px solid rgba(255,255,255,0.08);
}

/* PROCESS STEPS */
.steps-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 18px;
    margin-top: 30px;
    position: relative;
}

.step-card {
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 15px;
    padding: 25px 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.step-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #7c5cff, #a855f7);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.step-card:hover {
    background: rgba(255,255,255,0.05);
    border-color: rgba(124, 92, 255, 0.3);
    transform: translateY(-5px);
}

.step-card:hover::before {
    opacity: 1;
}

.step-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #7c5cff, #a855f7);
    border-radius: 12px;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
    color: white;
}

.step-icon {
    font-size: 32px;
    margin-bottom: 8px;
    display: block;
}

.step-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #fff;
}

.step-description {
    font-size: 12px;
    color: #aaa;
    line-height: 1.5;
    margin-bottom: 10px;
}

.step-highlight {
    display: inline-block;
    background: rgba(124, 92, 255, 0.15);
    border-left: 3px solid #7c5cff;
    padding: 8px 10px;
    border-radius: 6px;
    font-size: 11px;
    color: #7c5cff;
    font-weight: 500;
    margin-top: 8px;
}

.timeline-connector {
    display: none;
}

@media (min-width: 1024px) {
    .steps-container {
        grid-template-columns: repeat(5, 1fr);
    }

    .timeline-connector {
        display: block;
        position: absolute;
        top: 75px;
        left: calc(100% - 12px);
        width: calc(100% + 15px);
        height: 2px;
        background: linear-gradient(90deg, rgba(124, 92, 255, 0.3) 0%, transparent 100%);
        z-index: 0;
    }

    .step-card {
        position: relative;
        z-index: 1;
    }
}

.process-summary {
    margin-top: 35px;
    padding: 20px;
    background: rgba(124, 92, 255, 0.08);
    border: 1px solid rgba(124, 92, 255, 0.2);
    border-radius: 12px;
    text-align: center;
    font-size: 14px;
    color: #ddd;
    line-height: 1.6;
    font-weight: 500;
}

.process-summary strong {
    color: #7c5cff;
}

/* CLIENTS */
.client-card {
    min-height: 220px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 14px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 18px;
    padding: 28px;
}

.client-card h4 {
    margin: 0;
    font-size: 18px;
    color: #fff;
}

.client-card p {
    margin: 0;
    color: #aaa;
    line-height: 1.7;
}

.client-accent {
    display: inline-flex;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(124, 92, 255, 0.12);
    color: #7c5cff;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
}

.client-quote {
    font-size: 14px;
    color: #ddd;
    line-height: 1.8;
}

.client-copy {
    color: #bbb;
    font-size: 13px;
    margin-top: 8px;
}

/* PRICING */
.pricing-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:30px;
    margin-top:50px;
}
.price-card {
    background:rgba(255,255,255,0.03);
    border-radius:20px;
    padding:30px;
    border:1px solid rgba(255,255,255,0.08);
    transition:0.3s;
}
.price-card:hover {
    transform:translateY(-6px);
    box-shadow:0 0 40px rgba(124,92,255,0.2);
}
.popular {
    border:2px solid #7c5cff;
    transform:scale(1.05);
}
.label {
    background:linear-gradient(90deg,#7c5cff,#a855f7);
    padding:5px 12px;
    border-radius:20px;
    font-size:12px;
    display:inline-block;
    margin-bottom:10px;
}

.price {
    font-size:36px;
    margin:10px 0;
}
.sub {color:#aaa;}

ul {
    list-style:none;
    padding:0;
    margin-top:20px;
}
ul li {margin-bottom:10px;color:#ccc;}

.btn {
    width:100%;
    padding:12px;
    border-radius:10px;
    margin-top:15px;
    cursor:pointer;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn:focus {
    outline: 2px solid #7c5cff;
    outline-offset: 2px;
}

.btn:active {
    transform: translateY(0);
}

.primary {
    background:linear-gradient(90deg,#7c5cff,#a855f7);
    border:none;
    color:white;
}

.primary:hover {
    box-shadow: 0 4px 15px rgba(124, 92, 255, 0.4);
}

.outline {
    background:transparent;
    border:1px solid #555;
    color:white;
}

.outline:hover {
    border-color: #7c5cff;
    color: #7c5cff;
}
.logo-grid {
    display:grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap:22px;
    margin-top:30px;
    align-items:center;
}

.logo-section {
    padding: 30px 0 18px;
}

.logo-section-shell {
    position: relative;
    padding: 34px 28px 26px;
    border-radius: 28px;
    background:
        radial-gradient(circle at top left, rgba(124, 92, 255, 0.16), transparent 28%),
        radial-gradient(circle at bottom right, rgba(37, 211, 102, 0.10), transparent 24%),
        linear-gradient(180deg, rgba(255,255,255,0.06) 0%, rgba(255,255,255,0.03) 100%);
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.24);
}

.logo-section-head {
    max-width: 760px;
    margin: 0 auto;
    text-align: center;
}

.logo-kicker {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(124, 92, 255, 0.1);
    border: 1px solid rgba(124, 92, 255, 0.22);
    color: #cbbbff;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.logo-kicker::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #25d366;
}

.logo-section-title {
    margin: 18px 0 12px;
    font-size: 36px;
    line-height: 1.18;
}

.logo-section-copy {
    margin: 0 auto;
    max-width: 720px;
    color: #aaa;
    font-size: 16px;
    line-height: 1.75;
}

.logo-box {
    min-height: 132px;
    background: linear-gradient(180deg, #ffffff 0%, #f3f5fb 100%);
    padding: 12px 16px;
    border-radius: 18px;
    display:flex;
    justify-content:center;
    align-items:center;
    border: 1px solid rgba(255,255,255,0.18);
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.16);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.logo-box img {
    width: 100%;
    max-width: 180px;
    max-height: 82px;
    object-fit: contain;
    filter: saturate(1.05) contrast(1.02);
}

.logo-box:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 20px 36px rgba(124, 92, 255, 0.22);
}

.logo-box:hover img {
    filter: none;
    opacity:1;
}

/* ADDON */
.addon {
    margin-top:40px;
    padding:26px;
    background:rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius:20px;
    text-align: left;
}

.addon-kicker {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(37, 211, 102, 0.1);
    color: #8ef0b0;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.addon-kicker::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #25d366;
}

.addon-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 22px;
    align-items: start;
    margin-top: 16px;
}

.addon-copy h3 {
    margin: 0 0 8px;
    font-size: 26px;
}

.addon-price {
    margin: 0 0 12px;
    font-size: 18px;
    font-weight: 700;
    color: #fff;
}

.addon-copy p {
    margin: 0;
    color: #b6b6b6;
    line-height: 1.8;
}

.addon-list {
    margin: 16px 0 0;
    padding: 0;
    list-style: none;
}

.addon-list li {
    margin-bottom: 10px;
    color: #ddd;
}

.addon-side {
    padding: 18px;
    border-radius: 16px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.06);
}

.addon-side-title {
    display: block;
    margin-bottom: 10px;
    font-size: 13px;
    font-weight: 700;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.addon-chip-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.addon-chip {
    display: inline-flex;
    align-items: center;
    padding: 9px 12px;
    border-radius: 999px;
    background: rgba(124, 92, 255, 0.12);
    color: #d8d1ff;
    font-size: 12px;
    font-weight: 600;
}

.trust-contact {
    margin-top: 26px;
    padding: 26px;
    border-radius: 20px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.08);
}

.trust-contact-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 22px;
    align-items: start;
}

.trust-contact h3 {
    margin: 0 0 10px;
    font-size: 24px;
}

.trust-contact p {
    margin: 0;
    color: #b6b6b6;
    line-height: 1.8;
}

.trust-points {
    margin: 16px 0 0;
    padding: 0;
    list-style: none;
}

.trust-points li {
    margin-bottom: 10px;
    color: #ddd;
}

.contact-card {
    padding: 18px;
    border-radius: 16px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.06);
}

.contact-card strong {
    display: block;
    margin-bottom: 10px;
    color: #fff;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.contact-card a,
.contact-card span {
    display: block;
    margin-bottom: 10px;
    color: #d7d7d7;
    text-decoration: none;
    line-height: 1.7;
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
    margin-top: 28px;
}

.faq-item {
    padding: 22px;
    border-radius: 18px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.08);
    text-align: left;
}

.faq-item h3 {
    margin: 0 0 10px;
    font-size: 18px;
}

.faq-item p {
    margin: 0;
    color: #b4b4b4;
    line-height: 1.8;
}

/* FORM */
.form-group {
    margin-bottom: 15px;
    text-align: left;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    color: #ccc;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 8px;
    background: rgba(255,255,255,0.05);
    color: #fff;
    font-family: inherit;
    font-size: 14px;
    box-sizing: border-box;
    transition: 0.3s;
}

.form-group input:focus {
    outline: none;
    border-color: #7c5cff;
    box-shadow: 0 0 10px rgba(124,92,255,0.2);
}

.form-group input::placeholder {
    color: #666;
}

.form-section {
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 15px;
    padding: 30px;
    margin-top: 30px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.form-section h3 {
    margin-top: 0;
}

.error-message {
    color: #ff6b6b;
    font-size: 12px;
    margin-top: 5px;
    display: none;
}

/* FOOTER */
.footer {
    margin-top:100px;
    padding:60px 0 0;
    background: linear-gradient(180deg, #0a0a0a 0%, #050505 100%);
    border-top:1px solid rgba(124, 92, 255, 0.2);
    color: #ccc;
}

.footer-wrapper {
    padding: 60px 0 40px;
}

.footer-container {
    display:grid;
    grid-template-columns: 1.5fr 1fr 1fr 1fr 1fr;
    gap:50px;
    margin-bottom: 40px;
}

/* LEFT SIDE - BRAND */
.footer-left {
    display: flex;
    flex-direction: column;
}

.footer-logo {
    width:150px;
    margin-bottom:15px;
    filter: brightness(0.9);
}

.footer-text {
    color:#999;
    font-size:13px;
    line-height:1.8;
    margin-bottom: 20px;
}

.footer-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(124, 92, 255, 0.1);
    border: 1px solid rgba(124, 92, 255, 0.3);
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    color: #7c5cff;
    margin-bottom: 10px;
    width: fit-content;
}

.footer-badge::before {
    content: '✓';
    font-weight: bold;
}

.social-links {
    display: flex;
    gap: 12px;
    margin-top: 15px;
}

.social-link {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(124, 92, 255, 0.1);
    border: 1px solid rgba(124, 92, 255, 0.2);
    border-radius: 8px;
    color: #7c5cff;
    text-decoration: none;
    font-size: 16px;
    transition: all 0.3s;
}

.social-link:hover {
    background: rgba(124, 92, 255, 0.3);
    border-color: #7c5cff;
    transform: translateY(-2px);
}

/* FOOTER COLUMNS */
.footer-col {
    display: flex;
    flex-direction: column;
}

.footer-col h4 {
    margin: 0 0 15px 0;
    font-size:13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #fff;
    font-weight: 600;
}

.footer-col a {
    display:block;
    color:#999;
    text-decoration:none;
    margin-bottom:10px;
    font-size:13px;
    transition: all 0.2s;
}

.footer-col a:hover {
    color:#7c5cff;
    padding-left: 4px;
}

/* FOOTER DIVIDER */
.footer-divider {
    height: 1px;
    background: linear-gradient(90deg, rgba(124, 92, 255, 0) 0%, rgba(124, 92, 255, 0.3) 50%, rgba(124, 92, 255, 0) 100%);
    margin: 30px 0;
}

/* FOOTER BOTTOM */
.footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
    border-top: 1px solid rgba(255,255,255,0.05);
    color:#666;
    font-size:12px;
}

.footer-payments {
    display: flex;
    gap: 15px;
    align-items: center;
}

.payment-method {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 24px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 4px;
    font-size: 11px;
    color: #999;
}

.whatsapp-float {
    position: fixed;
    right: 20px;
    bottom: 20px;
    min-width: 60px;
    height: 60px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 0 20px;
    border-radius: 999px;
    background: linear-gradient(135deg, #25d366, #128c7e);
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.02em;
    border: 1px solid rgba(255, 255, 255, 0.18);
    box-shadow: 0 12px 30px rgba(37, 211, 102, 0.35);
    z-index: 1200;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.whatsapp-float::before {
    content: '';
    position: absolute;
    inset: -8px;
    border-radius: inherit;
    border: 2px solid rgba(37, 211, 102, 0.35);
    animation: whatsapp-pulse 2.2s ease-out infinite;
}

.whatsapp-float:hover {
    transform: translateY(-3px) scale(1.04);
    box-shadow: 0 16px 36px rgba(37, 211, 102, 0.45);
}

.whatsapp-float svg {
    width: 30px;
    height: 30px;
    fill: currentColor;
    flex-shrink: 0;
}

.whatsapp-float-label {
    white-space: nowrap;
}

@keyframes whatsapp-pulse {
    0% {
        transform: scale(0.94);
        opacity: 0.95;
    }
    70% {
        transform: scale(1.08);
        opacity: 0;
    }
    100% {
        transform: scale(1.08);
        opacity: 0;
    }
}

/* FOOTER LINKS ANIMATION */
.footer-col a::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #7c5cff, #a855f7);
    transition: width 0.3s ease;
}

.footer-col a {
    position: relative;
}

.footer-col a:hover::before {
    width: 100%;
}

/* MOBILE FIX */
@media (max-width:1024px) {
    .hero h1 {
        font-size: 36px;
    }

    .footer-right {
        gap: 40px;
    }
}

@media (max-width:768px) {
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
        width: auto;
        justify-content: flex-start;
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
        text-align: center;
    }

    .nav-links a {
        margin: 0;
        width: 100%;
        padding: 12px 14px;
        background: rgba(255,255,255,0.04);
    }

    .nav-btn {
        justify-content: center;
        width: 100%;
        text-align: center;
    }

    .nav-brand-logo {
        width: 44px;
        height: 44px;
    }

    .nav-brand-title {
        font-size: 16px;
    }

    .nav-brand-subtitle {
        font-size: 11px;
        text-align: left;
    }

    .hero {
        padding: 52px 0 40px;
    }

    .hero-layout {
        grid-template-columns: 1fr;
        gap: 22px;
    }

    .hero-content {
        text-align: center;
        max-width: none;
    }

    .hero-eyebrow {
        justify-content: center;
        margin-bottom: 14px;
    }

    .hero h1 {
        font-size: 34px;
    }

    .hero p {
        font-size: 14px;
    }

    .hero-lead {
        max-width: none;
        font-size: 15px;
        line-height: 1.7;
    }

    .hero-actions {
        justify-content: center;
        margin-top: 22px;
    }

    .hero-primary-btn,
    .hero-secondary-btn {
        width: 100%;
    }

    .hero-trust-row {
        justify-content: center;
    }

    .hero-panel {
        padding: 20px;
        border-radius: 20px;
    }

    .hero-panel h3 {
        font-size: 20px;
    }

    .hero-stats {
        grid-template-columns: 1fr;
    }

    .section {
        padding: 50px 0;
    }

    .section h2 {
        font-size: 24px;
    }

    .grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .pricing-grid {
        grid-template-columns: 1fr;
    }

    .price-card {
        padding: 20px;
    }

    .popular {
        transform: scale(1);
    }

    .addon {
        padding: 20px 18px;
    }

    .addon-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .addon-copy h3 {
        font-size: 22px;
    }

    .trust-contact {
        padding: 20px 18px;
    }

    .trust-contact-grid,
    .faq-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .logo-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }

    .logo-section {
        padding: 10px 0 0;
    }

    .logo-section-shell {
        padding: 22px 16px 18px;
        border-radius: 22px;
    }

    .logo-section-title {
        font-size: 24px;
    }

    .logo-section-copy {
        font-size: 14px;
        line-height: 1.7;
    }

    .logo-box {
        min-height: 96px;
        padding: 10px 12px;
    }

    .logo-box img {
        max-width: 130px;
        max-height: 58px;
    }

    .footer-container {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .footer-bottom {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .footer-payments {
        justify-content: center;
    }

    .btn {
        padding: 10px;
        font-size: 14px;
    }

    .service-strip {
        padding: 10px 0 0;
    }

    .service-strip-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .service-strip-card {
        padding: 18px 16px;
        border-radius: 16px;
    }

    .service-strip-title {
        font-size: 16px;
    }

    .service-strip-copy {
        font-size: 13px;
        line-height: 1.6;
    }

    #how.section {
        padding: 40px 0;
    }

    .section h2 {
        font-size: 20px;
    }

    .steps-container {
        gap: 12px;
        margin-top: 20px;
    }

    .step-card {
        padding: 18px 15px;
    }

    .step-title {
        font-size: 14px;
    }

    .step-description {
        font-size: 11px;
    }

    .process-summary {
        margin-top: 20px;
        padding: 15px;
        font-size: 12px;
    }

    .whatsapp-float {
        right: 14px;
        bottom: 14px;
        min-width: 54px;
        height: 54px;
        padding: 0;
        gap: 0;
        border-radius: 50%;
    }

    .whatsapp-float svg {
        width: 26px;
        height: 26px;
    }

    .whatsapp-float::before {
        inset: -6px;
    }

    .whatsapp-float-label {
        display: none;
    }
}
</style>
</head>

<body>

<a href="#main-content" class="skip-link">Skip to main content</a>

<div class="container">

<!-- NAVBAR -->
<nav class="navbar" role="navigation" aria-label="Main navigation">
    <a href="{{ url('/') }}" class="nav-brand" aria-label="COD Returns Lanka home">
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
            <a href="{{ route('spin.services') }}">Our Services</a>
            <a href="#how">How it Works</a>
            <a href="#clients">Clients</a>
            <a href="#pricing">Pricing</a>
        </div>
        <a href="#pricing" class="nav-btn">Start Free Trial</a>
    </div>
</nav>

<!-- MAIN CONTENT -->
<main id="main-content">

<!-- HERO -->
<section class="hero">
    <div class="hero-layout">
        <div class="hero-content">
            <div class="hero-eyebrow">Built for Sri Lankan eCommerce brands</div>
            <h1>Reduce COD Returns and <span class="hero-title-accent">protect your monthly revenue</span></h1>
            <p class="hero-lead">We help online stores confirm orders, filter risky customers, and coordinate deliveries before failed COD orders turn into courier losses.</p>

            <div class="hero-actions">
                <a href="#pricing" class="hero-primary-btn">Start Free Trial</a>
                <a href="https://wa.me/94729708209?text=Hi%20COD%20Returns%20Lanka%2C%20I%20want%20to%20learn%20how%20you%20reduce%20COD%20returns." class="hero-secondary-btn" target="_blank" rel="noopener noreferrer">Chat on WhatsApp</a>
            </div>

            <div class="hero-trust-row">
                <div class="hero-trust-chip"><strong>Fast setup</strong> Start in 24 hours</div>
                <div class="hero-trust-chip"><strong>Human support</strong> WhatsApp assistance included</div>
                <div class="hero-trust-chip"><strong>Weekly visibility</strong> Clear savings reports</div>
            </div>
        </div>

        <aside class="hero-panel" aria-label="Business impact summary">
            <span class="hero-panel-label">Performance Snapshot</span>
            <h3>Designed for brands that want fewer failed deliveries and stronger COD margins.</h3>
            <p>Every order is more valuable when your team confirms genuine buyers, reduces fake dispatches, and keeps courier costs under control.</p>

            <div class="hero-stats">
                <div class="hero-stat">
                    <span class="hero-stat-value">30%</span>
                    <span class="hero-stat-label">Potential reduction in low-quality COD dispatches</span>
                </div>
                <div class="hero-stat">
                    <span class="hero-stat-value">24h</span>
                    <span class="hero-stat-label">Typical onboarding turnaround for new merchants</span>
                </div>
                <div class="hero-stat">
                    <span class="hero-stat-value">Weekly</span>
                    <span class="hero-stat-label">Reporting rhythm for performance and savings</span>
                </div>
                <div class="hero-stat">
                    <span class="hero-stat-value">1:1</span>
                    <span class="hero-stat-label">Direct WhatsApp support for enquiries and follow-up</span>
                </div>
            </div>

            <div class="hero-proof">
                <span class="hero-proof-title">What this improves</span>
                <p>More qualified enquiries, better first impressions, and stronger confidence before customers scroll into the rest of the offer.</p>
            </div>
        </aside>
    </div>
</section>

<!-- SERVICE STRIP -->
<section class="service-strip" aria-label="Service highlights">
    <div class="service-strip-grid">
        <div class="service-strip-card">
            <h2 class="service-strip-title">Order confirmation before dispatch</h2>
            <p class="service-strip-copy">We verify customer intent and availability before your parcels leave the warehouse.</p>
        </div>
        <div class="service-strip-card">
            <h2 class="service-strip-title">Risk filtering for suspicious orders</h2>
            <p class="service-strip-copy">Low-quality and high-risk COD orders are identified early so you avoid unnecessary courier costs.</p>
        </div>
        <div class="service-strip-card">
            <h2 class="service-strip-title">Weekly reporting on savings</h2>
            <p class="service-strip-copy">Clear visibility into return reduction, delivery performance, and the revenue you protect each month.</p>
        </div>
    </div>
</section>

<section class="logo-section section" id="client-logos">
    <div class="logo-section-shell">
        <div class="logo-section-head">
            <span class="logo-kicker">Brand Trust</span>
            <h2 class="logo-section-title">Trusted by leading eCommerce brands</h2>
            <p class="logo-section-copy">
                Businesses across Sri Lanka use our workflow to reduce failed COD deliveries, improve confirmation quality, and protect monthly revenue.
            </p>
        </div>

        <div class="logo-grid">
            <div class="logo-box"><img src="{{ asset('/img/horns.jpg') }}" alt="Horns brand logo"></div>
            <div class="logo-box"><img src="{{ asset('/img/hdonline.jpeg') }}" alt="HD Online brand logo"></div>
            <div class="logo-box"><img src="{{ asset('/img/aliver.jpeg') }}" alt="Aliver brand logo"></div>
            <div class="logo-box"><img src="{{ asset('/img/blits.jpeg') }}" alt="Blits brand logo"></div>
            <div class="logo-box"><img src="{{ asset('/img/element.jpeg') }}" alt="Element brand logo"></div>
            <div class="logo-box"><img src="{{ asset('/img/spotbuy.jpeg') }}" alt="SpotBuy brand logo"></div>
            <div class="logo-box"><img src="{{ asset('/img/lxd.jpeg') }}" alt="LXD brand logo"></div>
            <div class="logo-box"><img src="{{ asset('/img/lusey.jpeg') }}" alt="Lusey brand logo"></div>
            <div class="logo-box"><img src="{{ asset('/img/ceylonmart.jpeg') }}" alt="Ceylon Mart brand logo"></div>
            <div class="logo-box"><img src="{{ asset('/img/vitamin.jpeg') }}" alt="Vitamin brand logo"></div>
            <div class="logo-box"><img src="{{ asset('/img/sh.webp') }}" alt="SH brand logo"></div>
            <div class="logo-box"><img src="{{ asset('/img/srilnakanonline.jpeg') }}" alt="Sri Lanka Online brand logo"></div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section id="how" class="section">
    <h2>How We Reduce Your COD Losses</h2>
    <p style="color: #aaa; font-size: 14px; max-width: 650px; margin: 8px auto 0;">
        Our 5-step system confirms customers, filters risk, and ensures successful delivery. Everything you need to reduce returns.
    </p>

    <div class="steps-container">
        <!-- STEP 1 -->
        <div class="step-card">
            <div class="timeline-connector"></div>
            <div class="step-number">01</div>
            <span class="step-icon">📤</span>
            <h4 class="step-title">Order Intake & Validation</h4>
            <p class="step-description">
                Upload your daily COD orders via Excel, CSV, or direct integration. We automatically clean, validate, and prepare data.
            </p>
            <div class="step-highlight">✅ Same-day processing</div>
        </div>

        <!-- STEP 2 -->
        <div class="step-card">
            <div class="timeline-connector"></div>
            <div class="step-number">02</div>
            <span class="step-icon">☎️</span>
            <h4 class="step-title">Smart Confirmation Calls</h4>
            <p class="step-description">
                We call customers BEFORE dispatch to verify address, availability, and purchase intent. Stop sending parcels to fake customers.
            </p>
            <div class="step-highlight">🎯 Reduces returns by 30%</div>
        </div>

        <!-- STEP 3 -->
        <div class="step-card">
            <div class="timeline-connector"></div>
            <div class="step-number">03</div>
            <span class="step-icon">🚨</span>
            <h4 class="step-title">Risk Filtering & Detection</h4>
            <p class="step-description">
                AI identifies high-risk orders using behavior patterns. Block fraudulent orders before wasting courier costs.
            </p>
            <div class="step-highlight">💰 Prevent fake orders</div>
        </div>

        <!-- STEP 4 -->
        <div class="step-card">
            <div class="timeline-connector"></div>
            <div class="step-number">04</div>
            <span class="step-icon">🚚</span>
            <h4 class="step-title">Delivery Coordination</h4>
            <p class="step-description">
                We follow up with both courier and customer during delivery. Ensure parcels reach destination successfully. This is what separates us.
            </p>
            <div class="step-highlight">✔️ Improve success rate</div>
        </div>

        <!-- STEP 5 -->
        <div class="step-card">
            <div class="timeline-connector"></div>
            <div class="step-number">05</div>
            <span class="step-icon">📊</span>
            <h4 class="step-title">Reporting & ROI Insights</h4>
            <p class="step-description">
                Weekly reports show return rate reduction, profit saved, and performance metrics. See exactly how much you're gaining.
            </p>
            <div class="step-highlight">💡 Data-driven decisions</div>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="process-summary">
        From order to delivery, we handle everything that reduces your returns. <strong>Our system pays for itself in the first week.</strong>
    </div>
</section>

<!-- CLIENTS -->
<section id="clients" class="section">
    <h2>Trusted by Growing Brands</h2>
    <p style="color: #aaa; max-width: 620px; margin: 0 auto 30px;">
        Real brands using our system to cut COD losses, recover failed deliveries, and turn returns into profit.
    </p>

    <div class="grid">
        <div class="card client-card">
            <span class="client-accent">Fashion Store</span>
            <h4>Reduced returns by 28%</h4>
            <p class="client-copy">“The confirmation workflow stopped low-quality orders and saved us courier costs every week.”</p>
        </div>

        <div class="card client-card">
            <span class="client-accent">Beauty Brand</span>
            <h4>Saved Rs 40,000+ / month</h4>
            <p class="client-copy">“Their verification and coordination process made our COD deliveries far more reliable.”</p>
        </div>

        <div class="card client-card">
            <span class="client-accent">Gadget Store</span>
            <h4>Improved delivery success</h4>
            <p class="client-copy">“We now spot risky orders before shipment and recover failed deliveries sooner.”</p>
        </div>
    </div>
</section>

<!-- PRICING -->
<section id="pricing" class="section">
<h2>Simple Pricing</h2>
<p style="color:#aaa; font-size: 15px; max-width: 760px; margin: 10px auto 0; line-height: 1.8;">
    Choose the service level that matches your monthly order volume. Each plan includes COD intelligence checks to help identify genuine buyers before dispatch.
</p>

<div class="pricing-grid">

<div class="price-card">
    <h3>Starter</h3>
    <div class="price">Rs 5,000</div>
    <p class="sub">Best for up to 500 orders per month</p>
    <button class="btn outline" onclick="payNow('Starter',5000)">Choose Plan</button>
    <ul>
        <li>✔ COD intelligence check for submitted orders</li>
        <li>✔ Pre-dispatch confirmation calls</li>
        <li>✔ Address and availability verification</li>
        <li>✔ Basic weekly summary</li>
        <li>✔ 200 credits included</li>
    </ul>
</div>

<div class="price-card popular">
    <div class="label">MOST POPULAR</div>
    <h3>Growth</h3>
    <div class="price">Rs 10,000</div>
    <p class="sub">Best for up to 1000 orders per month</p>
    <button class="btn primary" onclick="payNow('Growth',10000)">Choose Plan</button>
    <ul>
        <li>✔ Everything in Starter</li>
        <li>✔ Stronger COD intelligence and risk filtering</li>
        <li>✔ Returns and failed-delivery follow-up</li>
        <li>✔ Courier Coordination</li>
        <li>✔ Weekly performance reporting</li>
        <li>✔ 500 credits included</li>
    </ul>
</div>

<div class="price-card">
    <h3>Scale</h3>
    <div class="price">From Rs 20,000</div>
    <p class="sub">Best for 2000+ orders and larger teams</p>
    <button class="btn outline" onclick="payNow('Scale',20000)">Choose Plan</button>
    <ul>
        <li>✔ Everything in Growth</li>
        <li>✔ Full COD intelligence workflow</li>
        <li>✔ Priority handling and faster follow-up</li>
        <li>✔ Re-delivery and return case support</li>
        <li>✔ Advanced reporting visibility</li>
        <li>✔ 1500 credits included</li>
    </ul>
</div>

</div>

<div class="addon">
    <span class="addon-kicker">Additional Services</span>
    <div class="addon-grid">
        <div class="addon-copy">
            <h3>Tamil Support Add-on</h3>
            <p class="addon-price">Rs 5,000 / month</p>
            <p>Best for stores serving Tamil-speaking customers who need clearer confirmation calls, smoother delivery communication, and better follow-up quality.</p>
            <ul class="addon-list">
                <li>✔ 500 Tamil-language confirmation and support calls</li>
                <li>✔ Better communication for Tamil-speaking customers</li>
                <li>✔ Supports smoother delivery follow-up and fewer failed handovers</li>
            </ul>
        </div>

        <div class="addon-side">
            <span class="addon-side-title">Other Support Options</span>
            <div class="addon-chip-list">
                <span class="addon-chip">Custom volume plans</span>
                <span class="addon-chip">Priority handling</span>
                <span class="addon-chip">Advanced reporting</span>
                <span class="addon-chip">Re-delivery follow-up</span>
                <span class="addon-chip">Operational guidance</span>
            </div>
        </div>
    </div>
</div>

<p style="color:#8f8f8f; font-size: 13px; max-width: 760px; margin: 18px auto 0; line-height: 1.8;">
    Call credits are used for customer confirmation and operational follow-up. Plans can be adjusted based on order volume and service needs.
</p>

<div class="trust-contact">
    <div class="trust-contact-grid">
        <div>
            <h3>Before you pay</h3>
            <p>We recommend choosing the plan that matches your current order volume. If you are unsure, contact us first and we will guide you to the right service setup.</p>
            <ul class="trust-points">
                <li>✔ COD intelligence checks are included in every plan</li>
                <li>✔ WhatsApp support is available for service enquiries and onboarding</li>
                <li>✔ Plans can be adjusted based on monthly order volume</li>
            </ul>
        </div>
        <div class="contact-card">
            <strong>Direct Contact</strong>
            <a href="https://wa.me/94715969669?text=Hi%20COD%20Returns%20Lanka%2C%20I%20need%20help%20choosing%20the%20right%20plan." target="_blank" rel="noopener noreferrer">WhatsApp: +94 71 596 9669</a>
            <span>Fast response for pricing and service questions</span>
            <a href="{{ route('spin.services') }}">View full service breakdown</a>
        </div>
    </div>
</div>

<div class="form-section">
    <h3>Get Started Today</h3>
    <p style="color: #aaa; margin-bottom: 20px;">Fill in your details to choose your plan</p>
    <form id="paymentForm">
        <div class="form-group">
            <label for="first_name">Full Name *</label>
            <input type="text" id="first_name" name="first_name" required placeholder="Your full name">
            <span class="error-message" id="first_name-error"></span>
        </div>

        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" required placeholder="your@email.com">
            <span class="error-message" id="email-error"></span>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number (WhatsApp) *</label>
            <input type="tel" id="phone" name="phone" required placeholder="0771234567">
            <span class="error-message" id="phone-error"></span>
        </div>

        <div class="form-group">
            <label for="plan">Select Plan *</label>
            <select id="plan" name="plan" style="width: 100%; padding: 12px; border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; background: rgba(255,255,255,0.05); color: #fff; font-family: inherit;" required>
                <option value="">Choose a plan...</option>
                <option value="Starter">Starter - Rs 5,000 (Up to 500 Orders)</option>
                <option value="Growth">Growth - Rs 10,000 (Up to 1000 Orders)</option>
                <option value="Scale">Scale - Rs 20,000 (2000+ Orders)</option>
            </select>
        </div>

        <button type="button" class="btn primary" onclick="handleFormSubmit()">Proceed to Payment</button>
    </form>
</div>

</section>

<section class="section" id="faq">
    <h2>Frequently Asked Questions</h2>
    <p style="color:#aaa; font-size: 15px; max-width: 760px; margin: 10px auto 0; line-height: 1.8;">
        Clear answers to the questions most stores ask before choosing a COD support plan.
    </p>

    <div class="faq-grid">
        <div class="faq-item">
            <h3>What is a COD intelligence check?</h3>
            <p>It is our review process for identifying genuine buyers, checking delivery readiness, and spotting low-quality COD orders before dispatch.</p>
        </div>
        <div class="faq-item">
            <h3>What is a call credit?</h3>
            <p>Call credits are used for customer confirmation and related operational follow-up. Higher plans include more credits for larger order volumes.</p>
        </div>
        <div class="faq-item">
            <h3>Do you include courier charges?</h3>
            <p>No. The plans cover verification, coordination, and reporting support. Courier costs remain separate from this service.</p>
        </div>
        <div class="faq-item">
            <h3>How quickly can we start?</h3>
            <p>Most stores can get started quickly once plan details are confirmed. Contact us on WhatsApp and we can guide you through onboarding.</p>
        </div>
        <div class="faq-item">
            <h3>Can I upgrade my plan later?</h3>
            <p>Yes. If your order volume grows or you need more support services, we can move you to a better-fit plan.</p>
        </div>
        <div class="faq-item">
            <h3>Do you support Tamil-speaking customers?</h3>
            <p>Yes. Tamil-language confirmation and support calls are available through the Tamil Support Add-on.</p>
        </div>
    </div>
</section>
</main>

<footer class="footer" role="contentinfo">

    <div class="footer-wrapper">
        <div class="footer-container">

            <!-- LEFT SIDE - BRAND & TAGLINE -->
            <div class="footer-left">
                <img src="{{ asset('/img/cod.jpeg') }}" class="footer-logo" alt="COD Returns Lanka logo">

                <p class="footer-text">
                    Converting returns into profit for Sri Lankan eCommerce businesses. Join 1000+ merchants reducing losses.
                </p>

                <div class="footer-badge">Trusted by Leaders</div>
                <div class="footer-badge">Secure & Verified</div>

                <div class="social-links">
                    <a href="https://wa.me/94729708209?text=Hi%20COD%20Returns%20Lanka%2C%20I%20have%20an%20inquiry%20about%20your%20service." class="social-link" title="WhatsApp" aria-label="Contact us on WhatsApp">W</a>
                    <a href="#" class="social-link" title="LinkedIn" aria-label="Follow us on LinkedIn">in</a>
                    <a href="#" class="social-link" title="Email" aria-label="Send us an email">@</a>
                </div>
                
            </div>

            <div class="footer-col">
                <h4>Explore</h4>
                <a href="{{ route('spin.services') }}">Our Services</a>
                <a href="#how">How It Works</a>
                <a href="#clients">Clients</a>
                <a href="#pricing">Pricing</a>
            </div>

            <div class="footer-col">
                <h4>Support</h4>
                <a href="https://wa.me/94715969669?text=Hi%20COD%20Returns%20Lanka%2C%20I%20want%20to%20ask%20about%20your%20service." target="_blank" rel="noopener noreferrer">WhatsApp Support</a>
                <a href="#faq">FAQ</a>
                <a href="#pricing">Choose a Plan</a>
                <a href="{{ route('spin.services') }}">Service Breakdown</a>
            </div>

            <div class="footer-col">
                <h4>Direct Contact</h4>
                <a href="https://wa.me/94715969669?text=Hi%20COD%20Returns%20Lanka%2C%20I%20have%20an%20enquiry." target="_blank" rel="noopener noreferrer">WhatsApp: +94 71 596 9669</a>
                <a href="{{ route('spin.services') }}">View Services</a>
                <a href="#pricing">Pricing Plans</a>
                <a href="#faq">Common Questions</a>
            </div>

        </div>

        <!-- DIVIDER -->
        <div class="footer-divider"></div>


        <!-- FOOTER BOTTOM -->
        <div class="footer-bottom">
            <div>
                <p style="margin: 0; font-size: 12px;">© 2026 COD Returns Lanka. All rights reserved. | Built for Sri Lanka's eCommerce growth</p>
            </div>
            <div class="footer-payments">
                <span style="font-size: 11px; color: #666;">Payment Methods:</span>
                <div class="payment-method">PayHere</div>
                <div class="payment-method">Bank</div>
            </div>
        </div>
    </div>

</footer>

</div>

<a
    href="https://wa.me/94729708209?text=Hi%20COD%20Returns%20Lanka%2C%20I%20have%20an%20inquiry%20about%20your%20service."
    class="whatsapp-float"
    aria-label="Chat with us on WhatsApp"
    title="Chat with us on WhatsApp"
    target="_blank"
    rel="noopener noreferrer"
>
    <svg viewBox="0 0 32 32" aria-hidden="true">
        <path d="M19.11 17.23c-.27-.14-1.61-.79-1.86-.88-.25-.09-.43-.14-.61.14-.18.27-.7.88-.86 1.06-.16.18-.32.2-.59.07-.27-.14-1.14-.42-2.18-1.35-.81-.72-1.35-1.61-1.51-1.88-.16-.27-.02-.42.12-.56.12-.12.27-.32.41-.48.14-.16.18-.27.27-.45.09-.18.05-.34-.02-.48-.07-.14-.61-1.47-.84-2.02-.22-.53-.45-.46-.61-.47l-.52-.01c-.18 0-.48.07-.73.34-.25.27-.95.93-.95 2.27s.98 2.64 1.11 2.82c.14.18 1.91 2.92 4.63 4.09.65.28 1.16.44 1.56.56.66.21 1.26.18 1.73.11.53-.08 1.61-.66 1.84-1.29.23-.64.23-1.18.16-1.29-.07-.11-.25-.18-.52-.32zM16.02 4.5c-6.34 0-11.48 5.13-11.48 11.46 0 2.03.53 4.02 1.54 5.77L4.5 27.5l5.92-1.55a11.47 11.47 0 0 0 5.6 1.45h.01c6.33 0 11.47-5.13 11.47-11.46 0-3.07-1.19-5.95-3.37-8.12A11.39 11.39 0 0 0 16.02 4.5zm0 20.96h-.01a9.5 9.5 0 0 1-4.84-1.32l-.35-.21-3.51.92.94-3.42-.23-.35a9.45 9.45 0 0 1-1.46-5.06c0-5.23 4.25-9.48 9.49-9.48 2.53 0 4.9.98 6.69 2.78a9.39 9.39 0 0 1 2.78 6.7c0 5.23-4.26 9.48-9.5 9.48z"/>
    </svg>
    <span class="whatsapp-float-label">WhatsApp Us</span>
</a>

<!-- PAYHERE -->
<script src="https://www.payhere.lk/lib/payhere.js"></script>
<script>
function validateForm() {
    const form = document.getElementById('paymentForm');
    const firstName = document.getElementById('first_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const plan = document.getElementById('plan').value;

    let isValid = true;

    // Clear previous errors
    document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');

    // Validate first name
    if (firstName.length < 2) {
        showError('first_name', 'Please enter a valid name');
        isValid = false;
    }

    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showError('email', 'Please enter a valid email address');
        isValid = false;
    }

    // Validate phone
    const phoneRegex = /^[0-9\-\+\(\)\s]{9,}$/;
    if (!phoneRegex.test(phone)) {
        showError('phone', 'Please enter a valid phone number');
        isValid = false;
    }

    // Validate plan
    if (!plan) {
        showError('plan', 'Please select a plan');
        isValid = false;
    }

    return isValid;
}

function showError(fieldId, message) {
    const errorElement = document.getElementById(fieldId + '-error');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

function handleFormSubmit() {
    if (!validateForm()) {
        return;
    }

    const plan = document.getElementById('plan').value;
    const amounts = {
        'Starter': 5000,
        'Growth': 10000,
        'Scale': 20000,
    };

    const amount = amounts[plan];
    payNow(plan, amount);
}

async function payNow(plan, amount) {
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const firstName = document.getElementById('first_name').value;

    try {
        const response = await fetch('{{ route("payment.initiate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                plan: plan,
                email: email,
                phone: phone,
                first_name: firstName,
            }),
        });

        if (!response.ok) {
            const error = await response.json();
            alert('Error: ' + (error.error || 'Payment initialization failed'));
            return;
        }

        const payment = await response.json();
        payhere.startPayment(payment);
    } catch (error) {
        console.error('Payment error:', error);
        alert('An error occurred. Please try again.');
    }
}

const navToggle = document.querySelector('.nav-toggle');
const navMenu = document.getElementById('mobile-nav-menu');

if (navToggle && navMenu) {
    const closeMenu = () => {
        navMenu.classList.remove('is-open');
        navToggle.setAttribute('aria-expanded', 'false');
        navToggle.setAttribute('aria-label', 'Open navigation menu');
    };

    navToggle.addEventListener('click', () => {
        const isOpen = navMenu.classList.toggle('is-open');
        navToggle.setAttribute('aria-expanded', String(isOpen));
        navToggle.setAttribute('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');
    });

    navMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                closeMenu();
            }
        });
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            navMenu.classList.remove('is-open');
            navToggle.setAttribute('aria-expanded', 'false');
            navToggle.setAttribute('aria-label', 'Open navigation menu');
        }
    });
}
</script>

</body>
</html>
