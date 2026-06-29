/* ============================================================
   gallery.js — Lazy load gallery + Lightbox with swipe
   ============================================================ */

(function () {
  'use strict';

  /* ── Config ─────────────────────────────────────────── */
  const SERVICE_SLUG = window.SERVICE_SLUG || '';
  const SERVICE_ID   = window.SERVICE_ID   || 0;
  const BATCH_SIZE   = 9;

  /* ── State ──────────────────────────────────────────── */
  let offset          = 0;
  let loading         = false;
  let allLoaded       = false;
  let activeSubcat    = null;   // null = all
  let imageIndex      = 0;     // current lightbox index
  let imageCache      = [];    // all loaded image objects [{id,filepath,filename}]
  let touchStartX     = 0;
  let touchStartY     = 0;

  /* ── DOM refs ───────────────────────────────────────── */
  const grid      = document.getElementById('gallery-grid');
  const sentinel  = document.getElementById('gallery-sentinel');
  const lightbox  = document.getElementById('lightbox');
  const lbImg     = document.getElementById('lb-img');
  const lbCounter = document.getElementById('lb-counter');
  const lbPrev    = document.getElementById('lb-prev');
  const lbNext    = document.getElementById('lb-next');
  const lbBack    = document.getElementById('lb-back');

  /* ── Fetch images from API ──────────────────────────── */
  function fetchImages(reset) {
    if (loading || allLoaded) return;
    loading = true;

    if (reset) {
      offset = 0;
      allLoaded = false;
      imageCache = [];
      grid.innerHTML = renderSkeletons();
    }

    const params = new URLSearchParams({
      service_id: SERVICE_ID,
      offset,
      limit: BATCH_SIZE,
    });
    if (activeSubcat) params.set('subcat_id', activeSubcat);

    fetch('/api/gallery.php?' + params.toString())
      .then(r => r.json())
      .then(data => {
        loading = false;

        // Remove skeletons / spinner
        grid.querySelectorAll('.gallery-skeleton, .gallery-loading-spinner').forEach(el => el.remove());

        if (!data.images || data.images.length === 0) {
          allLoaded = true;
          if (imageCache.length === 0) {
            grid.innerHTML = '<p style="grid-column:1/-1;padding:48px;text-align:center;color:#888;font-size:13px;letter-spacing:.06em;">No images yet.</p>';
          } else {
            showViewMoreBtn();
          }
          return;
        }

        data.images.forEach(img => {
          imageCache.push(img);
          const item = buildGalleryItem(img, imageCache.length - 1);
          grid.appendChild(item);
        });

        offset += data.images.length;

        if (data.images.length < BATCH_SIZE) {
          allLoaded = true;
          showViewMoreBtn();
        }
      })
      .catch(() => {
        loading = false;
        grid.querySelectorAll('.gallery-skeleton, .gallery-loading-spinner').forEach(el => el.remove());
        grid.insertAdjacentHTML('beforeend',
          '<p style="grid-column:1/-1;padding:48px;text-align:center;color:#888;font-size:13px;">Failed to load images.</p>');
      });
  }

  /* ── Build gallery item ─────────────────────────────── */
  function buildGalleryItem(imgData, index) {
    const div = document.createElement('div');
    div.className = 'gallery-item';
    div.setAttribute('data-index', index);
    div.setAttribute('role', 'button');
    div.setAttribute('aria-label', 'View photo ' + (index + 1));
    div.setAttribute('tabindex', '0');

    const img = document.createElement('img');
    img.src = imgData.url;
    img.alt = imgData.filename || 'Photo';
    img.loading = 'lazy';
    img.ondragstart = () => false;

    const overlay = document.createElement('div');
    overlay.className = 'gallery-item-overlay';

    div.appendChild(img);
    div.appendChild(overlay);

    div.addEventListener('click', () => openLightbox(index));
    div.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') openLightbox(index); });

    return div;
  }

  /* ── Skeletons while loading ────────────────────────── */
  function renderSkeletons() {
    return Array.from({ length: BATCH_SIZE }, () =>
      '<div class="gallery-skeleton" aria-hidden="true"></div>'
    ).join('');
  }

  /* ── Append loading spinner (for scroll trigger) ────── */
  function appendSpinner() {
    const div = document.createElement('div');
    div.className = 'gallery-loading-spinner';
    div.setAttribute('aria-hidden', 'true');
    div.innerHTML = '<div class="spinner"></div>';
    grid.appendChild(div);
  }

  /* ── View More button ───────────────────────────────── */
  function showViewMoreBtn() {
    // Remove existing
    const existing = grid.querySelector('.gallery-end');
    if (existing) existing.remove();

    const end = document.createElement('div');
    end.className = 'gallery-end';
    end.innerHTML = '<button class="btn-view-more" onclick="window.scrollTo({top:document.body.scrollHeight,behavior:\'smooth\'})">View More</button>';
    grid.appendChild(end);
  }

  /* ── Infinite scroll observer ───────────────────────── */
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting && !loading && !allLoaded) {
        appendSpinner();
        fetchImages(false);
      }
    });
  }, { rootMargin: '400px' });

  if (sentinel) observer.observe(sentinel);

  /* ── Subcategory filter ─────────────────────────────── */
  document.querySelectorAll('[data-subcat]').forEach(btn => {
    btn.addEventListener('click', function () {
      document.querySelectorAll('[data-subcat], .sidebar-all-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      activeSubcat = this.dataset.subcat === 'all' ? null : this.dataset.subcat;
      allLoaded = false;
      fetchImages(true);
    });
  });

  /* ── Scroll to gallery ──────────────────────────────── */
  const scrollBtn = document.getElementById('btn-scroll-gallery');
  const gallerySection = document.getElementById('gallery-section');
  if (scrollBtn && gallerySection) {
    scrollBtn.addEventListener('click', () => {
      gallerySection.scrollIntoView({ behavior: 'smooth' });
    });
  }

  /* ================================================================
     LIGHTBOX
  ================================================================ */
  function openLightbox(index) {
    imageIndex = index;
    renderLightboxImg();
    lightbox.classList.add('open');
    document.body.style.overflow = 'hidden';
    lightbox.focus();
  }

  function closeLightbox() {
    lightbox.classList.remove('open');
    document.body.style.overflow = '';
  }

  function renderLightboxImg() {
    const item = imageCache[imageIndex];
    if (!item) return;
    lbImg.src = item.url;
    lbImg.alt = item.filename || 'Photo';
    lbCounter.textContent = (imageIndex + 1) + ' / ' + imageCache.length;
    lbPrev.disabled = imageIndex === 0;
    lbNext.disabled = imageIndex === imageCache.length - 1;

    // Pre-load adjacent
    if (imageIndex + 1 < imageCache.length) {
      const preload = new Image();
      preload.src = imageCache[imageIndex + 1].url;
    }
    if (imageIndex - 1 >= 0) {
      const preload = new Image();
      preload.src = imageCache[imageIndex - 1].url;
    }
  }

  function prevImage() {
    if (imageIndex > 0) { imageIndex--; renderLightboxImg(); }
  }

  function nextImage() {
    if (imageIndex < imageCache.length - 1) {
      imageIndex++;
      renderLightboxImg();
      // If near end and more to load, trigger load
      if (imageIndex >= imageCache.length - 3 && !allLoaded) {
        appendSpinner();
        fetchImages(false);
      }
    }
  }

  /* Buttons */
  if (lbBack)  lbBack.addEventListener('click', closeLightbox);
  if (lbPrev)  lbPrev.addEventListener('click', prevImage);
  if (lbNext)  lbNext.addEventListener('click', nextImage);

  /* Keyboard */
  document.addEventListener('keydown', e => {
    if (!lightbox.classList.contains('open')) return;
    if (e.key === 'ArrowLeft')  prevImage();
    if (e.key === 'ArrowRight') nextImage();
    if (e.key === 'Escape')     closeLightbox();
  });

  /* Touch swipe */
  const touchZone = document.getElementById('lb-touch-zone');
  if (touchZone) {
    touchZone.addEventListener('touchstart', e => {
      touchStartX = e.changedTouches[0].screenX;
      touchStartY = e.changedTouches[0].screenY;
    }, { passive: true });

    touchZone.addEventListener('touchend', e => {
      const dx = e.changedTouches[0].screenX - touchStartX;
      const dy = e.changedTouches[0].screenY - touchStartY;
      if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 40) {
        if (dx < 0) nextImage();
        else        prevImage();
      }
    }, { passive: true });
  }

  /* ── Init ────────────────────────────────────────────── */
  fetchImages(true);

  /* ── Photo protection ───────────────────────────────── */
  document.addEventListener('contextmenu', e => e.preventDefault());
  document.addEventListener('keydown', e => {
    if ((e.ctrlKey || e.metaKey) && ['s','u','p'].includes(e.key.toLowerCase())) e.preventDefault();
    if (e.key === 'F12') e.preventDefault();
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && ['i','j','c'].includes(e.key.toLowerCase())) e.preventDefault();
  });
  document.querySelectorAll('img').forEach(img => {
    img.addEventListener('dragstart', e => e.preventDefault());
  });

})();
