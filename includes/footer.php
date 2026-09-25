<?php
/**
 * FoodBites — Global Footer
 * Theme  : Food-App (Uber Eats / Zomato style)
 * i18n   : English · Kiswahili · Arabic · French
 */

// ── Footer translations ───────────────────────────────────────────────────────
$lang = $_SESSION['lang'] ?? 'en';
$isRtl = ($lang === 'ar');

$footerT = [
    'en' => [
        'tagline'      => 'Authentic Tanzanian cuisine, delivered fresh to your door.',
        'quick_links'  => 'Quick Links',
        'categories'   => 'Categories',
        'contact'      => 'Contact Us',
        'follow'       => 'Follow Us',
        'menu'         => 'Menu',
        'cart'         => 'Cart',
        'orders'       => 'My Orders',
        'register'     => 'Register',
        'breakfast'    => 'Breakfast',
        'lunch'        => 'Lunch',
        'dinner'       => 'Dinner',
        'combos'       => 'Combos',
        'events'       => 'Event Catering',
        'address'      => 'Dar es Salaam, Tanzania',
        'hours'        => 'Mon–Sun: 7:00 AM – 10:00 PM',
        'rights'       => 'All rights reserved. Built for Tanzania',
        'secure'       => 'Secure Payments',
        'newsletter'   => 'Get Deals',
        'nl_input'     => 'Your email address',
        'nl_btn'       => 'Subscribe',
        'back_top'     => 'Back to top',
        'privacy'      => 'Privacy Policy',
        'terms'        => 'Terms of Service',
    ],
    'sw' => [
        'tagline'      => 'Chakula cha Tanzania halisi, kinawasilishwa kipya hadi mlangoni mwako.',
        'quick_links'  => 'Viungo vya Haraka',
        'categories'   => 'Kategoria',
        'contact'      => 'Wasiliana Nasi',
        'follow'       => 'Tufuate',
        'menu'         => 'Menyu',
        'cart'         => 'Kikapu',
        'orders'       => 'Maagizo Yangu',
        'register'     => 'Jisajili',
        'breakfast'    => 'Kiamsha kinywa',
        'lunch'        => 'Chakula cha Mchana',
        'dinner'       => 'Chakula cha Jioni',
        'combos'       => 'Mchanganyiko',
        'events'       => 'Catering ya Matukio',
        'address'      => 'Dar es Salaam, Tanzania',
        'hours'        => 'Jumatatu–Jumapili: 7:00 AM – 10:00 PM',
        'rights'       => 'Haki zote zimehifadhiwa. Imejengwa kwa Tanzania',
        'secure'       => 'Malipo Salama',
        'newsletter'   => 'Pata Ofa',
        'nl_input'     => 'Anwani yako ya barua pepe',
        'nl_btn'       => 'Jiandikishe',
        'back_top'     => 'Rudi juu',
        'privacy'      => 'Sera ya Faragha',
        'terms'        => 'Masharti ya Huduma',
    ],
    'ar' => [
        'tagline'      => 'مأكولات تنزانية أصيلة، تُوصَل طازجة إلى باب منزلك.',
        'quick_links'  => 'روابط سريعة',
        'categories'   => 'الفئات',
        'contact'      => 'تواصل معنا',
        'follow'       => 'تابعنا',
        'menu'         => 'القائمة',
        'cart'         => 'السلة',
        'orders'       => 'طلباتي',
        'register'     => 'تسجيل',
        'breakfast'    => 'الإفطار',
        'lunch'        => 'الغداء',
        'dinner'       => 'العشاء',
        'combos'       => 'وجبات مجمعة',
        'events'       => 'تقديم طعام للمناسبات',
        'address'      => 'دار السلام، تنزانيا',
        'hours'        => 'الاثنين–الأحد: 7:00 صباحاً – 10:00 مساءً',
        'rights'       => 'جميع الحقوق محفوظة. صُنع لتنزانيا',
        'secure'       => 'مدفوعات آمنة',
        'newsletter'   => 'احصل على عروض',
        'nl_input'     => 'بريدك الإلكتروني',
        'nl_btn'       => 'اشترك',
        'back_top'     => 'العودة إلى الأعلى',
        'privacy'      => 'سياسة الخصوصية',
        'terms'        => 'شروط الخدمة',
    ],
    'fr' => [
        'tagline'      => 'Cuisine tanzanienne authentique, livrée fraîche à votre porte.',
        'quick_links'  => 'Liens Rapides',
        'categories'   => 'Catégories',
        'contact'      => 'Contactez-nous',
        'follow'       => 'Suivez-nous',
        'menu'         => 'Menu',
        'cart'         => 'Panier',
        'orders'       => 'Mes Commandes',
        'register'     => 'S\'inscrire',
        'breakfast'    => 'Petit-déjeuner',
        'lunch'        => 'Déjeuner',
        'dinner'       => 'Dîner',
        'combos'       => 'Combos',
        'events'       => 'Traiteur Événementiel',
        'address'      => 'Dar es Salaam, Tanzanie',
        'hours'        => 'Lun–Dim : 7h00 – 22h00',
        'rights'       => 'Tous droits réservés. Fait pour la Tanzanie',
        'secure'       => 'Paiements Sécurisés',
        'newsletter'   => 'Obtenir des Offres',
        'nl_input'     => 'Votre adresse e-mail',
        'nl_btn'       => 'S\'abonner',
        'back_top'     => 'Retour en haut',
        'privacy'      => 'Politique de Confidentialité',
        'terms'        => 'Conditions d\'Utilisation',
    ],
];

