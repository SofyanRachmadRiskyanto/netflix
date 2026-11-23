<?php
declare(strict_types=1);

require __DIR__ . DIRECTORY_SEPARATOR . 'auth.php';
start_app_session();
require_login();

$sections = [
    [
        'id' => 'trending',
        'title' => 'Sedang ramai dibicarakan',
        'items' => [
            ['title' => 'Kota Setelah Hujan', 'category' => 'Serial | Drama', 'style' => 'poster--rain'],
            ['title' => 'Sinyal dari Utara', 'category' => 'Serial | Misteri', 'style' => 'poster--signal'],
            ['title' => 'Ruang Antara', 'category' => 'Serial | Fiksi ilmiah', 'style' => 'poster--orbit'],
            ['title' => 'Musim yang Berbeda', 'category' => 'Serial | Keluarga', 'style' => 'poster--season'],
            ['title' => 'Jejak Samudra', 'category' => 'Film | Petualangan', 'style' => 'poster--ocean'],
            ['title' => 'Pulang Sebelum Pagi', 'category' => 'Film | Drama', 'style' => 'poster--dawn'],
        ],
    ],
    [
        'id' => 'serial',
        'title' => 'Serial pilihan iflix',
        'items' => [
            ['title' => 'Kota Setelah Hujan', 'category' => 'Drama | 8 episode', 'style' => 'poster--rain'],
            ['title' => 'Sinyal dari Utara', 'category' => 'Misteri | 6 episode', 'style' => 'poster--signal'],
            ['title' => 'Ruang Antara', 'category' => 'Fiksi ilmiah | 10 episode', 'style' => 'poster--orbit'],
            ['title' => 'Musim yang Berbeda', 'category' => 'Keluarga | 8 episode', 'style' => 'poster--season'],
            ['title' => 'Laut di Ujung Jalan', 'category' => 'Petualangan | 6 episode', 'style' => 'poster--ocean'],
        ],
    ],
    [
        'id' => 'film',
        'title' => 'Film untuk malam ini',
        'items' => [
            ['title' => 'Jejak Samudra', 'category' => 'Petualangan | 1 jam 52 menit', 'style' => 'poster--ocean'],
            ['title' => 'Pulang Sebelum Pagi', 'category' => 'Drama | 1 jam 41 menit', 'style' => 'poster--dawn'],
            ['title' => 'Langit Terakhir', 'category' => 'Aksi | 2 jam 3 menit', 'style' => 'poster--sky'],
            ['title' => 'Kebun Rahasia', 'category' => 'Fantasi | 1 jam 48 menit', 'style' => 'poster--garden'],
            ['title' => 'Sisi Kota', 'category' => 'Drama | 1 jam 37 menit', 'style' => 'poster--signal'],
        ],
    ],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - iflix</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page home-page">
        <header class="home-header">
            <a class="home-brand" href="#beranda" aria-label="iflix beranda">iflix</a>
            <nav class="home-nav" aria-label="Navigasi utama">
                <a class="active" href="#beranda">Beranda</a>
                <a href="#serial">Serial</a>
                <a href="#film">Film</a>
            </nav>
            <div class="home-account">
                <div class="home-tools">
                    <div class="search-control" role="search">
                        <button class="icon-button" id="search-toggle" type="button" aria-label="Buka pencarian" aria-controls="catalog-search" aria-expanded="false">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="10.8" cy="10.8" r="6.8"></circle>
                                <path d="m16 16 5 5"></path>
                            </svg>
                        </button>
                        <input class="search-input" id="catalog-search" type="search" placeholder="Cari judul..." aria-label="Cari judul" autocomplete="off" hidden>
                    </div>
                    <details class="notification-control">
                        <summary class="icon-button" aria-label="Buka notifikasi">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                                <path d="M10 21h4"></path>
                            </svg>
                            <span class="notification-badge">5</span>
                        </summary>
                        <div class="notification-panel">
                            <h2>Notifikasi</h2>
                            <ul>
                                <li><span class="notification-dot"></span><div><strong>Episode baru tersedia</strong><p>Lanjutan Kota Setelah Hujan sudah tayang.</p></div></li>
                                <li><span class="notification-dot"></span><div><strong>Pilihan baru untukmu</strong><p>Temukan cerita pilihan minggu ini.</p></div></li>
                                <li><span class="notification-dot"></span><div><strong>Jangan lewatkan</strong><p>Sinyal dari Utara menantimu.</p></div></li>
                                <li><span class="notification-dot"></span><div><strong>Koleksi diperbarui</strong><p>Ada film baru di pilihan iflix.</p></div></li>
                                <li><span class="notification-dot"></span><div><strong>Selamat datang!</strong><p>Mulai jelajahi koleksi iflix.</p></div></li>
                            </ul>
                        </div>
                    </details>
                </div>
                <span class="profile-email"><?= escape_html((string) $_SESSION['email']) ?></span>
                <form method="post" action="logout.php">
                    <input type="hidden" name="csrf_token" value="<?= escape_html(csrf_token()) ?>">
                    <button class="signout-button" type="submit">Keluar</button>
                </form>
            </div>
        </header>

        <main class="home-main" id="beranda">
            <section class="hero-feature" aria-labelledby="hero-title">
                <div class="hero-art" aria-hidden="true">
                    <span class="hero-orb"></span>
                    <span class="hero-line"></span>
                </div>
                <div class="hero-copy">
                    <span class="eyebrow">SERIAL PILIHAN IFLIX</span>
                    <h1 id="hero-title">Di Balik Cahaya</h1>
                    <div class="hero-meta">
                        <span>2026</span>
                        <span class="meta-divider" aria-hidden="true"></span>
                        <span>Drama petualangan</span>
                        <span class="age-rating">13+</span>
                    </div>
                    <p>
                        Sebuah perjalanan pulang membawa Alya pada rahasia kecil yang mengubah
                        cara pandangnya tentang keluarga, rumah, dan kesempatan kedua.
                    </p>
                    <div class="hero-actions">
                        <a class="primary-link" href="#trending"><span aria-hidden="true">&#9654;</span> Jelajahi koleksi</a>
                        <a class="secondary-link" href="#serial"><span aria-hidden="true">i</span> Lihat serial</a>
                    </div>
                </div>
                <div class="hero-gradient" aria-hidden="true"></div>
            </section>

            <div class="catalog">
                <?php foreach ($sections as $section): ?>
                    <section class="content-section" id="<?= escape_html($section['id']) ?>">
                        <div class="section-heading">
                            <h2><?= escape_html($section['title']) ?></h2>
                            <span class="scroll-hint">Geser untuk melihat lebih banyak</span>
                        </div>
                        <div class="media-row" tabindex="0" aria-label="<?= escape_html($section['title']) ?>">
                            <?php foreach ($section['items'] as $item): ?>
                                <article class="media-card">
                                    <div class="poster <?= escape_html($item['style']) ?>">
                                        <span class="poster-label">IFLIX ORIGINAL</span>
                                        <span class="poster-title"><?= escape_html($item['title']) ?></span>
                                    </div>
                                    <h3><?= escape_html($item['title']) ?></h3>
                                    <p><?= escape_html($item['category']) ?></p>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>
            <p class="empty-search" id="empty-search" hidden>Tidak ada judul yang cocok.</p>
        </main>

        <footer class="home-footer">
            <span>iflix</span>
            <span>Jelajahi cerita baru setiap hari.</span>
        </footer>
    </div>
    <script>
        const searchToggle = document.querySelector('#search-toggle');
        const searchInput = document.querySelector('#catalog-search');
        const mediaCards = Array.from(document.querySelectorAll('.media-card'));
        const contentSections = Array.from(document.querySelectorAll('.content-section'));
        const emptySearch = document.querySelector('#empty-search');

        function filterCatalog() {
            const query = searchInput.value.trim().toLocaleLowerCase('id-ID');
            let hasResults = false;

            mediaCards.forEach((card) => {
                const matches = card.textContent.toLocaleLowerCase('id-ID').includes(query);
                card.hidden = !matches;
                hasResults = hasResults || matches;
            });

            contentSections.forEach((section) => {
                section.hidden = !section.querySelector('.media-card:not([hidden])');
            });

            emptySearch.hidden = hasResults;
        }

        searchToggle.addEventListener('click', () => {
            const isOpening = searchInput.hidden;
            searchInput.hidden = !isOpening;
            searchToggle.setAttribute('aria-expanded', String(isOpening));

            if (isOpening) {
                searchInput.focus();
            } else {
                searchInput.value = '';
                filterCatalog();
            }
        });

        searchInput.addEventListener('input', filterCatalog);
        searchInput.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                searchToggle.click();
                searchToggle.focus();
            }
        });
    </script>
</body>
</html>
