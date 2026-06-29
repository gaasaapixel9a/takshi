<?php
require_once __DIR__ . '/config.php';

// Fetch services from DB
try {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC");
    $services = $stmt->fetchAll();
} catch (Exception $e) {
    // Fallback if DB not set up yet
    $services = [
        ['id'=>1,'slug'=>'wedding','name'=>'Wedding'],
        ['id'=>2,'slug'=>'newborn','name'=>'New Born'],
        ['id'=>3,'slug'=>'model-shoot','name'=>'Model Shoot'],
        ['id'=>4,'slug'=>'maternity','name'=>'Maternity'],
        ['id'=>5,'slug'=>'corporate','name'=>'Corporate'],
        ['id'=>6,'slug'=>'couple-portraits','name'=>'Couple Portraits'],
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thakshi Photography</title>
    <meta name="description" content="Capturing Moments. Capturing Memories. — Professional photography services.">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>
<body>

<!-- ===================== NAVBAR ===================== -->
<nav class="navbar" role="navigation" aria-label="Main navigation">
    <span class="navbar-brand">Thakshi Photography</span>
    <div class="navbar-search" role="search">
        <input
            type="search"
            id="site-search"
            placeholder="Search"
            aria-label="Search"
            autocomplete="off"
        >
        <button class="navbar-search-btn" aria-label="Submit search" onclick="handleSearch()">
            <i class="ti ti-search" aria-hidden="true"></i>
        </button>
    </div>
</nav>

<!-- ===================== PAGE WRAP ===================== -->
<main class="page-wrap">

    <!-- HEADING -->
    <section class="home-heading">
        <h1>Thakshi Photography</h1>
        <address>Bengaluru, Karnataka, India</address>
    </section>

    <!-- SERVICES GRID -->
    <section class="services-section">
        <p class="services-label">Our Services</p>
        <div class="services-grid">
            <?php foreach ($services as $s): ?>
            <a
                class="service-card"
                href="<?= SITE_URL ?>/<?= htmlspecialchars($s['slug']) ?>"
                data-service="<?= htmlspecialchars($s['slug']) ?>"
                data-id="<?= (int)$s['id'] ?>"
                onclick="handleServiceClick(event, this)"
                aria-label="<?= htmlspecialchars($s['name']) ?> photography"
            >
                <!-- Hero image: upload to /uploads/SERVICE_SLUG/hero.jpg -->
                <?php
                $heroPath = __DIR__ . '/uploads/' . $s['slug'] . '/hero.jpg';
                $heroUrl = file_exists($heroPath)
                    ? SITE_URL . '/uploads/' . $s['slug'] . '/hero.jpg'
                    : SITE_URL . '/assets/images/placeholder-' . $s['slug'] . '.jpg';
                ?>
                <img
                    src="<?= $heroUrl ?>"
                    alt="<?= htmlspecialchars($s['name']) ?>"
                    loading="lazy"
                    ondragstart="return false;"
                >
                <div class="service-card-overlay"></div>
                <span class="service-card-name"><?= htmlspecialchars($s['name']) ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<!-- ===================== FOOTER ===================== -->
<footer class="footer">
    <div class="footer-top">

        <!-- Brand -->
        <div class="footer-brand">
            <div class="footer-logo-box" aria-hidden="true">
                <span>TP</span>
            </div>
            <p class="footer-name">Thakshi Photography</p>
            <p class="footer-tagline">Capturing Moments.<br>Capturing Memories.</p>
        </div>

        <!-- Contact -->
        <div class="footer-col">
            <h4>Contact</h4>
            <ul class="footer-contact-list">
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    <a href="tel:7676908368">7676908368</a>
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    <a href="mailto:thakshiphotography@gmail.com">thakshiphotography@gmail.com</a>
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-linecap="round" stroke-linejoin="round"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-linecap="round"/></svg>
                    <a href="https://instagram.com/thakshi_photography_" target="_blank" rel="noopener">thakshi_photography_</a>
                </li>
            </ul>
        </div>

        <!-- Services -->
        <div class="footer-col">
            <h4>Services</h4>
            <ul class="footer-services-list">
                <?php foreach ($services as $s): ?>
                <li>
                    <a href="<?= SITE_URL ?>/<?= htmlspecialchars($s['slug']) ?>">
                        <?= htmlspecialchars($s['name']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> Thakshi Photography. All rights reserved.</p>
        <p>Powered by <a href="#" target="_blank" rel="noopener">Basebit</a></p>
    </div>
</footer>

<!-- ===================== SCRIPTS ===================== -->
<script>
// Disable right-click
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    return false;
});

// Disable common copy shortcuts on images
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && ['s','u','p'].includes(e.key.toLowerCase())) {
        e.preventDefault();
    }
    if (e.key === 'F12') e.preventDefault();
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && ['i','j','c'].includes(e.key.toLowerCase())) {
        e.preventDefault();
    }
});

// Prevent drag on all images
document.querySelectorAll('img').forEach(img => {
    img.addEventListener('dragstart', e => e.preventDefault());
});

// Search
function handleSearch() {
    const q = document.getElementById('site-search').value.trim();
    if (q) window.location.href = '/?search=' + encodeURIComponent(q);
}
document.getElementById('site-search').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') handleSearch();
});

// Service click — will show access popup (Phase 3)
// For now, navigates directly
function handleServiceClick(e, el) {
    // Phase 3 will intercept this and show the popup if not authorized
    // For now: let the link work normally
}
</script>
</body>
</html>
