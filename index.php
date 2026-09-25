<?php
/**
 * FoodBites — Landing / Home Page
 * Theme  : Food-App (Uber Eats / Zomato style)
 * i18n   : English · Kiswahili · Arabic · French
 */
require_once __DIR__ . '/includes/functions.php';

// ── Page Translations ─────────────────────────────────────────────────────────
// Note: Language selection ($lang, $isRtl, $dir) handled by header.php
$t = [
    'en' => [
        'hero_badge'        => 'Proudly Tanzanian',
        'hero_title_1'      => 'Authentic Taste of',
        'hero_highlight'    => 'Tanzania',
        'hero_title_2'      => 'Delivered to You',
        'hero_sub'          => 'From Ugali to Pilau, Nyama Choma to Zanzibar Pizza — order fresh Tanzanian cuisine for delivery or events.',
        'order_now'         => 'Order Now',
        'explore_menu'      => 'Explore Menu',
        'dishes'            => 'Dishes',
        'orders'            => 'Orders',
        'rating'            => 'Rating',
        'customers'         => 'Customers',
        'all_items'         => 'All Items',
        'fan_favorites'     => 'Fan Favorites',
        'featured_dishes'   => 'Featured Dishes',
        'featured_sub'      => 'Hand-picked favorites loved by our customers',
        'view_full_menu'    => 'View Full Menu',
        'no_featured'       => 'No featured dishes right now.',
        'browse_menu'       => 'Browse full menu →',
        'add'               => 'Add',
        'how_title'         => 'How It Works',
        'how_sub'           => 'Four easy steps to great food',
        'step1_title'       => 'Browse Menu',
        'step1_desc'        => 'Explore our wide selection of authentic Tanzanian dishes by category.',
        'step2_title'       => 'Add to Cart',
        'step2_desc'        => 'Select your favorite dishes and customize your order quantity.',
        'step3_title'       => 'Pay Easily',
        'step3_desc'        => 'Pay with M-Pesa, Tigo Pesa, Airtel Money, or Cash on Delivery.',
        'step4_title'       => 'Get Delivered',
        'step4_desc'        => 'Track your order in real-time and enjoy fresh food at your door.',
        'home_delivery'     => 'Home Delivery',
        'home_delivery_desc'=> 'Order now or schedule for later. Fresh food delivered to your doorstep.',
        'event_catering'    => 'Event Catering',
        'event_desc'        => 'Weddings, office parties, conferences. We cater for 10 to 1000+ guests.',
        'bulk_office'       => 'Bulk / Office',
        'bulk_desc'         => 'Daily office lunch programs and bulk catering for your team.',
        'enquire'           => 'Enquire',
        'view_packages'     => 'View Packages',
        'popular'           => 'Popular',
        'happy_customers'   => 'Happy Customers',
        'what_say'          => 'What People Say',
        'wa_title'          => 'Order via WhatsApp',
        'wa_desc'           => 'Prefer to chat? Send us a message and we\'ll handle your order personally.',
        'wa_btn'            => 'Chat on WhatsApp',
        'nl_title'          => 'Get Exclusive Deals',
        'nl_desc'           => 'Subscribe and get 10% off your first order + weekly specials.',
        'nl_placeholder'    => 'your@email.com',
        'nl_btn'            => 'Subscribe',
        'nl_note'           => 'No spam. Unsubscribe anytime.',
        'nl_success'        => 'You\'re subscribed! Check your inbox.',
        'secure_payments'   => 'Secure Payments',
        'today_special'     => "Today's Special",
        'simple'            => 'Simple',
        'just_ordered'      => 'Just ordered!',
        'fast_delivery'     => 'Fast Delivery',
        'featured'          => 'Featured',
        'combo'             => 'Combo',
        'bulk'              => 'Bulk',
        'reviews'           => [
            ['text' => '"The Ugali na Nyama Choma was absolutely perfect. Tastes just like home-cooked!"', 'name' => 'Amina J.', 'loc' => 'Kinondoni, DSM'],
            ['text' => '"Catered our office event for 200 people. Everything was on time and delicious!"', 'name' => 'Brian M.', 'loc' => 'Mikocheni, DSM'],
            ['text' => '"Best Pilau in Dar! Fast delivery and the packaging kept it warm. Will order again."', 'name' => 'Fatuma K.', 'loc' => 'Ilala, DSM'],
        ],
    ],
    'sw' => [
        'hero_badge'        => 'Kwa Fahari ya Tanzania',
        'hero_title_1'      => 'Ladha ya Kweli ya',
        'hero_highlight'    => 'Tanzania',
        'hero_title_2'      => 'Inawasilishwa Kwako',
        'hero_sub'          => 'Kuanzia Ugali hadi Pilau, Nyama Choma hadi Pizza ya Zanzibar — agiza chakula kipya cha Tanzania kwa uwasilishaji au matukio.',
        'order_now'         => 'Agiza Sasa',
        'explore_menu'      => 'Angalia Menyu',
        'dishes'            => 'Vyakula',
        'orders'            => 'Maagizo',
        'rating'            => 'Ukadiriaji',
        'customers'         => 'Wateja',
        'all_items'         => 'Vitu Vyote',
        'fan_favorites'     => 'Vipendwa vya Wateja',
        'featured_dishes'   => 'Vyakula Maalum',
        'featured_sub'      => 'Vipendwa vilivyochaguliwa na wateja wetu',
        'view_full_menu'    => 'Tazama Menyu Kamili',
        'no_featured'       => 'Hakuna vyakula maalum kwa sasa.',
        'browse_menu'       => 'Tazama menyu yote →',
        'add'               => 'Ongeza',
        'how_title'         => 'Jinsi Inavyofanya Kazi',
        'how_sub'           => 'Hatua nne rahisi kwa chakula kizuri',
        'step1_title'       => 'Angalia Menyu',
        'step1_desc'        => 'Chunguza uchaguzi wetu mpana wa vyakula vya Tanzania kwa kategoria.',
        'step2_title'       => 'Ongeza kwenye Kikapu',
        'step2_desc'        => 'Chagua vyakula unavyovipenda na ubadilishe idadi ya agizo lako.',
        'step3_title'       => 'Lipa Kwa Urahisi',
        'step3_desc'        => 'Lipa kwa M-Pesa, Tigo Pesa, Airtel Money, au Pesa Taslimu.',
        'step4_title'       => 'Pokea Uwasilishaji',
        'step4_desc'        => 'Fuatilia agizo lako kwa wakati halisi na ufurahie chakula kipya.',
        'home_delivery'     => 'Uwasilishaji Nyumbani',
        'home_delivery_desc'=> 'Agiza sasa au panga baadaye. Chakula kipya kinawasilishwa mlangoni mwako.',
        'event_catering'    => 'Catering ya Matukio',
        'event_desc'        => 'Harusi, sherehe za ofisi, makongamano. Tunahudumia wageni 10 hadi 1000+.',
        'bulk_office'       => 'Jumla / Ofisi',
        'bulk_desc'         => 'Programu za chakula cha mchana cha ofisi na catering ya jumla kwa timu yako.',
        'enquire'           => 'Uliza',
        'view_packages'     => 'Tazama Pakiti',
        'popular'           => 'Maarufu',
        'happy_customers'   => 'Wateja Wafurahi',
        'what_say'          => 'Watu Wanasema Nini',
        'wa_title'          => 'Agiza kupitia WhatsApp',
        'wa_desc'           => 'Unapendelea mazungumzo? Tutumie ujumbe na tutashughulikia agizo lako.',
        'wa_btn'            => 'Zungumza kwenye WhatsApp',
        'nl_title'          => 'Pata Ofa Maalum',
        'nl_desc'           => 'Jiandikishe na upate punguzo la 10% kwenye agizo lako la kwanza.',
        'nl_placeholder'    => 'barua@pepe.com',
        'nl_btn'            => 'Jiandikishe',
        'nl_note'           => 'Hakuna barua taka. Ondoa usajili wakati wooote.',
        'nl_success'        => 'Umejisajili! Angalia kisanduku chako cha barua pepe.',
        'secure_payments'   => 'Malipo Salama',
        'today_special'     => 'Maalum ya Leo',
        'simple'            => 'Rahisi',
        'just_ordered'      => 'Imeagizwa tu!',
        'fast_delivery'     => 'Uwasilishaji wa Haraka',
        'featured'          => 'Maalum',
        'combo'             => 'Mchanganyiko',
        'bulk'              => 'Jumla',
        'reviews'           => [
            ['text' => '"Ugali na Nyama Choma ilikuwa kamili kabisa. Ina ladha kama iliyopikwa nyumbani!"', 'name' => 'Amina J.', 'loc' => 'Kinondoni, DSM'],
            ['text' => '"Tulihudumia tukio la ofisi yetu kwa watu 200. Kila kitu kilikuwa kwa wakati na kitamu!"', 'name' => 'Brian M.', 'loc' => 'Mikocheni, DSM'],
            ['text' => '"Pilau bora zaidi Dar! Uwasilishaji wa haraka na ufungashaji uliweka joto. Nitaagiza tena."', 'name' => 'Fatuma K.', 'loc' => 'Ilala, DSM'],
        ],
    ],
    'ar' => [
        'hero_badge'        => 'بفخر تنزاني',
        'hero_title_1'      => 'النكهة الأصيلة من',
        'hero_highlight'    => 'تنزانيا',
        'hero_title_2'      => 'تُوصَل إليك',
        'hero_sub'          => 'من أوغالي إلى بيلاو، وشواء اللحم إلى بيتزا زنجبار — اطلب المأكولات التنزانية الطازجة للتوصيل أو للمناسبات.',
        'order_now'         => 'اطلب الآن',
        'explore_menu'      => 'استكشف القائمة',
        'dishes'            => 'الأطباق',
        'orders'            => 'الطلبات',
        'rating'            => 'التقييم',
        'customers'         => 'العملاء',
        'all_items'         => 'جميع العناصر',
        'fan_favorites'     => 'المفضلة لدى الجماهير',
        'featured_dishes'   => 'الأطباق المميزة',
        'featured_sub'      => 'مفضلات مختارة بعناية يحبها عملاؤنا',
        'view_full_menu'    => 'عرض القائمة الكاملة',
        'no_featured'       => 'لا توجد أطباق مميزة الآن.',
        'browse_menu'       => 'تصفح القائمة الكاملة ←',
        'add'               => 'أضف',
        'how_title'         => 'كيف يعمل',
        'how_sub'           => 'أربع خطوات سهلة للحصول على طعام رائع',
        'step1_title'       => 'تصفح القائمة',
        'step1_desc'        => 'استكشف تشكيلتنا الواسعة من الأطباق التنزانية الأصيلة حسب الفئة.',
        'step2_title'       => 'أضف إلى السلة',
        'step2_desc'        => 'اختر أطباقك المفضلة وخصص كميات طلبك.',
        'step3_title'       => 'ادفع بسهولة',
        'step3_desc'        => 'ادفع عبر M-Pesa أو Tigo Pesa أو Airtel Money أو الدفع عند الاستلام.',
        'step4_title'       => 'استلم طلبك',
        'step4_desc'        => 'تتبع طلبك في الوقت الفعلي واستمتع بالطعام الطازج على باب منزلك.',
        'home_delivery'     => 'التوصيل للمنزل',
        'home_delivery_desc'=> 'اطلب الآن أو جدول لاحقًا. طعام طازج يُوصَل إلى باب منزلك.',
        'event_catering'    => 'تقديم طعام للمناسبات',
        'event_desc'        => 'حفلات الأعراس، حفلات المكاتب، المؤتمرات. نخدم من 10 إلى أكثر من 1000 ضيف.',
        'bulk_office'       => 'الجملة / المكتب',
        'bulk_desc'         => 'برامج غداء المكتب اليومية وتقديم الطعام بالجملة لفريقك.',
        'enquire'           => 'استفسر',
        'view_packages'     => 'عرض الباقات',
        'popular'           => 'الأكثر شيوعًا',
        'happy_customers'   => 'عملاء سعداء',
        'what_say'          => 'ماذا يقول الناس',
        'wa_title'          => 'اطلب عبر واتساب',
        'wa_desc'           => 'تفضّل الدردشة؟ أرسل لنا رسالة وسنتولى طلبك شخصيًا.',
        'wa_btn'            => 'تحدث على واتساب',
        'nl_title'          => 'احصل على عروض حصرية',
        'nl_desc'           => 'اشترك واحصل على خصم 10% على طلبك الأول + عروض أسبوعية.',
        'nl_placeholder'    => 'بريدك@الإلكتروني.com',
        'nl_btn'            => 'اشترك',
        'nl_note'           => 'لا رسائل مزعجة. إلغاء الاشتراك في أي وقت.',
        'nl_success'        => 'تم اشتراكك! تحقق من صندوق الوارد.',
        'secure_payments'   => 'مدفوعات آمنة',
        'today_special'     => 'عرض اليوم',
        'simple'            => 'بسيط',
        'just_ordered'      => 'تم الطلب للتو!',
        'fast_delivery'     => 'توصيل سريع',
        'featured'          => 'مميز',
        'combo'             => 'وجبة مجمعة',
        'bulk'              => 'بالجملة',
        'reviews'           => [
            ['text' => '"كان أوغالي ونياما تشوما مثاليًا تمامًا. يذكّرني بالطعام المطبوخ في البيت!"', 'name' => 'أمينة ج.', 'loc' => 'كينوندوني، DSM'],
            ['text' => '"خدمنا في حفل مكتبنا لـ 200 شخص. كل شيء كان في الوقت المناسب ولذيذ!"', 'name' => 'بريان م.', 'loc' => 'ميكوشيني، DSM'],
            ['text' => '"أفضل بيلاو في دار السلام! توصيل سريع والتغليف حافظ على الحرارة. سأطلب مرة أخرى."', 'name' => 'فاطمة ك.', 'loc' => 'إيلالا، DSM'],
        ],
    ],
    'fr' => [
        'hero_badge'        => 'Fièrement Tanzanien',
        'hero_title_1'      => 'Le Goût Authentique de la',
        'hero_highlight'    => 'Tanzanie',
        'hero_title_2'      => 'Livré Chez Vous',
        'hero_sub'          => 'De l\'Ugali au Pilau, du Nyama Choma à la Pizza de Zanzibar — commandez de la cuisine tanzanienne fraîche pour la livraison ou vos événements.',
        'order_now'         => 'Commander',
        'explore_menu'      => 'Explorer le Menu',
        'dishes'            => 'Plats',
        'orders'            => 'Commandes',
        'rating'            => 'Note',
        'customers'         => 'Clients',
        'all_items'         => 'Tous les Plats',
        'fan_favorites'     => 'Coups de Cœur',
        'featured_dishes'   => 'Plats Vedettes',
        'featured_sub'      => 'Les favoris sélectionnés par nos clients',
        'view_full_menu'    => 'Voir le Menu Complet',
        'no_featured'       => 'Aucun plat vedette pour le moment.',
        'browse_menu'       => 'Parcourir le menu complet →',
        'add'               => 'Ajouter',
        'how_title'         => 'Comment Ça Marche',
        'how_sub'           => 'Quatre étapes simples pour un repas délicieux',
        'step1_title'       => 'Parcourir le Menu',
        'step1_desc'        => 'Explorez notre large sélection de plats tanzaniens authentiques par catégorie.',
        'step2_title'       => 'Ajouter au Panier',
        'step2_desc'        => 'Sélectionnez vos plats préférés et personnalisez les quantités.',
        'step3_title'       => 'Payer Facilement',
        'step3_desc'        => 'Payez avec M-Pesa, Tigo Pesa, Airtel Money ou Paiement à la Livraison.',
        'step4_title'       => 'Se Faire Livrer',
        'step4_desc'        => 'Suivez votre commande en temps réel et profitez d\'un repas frais à votre porte.',
        'home_delivery'     => 'Livraison à Domicile',
        'home_delivery_desc'=> 'Commandez maintenant ou planifiez plus tard. Nourriture fraîche livrée à votre porte.',
        'event_catering'    => 'Traiteur Événementiel',
        'event_desc'        => 'Mariages, fêtes d\'entreprise, conférences. Nous servons de 10 à 1000+ convives.',
        'bulk_office'       => 'Gros / Bureau',
        'bulk_desc'         => 'Programmes de déjeuner de bureau quotidiens et restauration en gros pour votre équipe.',
        'enquire'           => 'Renseigner',
        'view_packages'     => 'Voir les Formules',
        'popular'           => 'Populaire',
        'happy_customers'   => 'Clients Satisfaits',
        'what_say'          => 'Ce Que Disent les Gens',
        'wa_title'          => 'Commander via WhatsApp',
        'wa_desc'           => 'Vous préférez discuter? Envoyez-nous un message et nous gérerons votre commande.',
        'wa_btn'            => 'Discuter sur WhatsApp',
        'nl_title'          => 'Obtenez des Offres Exclusives',
        'nl_desc'           => 'Abonnez-vous et obtenez 10% de réduction sur votre première commande.',
        'nl_placeholder'    => 'votre@email.com',
        'nl_btn'            => 'S\'abonner',
        'nl_note'           => 'Pas de spam. Désabonnez-vous à tout moment.',
        'nl_success'        => 'Vous êtes abonné ! Vérifiez votre boîte de réception.',
        'secure_payments'   => 'Paiements Sécurisés',
        'today_special'     => 'Spécial du Jour',
        'simple'            => 'Simple',
        'just_ordered'      => 'Vient d\'être commandé !',
        'fast_delivery'     => 'Livraison Rapide',
        'featured'          => 'Vedette',
        'combo'             => 'Combo',
        'bulk'              => 'Gros',
        'reviews'           => [
            ['text' => '"L\'Ugali na Nyama Choma était absolument parfait. Ça a le goût d\'un repas fait maison !"', 'name' => 'Amina J.', 'loc' => 'Kinondoni, DSM'],
            ['text' => '"Nous avons organisé notre événement de bureau pour 200 personnes. Tout était à l\'heure et délicieux !"', 'name' => 'Brian M.', 'loc' => 'Mikocheni, DSM'],
            ['text' => '"Meilleur Pilau à Dar ! Livraison rapide et l\'emballage a gardé la chaleur. Je commanderai à nouveau."', 'name' => 'Fatuma K.', 'loc' => 'Ilala, DSM'],
        ],
    ],
];

