// RelyDrip decorative FX — beat bar, scrolling tickers, faint background
// text, mouse-follow canvas drip trail, hero speak-lines, manifesto
// scroll-reveal, and the custom-order configurator price estimator.
// Every feature guards on the presence of its root element so this can
// run unconditionally on every storefront page.

import { Carousel } from 'bootstrap';

const SPEAK_INTERVAL = 2200;
// Banner rotates every 2 speak-line changes, both started from the same
// DOMContentLoaded tick, so the two stay in phase instead of drifting.
const HERO_BANNER_INTERVAL = SPEAK_INTERVAL * 2;

export function initStorefrontFx() {
    initBeatBar();
    initTickers();
    initSpeakLines();
    initHeroBannerCarousel();
    initVerticalBackground();
    initDripCanvas();
    initScrollReveal();
    initConfigurator();
}

function initBeatBar() {
    const bb = document.getElementById('bbar');
    if (!bb) return;

    let html = '';
    for (let i = 0; i < 60; i++) {
        const delay = (Math.random() * 0.35).toFixed(2);
        const duration = (0.2 + Math.random() * 0.4).toFixed(2);
        html += `<div class="bb" style="animation-delay:${delay}s;animation-duration:${duration}s"></div>`;
    }
    bb.innerHTML = html;
}

const TICK_WORDS = [
    'GOLD', 'SILVER', 'PLATINUM', '9K', '12K', '16K', '18K', '22K', '24K',
    'HANDCRAFTED', 'CUSTOM ORDERS', 'RINGS', 'CHAINS', 'PENDANTS', 'EARRINGS',
    'BRACELETS', 'BORN IN SURAT', 'NEW JERSEY USA', 'WORN BY THE WORLD',
    'DRIP YOU CAN RELY ON',
];

function initTickers() {
    document.querySelectorAll('.ti').forEach((el) => {
        const doubled = [...TICK_WORDS, ...TICK_WORDS];
        el.innerHTML = doubled.map((w) => `<span style="padding:0 2rem;">${w} &nbsp;&middot;&nbsp;</span>`).join('');
    });
}

const SPEAK_LINES = [
    'Jewelry is a Statement.',
    'A Statement of Personality.',
    'Showcase Your Vision.',
    'Your Art. Your Identity. Your Drip.',
    'Born in Surat. Worn by the World.',
    'Real Gold. Real Craft. Real You.',
    'Drip You Can Rely On.',
];

function initSpeakLines() {
    const wrap = document.getElementById('speak');
    if (!wrap) return;

    wrap.innerHTML = SPEAK_LINES.map((line, i) => `<div class="speak-line" id="sl${i}">${line}</div>`).join('');

    let current = 0;
    function nextLine() {
        wrap.querySelectorAll('.speak-line').forEach((el) => el.classList.remove('on'));
        const el = document.getElementById('sl' + current);
        if (el) el.classList.add('on');
        current = (current + 1) % SPEAK_LINES.length;
    }
    nextLine();
    setInterval(nextLine, SPEAK_INTERVAL);
}

function initHeroBannerCarousel() {
    const el = document.getElementById('heroBannerCarousel');
    if (!el) return;

    Carousel.getOrCreateInstance(el, { interval: HERO_BANNER_INTERVAL, ride: 'carousel' });

    const indicators = el.closest('.hero')?.querySelectorAll('.hero-indicators button');
    if (!indicators?.length) return;

    el.addEventListener('slid.bs.carousel', (event) => {
        indicators.forEach((btn, i) => btn.classList.toggle('active', i === event.to));
    });
}

const BG_WORDS = [
    'STATEMENT', 'PERSONALITY', 'VISION', 'ART', 'IDENTITY', 'GOLD', 'SILVER',
    'PLATINUM', 'REAL DRIP', 'RELYDRIP', 'BORN IN SURAT', 'NEW JERSEY USA',
    'WORN BY THE WORLD', 'YOUR ART', 'YOUR VISION', 'HANDCRAFTED', 'CULTURE',
    'DRIP YOU CAN RELY ON', 'EST 2026',
];

function initVerticalBackground() {
    const vbg = document.getElementById('g-vbg');
    if (!vbg) return;

    let html = '';
    for (let i = 0; i < 100; i++) {
        const parts = [];
        for (let j = 0; j < 6; j++) {
            parts.push(BG_WORDS[Math.floor(Math.random() * BG_WORDS.length)]);
        }
        html += `<span class="vl" style="padding-left:${Math.random() * 100}px">${parts.join('   ·   ')}</span>`;
    }
    vbg.innerHTML = html;
}