$F = $footerT[$lang];
?>

</main><!-- /.main-content -->

<style>
/* ═══════════════════════════════════════════════════════
   FOOTER — Food-App Theme
═══════════════════════════════════════════════════════ */
.site-footer {
    background: #111111;
    color: rgba(255,255,255,.75);
    font-family: var(--font, 'Poppins', sans-serif);
    margin-top: auto;
}

/* Back to top strip */
.footer-top-strip {
    background: var(--primary, #16A34A);
    text-align: center;
    padding: .6rem 0;
}
.back-to-top {
    display: inline-flex; align-items: center; gap: .4rem;
    color: #fff; font-size: .82rem; font-weight: 600;
    text-decoration: none; letter-spacing: .04em;
    transition: opacity .2s;
}
.back-to-top:hover { opacity: .8; }

/* Main footer body */
.footer-body { padding: 3.5rem 0 2rem; }
.footer-container { max-width: 1200px; margin: 0 auto; padding: 0 1.25rem; }

.footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.4fr;
    gap: 2.5rem;
    margin-bottom: 3rem;
}

/* Brand column */
.footer-brand {}
.footer-logo-wrap {
    display: flex; align-items: center; gap: .6rem;
    margin-bottom: 1rem; text-decoration: none;
}
.footer-logo-svg { height: 40px; width: auto; }
.footer-tagline {
    font-size: .88rem; line-height: 1.65;
    color: rgba(255,255,255,.55);
    margin-bottom: 1.5rem; max-width: 240px;
}

