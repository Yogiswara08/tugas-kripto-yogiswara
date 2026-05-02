<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Labs - Kripto & FPB</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">🔬 CryptoLab</div>
            <div class="nav-links">
                <a href="index.php"        class="nav-link active">🏠 Beranda</a>
                <a href="fpb.php"          class="nav-link">🔢 FPB Calculator</a>
                <a href="kripto.php"       class="nav-link">🔐 Kriptografi</a>
                <a href="simulasi_rsa.php" class="nav-link">🛡️ Simulasi RSA</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="hero">
            <h1>Laboratorium Kriptografi & Matematika</h1>
            <p>Kumpulan alat bantu untuk pembelajaran kriptografi dan algoritma matematika</p>
        </div>

        <div class="projects-grid">
            <!-- Project 1: FPB Calculator -->
            <a href="fpb.php" class="project-card">
                <div class="card-header">
                    <div class="card-icon">🔢</div>
                    <h2>Kalkulator FPB</h2>
                </div>
                <div class="card-body">
                    <p>Menghitung Faktor Persekutuan Terbesar (FPB) menggunakan <strong>Algoritma Euclidean</strong> dengan langkah-langkah detail.</p>
                    <div class="card-tags">
                        <span class="tag">Algoritma Euclidean</span>
                        <span class="tag">Matematika</span>
                        <span class="tag">FPB</span>
                    </div>
                    <span class="btn-card">Hitung Sekarang →</span>
                </div>
            </a>

            <!-- Project 2: Kriptografi -->
            <a href="kripto.php" class="project-card">
                <div class="card-header">
                    <div class="card-icon">🔐</div>
                    <h2>Enkripsi & Dekripsi</h2>
                </div>
                <div class="card-body">
                    <p>Mengenkripsi dan mendekripsi pesan menggunakan <strong>Caesar Cipher</strong> dan <strong>Vigenère Cipher</strong>.</p>
                    <div class="card-tags">
                        <span class="tag">Caesar Cipher</span>
                        <span class="tag">Vigenère Cipher</span>
                        <span class="tag">Kriptografi</span>
                    </div>
                    <span class="btn-card">Coba Sekarang →</span>
                </div>
            </a>

            <!-- Project 3: Simulasi RSA -->
            <a href="simulasi_rsa.php" class="project-card">
                <div class="card-header">
                    <div class="card-icon">🛡️</div>
                    <h2>Simulasi RSA</h2>
                </div>
                <div class="card-body">
                    <p>Simulasi pengiriman pesan terenkripsi menggunakan <strong>RSA 2048-bit</strong> antara Alice (penerima) dan Bob (pengirim).</p>
                    <div class="card-tags">
                        <span class="tag">RSA</span>
                        <span class="tag">Asimetris</span>
                        <span class="tag">OpenSSL</span>
                    </div>
                    <span class="btn-card">Lihat Simulasi →</span>
                </div>
            </a>
        </div>
    </div>

    <footer>
        <p>Copyright &copy; <?= date('Y') ?> — Dibuat oleh Yogiswara Putra Rainanda</p>
        <p style="font-size: 0.8rem; margin-top: 0.5rem;">Mata Kuliah: Kriptografi</p>
    </footer>
    <script src="assets/main.js"></script>
</body>
</html>