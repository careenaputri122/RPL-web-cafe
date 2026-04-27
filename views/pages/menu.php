<?php
// Pastikan variabel tersedia
$menus    = $menus    ?? [];
$category = $category ?? 'semua';
$search   = $search   ?? '';

// Helper format harga Indonesia
function formatHarga(int $harga): string {
    return 'Rp ' . number_format($harga, 0, ',', '.');
}
?>

<!-- ══════════════════════════════════════════════════
     HALAMAN MENU — Damian Cafe
     ══════════════════════════════════════════════════ -->

<style>
/* ── Page wrapper ──────────────────────────── */
.dc-menu-page {
    background-color: #ffffff;
    min-height: 100vh;
    padding-top: 80px; /* tinggi navbar */
}

/* ── Hero / heading section ────────────────── */
.dc-menu-hero {
    padding: 56px 0 32px;
}
.dc-menu-label {
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #f59e0b;
    margin-bottom: 10px;
}
.dc-menu-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 800;
    color: #111827;
    line-height: 1.15;
    margin-bottom: 12px;
}
.dc-menu-subtitle {
    color: #6b7280;
    font-size: 0.975rem;
    max-width: 460px;
}

/* ── Search + Filter bar ───────────────────── */
.dc-filter-bar {
    padding: 24px 0 36px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 16px;
}
.dc-search-wrap {
    position: relative;
    flex: 1 1 280px;
    max-width: 420px;
}
.dc-search-wrap .dc-search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 0.9rem;
    pointer-events: none;
}
.dc-search-input {
    width: 100%;
    padding: 12px 16px 12px 42px;
    border: 1.5px solid #e5e7eb;
    border-radius: 50px;
    font-size: 0.9rem;
    color: #374151;
    background: #fff;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.dc-search-input::placeholder { color: #9ca3af; }
.dc-search-input:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245,158,11,.12);
}

/* ── Filter pills ──────────────────────────── */
.dc-filter-pills {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.dc-pill {
    padding: 10px 22px;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 500;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    color: #374151;
    cursor: pointer;
    text-decoration: none;
    transition: all .2s;
    white-space: nowrap;
}
.dc-pill:hover {
    border-color: #f59e0b;
    color: #f59e0b;
    text-decoration: none;
}
.dc-pill.active {
    background: #1a1a2e;
    border-color: #1a1a2e;
    color: #fff;
}

/* ── Menu grid ─────────────────────────────── */
.dc-menu-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    padding-bottom: 80px;
}
@media (max-width: 1200px) { .dc-menu-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 860px)  { .dc-menu-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 540px)  { .dc-menu-grid { grid-template-columns: 1fr; } }

/* ── Card ──────────────────────────────────── */
.dc-card {
    background: #fff;
    border: 1.5px solid #f3f4f6;
    border-radius: 16px;
    overflow: hidden;
    transition: transform .25s, box-shadow .25s;
    position: relative;
}
.dc-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 40px rgba(0,0,0,.09);
}
.dc-card.dc-card--habis { opacity: .85; }

/* card top badges */
.dc-card-badges {
    position: absolute;
    top: 12px;
    left: 12px;
    right: 12px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    z-index: 2;
    pointer-events: none;
}
.dc-badge-cat {
    background: rgba(255,255,255,.88);
    backdrop-filter: blur(6px);
    border-radius: 50px;
    padding: 4px 12px;
    font-size: 0.72rem;
    font-weight: 600;
    color: #374151;
    text-transform: capitalize;
}
.dc-badge-status {
    border-radius: 50px;
    padding: 4px 12px;
    font-size: 0.72rem;
    font-weight: 600;
}
.dc-badge-status.tersedia {
    background: rgba(220,252,231,.9);
    color: #15803d;
}
.dc-badge-status.habis {
    background: rgba(254,226,226,.9);
    color: #dc2626;
}

/* image container */
.dc-card-img-wrap {
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    background: #f9fafb;
}
.dc-card-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s ease;
}
.dc-card:hover .dc-card-img-wrap img { transform: scale(1.07); }