// ── Database with error handling ──────────────────────────────────────────────
try { $db = getDB(); } catch (Exception $e) { error_log('DB: ' . $e->getMessage()); $db = null; }

function safeQuery($db, string $sql, array $p = [], bool $one = false) {
    if (!$db) return $one ? null : [];
    try { $s = $db->prepare($sql); $s->execute($p); return $one ? $s->fetch() : $s->fetchAll(); }
    catch (Exception $e) { error_log('Q: ' . $e->getMessage()); return $one ? null : []; }
}

function getCached(string $key, callable $fn, int $ttl = 300) {
    if (function_exists('apcu_fetch')) {
        $v = apcu_fetch($key, $ok); if ($ok) return $v;
        $v = $fn(); apcu_store($key, $v, $ttl); return $v;
    }
    $f = sys_get_temp_dir() . '/fb_' . md5($key) . '.cache';
    if (file_exists($f) && (time() - filemtime($f)) < $ttl) return unserialize(file_get_contents($f));
    $v = $fn(); file_put_contents($f, serialize($v)); return $v;
}

$featured   = getCached('featured', fn() => safeQuery($db,
    'SELECT p.*, c.name AS category, c.icon AS cat_icon FROM products p
     LEFT JOIN categories c ON p.category_id = c.id
     WHERE p.status="active" AND p.is_featured=1 ORDER BY p.created_at DESC LIMIT 6'), 300);

$categories = getCached('cats', fn() => safeQuery($db,
    'SELECT * FROM categories ORDER BY sort_order LIMIT 20'), 600);

$stats = getCached('stats', function() use ($db) {
    $r = safeQuery($db,
        'SELECT (SELECT COUNT(*) FROM products WHERE status="active") AS products,
                (SELECT COUNT(*) FROM orders) AS orders,
                (SELECT COUNT(*) FROM users WHERE role="customer") AS customers', [], true);
    return [
        'products'  => $r ? number_format((int)$r['products'])  . '+' : '50+',
        'orders'    => $r ? number_format((int)$r['orders'])    . '+' : '200+',
        'customers' => $r ? number_format((int)$r['customers']) . '+' : '500+',
    ];
}, 120);

// Deterministic per-day pick (changes daily, stable across repeated page loads the same day)
$todaySpecial = safeQuery($db,
    'SELECT p.*, c.name AS category FROM products p
     LEFT JOIN categories c ON p.category_id = c.id
     WHERE p.status="active"
     ORDER BY RAND(TO_DAYS(CURDATE())) LIMIT 1', [], true);

$pageTitle  = 'Home';
$pageDesc   = 'FoodBites — Authentic Tanzanian catering and food delivery.';
$activePage = 'home';
require_once __DIR__ . '/includes/header.php';

// ── Active translations shorthand ──────────────────────────────────────────────
// $lang is set by header.php, now we can use it
$T = $t[$lang];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════════════════
   FOOD APP THEME — Variables
═══════════════════════════════════════════════════════════ */
:root {
    --primary:        #16A34A;   /* fiery red-orange  */
    --primary-dark:   #15803D;
    --primary-light:  #22C55E;
    --accent:         #F59E0B;   /* warm amber        */
    --accent-light:   #FCD34D;
    --success:        #25D366;
    --bg:             #FFFAF7;   /* warm off-white    */
    --surface:        #FFFFFF;
    --surface-2:      #FFF4EF;
    --text:           #1A1A1A;
    --text-muted:     #6B6B6B;
    --border:         #F0E6E0;
    --shadow-sm:      0 2px 8px rgba(22,163,74,.10);
    --shadow-md:      0 6px 24px rgba(22,163,74,.14);
    --shadow-lg:      0 16px 48px rgba(22,163,74,.18);
    --radius-sm:      8px;
    --radius-md:      16px;
    --radius-lg:      24px;
    --radius-xl:      32px;
    --font:           'Poppins', sans-serif;
    --font-ar:        'Noto Sans Arabic', sans-serif;
    --transition:     .25s cubic-bezier(.4,0,.2,1);
}

/* RTL font override */
[dir="rtl"] { font-family: var(--font-ar), var(--font); }

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: var(--font);
    background: var(--bg);
    color: var(--text);
    line-height: 1.6;
    overflow-x: hidden;
}

/* ── Utility ─────────────────────────────────────────── */
.container { max-width: 1200px; margin: 0 auto; padding: 0 1.25rem; }
.section    { padding: 5rem 0; }
.text-gradient {
    background: linear-gradient(135deg, var(--primary), var(--accent));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.btn {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .65rem 1.4rem; border-radius: 999px;
    font-family: inherit; font-weight: 600; font-size: .95rem;
    cursor: pointer; border: none; text-decoration: none;
    transition: var(--transition);
}
.btn-primary  { background: var(--primary); color: #fff; }
.btn-primary:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: var(--shadow-md); }
.btn-ghost    { background: rgba(255,255,255,.18); color: #fff; border: 2px solid rgba(255,255,255,.5); backdrop-filter: blur(6px); }
.btn-ghost:hover { background: rgba(255,255,255,.3); }
.btn-sm  { padding: .45rem 1rem; font-size: .85rem; }
.btn-lg  { padding: .8rem 1.8rem; font-size: 1rem; }
.btn-xl  { padding: .9rem 2rem;   font-size: 1.05rem; }

/* ── Language Switcher ───────────────────────────────── */
.lang-bar {
    background: var(--primary-dark);
    padding: .4rem 0;
    text-align: <?= $isRtl ? 'left' : 'right' ?>;
}
.lang-bar .container { display: flex; justify-content: flex-end; align-items: center; gap: .5rem; flex-wrap: wrap; }
[dir="rtl"] .lang-bar .container { justify-content: flex-start; }
.lang-btn {
    padding: .25rem .75rem;
    border-radius: 999px;
    font-size: .78rem;
    font-weight: 600;
    color: rgba(255,255,255,.75);
    text-decoration: none;
    border: 1.5px solid rgba(255,255,255,.2);
    transition: var(--transition);
    letter-spacing: .02em;
}
.lang-btn:hover, .lang-btn.active {
    background: #fff;
    color: var(--primary-dark);
    border-color: #fff;
}

/* ── Hero ────────────────────────────────────────────── */
.hero {
    position: relative;
    background: linear-gradient(135deg, #1a0800 0%, #3d1200 40%, #6b2000 100%);
    overflow: hidden;
    padding: 6rem 0 5rem;
    color: #fff;
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff4500' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.hero-blob {
    position: absolute; border-radius: 50%;
    filter: blur(80px); opacity: .25; pointer-events: none;
}
.blob-1 { width: 500px; height: 500px; background: var(--primary); top: -120px; <?= $isRtl ? 'left' : 'right' ?>: -100px; }
.blob-2 { width: 350px; height: 350px; background: var(--accent);  bottom: -80px; <?= $isRtl ? 'right' : 'left' ?>: -60px; }

.hero-container {
    position: relative;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: center;
}
.hero-badge {
    display: inline-flex; align-items: center; gap: .4rem;
    background: rgba(245,158,11,.15);
    border: 1px solid rgba(245,158,11,.35);
    color: var(--accent-light);
    padding: .35rem 1rem; border-radius: 999px;
    font-size: .85rem; font-weight: 600; margin-bottom: 1.25rem;
}
.hero-title { font-size: clamp(2rem, 4vw, 3.2rem); font-weight: 800; line-height: 1.15; margin-bottom: 1.25rem; }
.hero-subtitle { font-size: 1.05rem; opacity: .85; margin-bottom: 2rem; max-width: 480px; }
.hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2.5rem; }

.hero-stats {
    display: flex; gap: 1.5rem; flex-wrap: wrap;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255,255,255,.12);
}
.hero-stat { display: flex; flex-direction: column; align-items: center; }
.stat-icon  { font-size: .9rem; color: var(--accent); margin-bottom: .1rem; }
.stat-num   { font-size: 1.4rem; font-weight: 800; color: #fff; }
.stat-label { font-size: .75rem; opacity: .7; }

/* Hero visual */
.hero-visual { position: relative; display: flex; justify-content: center; align-items: center; }
.hero-img-wrap {
    position: relative; width: 380px; height: 380px;
    background: radial-gradient(circle, rgba(255,100,0,.2) 0%, transparent 70%);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
}
.hero-main-img {
    width: 320px; height: 320px; border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(245,158,11,.4);
    box-shadow: var(--shadow-lg);
}
.food-float-card {
    position: absolute;
    background: rgba(255,255,255,.12);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: var(--radius-md);
    padding: .55rem 1rem;
    font-size: .82rem; font-weight: 600; color: #fff;
    display: flex; align-items: center; gap: .4rem;
    white-space: nowrap;
    animation: floatCard 3s ease-in-out infinite;
}
.card-1 { top: 10%;  <?= $isRtl ? 'right' : 'left' ?>: -10%; animation-delay: 0s; }
.card-2 { top: 45%;  <?= $isRtl ? 'left' : 'right' ?>: -12%; animation-delay: 1s; }
.card-3 { bottom: 12%; <?= $isRtl ? 'right' : 'left' ?>: -8%; animation-delay: 2s; }
@keyframes floatCard {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(-8px); }
}

/* ── Today's Special ─────────────────────────────────── */
.todays-special-strip {
    background: linear-gradient(90deg, var(--primary), var(--accent));
    color: #fff; padding: .85rem 0;
}
[dir="rtl"] .todays-special-strip { background: linear-gradient(270deg, var(--primary), var(--accent)); }
.special-inner { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
.special-label { font-weight: 700; white-space: nowrap; display: flex; align-items: center; gap: .4rem; }
.special-info  { flex: 1; min-width: 160px; }
.special-info strong { display: block; }
.special-info span   { font-size: .83rem; opacity: .88; }

/* ── Categories ──────────────────────────────────────── */
.categories-section { background: var(--surface); border-bottom: 1px solid var(--border); padding: 1rem 0; position: sticky; top: 0; z-index: 90; box-shadow: var(--shadow-sm); }
.categories-scroll  { display: flex; gap: .6rem; overflow-x: auto; padding-bottom: .25rem; scrollbar-width: none; }
.categories-scroll::-webkit-scrollbar { display: none; }
.category-pill {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .45rem 1.1rem; border-radius: 999px;
    background: var(--surface-2); color: var(--text-muted);
    font-size: .85rem; font-weight: 500; white-space: nowrap;
    text-decoration: none; border: 1.5px solid transparent;
    transition: var(--transition);
}
.category-pill:hover, .category-pill.active {
    background: var(--primary); color: #fff;
    border-color: var(--primary);
    box-shadow: 0 2px 10px rgba(22,163,74,.3);
}

/* ── Section headers ─────────────────────────────────── */
.section-header { text-align: center; margin-bottom: 3rem; }
.section-tag {
    display: inline-flex; align-items: center; gap: .4rem;
    background: var(--surface-2); color: var(--primary);
    padding: .3rem .9rem; border-radius: 999px;
    font-size: .8rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .08em; margin-bottom: .75rem;
    border: 1px solid rgba(22,163,74,.15);
}
.section-title { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; margin-bottom: .5rem; }
.section-sub   { color: var(--text-muted); font-size: .95rem; }

/* ── Food grid ───────────────────────────────────────── */
.featured-section { background: var(--bg); }
.food-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}
.food-card {
    background: var(--surface);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border);
    transition: var(--transition);
}
.food-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-md);
    border-color: rgba(22,163,74,.2);
}
.food-card-img { position: relative; height: 200px; overflow: hidden; background: var(--surface-2); }
.food-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
.food-card:hover .food-card-img img { transform: scale(1.06); }

.food-badges { position: absolute; top: .6rem; <?= $isRtl ? 'right' : 'left' ?>: .6rem; display: flex; flex-direction: column; gap: .3rem; }
.food-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .2rem .6rem; border-radius: 999px;
    font-size: .72rem; font-weight: 700;
}
.badge-featured { background: rgba(245,158,11,.9);  color: #fff; }
.badge-combo    { background: rgba(99,102,241,.9);  color: #fff; }
.badge-bulk     { background: rgba(16,185,129,.9);  color: #fff; }

.food-card-body { padding: 1.1rem; }
.food-meta    { margin-bottom: .35rem; }
.food-category { font-size: .78rem; color: var(--primary); font-weight: 600; }
.food-name  { font-size: 1rem; font-weight: 700; margin-bottom: .35rem; line-height: 1.3; }
.food-desc  {
    font-size: .83rem; color: var(--text-muted); margin-bottom: .9rem;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.food-footer { display: flex; align-items: center; justify-content: space-between; }
.food-price  { font-size: 1.05rem; font-weight: 800; color: var(--primary); }
.btn-add-cart {
    display: inline-flex; align-items: center; gap: .35rem;
    background: var(--primary); color: #fff;
    border: none; border-radius: 999px;
    padding: .45rem 1rem; font-size: .85rem; font-weight: 600;
    cursor: pointer; transition: var(--transition);
}
.btn-add-cart:hover { background: var(--primary-dark); transform: scale(1.05); }

.empty-state { text-align: center; padding: 3rem; color: var(--text-muted); }
.empty-state i { color: var(--border); margin-bottom: 1rem; display: block; }

.section-cta { text-align: center; }

/* ── How it works ────────────────────────────────────── */
.how-section { background: var(--surface-2); }
.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem; position: relative;
}
.step-card {
    background: var(--surface); border-radius: var(--radius-lg);
    padding: 2rem 1.5rem; text-align: center;
    box-shadow: var(--shadow-sm); position: relative;
    border: 1px solid var(--border);
    transition: var(--transition);
}
.step-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
.step-num {
    font-size: .75rem; font-weight: 800; color: var(--primary);
    letter-spacing: .1em; margin-bottom: .75rem;
    background: var(--surface-2); display: inline-block;
    padding: .2rem .65rem; border-radius: 999px;
}
.step-icon {
    width: 60px; height: 60px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: #fff; margin: 0 auto .9rem;
    box-shadow: 0 4px 14px rgba(22,163,74,.3);
}
.step-card h3 { font-size: 1rem; font-weight: 700; margin-bottom: .4rem; }
.step-card p  { font-size: .85rem; color: var(--text-muted); }

/* ── Order types ─────────────────────────────────────── */
.order-types-section { background: var(--bg); }
.order-types-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;
}
.order-type-card {
    background: var(--surface); border-radius: var(--radius-lg);
    padding: 2rem 1.5rem; text-align: center;
    border: 1.5px solid var(--border);
    position: relative; transition: var(--transition);
    box-shadow: var(--shadow-sm);
}
.order-type-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); border-color: var(--primary); }
.order-type-card.featured { border-color: var(--primary); box-shadow: var(--shadow-md); }
.ot-badge {
    position: absolute; top: -12px; left: 50%; transform: translateX(-50%);
    background: var(--primary); color: #fff;
    padding: .2rem .8rem; border-radius: 999px;
    font-size: .75rem; font-weight: 700;
    white-space: nowrap;
}
.ot-icon {
    width: 64px; height: 64px; border-radius: 50%;
    background: var(--surface-2); display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; color: var(--primary);
    margin: 0 auto 1rem;
}
.order-type-card h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: .5rem; }
.order-type-card p  { font-size: .85rem; color: var(--text-muted); margin-bottom: 1.2rem; }

