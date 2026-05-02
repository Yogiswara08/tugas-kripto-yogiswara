<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ========== Tangani Permintaan Generate Ulang ==========
if (isset($_POST['regenerate'])) {
    unset($_SESSION['alice_private'], $_SESSION['alice_public']);
    // Redirect agar tidak mengirim ulang form dengan POST
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ========== 1. Setup Kunci Alice ==========
if (!isset($_SESSION['alice_private']) || !isset($_SESSION['alice_public'])) {
    $config = [
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
        "config" => "C:/laragon/bin/php/php-8.1.10-Win32-vs16-x64/extras/ssl/openssl.cnf"
    ];

    // Bangkitkan pasangan kunci
    $res = openssl_pkey_new($config);
    if ($res === false) {
        die("<b>Gagal membuat kunci RSA:</b> " . openssl_error_string());
    }

    // Ekspor private key
    if (!openssl_pkey_export($res, $privateKeyAlice, null, $config)) {
        die("<b>Gagal mengekspor private key:</b> " . openssl_error_string());
    }

    // Ambil detail public key
    $details = openssl_pkey_get_details($res);
    if ($details === false) {
        die("<b>Gagal mengambil detail kunci:</b> " . openssl_error_string());
    }
    $publicKeyAlice = $details["key"];

    $_SESSION['alice_private'] = $privateKeyAlice;
    $_SESSION['alice_public']  = $publicKeyAlice;
} else {
    $privateKeyAlice = $_SESSION['alice_private'];
    $publicKeyAlice  = $_SESSION['alice_public'];
}

// Proses Form Bob
$pesanBob = '';
$cipherBase64 = '';
$pesanDekripsi = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pesan_bob'])) {
    $pesanBob = trim($_POST['pesan_bob']);
    if ($pesanBob === '') {
        $error = 'Pesan tidak boleh kosong.';
    } else {
        $encrypted = null;
        if (!openssl_public_encrypt($pesanBob, $encrypted, $publicKeyAlice)) {
            $error = 'Enkripsi gagal: ' . openssl_error_string();
        } else {
            $cipherBase64 = base64_encode($encrypted);
            $decrypted = null;
            if (!openssl_private_decrypt(base64_decode($cipherBase64), $decrypted, $privateKeyAlice)) {
                $error = 'Dekripsi gagal: ' . openssl_error_string();
            } else {
                $pesanDekripsi = $decrypted;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulasi Kirim Surat RSA</title>
    <link rel="stylesheet" href="assets/rsa.css">
</head>
<body>

    <!-- ── Navbar ───────────────────────────────────────── -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">🔬 CryptoLab</div>
            <div class="nav-links">
                <a href="index.php"        class="nav-link">🏠 Beranda</a>
                <a href="fpb.php"          class="nav-link">🔢 FPB Calculator</a>
                <a href="kripto.php"       class="nav-link">🔐 Kriptografi</a>
                <a href="simulasi_rsa.php" class="nav-link active">🛡️ Simulasi RSA</a>
            </div>
        </div>
    </nav>

    <div class="wrapper">
        <!-- ── Page Header ──────────────────────────────── -->
        <div class="page-header">
            <h1>🛡️ Simulasi Kirim Surat RSA</h1>
            <p>Percakapan satu arah: Bob mengenkripsi pesan, Alice mendekripsinya</p>
        </div>

        <div class="steps">

            <!-- ── Step 1: Alice Setup ───────────────────── -->
            <div class="step-card step-alice">
                <div class="step-header">
                    <div class="step-num">1</div>
                    <div class="step-avatar">👩</div>
                    <div class="step-title">
                        <h2>Public Key Alice <span class="badge badge-pub">Dipublikasikan</span></h2>
                        <small>Kunci publik ini bisa dilihat siapa saja, termasuk Bob.</small>
                    </div>
                </div>
                <div class="step-body">
                    <div class="field">
                        <span class="field-label">🔓 Public Key Alice</span>
                        <div class="field-value"><?= htmlspecialchars($publicKeyAlice) ?></div>
                        <button type="button" class="copy-btn" onclick="copyText(this, <?= htmlspecialchars(json_encode($publicKeyAlice)) ?>)">📋 Salin Public Key</button>
                    </div>
                    <form method="post" style="margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                        <button type="submit" name="regenerate" value="1" class="btn-secondary">🔄 Generate Ulang Kunci RSA</button>
                    </form>
                </div>
            </div>

            <div class="flow-arrow">↓</div>

            <!-- ── Step 2: Bob Encrypt ───────────────────── -->
            <div class="step-card step-bob">
                <div class="step-header">
                    <div class="step-num">2</div>
                    <div class="step-avatar">👨</div>
                    <div class="step-title">
                        <h2>Bob Menulis Pesan Rahasia</h2>
                        <small>Bob akan mengenkripsi pesannya menggunakan Public Key Alice.</small>
                    </div>
                </div>
                <div class="step-body">
                    <form method="post" action="">
                        <div class="field">
                            <label for="pesan_bob" class="field-label">📝 Pesan Asli (Plaintext)</label>
                            <input type="text" id="pesan_bob" name="pesan_bob" 
                                   placeholder="Masukkan pesan, misal: Rahasia Negara X"
                                   value="<?= htmlspecialchars($pesanBob) ?>" required>
                        </div>
                        <button type="submit" class="btn-primary">🔒 Enkripsi & Kirim ke Alice</button>
                        
                        <?php if ($error): ?>
                            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <?php if ($cipherBase64 !== ''): ?>
            <div class="flow-arrow">↓</div>

            <!-- ── Step 3: Bob Sends Cipher ──────────────── -->
            <div class="step-card step-bob">
                <div class="step-header">
                    <div class="step-num">3</div>
                    <div class="step-avatar">📨</div>
                    <div class="step-title">
                        <h2>Ciphertext yang Dikirim Bob</h2>
                        <small>Ini adalah data acak yang tidak bisa dibaca tanpa Private Key.</small>
                    </div>
                </div>
                <div class="step-body">
                    <div class="field">
                        <span class="field-label">🔒 Hasil Enkripsi (Base64)</span>
                        <div class="field-value cipher"><?= htmlspecialchars($cipherBase64) ?></div>
                        <button type="button" class="copy-btn" onclick="copyText(this, <?= htmlspecialchars(json_encode($cipherBase64)) ?>)">📋 Salin Ciphertext</button>
                    </div>
                </div>
            </div>

            <div class="flow-arrow">↓</div>

            <!-- ── Step 4: Alice Decrypt ─────────────────── -->
            <div class="step-card step-decrypt">
                <div class="step-header">
                    <div class="step-num">4</div>
                    <div class="step-avatar">👩</div>
                    <div class="step-title">
                        <h2>Alice Membaca Pesan <span class="badge badge-ok">Sukses</span></h2>
                        <small>Alice mendekripsi ciphertext menggunakan Private Key-nya sendiri.</small>
                    </div>
                </div>
                <div class="step-body">
                    <div class="field">
                        <span class="field-label">📬 Pesan Asli Berhasil Didekripsi</span>
                        <div class="field-value highlight"><?= htmlspecialchars($pesanDekripsi) ?></div>
                    </div>
                    <p style="font-size:13px; margin-top:8px; color:#475569;">
                        ✅ Integritas terjaga — Hanya Alice (pemilik private key) yang bisa membaca pesan ini.
                    </p>
                </div>
            </div>
            <?php endif; ?>

            <details>
                <summary>🔑 Lihat Private Key Alice (rahasia, hanya simulasi)</summary>
                <div class="field" style="margin-top: 12px;">
                    <div class="field-value"><?= htmlspecialchars($privateKeyAlice) ?></div>
                    <button type="button" class="copy-btn" onclick="copyText(this, <?= htmlspecialchars(json_encode($privateKeyAlice)) ?>)">📋 Salin Private Key</button>
                </div>
            </details>

        </div><!-- /steps -->
    </div><!-- /wrapper -->

    <footer>
        <p>Copyright &copy; <?= date('Y') ?> — Dibuat oleh Yogiswara Putra Rainanda</p>
        <p style="font-size: 0.8rem; margin-top: 0.5rem;">Simulasi RSA Sederhana &bull; Laragon + PHP OpenSSL (2048-bit)</p>
    </footer>

    <script src="assets/rsa.js"></script>
</body>
</html>