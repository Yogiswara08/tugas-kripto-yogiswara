<?php
// ============================================================
//  CRYPTO FUNCTIONS
// ============================================================

function caesar_cipher(string $text, int $key, bool $is_encrypt): string
{
    $key = (($key % 26) + 26) % 26;
    if (!$is_encrypt) {
        $key = (26 - $key) % 26;
    }

    $result = '';
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_alpha($char)) {
            $base   = ctype_upper($char) ? ord('A') : ord('a');
            $result .= chr(($key + ord($char) - $base) % 26 + $base);
        } else {
            $result .= $char;
        }
    }
    return $result;
}

function vigenere_cipher(string $text, string $key, bool $is_encrypt): string
{
    if (empty($key)) return $text;

    $clean_key = strtoupper(preg_replace('/[^A-Za-z]/', '', $key));
    if (empty($clean_key)) return $text;

    $result   = '';
    $key_len  = strlen($clean_key);
    $key_idx  = 0;

    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_alpha($char)) {
            $base     = ctype_upper($char) ? ord('A') : ord('a');
            $shift    = ord($clean_key[$key_idx % $key_len]) - ord('A');
            if (!$is_encrypt) {
                $shift = (26 - $shift) % 26;
            }
            $result  .= chr(($shift + ord($char) - $base) % 26 + $base);
            $key_idx++;
        } else {
            $result .= $char;
        }
    }
    return $result;
}