/* Social links */
.footer-social { display: flex; gap: .6rem; margin-bottom: 1.75rem; }
.social-link {
    width: 38px; height: 38px; border-radius: 50%;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12);
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.65); font-size: 1rem;
    text-decoration: none;
    transition: background .2s, color .2s, transform .2s;
}
.social-link:hover {
    background: var(--primary, #16A34A);
    color: #fff; border-color: var(--primary, #16A34A);
    transform: translateY(-3px);
}

/* Newsletter mini (brand col) */
.footer-nl { display: flex; gap: .4rem; }
.footer-nl input {
    flex: 1; padding: .55rem .9rem;
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 999px; background: rgba(255,255,255,.07);
    color: #fff; font-family: inherit; font-size: .83rem; outline: none;
    transition: border-color .2s;
}
.footer-nl input::placeholder { color: rgba(255,255,255,.35); }
.footer-nl input:focus { border-color: var(--primary, #16A34A); }
.footer-nl button {
    padding: .55rem 1.1rem; border-radius: 999px;
    background: var(--primary, #16A34A); color: #fff;
    border: none; font-family: inherit; font-size: .83rem;
    font-weight: 600; cursor: pointer; white-space: nowrap;
    transition: background .2s, transform .2s;
}
.footer-nl button:hover { background: var(--primary-dark, #15803D); transform: scale(1.04); }

/* Link columns */
.footer-col h4 {
    font-size: .78rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .1em; color: #fff;
    margin-bottom: 1.1rem;
    display: flex; align-items: center; gap: .4rem;
}
.footer-col h4 i { color: var(--primary, #16A34A); font-size: .85rem; }
.footer-col ul { list-style: none; display: flex; flex-direction: column; gap: .55rem; }
.footer-col ul li a {
    display: flex; align-items: center; gap: .5rem;
    color: rgba(255,255,255,.55); font-size: .87rem;
    text-decoration: none; transition: color .2s, gap .2s;
}
.footer-col ul li a:hover { color: var(--primary, #16A34A); gap: .75rem; }
.footer-col ul li a i {
    width: 16px; text-align: center;
    color: rgba(255,255,255,.3); font-size: .8rem;
    transition: color .2s;
}
.footer-col ul li a:hover i { color: var(--primary, #16A34A); }

/* Contact column */
.footer-contact-item {
    display: flex; align-items: flex-start; gap: .65rem;
    margin-bottom: .75rem; font-size: .87rem;
    color: rgba(255,255,255,.55);
}
.footer-contact-item i {
    color: var(--primary, #16A34A);
    font-size: .9rem; margin-top: .15rem; flex-shrink: 0;
    width: 16px; text-align: center;
}
.footer-contact-item a {
    color: rgba(255,255,255,.55); text-decoration: none;
    transition: color .2s;
}
.footer-contact-item a:hover { color: var(--primary, #16A34A); }

/* Divider */
.footer-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.1), transparent);
    margin-bottom: 1.5rem;
}

/* Bottom bar */
.footer-bottom {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem;
}
.footer-bottom-left { display: flex; flex-direction: column; gap: .3rem; }
.footer-copyright {
    font-size: .8rem; color: rgba(255,255,255,.4);
    display: flex; align-items: center; gap: .35rem; flex-wrap: wrap;
}
.footer-copyright i { color: var(--primary, #16A34A); }
.footer-legal { display: flex; gap: 1rem; }
.footer-legal a {
    font-size: .75rem; color: rgba(255,255,255,.3);
    text-decoration: none; transition: color .2s;
}
.footer-legal a:hover { color: rgba(255,255,255,.65); }

/* Payment chips */
.footer-payments { display: flex; flex-direction: column; align-items: flex-end; gap: .5rem; }
[dir="rtl"] .footer-payments { align-items: flex-start; }
.footer-payments-label {
    font-size: .72rem; color: rgba(255,255,255,.3);
    text-transform: uppercase; letter-spacing: .08em;
    display: flex; align-items: center; gap: .3rem;
}
.footer-payments-chips { display: flex; gap: .4rem; flex-wrap: wrap; justify-content: flex-end; }
[dir="rtl"] .footer-payments-chips { justify-content: flex-start; }
.pay-chip {
    padding: .28rem .75rem; border-radius: 999px;
    font-size: .72rem; font-weight: 700;
    border: 1px solid transparent;
}
.pay-chip.mpesa    { background: rgba(34,139,34,.15);  color: #7ec87e; border-color: rgba(34,139,34,.25); }
.pay-chip.tigopesa { background: rgba(30,144,255,.15); color: #7ab8ff; border-color: rgba(30,144,255,.25); }
.pay-chip.airtel   { background: rgba(220,20,60,.15);  color: #f08090; border-color: rgba(220,20,60,.25); }
.pay-chip.cod      { background: rgba(245,158,11,.15);  color: #ffc55c; border-color: rgba(245,158,11,.25); }

/* RTL */
[dir="rtl"] .footer-payments { align-items: flex-start; }
[dir="rtl"] .footer-bottom   { flex-direction: row-reverse; }
[dir="rtl"] .footer-contact-item { flex-direction: row-reverse; text-align: right; }

/* Responsive */
@media (max-width: 960px) {
    .footer-grid { grid-template-columns: 1fr 1fr; gap: 2rem; }
    .footer-brand { grid-column: span 2; }
}
@media (max-width: 560px) {
    .footer-grid { grid-template-columns: 1fr; }
    .footer-brand { grid-column: span 1; }
    .footer-tagline { max-width: 100%; }
    .footer-bottom { flex-direction: column; align-items: flex-start; }
    .footer-payments { align-items: flex-start; }
    .footer-payments-chips { justify-content: flex-start; }
}
</style>

<!-- ══════════════════════════════════════════════════════════
     BACK TO TOP STRIP
══════════════════════════════════════════════════════════ -->
<div class="footer-top-strip">
    <a href="#" class="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;">
        <i class="fa-solid fa-chevron-up"></i> <?= e($F['back_top']) ?>
    </a>
</div>

<!-- ══════════════════════════════════════════════════════════
     MAIN FOOTER BODY
══════════════════════════════════════════════════════════ -->
<footer class="site-footer" role="contentinfo">
    <div class="footer-body">
        <div class="footer-container">
            <div class="footer-grid">

                <!-- ── Brand ──────────────────────────────── -->
                <div class="footer-brand">
                    <a href="<?= BASE_URL ?>/index.php" class="footer-logo-wrap" aria-label="FoodBites Home">
                        <svg class="footer-logo-svg" viewBox="0 0 500 120" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="fgFooter" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#16A34A"/>
                                    <stop offset="100%" stop-color="#F59E0B"/>
                                </linearGradient>
                            </defs>
                            <g transform="translate(10,10)">
                                <circle cx="50" cy="50" r="44" fill="rgba(22,163,74,.12)" stroke="url(#fgFooter)" stroke-width="3.5"/>
                                <path d="M38,28 L38,48 M34,28 L34,42 M42,28 L42,42 M38,48 L38,70"
                                      stroke="#16A34A" stroke-width="3" stroke-linecap="round"/>
                                <rect x="57" y="28" width="7" height="24" rx="2" fill="rgba(255,255,255,.8)"/>
                                <circle cx="63.5" cy="38" r="4.5" fill="rgba(17,17,17,1)"/>
                                <circle cx="50" cy="18" r="2" fill="#F59E0B" opacity=".5"/>
                            </g>
                            <text x="122" y="68" font-family="Poppins,sans-serif" font-size="54" font-weight="800" fill="url(#fgFooter)">Food</text>
                            <text x="272" y="68" font-family="Poppins,sans-serif" font-size="54" font-weight="800" fill="#ffffff">Bites</text>
                            <text x="124" y="95" font-family="Poppins,sans-serif" font-size="15" font-weight="600" fill="rgba(255,255,255,.4)" letter-spacing="4.5">TASTE OF TANZANIA</text>
                        </svg>
                    </a>

                    <p class="footer-tagline"><?= e($F['tagline']) ?></p>

                    <!-- Social icons -->
                    <div class="footer-social">
                        <a href="#" class="social-link" aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://wa.me/255700000000" target="_blank" rel="noopener noreferrer"
                           class="social-link" aria-label="WhatsApp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="TikTok">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Twitter / X">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                    </div>

                    <!-- Mini newsletter -->
                    <div class="footer-nl">
                        <input type="email" id="footerEmail"
                               placeholder="<?= e($F['nl_input']) ?>"
                               autocomplete="email" aria-label="<?= e($F['nl_input']) ?>">
                        <button onclick="footerSubscribe()">
                            <i class="fa-solid fa-paper-plane"></i> <?= e($F['nl_btn']) ?>
                        </button>
                    </div>
                </div>

                <!-- ── Quick Links ──────────────────────── -->
                <div class="footer-col">
                    <h4><i class="fa-solid fa-bolt"></i> <?= e($F['quick_links']) ?></h4>
                    <ul>
                        <li>
                            <a href="<?= BASE_URL ?>/customer/menu.php">
                                <i class="fa-solid fa-utensils"></i> <?= e($F['menu']) ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= BASE_URL ?>/customer/cart.php">
                                <i class="fa-solid fa-basket-shopping"></i> <?= e($F['cart']) ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= BASE_URL ?>/customer/order_history.php">
                                <i class="fa-solid fa-bag-shopping"></i> <?= e($F['orders']) ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= BASE_URL ?>/auth/register.php">
                                <i class="fa-solid fa-user-plus"></i> <?= e($F['register']) ?>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- ── Categories ──────────────────────── -->
                <div class="footer-col">
                    <h4><i class="fa-solid fa-layer-group"></i> <?= e($F['categories']) ?></h4>
                    <ul>
                        <li>
                            <a href="<?= BASE_URL ?>/customer/menu.php?category=breakfast">
                                <i class="fa-solid fa-mug-hot"></i> <?= e($F['breakfast']) ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= BASE_URL ?>/customer/menu.php?category=lunch">
                                <i class="fa-solid fa-bowl-food"></i> <?= e($F['lunch']) ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= BASE_URL ?>/customer/menu.php?category=dinner">
                                <i class="fa-solid fa-moon"></i> <?= e($F['dinner']) ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= BASE_URL ?>/customer/menu.php?category=combo">
                                <i class="fa-solid fa-boxes-stacked"></i> <?= e($F['combos']) ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= BASE_URL ?>/customer/menu.php?category=events">
                                <i class="fa-solid fa-champagne-glasses"></i> <?= e($F['events']) ?>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- ── Contact ──────────────────────────── -->
                <div class="footer-col">
                    <h4><i class="fa-solid fa-headset"></i> <?= e($F['contact']) ?></h4>

                    <div class="footer-contact-item">
                        <i class="fa-solid fa-location-dot"></i>
                        <span><?= e($F['address']) ?></span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa-solid fa-phone"></i>
                        <a href="tel:+255700000000">+255 700 000 000</a>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa-solid fa-envelope"></i>
                        <a href="mailto:hello@foodbites.co.tz">hello@foodbites.co.tz</a>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa-solid fa-clock"></i>
                        <span><?= e($F['hours']) ?></span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa-brands fa-whatsapp" style="color:#25D366;"></i>
                        <a href="https://wa.me/255700000000" target="_blank" rel="noopener noreferrer"
                           style="color:#25D366;">
                            +255 700 000 000
                        </a>
                    </div>
                </div>

            </div><!-- /.footer-grid -->

            <!-- Divider -->
            <div class="footer-divider"></div>

            <!-- ── Bottom bar ──────────────────────────── -->
            <div class="footer-bottom">
                <div class="footer-bottom-left">
                    <p class="footer-copyright">
                        <i class="fa-regular fa-copyright"></i>
                        <?= date('Y') ?> <?= e(APP_NAME) ?>.
                        <?= e($F['rights']) ?>
                        &nbsp;·&nbsp;
                        <i class="fa-solid fa-heart" style="color:var(--primary,#16A34A);"></i>
                        Made with love
                    </p>
                    <div class="footer-legal">
                        <a href="<?= BASE_URL ?>/privacy.php"><?= e($F['privacy']) ?></a>
                        <a href="<?= BASE_URL ?>/terms.php"><?= e($F['terms']) ?></a>
                    </div>
                </div>

                <div class="footer-payments">
                    <div class="footer-payments-label">
                        <i class="fa-solid fa-lock"></i> <?= e($F['secure']) ?>
                    </div>
                    <div class="footer-payments-chips">
                        <span class="pay-chip mpesa">
                            <i class="fa-solid fa-mobile-screen"></i> M-Pesa
                        </span>
                        <span class="pay-chip tigopesa">
                            <i class="fa-solid fa-mobile-screen"></i> Tigo Pesa
                        </span>
                        <span class="pay-chip airtel">
                            <i class="fa-solid fa-mobile-screen"></i> Airtel
                        </span>
                        <span class="pay-chip cod">
                            <i class="fa-solid fa-money-bill-wave"></i> Cash
                        </span>
                    </div>
                </div>
            </div>

        </div><!-- /.footer-container -->
    </div><!-- /.footer-body -->
</footer>

<!-- Global JS -->
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>

<script>
// Footer newsletter
function footerSubscribe() {
    const email = document.getElementById('footerEmail')?.value.trim();
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return;
    fetch('<?= BASE_URL ?>/api/newsletter.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, lang: '<?= $lang ?>' })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            const wrap = document.querySelector('.footer-nl');
            if (wrap) wrap.innerHTML =
                '<p style="color:#7ec87e;font-size:.83rem;display:flex;align-items:center;gap:.4rem;">' +
                '<i class="fa-solid fa-circle-check"></i> ' +
                <?= json_encode($F['nl_btn'] === 'Subscribe' ? 'Subscribed! Check your inbox.' : ($F['nl_btn'] === 'Jiandikishe' ? 'Umejisajili!' : ($F['nl_btn'] === 'اشترك' ? 'تم الاشتراك!' : 'Abonné !'))) ?> +
                '</p>';
        }
    })
    .catch(console.error);
}

// Smooth scroll for all #anchor links in footer
document.querySelectorAll('.site-footer a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
    });
});
</script>

<?= $extraScripts ?? '' ?>
</body>
</html>