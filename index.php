<?php
// =============================================
// FUNGSI ALGORITMA EUCLIDEAN untuk FPB
// =============================================
function hitungFPB($a, $b) {
    // Algoritma Euclidean: FPB(a,b) = FPB(b, a mod b)
    while ($b != 0) {
        $sisa = $a % $b;
        $a = $b;
        $b = $sisa;
    }
    return $a;
}

// Inisialisasi variabel
$hasil      = null;
$keterangan = null;
$nilaiA     = '';
$nilaiB     = '';
$error      = '';

// Proses jika form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nilaiA = trim($_POST['angka1'] ?? '');
    $nilaiB = trim($_POST['angka2'] ?? '');

    // Validasi input
    if ($nilaiA === '' || $nilaiB === '') {
        $error = 'Kedua angka harus diisi!';
    } elseif (!ctype_digit($nilaiA) || !ctype_digit($nilaiB)) {
        $error = 'Input harus berupa angka bulat positif!';
    } elseif ((int)$nilaiA == 0 || (int)$nilaiB == 0) {
        $error = 'Angka tidak boleh nol!';
    } else {
        $a      = (int)$nilaiA;
        $b      = (int)$nilaiB;
        $hasil  = hitungFPB($a, $b);

        if ($hasil == 1) {
            $keterangan = 'RELATIF PRIMA';
        } else {
            $keterangan = 'TIDAK RELATIF PRIMA';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator FPB — Algoritma Euclidean</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:       #0d0f14;
            --surface:  #161a24;
            --border:   #2a2f3d;
            --accent:   #00e5c3;
            --accent2:  #7c5cfc;
            --text:     #e8eaf0;
            --muted:    #6b7280;
            --error:    #ff5f72;
            --success:  #00e5c3;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Space Mono', monospace;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Grid background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,229,195,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,229,195,.04) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        /* Glow blob */
        body::after {
            content: '';
            position: fixed;
            top: -20%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(124,92,252,.18) 0%, transparent 70%);
            pointer-events: none;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2.5rem 3rem;
            width: 100%;
            max-width: 520px;
            position: relative;
            box-shadow: 0 0 60px rgba(0,0,0,.5);
            animation: slideUp .5s ease both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Corner accent */
        .card::before {
            content: '';
            position: absolute;
            top: -1px; left: -1px;
            width: 80px; height: 80px;
            border-top: 2px solid var(--accent);
            border-left: 2px solid var(--accent);
            border-radius: 16px 0 0 0;
        }

        .badge {
            display: inline-block;
            font-size: .65rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--accent);
            border: 1px solid var(--accent);
            border-radius: 4px;
            padding: .2rem .6rem;
            margin-bottom: 1rem;
        }

        h1 {
            font-family: 'Syne', sans-serif;
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: .4rem;
            background: linear-gradient(135deg, var(--text) 40%, var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: .78rem;
            color: var(--muted);
            margin-bottom: 2rem;
        }

        .divider {
            height: 1px;
            background: var(--border);
            margin-bottom: 2rem;
        }

        .field {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            font-size: .72rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: .5rem;
        }

        input[type="text"] {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: .75rem 1rem;
            font-family: 'Space Mono', monospace;
            font-size: 1rem;
            color: var(--text);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        input[type="text"]:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(0,229,195,.12);
        }

        input[type="text"]::placeholder { color: var(--muted); }

        .btn {
            width: 100%;
            margin-top: .5rem;
            padding: .85rem;
            background: linear-gradient(135deg, var(--accent2), var(--accent));
            border: none;
            border-radius: 8px;
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: .04em;
            color: #0d0f14;
            cursor: pointer;
            transition: opacity .2s, transform .15s;
        }

        .btn:hover  { opacity: .9; transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }

        /* Error */
        .alert-error {
            background: rgba(255,95,114,.1);
            border: 1px solid var(--error);
            border-radius: 8px;
            padding: .75rem 1rem;
            font-size: .82rem;
            color: var(--error);
            margin-top: 1.25rem;
        }

        /* Result box */
        .result-box {
            margin-top: 1.5rem;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1.5rem;
            animation: fadeIn .4s ease both;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(.97); }
            to   { opacity: 1; transform: scale(1); }
        }

        .result-label {
            font-size: .68rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: .4rem;
        }

        .result-value {
            font-family: 'Syne', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            color: var(--accent);
            line-height: 1;
            margin-bottom: 1rem;
        }

        .result-keterangan {
            display: inline-block;
            padding: .35rem .85rem;
            border-radius: 6px;
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .05em;
        }

        .prima    { background: rgba(0,229,195,.15); color: var(--accent);  border: 1px solid var(--accent); }
        .notprima { background: rgba(124,92,252,.15); color: var(--accent2); border: 1px solid var(--accent2); }

        .result-detail {
            margin-top: .85rem;
            font-size: .75rem;
            color: var(--muted);
        }

        .result-detail span { color: var(--text); }

        footer {
            margin-top: 2rem;
            text-align: center;
            font-size: .68rem;
            color: var(--muted);
            letter-spacing: .05em;
        }
    </style>
</head>
<body>
<div class="card">
    <span class="badge">Algoritma Euclidean</span>
    <h1>Kalkulator<br>FPB</h1>
    <p class="subtitle">Masukkan dua angka bulat positif untuk menghitung Faktor Persekutuan Terbesar.</p>
    <div class="divider"></div>

    <form method="POST" action="">
        <div class="field">
            <label for="angka1">Angka 1 (Nilai A)</label>
            <input type="text" id="angka1" name="angka1"
                   placeholder="Contoh: 48"
                   value="<?= htmlspecialchars($nilaiA) ?>">
        </div>
        <div class="field">
            <label for="angka2">Angka 2 (Nilai B)</label>
            <input type="text" id="angka2" name="angka2"
                   placeholder="Contoh: 18"
                   value="<?= htmlspecialchars($nilaiB) ?>">
        </div>
        <button type="submit" class="btn">Hitung FPB</button>
    </form>

    <?php if ($error): ?>
        <div class="alert-error">⚠ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($hasil !== null): ?>
        <div class="result-box">
            <div class="result-label">Hasil FPB</div>
            <div class="result-value"><?= $hasil ?></div>

            <?php if ($keterangan === 'RELATIF PRIMA'): ?>
                <span class="result-keterangan prima">✔ Kedua angka RELATIF PRIMA</span>
            <?php else: ?>
                <span class="result-keterangan notprima">✘ TIDAK RELATIF PRIMA</span>
            <?php endif; ?>

            <div class="result-detail">
                FPB( <span><?= (int)$nilaiA ?></span> , <span><?= (int)$nilaiB ?></span> ) = <span><?= $hasil ?></span>
            </div>
        </div>
    <?php endif; ?>

    <footer>Yogiswara Putra Rainanda</footer>
</div>
</body>
</html>