$output     = '';
$input_text = '';
$operation  = 'encrypt';
$algorithm  = 'caesar';
$caesar_key = 3;
$vigenere_key = '';
$error      = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_text   = $_POST['message']      ?? '';
    $operation    = $_POST['operation']    ?? 'encrypt';
    $algorithm    = $_POST['algorithm']    ?? 'caesar';
    $caesar_key   = (int)($_POST['caesar_key'] ?? 3);
    $vigenere_key = trim($_POST['vigenere_key'] ?? '');
    $is_encrypt   = ($operation === 'encrypt');

    if (empty($input_text)) {
        $error = 'Masukkan pesan terlebih dahulu.';
    } elseif ($algorithm === 'caesar') {
        if ($caesar_key < 1 || $caesar_key > 25) {
            $error = 'Key Caesar harus antara 1 – 25.';
        } else {
            $output = caesar_cipher($input_text, $caesar_key, $is_encrypt);
        }
    } elseif ($algorithm === 'vigenere') {
        if (empty($vigenere_key) || !ctype_alpha(str_replace(' ', '', $vigenere_key))) {
            $error = 'Key Vigenère harus berisi huruf saja (minimal 1 karakter).';
        } else {
            $output = vigenere_cipher($input_text, $vigenere_key, $is_encrypt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CryptoLab — Enkripsi & Dekripsi Pesan</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/kripto.css">
</head>
<body>
  <nav class="navbar">
    <div class="nav-container">
      <div class="logo">🔬 CryptoLab</div>
      <div class="nav-links">
        <a href="index.php"        class="nav-link">🏠 Beranda</a>
        <a href="fpb.php"          class="nav-link">🔢 FPB Calculator</a>
        <a href="kripto.php"       class="nav-link active">🔐 Kriptografi</a>
        <a href="simulasi_rsa.php" class="nav-link">🛡️ Simulasi RSA</a>
      </div>
    </div>
  </nav>

  <div class="wrapper">
    <div class="card">
      <div class="site-header">
        <h1>🔐 Enkripsi & Dekripsi</h1>
        <p class="tagline">Caesar Cipher | Vigenère Cipher</p>
      </div>

      <section class="algo-selector">
        <p class="section-label">Pilih Algoritma</p>
        <div class="radio-group" id="algo-group">
          <label class="radio-card <?= ($algorithm === 'caesar') ? 'active' : '' ?>">
            <input type="radio" name="algorithm_radio" value="caesar" <?= ($algorithm === 'caesar') ? 'checked' : '' ?> style="display:none">
            <span class="algo-icon">🔤</span>
            <span class="algo-name">Caesar Cipher</span>
            <span class="algo-desc">Pergeseran sederhana A–Z</span>
          </label>
          <label class="radio-card <?= ($algorithm === 'vigenere') ? 'active' : '' ?>">
            <input type="radio" name="algorithm_radio" value="vigenere" <?= ($algorithm === 'vigenere') ? 'checked' : '' ?> style="display:none">
            <span class="algo-icon">🔑</span>
            <span class="algo-name">Vigenère Cipher</span>
            <span class="algo-desc">Kunci berupa kata/frasa</span>
          </label>
        </div>
      </section>

      <hr class="divider" />

      <form method="POST" action="" id="crypto-form">
        <input type="hidden" name="algorithm" id="algorithm-hidden" value="<?= htmlspecialchars($algorithm) ?>" />
        <input type="hidden" name="operation" id="operation-hidden" value="<?= htmlspecialchars($operation) ?>" />

        <div class="form-group">
          <label class="form-label" for="message">✉️ Pesan</label>
          <textarea id="message" name="message" class="form-textarea" placeholder="Ketik pesan di sini…" rows="5" required><?= htmlspecialchars($input_text) ?></textarea>
          <span class="char-count"><span id="char-count">0</span> karakter</span>
        </div>

        <div class="keys-row">
          <div class="form-group key-field" id="caesar-key-group" style="<?= ($algorithm === 'vigenere') ? 'display:none' : '' ?>">
            <label class="form-label" for="caesar_key">🔢 Key (Pergeseran)</label>
            <div class="number-input-wrap">
              <button type="button" class="num-btn" id="btn-minus">−</button>
              <input type="number" id="caesar_key" name="caesar_key" class="form-input number-input" value="<?= (int)$caesar_key ?>" min="1" max="25" />
              <button type="button" class="num-btn" id="btn-plus">+</button>
            </div>
            <p class="input-hint">Rentang: 1 – 25</p>
          </div>

          <div class="form-group key-field" id="vigenere-key-group" style="<?= ($algorithm === 'caesar') ? 'display:none' : '' ?>">
            <label class="form-label" for="vigenere_key">🗝️ Key (Kata/Frasa)</label>
            <input type="text" id="vigenere_key" name="vigenere_key" class="form-input" placeholder="Contoh: SECRET" value="<?= htmlspecialchars($vigenere_key) ?>" />
            <p class="input-hint">Hanya huruf (spasi diabaikan)</p>
          </div>
        </div>

        <div class="action-row">
          <button type="submit" class="btn btn-encrypt" onclick="setOperation('encrypt')">🔒 Enkripsi</button>
          <button type="submit" class="btn btn-decrypt" onclick="setOperation('decrypt')">🔓 Dekripsi</button>
        </div>
      </form>

      <?php if ($error): ?>
      <div class="alert" id="error-box">
        <span>⚠️</span> <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <?php if ($output !== '' && !$error): ?>
      <section class="result-section" id="result-section">
        <div class="result-header">
          <span class="result-badge <?= $operation === 'encrypt' ? 'badge-encrypt' : 'badge-decrypt' ?>">
            <?= $operation === 'encrypt' ? '🔒 Hasil Enkripsi' : '🔓 Hasil Dekripsi' ?>
          </span>
          <button type="button" class="copy-btn" onclick="copyResult()">📋 Salin</button>
        </div>
        <pre class="result-text" id="result-text"><?= htmlspecialchars($output) ?></pre>
      </section>
      <?php endif; ?>
    </div>

    <section class="info-section">
      <div class="info-card"><h3>Caesar Cipher</h3><p>Setiap huruf digeser sebanyak n posisi dalam alfabet.</p></div>
      <div class="info-card"><h3>Vigenère Cipher</h3><p>Menggunakan kata kunci sebagai urutan pergeseran.</p></div>
      <div class="info-card"><h3>Validasi Input</h3><p>Hanya huruf A–Z yang dienkripsi. Spasi, angka, dan simbol dibiarkan.</p></div>
    </section>

    
  </div>

  <script src="assets/kripto.js"></script>
</body>
</html>