/* card body */
.dc-card-body {
    padding: 16px 18px 20px;
}
.dc-card-name {
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 6px;
    line-height: 1.3;
}
.dc-card-desc {
    font-size: 0.8rem;
    color: #6b7280;
    line-height: 1.5;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.dc-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.dc-card-price {
    font-size: 1rem;
    font-weight: 700;
    color: #f59e0b;
}
.dc-btn-add {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f59e0b;
    border: none;
    color: #fff;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background .2s, transform .15s;
    flex-shrink: 0;
}
.dc-btn-add:hover { background: #d97706; transform: scale(1.1); }
.dc-btn-add:disabled {
    background: #d1d5db;
    cursor: not-allowed;
    transform: none;
}

/* ── Empty state ───────────────────────────── */
.dc-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 0;
    color: #9ca3af;
}
.dc-empty i { font-size: 3rem; margin-bottom: 16px; display: block; }
.dc-empty p { font-size: 1rem; }

/* ── Result count ──────────────────────────── */
.dc-result-count {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 20px;
}
.dc-result-count span { color: #111827; font-weight: 600; }
</style>

<div class="dc-menu-page">
<div class="container">

    <!-- ── Heading ─────────────────────────────────────── -->
    <div class="dc-menu-hero">
        <p class="dc-menu-label">Katalog</p>
        <h1 class="dc-menu-title">Menu Kami</h1>
        <p class="dc-menu-subtitle">Temukan hidangan favorit Anda dari pilihan menu autentik kami</p>
    </div>

    <!-- ── Filter bar ──────────────────────────────────── -->
    <form method="GET" action="<?= base_url() ?>">
        <input type="hidden" name="page" value="menu">
        <div class="dc-filter-bar">
            <!-- Search -->
            <div class="dc-search-wrap">
                <i class="fa fa-search dc-search-icon"></i>
                <input
                    type="text"
                    name="q"
                    class="dc-search-input"
                    placeholder="Cari menu..."
                    value="<?= htmlspecialchars($search) ?>"
                    autocomplete="off"
                >
            </div>
            <!-- Pills -->
            <div class="dc-filter-pills">
                <?php
                $tabs = [
                    'semua'   => 'Semua',
                    'makanan' => 'Makanan',
                    'minuman' => 'Minuman',
                    'dessert' => 'Dessert',
                ];
                foreach ($tabs as $val => $label):
                    $isActive = ($category === $val) ? 'active' : '';
                ?>
                    <a href="<?= base_url('?page=menu&kategori=' . $val . ($search ? '&q=' . urlencode($search) : '')) ?>"
                       class="dc-pill <?= $isActive ?>">
                        <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </form>

    <!-- ── Result info ─────────────────────────────────── -->
    <?php if ($search !== ''): ?>
        <p class="dc-result-count">
            Menampilkan <span><?= count($menus) ?></span> hasil untuk
            "<span><?= htmlspecialchars($search) ?></span>"
        </p>
    <?php endif; ?>

    <!-- ── Menu grid ───────────────────────────────────── -->
    <div class="dc-menu-grid">

        <?php if (empty($menus)): ?>
            <div class="dc-empty">
                <i class="fa fa-utensils"></i>
                <p>Menu tidak ditemukan.<br>Coba kata kunci lain atau pilih kategori berbeda.</p>
            </div>

        <?php else: foreach ($menus as $item):
            $tersedia = strtolower($item['status']) === 'tersedia';
            $kat      = ucfirst($item['kategori']);
            $imgSrc   = !empty($item['gambar']) ? $item['gambar'] : asset('img/placeholder.jpg');
        ?>
            <div class="dc-card <?= !$tersedia ? 'dc-card--habis' : '' ?>">

                <!-- Badges -->
                <div class="dc-card-badges">
                    <span class="dc-badge-cat"><?= htmlspecialchars($kat) ?></span>
                    <span class="dc-badge-status <?= $tersedia ? 'tersedia' : 'habis' ?>">
                        <?= $tersedia ? 'Tersedia' : 'Habis' ?>
                    </span>
                </div>

                <!-- Image -->
                <div class="dc-card-img-wrap">
                    <img
                        src="<?= htmlspecialchars($imgSrc) ?>"
                        alt="<?= htmlspecialchars($item['nama']) ?>"
                        loading="lazy"
                        onerror="this.src='https://placehold.co/400x400/fdf8f0/f59e0b?text=<?= urlencode($item['nama']) ?>'"
                    >
                </div>

                <!-- Body -->
                <div class="dc-card-body">
                    <p class="dc-card-name"><?= htmlspecialchars($item['nama']) ?></p>
                    <p class="dc-card-desc"><?= htmlspecialchars($item['deskripsi']) ?></p>
                    <div class="dc-card-footer">
                        <span class="dc-card-price"><?= formatHarga((int)$item['harga']) ?></span>
                        <button
                            class="dc-btn-add"
                            <?= !$tersedia ? 'disabled' : '' ?>
                            title="<?= $tersedia ? 'Tambah ke pesanan' : 'Menu habis' ?>"
                            onclick="dcAddToCart(<?= (int)$item['id'] ?>, '<?= htmlspecialchars(addslashes($item['nama'])) ?>', <?= (int)$item['harga'] ?>)"
                        >
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

        <?php endforeach; endif; ?>
    </div>

</div><!-- /container -->
</div><!-- /dc-menu-page -->

<script>
/**
 * Placeholder handler tombol "+" — nanti bisa disambungkan ke cart session/localStorage.
 * Untuk sekarang tampilkan toast Bootstrap.
 */
function dcAddToCart(id, nama, harga) {
    // Buat toast container kalau belum ada
    let tc = document.getElementById('dc-toast-container');
    if (!tc) {
        tc = document.createElement('div');
        tc.id = 'dc-toast-container';
        tc.className = 'position-fixed bottom-0 end-0 p-3';
        tc.style.zIndex = '9999';
        document.body.appendChild(tc);
    }

    const toastId = 'toast-' + Date.now();
    tc.innerHTML += `
        <div id="${toastId}" class="toast align-items-center text-white border-0" role="alert"
             style="background:#f59e0b; min-width:260px;">
            <div class="d-flex">
                <div class="toast-body fw-semibold">
                    <i class="fa fa-check-circle me-2"></i>${nama} ditambahkan!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
            </div>
        </div>`;

    const el = document.getElementById(toastId);
    const toast = new bootstrap.Toast(el, { delay: 2500 });
    toast.show();
    el.addEventListener('hidden.bs.toast', () => el.remove());
}

// Auto-submit search saat user berhenti mengetik (debounce 400ms)
(function () {
    const input = document.querySelector('.dc-search-input');
    if (!input) return;
    let timer;
    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => input.closest('form').submit(), 400);
    });
})();
</script>
