<?php
// service.php — Service page template
// URL: /wedding, /newborn, /model-shoot, /maternity, /corporate, /couple-portraits

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

// Get slug from URL (via .htaccess rewrite)
$slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['slug'])) : '';

if (!$slug) {
    header('Location: ' . SITE_URL);
    exit;
}

// Fetch service
$service = getServiceBySlug($slug);
if (!$service) {
    http_response_code(404);
    // Show 404
    ?>
    <!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Not Found — Thakshi Photography</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/service.css"></head>
    <body style="display:flex;align-items:center;justify-content:center;height:100vh;flex-direction:column;gap:16px;">
    <p style="font-family:'Barlow',sans-serif;font-size:11px;letter-spacing:.2em;text-transform:uppercase;color:#888;">404</p>
    <h1 style="font-family:'Barlow',sans-serif;font-size:28px;font-weight:800;letter-spacing:.08em;">Service Not Found</h1>
    <a href="<?= SITE_URL ?>" style="font-size:13px;color:#888;text-decoration:underline;">Back to Home</a>
    </body></html>
    <?php
    exit;
}

// Fetch subcategories
$subcategories = getSubcategories((int)$service['id']);

// Hero image
$heroPath = __DIR__ . '/uploads/' . $slug . '/hero.jpg';
$heroUrl  = file_exists($heroPath)
    ? SITE_URL . '/uploads/' . $slug . '/hero.jpg'
    : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=1600&q=80'; // fallback placeholder
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($service['name']) ?> — Thakshi Photography</title>
    <meta name="description" content="<?= htmlspecialchars($service['name']) ?> photography gallery by Thakshi Photography.">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/service.css">
    <!-- Preload hero -->
    <link rel="preload" as="image" href="<?= $heroUrl ?>">
</head>
<body>

<!-- ===================== NAVBAR ===================== -->
<nav class="navbar" role="navigation" aria-label="Main navigation">
    <a class="navbar-brand" href="<?= SITE_URL ?>">Thakshi Photography</a>
    <a class="navbar-back" href="<?= SITE_URL ?>" aria-label="Back to home">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        All Services
    </a>
</nav>

<!-- ===================== HERO ===================== -->
<section class="service-hero" aria-label="<?= htmlspecialchars($service['name']) ?> hero">
    <img
        src="<?= $heroUrl ?>"
        alt="<?= htmlspecialchars($service['name']) ?>"
        fetchpriority="high"
        ondragstart="return false;"
    >
    <div class="service-hero-overlay">
        <h1 class="service-hero-title"><?= strtoupper(htmlspecialchars($service['name'])) ?></h1>
        <button id="btn-scroll-gallery" class="btn-view-gallery" aria-label="View gallery">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h16.5v16.5H3.75z"/>
            </svg>
            View Gallery
        </button>
    </div>
    <div class="hero-scroll-hint" aria-hidden="true">
        <span>Scroll</span>
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
        </svg>
    </div>
</section>

<!-- ===================== GALLERY SECTION ===================== -->
<section id="gallery-section" class="gallery-section">

    <!-- Sticky Left Sidebar -->
    <aside class="gallery-sidebar" aria-label="Service filter">
        <div class="sidebar-service-name"><?= htmlspecialchars($service['name']) ?></div>

        <?php if (!empty($subcategories)): ?>
        <div>
            <p class="sidebar-label">Filter</p>
            <ul class="subcategory-list" role="list">
                <li>
                    <button
                        class="sidebar-all-btn active"
                        data-subcat="all"
                        aria-pressed="true"
                    >All</button>
                </li>
                <?php foreach ($subcategories as $sub): ?>
                <li>
                    <button
                        data-subcat="<?= (int)$sub['id'] ?>"
                        aria-pressed="false"
                    ><?= htmlspecialchars($sub['name']) ?></button>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </aside>

    <!-- Gallery Grid -->
    <div class="gallery-main">
        <div
            id="gallery-grid"
            class="gallery-grid"
            role="list"
            aria-label="<?= htmlspecialchars($service['name']) ?> photo gallery"
        >
            <!-- Images injected by gallery.js -->
        </div>
        <div id="gallery-sentinel" aria-hidden="true"></div>
    </div>

</section>

<!-- ===================== LIGHTBOX ===================== -->
<div
    id="lightbox"
    class="lightbox"
    role="dialog"
    aria-modal="true"
    aria-label="Photo viewer"
    tabindex="-1"
>
    <div class="lightbox-topbar">
        <button id="lb-back" class="lightbox-back" aria-label="Close photo viewer">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Back
        </button>
        <span id="lb-counter" class="lightbox-counter" aria-live="polite"></span>
    </div>

    <div class="lightbox-body">
        <!-- Touch zone for swipe -->
        <div id="lb-touch-zone" class="lightbox-touch-zone" aria-hidden="true"></div>

        <!-- Desktop arrows -->
        <button id="lb-prev" class="lightbox-arrow prev" aria-label="Previous photo">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
            </svg>
        </button>

        <div class="lightbox-img-wrap">
            <img id="lb-img" src="" alt="Photo" ondragstart="return false;">
        </div>

        <button id="lb-next" class="lightbox-arrow next" aria-label="Next photo">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
            </svg>
        </button>
    </div>
</div>

<!-- Pass PHP data to JS -->
<script>
    window.SERVICE_SLUG = <?= json_encode($slug) ?>;
    window.SERVICE_ID   = <?= (int)$service['id'] ?>;
    window.SITE_URL     = <?= json_encode(SITE_URL) ?>;
</script>
<script src="<?= SITE_URL ?>/assets/js/gallery.js" defer></script>
</body>
</html>