function initDripCanvas() {
    const canvas = document.getElementById('g-canvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const drops = [];
    let lastX = 0;
    let lastY = 0;
    let scrollY = 0;
    let ticking = false;

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    function spawn(x, y, count) {
        count = count || 1;
        for (let i = 0; i < count; i++) {
            if (drops.length > 200) return;
            drops.push({
                x: x + (Math.random() - 0.5) * 50,
                y: y + (Math.random() - 0.5) * 20,
                vy: 1 + Math.random() * 3.5,
                vx: (Math.random() - 0.5) * 1.2,
                r: 1 + Math.random() * 4,
                trail: [],
                life: 0,
                max: 50 + Math.random() * 100,
            });
        }
    }

    function frame() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (let i = drops.length - 1; i >= 0; i--) {
            const d = drops[i];
            d.trail.push({ x: d.x, y: d.y });
            if (d.trail.length > 20) d.trail.shift();
            d.x += d.vx;
            d.y += d.vy;
            d.vy += 0.06;
            d.life++;
            const fade = Math.max(0, 1 - d.life / d.max);
            for (let t = 0; t < d.trail.length; t++) {
                const a = (t / d.trail.length) * fade * 0.45;
                ctx.beginPath();
                ctx.arc(d.trail[t].x, d.trail[t].y, d.r * (t / d.trail.length) * 0.65, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(26,92,255,${a * 0.4})`;
                ctx.fill();
            }
            ctx.beginPath();
            ctx.arc(d.x, d.y, d.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(26,92,255,${fade * 0.25})`;
            ctx.fill();
            if (d.life >= d.max || d.y > canvas.height + 100) drops.splice(i, 1);
        }
        requestAnimationFrame(frame);
    }

    document.addEventListener('mousemove', (e) => {
        if (Math.abs(e.clientX - lastX) > 2 || Math.abs(e.clientY - lastY) > 2) {
            spawn(e.clientX, e.clientY, Math.random() < 0.7 ? 1 : 2);
            lastX = e.clientX;
            lastY = e.clientY;
        }
    });

    window.addEventListener('scroll', () => {
        scrollY = window.scrollY || 0;
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(() => {
                const n = 3 + Math.floor(Math.random() * 4);
                for (let i = 0; i < n; i++) {
                    spawn(40 + Math.random() * (canvas.width - 80), scrollY + 20 + Math.random() * (window.innerHeight - 40));
                }
                ticking = false;
            });
        }
    }, { passive: true });

    window.addEventListener('resize', resizeCanvas);

    resizeCanvas();
    frame();

    setTimeout(() => {
        for (let i = 0; i < 60; i++) {
            setTimeout(() => {
                spawn(60 + Math.random() * (window.innerWidth - 120), 40 + Math.random() * 280, 1);
            }, i * 40);
        }
    }, 300);
}

function initScrollReveal() {
    const lines = document.querySelectorAll('.mf-line:not(.revealed)');
    if (!lines.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) entry.target.classList.add('revealed');
        });
    }, { threshold: 0.3 });

    lines.forEach((line) => observer.observe(line));
}

// -----------------------------------------------------------------------
// Custom order configurator — client-side estimate only; the server
// computes/stores the authoritative estimate on submit.
// -----------------------------------------------------------------------

const CFG_PRICES = {
    'Cuban Link Chain': 30000, 'Tennis Chain': 10000, 'Pendant': 15000, 'Ring': 7500,
    'Bracelet': 20000, 'Earrings': 5000, 'Grillz': 25000, 'Hip Hop Set': 87500,
    '925 Sterling Silver': 0, '10K Yellow Gold': 5000, '14K Yellow Gold': 10000,
    '14K White Gold': 11000, '14K Rose Gold': 11000, '18K Yellow Gold': 18000,
    '18K White Gold': 20000, 'Platinum 950': 35000,
};

function initConfigurator() {
    const groups = document.querySelectorAll('.cfg-opts');
    if (!groups.length) return;

    groups.forEach((row) => {
        row.querySelectorAll('.cfg-opt').forEach((btn) => {
            btn.addEventListener('click', () => {
                row.querySelectorAll('.cfg-opt').forEach((b) => b.classList.remove('sel'));
                btn.classList.add('sel');
                const targetId = row.dataset.target;
                const hidden = targetId ? document.getElementById(targetId) : null;
                if (hidden) hidden.value = btn.textContent.trim();
                updateConfiguratorPrice();
            });
        });
    });

    updateConfiguratorPrice();
}

function updateConfiguratorPrice() {
    let total = 0;
    document.querySelectorAll('.cfg-opts').forEach((row) => {
        const selected = row.querySelector('.cfg-opt.sel');
        if (selected) total += CFG_PRICES[selected.textContent.trim()] || 0;
    });
    const el = document.getElementById('cfg-price');
    const hidden = document.getElementById('cfg-price-value');
    const estimate = Math.max(5000, total) * 2.5;
    if (el) el.textContent = '₹' + Math.round(estimate).toLocaleString('en-IN');
    if (hidden) hidden.value = Math.round(estimate);
}