/* ── Testimonials ────────────────────────────────────── */
.testimonials-section { background: var(--surface-2); }
.testimonials-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; }
.testimonial-card {
    background: var(--surface); border-radius: var(--radius-lg);
    padding: 1.75rem; box-shadow: var(--shadow-sm);
    border: 1px solid var(--border); transition: var(--transition);
}
.testimonial-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
.t-stars { color: var(--accent); margin-bottom: .75rem; font-size: .9rem; }
.testimonial-card p { font-style: italic; color: var(--text-muted); margin-bottom: 1.1rem; font-size: .92rem; }
.t-author { display: flex; align-items: center; gap: .75rem; }
.t-avatar { font-size: 2.4rem; color: var(--primary); }
.t-author strong { display: block; font-size: .9rem; }
.t-author span   { font-size: .78rem; color: var(--text-muted); }

/* ── WhatsApp ────────────────────────────────────────── */
.whatsapp-section { background: #f0fdf4; padding: 2.5rem 0; }
.whatsapp-inner {
    display: flex; align-items: center; justify-content: space-between;
    gap: 1.5rem; flex-wrap: wrap;
}
.whatsapp-text h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: .3rem; display: flex; align-items: center; gap: .5rem; }
.whatsapp-text h3 i { color: #25D366; font-size: 1.4rem; }
.whatsapp-text p  { color: var(--text-muted); font-size: .9rem; }
.btn-whatsapp {
    background: #25D366; color: #fff; border: none;
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .8rem 1.8rem; border-radius: 999px;
    font-weight: 700; font-family: inherit; font-size: .95rem;
    text-decoration: none; cursor: pointer;
    transition: var(--transition); white-space: nowrap;
}
.btn-whatsapp:hover { background: #1ebe5d; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,211,102,.35); }

/* ── Newsletter ──────────────────────────────────────── */
.newsletter-section { background: var(--surface); }
.newsletter-inner { text-align: center; max-width: 540px; margin: 0 auto; }
.nl-icon { color: var(--primary); margin-bottom: .75rem; }
.newsletter-inner h3 { font-size: 1.5rem; font-weight: 800; margin-bottom: .5rem; }
.newsletter-inner > p { color: var(--text-muted); font-size: .93rem; margin-bottom: 1.5rem; }
.nl-form { display: flex; gap: .6rem; flex-wrap: wrap; justify-content: center; margin-bottom: .75rem; }
.nl-input-wrap { position: relative; flex: 1; min-width: 200px; max-width: 320px; }
.nl-input-icon { position: absolute; <?= $isRtl ? 'right' : 'left' ?>: .9rem; top: 50%; transform: translateY(-50%); color: #999; font-size: .85rem; }
.nl-input-wrap input {
    width: 100%; padding: .75rem .9rem .75rem <?= $isRtl ? '.9rem' : '2.5rem' ?>;
    <?php if ($isRtl) echo 'padding-right: 2.5rem;' ?>
    border: 1.5px solid var(--border); border-radius: 999px;
    font-family: inherit; font-size: .93rem; background: var(--bg);
    outline: none; transition: var(--transition);
}
.nl-input-wrap input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(22,163,74,.12); }
.nl-note    { font-size: .78rem; color: var(--text-muted); }
.nl-success { color: #16a34a; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: .4rem; }

/* ── Payments ────────────────────────────────────────── */
.payments-strip { background: var(--surface-2); padding: 1.5rem 0; border-top: 1px solid var(--border); }
.payments-strip .container { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; justify-content: center; }
.payments-label { font-size: .85rem; font-weight: 700; color: var(--text-muted); display: flex; align-items: center; gap: .4rem; }
.payments-icons { display: flex; gap: .6rem; flex-wrap: wrap; justify-content: center; }
.pay-chip {
    padding: .4rem 1rem; border-radius: 999px;
    font-size: .82rem; font-weight: 700;
    display: inline-flex; align-items: center; gap: .35rem;
    border: 1.5px solid transparent;
}
.mpesa    { background: #e8f5e9; color: #1b5e20; border-color: #a5d6a7; }
.tigopesa { background: #e3f2fd; color: #0d47a1; border-color: #90caf9; }
.airtel   { background: #fce4ec; color: #880e4f; border-color: #f48fb1; }
.cod      { background: #fff8e1; color: #f57f17; border-color: #ffe082; }

/* ── WhatsApp FAB ────────────────────────────────────── */
.whatsapp-fab {
    position: fixed; bottom: 1.5rem; <?= $isRtl ? 'left' : 'right' ?>: 1.5rem;
    width: 58px; height: 58px; background: #25D366; color: #fff;
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 1.7rem; box-shadow: 0 4px 16px rgba(37,211,102,.45);
    z-index: 999; text-decoration: none;
    transition: var(--transition);
    animation: pulse 2.5s infinite;
}
.whatsapp-fab:hover { transform: scale(1.12); box-shadow: 0 6px 24px rgba(37,211,102,.6); }
@keyframes pulse {
    0%,100% { box-shadow: 0 4px 16px rgba(37,211,102,.45); }
    50%      { box-shadow: 0 4px 28px rgba(37,211,102,.75); }
}

/* ── Responsive ──────────────────────────────────────── */
@media (max-width: 768px) {
    .hero-container { grid-template-columns: 1fr; text-align: center; }
    .hero-visual    { display: none; }
    .hero-actions   { justify-content: center; }
    .hero-stats     { justify-content: center; }
    .whatsapp-inner { flex-direction: column; text-align: center; }
}
</style>

<section class="hero">
    <div class="hero-blob blob-1"></div>
    <div class="hero-blob blob-2"></div>
    <div class="container hero-container">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fa-solid fa-flag"></i> <?= e($T['hero_badge']) ?>
            </div>
            <h1 class="hero-title">
                <?= e($T['hero_title_1']) ?><br>
                <span class="text-gradient"><?= e($T['hero_highlight']) ?></span><br>
                <?= e($T['hero_title_2']) ?>
            </h1>
            <p class="hero-subtitle"><?= e($T['hero_sub']) ?></p>
            <div class="hero-actions">
                <a href="<?= BASE_URL ?>/customer/menu.php" class="btn btn-primary btn-xl">
                    <i class="fa-solid fa-utensils"></i> <?= e($T['order_now']) ?>
                </a>
                <a href="#featured" class="btn btn-ghost btn-xl">
                    <i class="fa-solid fa-chevron-down"></i> <?= e($T['explore_menu']) ?>
                </a>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <i class="fa-solid fa-bowl-food stat-icon"></i>
                    <span class="stat-num"><?= $stats['products'] ?></span>
                    <span class="stat-label"><?= e($T['dishes']) ?></span>
                </div>
                <div class="hero-stat">
                    <i class="fa-solid fa-bag-shopping stat-icon"></i>
                    <span class="stat-num"><?= $stats['orders'] ?></span>
                    <span class="stat-label"><?= e($T['orders']) ?></span>
                </div>
                <div class="hero-stat">
                    <i class="fa-solid fa-star stat-icon"></i>
                    <span class="stat-num">4.9</span>
                    <span class="stat-label"><?= e($T['rating']) ?></span>
                </div>
                <div class="hero-stat">
                    <i class="fa-solid fa-users stat-icon"></i>
                    <span class="stat-num"><?= $stats['customers'] ?></span>
                    <span class="stat-label"><?= e($T['customers']) ?></span>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-img-wrap">
                <div class="food-float-card card-1">
                    <i class="fa-solid fa-plate-wheat"></i> Ugali na Nyama
                </div>
                <div class="food-float-card card-2">
                    <i class="fa-solid fa-circle-check"></i> <?= e($T['just_ordered']) ?>
                </div>
                <div class="food-float-card card-3">
                    <i class="fa-solid fa-truck-fast"></i> <?= e($T['fast_delivery']) ?>
                </div>
                <img src="<?= BASE_URL ?>/assets/images/hero_food.jpg"
                     alt="Authentic Tanzanian food"
                     class="hero-main-img" width="320" height="320"
                     onerror="this.style.display='none'">
            </div>
        </div>
    </div>
</section>

<?php if ($todaySpecial): ?>
<section class="todays-special-strip">
    <div class="container">
        <div class="special-inner">
            <div class="special-label">
                <i class="fa-solid fa-bolt"></i> <?= e($T['today_special']) ?>
            </div>
            <div class="special-info">
                <strong><?= e($todaySpecial['name']) ?></strong>
                <span><?= e(mb_substr($todaySpecial['description'] ?? '', 0, 70)) ?>…</span>
            </div>
            <div class="special-price"><?= formatPrice((float)$todaySpecial['price']) ?></div>
            <button class="btn btn-sm" style="background:#fff;color:var(--primary);font-weight:700;"
                    onclick="addToCart(<?= (int)$todaySpecial['id'] ?>, this)">
                <i class="fa-solid fa-cart-plus"></i> <?= e($T['add']) ?>
            </button>
        </div>
    </div>
</section>
<?php endif; ?>

<nav class="categories-section" aria-label="Browse by category">
    <div class="container">
        <div class="categories-scroll">
            <a href="<?= BASE_URL ?>/customer/menu.php" class="category-pill active">
                <i class="fa-solid fa-utensils"></i> <?= e($T['all_items']) ?>
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= BASE_URL ?>/customer/menu.php?category=<?= urlencode($cat['slug']) ?>"
                   class="category-pill">
                    <i class="fa-solid <?= e($cat['icon']) ?>"></i> <?= e($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</nav>

<section class="section featured-section" id="featured" aria-labelledby="featured-title">
    <div class="container">
        <div class="section-header">
            <div class="section-tag"><i class="fa-solid fa-star"></i> <?= e($T['fan_favorites']) ?></div>
            <h2 class="section-title" id="featured-title"><?= e($T['featured_dishes']) ?></h2>
            <p class="section-sub"><?= e($T['featured_sub']) ?></p>
        </div>

        <?php if (empty($featured)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-bowl-food fa-3x"></i>
                <p><?= e($T['no_featured']) ?> <a href="<?= BASE_URL ?>/customer/menu.php"><?= e($T['browse_menu']) ?></a></p>
            </div>
        <?php else: ?>
            <div class="food-grid">
                <?php foreach ($featured as $product): ?>
                    <article class="food-card" data-id="<?= (int)$product['id'] ?>">
                        <div class="food-card-img">
                            <img src="<?= productImage($product['image']) ?>"
                                 alt="<?= e($product['name']) ?>"
                                 width="320" height="200" loading="lazy"
                                 onerror="this.src='<?= BASE_URL ?>/assets/images/default_food.jpg'">
                            <div class="food-badges">
                                <?php if ($product['is_featured']): ?>
                                    <span class="food-badge badge-featured">
                                        <i class="fa-solid fa-star"></i> <?= e($T['featured']) ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($product['is_combo']): ?>
                                    <span class="food-badge badge-combo">
                                        <i class="fa-solid fa-layer-group"></i> <?= e($T['combo']) ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($product['is_bulk']): ?>
                                    <span class="food-badge badge-bulk">
                                        <i class="fa-solid fa-boxes-stacked"></i> <?= e($T['bulk']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="food-card-body">
                            <div class="food-meta">
                                <span class="food-category">
                                    <?php if (!empty($product['cat_icon'])): ?><i class="fa-solid <?= e($product['cat_icon']) ?>"></i> <?php endif; ?><?= e($product['category'] ?? '') ?>
                                </span>
                            </div>
                            <h3 class="food-name"><?= e($product['name']) ?></h3>
                            <p class="food-desc"><?= e($product['description'] ?? '') ?></p>
                            <div class="food-footer">
                                <span class="food-price"><?= formatPrice((float)$product['price']) ?></span>
                                <button class="btn-add-cart"
                                        onclick="addToCart(<?= (int)$product['id'] ?>, this)"
                                        data-id="<?= (int)$product['id'] ?>"
                                        aria-label="<?= e($T['add']) ?> <?= e($product['name']) ?>">
                                    <i class="fa-solid fa-plus"></i>
                                    <span><?= e($T['add']) ?></span>
                                </button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="section-cta">
            <a href="<?= BASE_URL ?>/customer/menu.php" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-book-open"></i> <?= e($T['view_full_menu']) ?>
            </a>
        </div>
    </div>
</section>

<section class="section how-section">
    <div class="container">
        <div class="section-header">
            <div class="section-tag"><i class="fa-solid fa-rocket"></i> <?= e($T['simple']) ?></div>
            <h2 class="section-title"><?= e($T['how_title']) ?></h2>
            <p class="section-sub"><?= e($T['how_sub']) ?></p>
        </div>
        <div class="steps-grid">
            <?php
            $steps = [
                ['fa-magnifying-glass', '01', 'step1_title', 'step1_desc'],
                ['fa-cart-shopping',    '02', 'step2_title', 'step2_desc'],
                ['fa-mobile-screen-button','03','step3_title','step3_desc'],
                ['fa-truck-fast',       '04', 'step4_title', 'step4_desc'],
            ];
            foreach ($steps as [$icon, $num, $tk, $dk]):
            ?>
                <div class="step-card">
                    <div class="step-num"><?= $num ?></div>
                    <div class="step-icon"><i class="fa-solid <?= $icon ?>"></i></div>
                    <h3><?= e($T[$tk]) ?></h3>
                    <p><?= e($T[$dk]) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section order-types-section">
    <div class="container">
        <div class="order-types-grid">
            <div class="order-type-card">
                <div class="ot-icon"><i class="fa-solid fa-house"></i></div>
                <h3><?= e($T['home_delivery']) ?></h3>
                <p><?= e($T['home_delivery_desc']) ?></p>
                <a href="<?= BASE_URL ?>/customer/menu.php" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-arrow-right"></i> <?= e($T['order_now']) ?>
                </a>
            </div>
            <div class="order-type-card featured">
                <div class="ot-badge"><i class="fa-solid fa-fire"></i> <?= e($T['popular']) ?></div>
                <div class="ot-icon"><i class="fa-solid fa-champagne-glasses"></i></div>
                <h3><?= e($T['event_catering']) ?></h3>
                <p><?= e($T['event_desc']) ?></p>
                <a href="<?= BASE_URL ?>/customer/menu.php?category=events" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-envelope"></i> <?= e($T['enquire']) ?>
                </a>
            </div>
            <div class="order-type-card">
                <div class="ot-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                <h3><?= e($T['bulk_office']) ?></h3>
                <p><?= e($T['bulk_desc']) ?></p>
                <a href="<?= BASE_URL ?>/customer/menu.php?category=combo" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-list"></i> <?= e($T['view_packages']) ?>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="section testimonials-section">
    <div class="container">
        <div class="section-header">
            <div class="section-tag"><i class="fa-solid fa-heart"></i> <?= e($T['happy_customers']) ?></div>
            <h2 class="section-title"><?= e($T['what_say']) ?></h2>
        </div>
        <div class="testimonials-grid">
            <?php foreach ($T['reviews'] as $i => $r): ?>
                <div class="testimonial-card">
                    <div class="t-stars">
                        <?php $stars = $i === 2 ? 4 : 5;
                        for ($s = 0; $s < 5; $s++) echo $s < $stars
                            ? '<i class="fa-solid fa-star"></i>'
                            : '<i class="fa-solid fa-star-half-stroke"></i>'; ?>
                    </div>
                    <p><?= e($r['text']) ?></p>
                    <div class="t-author">
                        <i class="fa-solid fa-circle-user t-avatar"></i>
                        <div>
                            <strong><?= e($r['name']) ?></strong>
                            <span><?= e($r['loc']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="whatsapp-section">
    <div class="container">
        <div class="whatsapp-inner">
            <div class="whatsapp-text">
                <h3><i class="fa-brands fa-whatsapp"></i> <?= e($T['wa_title']) ?></h3>
                <p><?= e($T['wa_desc']) ?></p>
            </div>
            <a href="https://wa.me/255700000000?text=<?= urlencode('Hello, I want to order from FoodBites!') ?>"
               target="_blank" rel="noopener noreferrer" class="btn-whatsapp">
                <i class="fa-brands fa-whatsapp"></i> <?= e($T['wa_btn']) ?>
            </a>
        </div>
    </div>
</section>

<section class="section newsletter-section">
    <div class="container">
        <div class="newsletter-inner">
            <div class="nl-icon"><i class="fa-solid fa-envelope-open-text fa-2x"></i></div>
            <h3><?= e($T['nl_title']) ?></h3>
            <p><?= e($T['nl_desc']) ?></p>
            <div class="nl-form" id="nlForm">
                <div class="nl-input-wrap">
                    <i class="fa-solid fa-envelope nl-input-icon"></i>
                    <input type="email" id="nlEmail"
                           placeholder="<?= e($T['nl_placeholder']) ?>"
                           autocomplete="email" aria-label="Email">
                </div>
                <button class="btn btn-primary" onclick="subscribeNewsletter()">
                    <i class="fa-solid fa-paper-plane"></i> <?= e($T['nl_btn']) ?>
                </button>
            </div>
            <p class="nl-note"><i class="fa-solid fa-shield-halved"></i> <?= e($T['nl_note']) ?></p>
        </div>
    </div>
</section>

<section class="payments-strip">
    <div class="container">
        <p class="payments-label"><i class="fa-solid fa-lock"></i> <?= e($T['secure_payments']) ?></p>
        <div class="payments-icons">
            <span class="pay-chip mpesa"><i class="fa-solid fa-mobile-screen"></i> M-Pesa</span>
            <span class="pay-chip tigopesa"><i class="fa-solid fa-mobile-screen"></i> Tigo Pesa</span>
            <span class="pay-chip airtel"><i class="fa-solid fa-mobile-screen"></i> Airtel Money</span>
            <span class="pay-chip cod"><i class="fa-solid fa-money-bill-wave"></i> Cash on Delivery</span>
        </div>
    </div>
</section>

<a href="https://wa.me/255700000000" target="_blank" rel="noopener noreferrer"
   class="whatsapp-fab" aria-label="<?= e($T['wa_btn']) ?>">
    <i class="fa-brands fa-whatsapp"></i>
</a>

<script src="<?= BASE_URL ?>/assets/js/cart.js"></script>
<script>
const NL_SUCCESS = <?= json_encode($T['nl_success']) ?>;

function subscribeNewsletter() {
    const email = document.getElementById('nlEmail').value.trim();
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return;
    fetch('<?= BASE_URL ?>/api/newsletter.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, lang: '<?= $lang ?>' })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            document.getElementById('nlForm').innerHTML =
                `<p class="nl-success"><i class="fa-solid fa-circle-check"></i> ${NL_SUCCESS}</p>`;
        }
    })
    .catch(console.error);
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>