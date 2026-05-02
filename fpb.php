<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator FPB - Algoritma Euclidean</title>
    <link rel="stylesheet" href="assets/fpb.css">
    
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">🔬 CryptoLab</div>
            <div class="nav-links">
                <a href="index.php"        class="nav-link">🏠 Beranda</a>
                <a href="fpb.php"          class="nav-link active">🔢 FPB Calculator</a>
                <a href="kripto.php"       class="nav-link">🔐 Kriptografi</a>
                <a href="simulasi_rsa.php" class="nav-link">🛡️ Simulasi RSA</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <h1>🔐 Kalkulator FPB</h1>
            <p class="subtitle">Algoritma Euclidean</p>
            
            <div class="info-box">
                <strong>📚 Info:</strong> Masukkan dua bilangan bulat positif untuk menghitung 
                Faktor Persekutuan Terbesar (FPB) menggunakan Algoritma Euclidean.
            </div>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="angka1">📊 Angka 1 (Nilai A):</label>
                    <input type="number" 
                           id="angka1" 
                           name="angka1" 
                           required 
                           min="1" 
                           placeholder="Masukkan bilangan bulat positif"
                           value="<?php echo isset($_POST['angka1']) ? htmlspecialchars($_POST['angka1']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="angka2">📊 Angka 2 (Nilai B):</label>
                    <input type="number" 
                           id="angka2" 
                           name="angka2" 
                           required 
                           min="1" 
                           placeholder="Masukkan bilangan bulat positif"
                           value="<?php echo isset($_POST['angka2']) ? htmlspecialchars($_POST['angka2']) : ''; ?>">
                </div>
                
                <div class="button-group">
                    <button type="submit" name="hitung" class="btn-primary">🔍 Hitung FPB</button>
                    <button type="button" onclick="resetForm()" class="btn-secondary">🔄 Reset</button>
                </div>
            </form>
            
            <?php
            // Fungsi Algoritma Euclidean
            function euclideanGCD($a, $b) {
                $steps = [];
                $original_a = $a;
                $original_b = $b;
                
                if ($a <= 0 || $b <= 0) {
                    return [
                        'gcd' => null,
                        'steps' => [],
                        'error' => 'Masukkan bilangan bulat positif!'
                    ];
                }
                
                if ($a < $b) {
                    $temp = $a;
                    $a = $b;
                    $b = $temp;
                    $steps[] = "Menukar posisi: a = {$a}, b = {$b}";
                }
                
                $steps[] = "Memulai Algoritma Euclidean untuk GCD({$original_a}, {$original_b}):";
                
                while ($b != 0) {
                    $q = floor($a / $b);
                    $r = $a % $b;
                    $steps[] = "{$a} = {$q} × {$b} + {$r}";
                    $a = $b;
                    $b = $r;
                }
                
                $gcd = $a;
                $steps[] = "✅ FPB = {$gcd}";
                
                return [
                    'gcd' => $gcd,
                    'steps' => $steps,
                    'error' => null
                ];
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hitung'])) {
                $angka1 = isset($_POST['angka1']) ? intval($_POST['angka1']) : 0;
                $angka2 = isset($_POST['angka2']) ? intval($_POST['angka2']) : 0;
                
                $result = euclideanGCD($angka1, $angka2);
                
                if ($result['error']) {
                    echo '<div class="error">⚠️ ' . htmlspecialchars($result['error']) . '</div>';
                } else {
                    $gcd = $result['gcd'];
                    $isRelatifPrima = ($gcd == 1);
                    $statusClass = $isRelatifPrima ? 'prime' : 'not-prime';
                    $statusText = $isRelatifPrima ? 'RELATIF PRIMA' : 'TIDAK RELATIF PRIMA';
                    ?>
                    
                    <div class="result">
                        <h3>📈 Hasil Perhitungan</h3>
                        
                        <div class="fpb-value">
                            FPB = <?php echo $gcd; ?>
                        </div>
                        
                        <div class="status <?php echo $statusClass; ?>">
                            <?php echo $statusText; ?>
                            <?php if ($isRelatifPrima): ?>
                                <br><small>(FPB = 1 - Kedua bilangan saling prima)</small>
                            <?php else: ?>
                                <br><small>(FPB > 1 - Kedua bilangan tidak saling prima)</small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="steps">
                            <strong>📝 Langkah-langkah Algoritma Euclidean:</strong>
                            <pre><?php echo implode("\n", array_map('htmlspecialchars', $result['steps'])); ?></pre>
                        </div>
                        
                        <div style="margin-top: 15px; font-size: 12px; color: #999; text-align: center;">
                            <strong>Verifikasi:</strong> 
                            <?php echo $angka1; ?> ÷ <?php echo $gcd; ?> = <?php echo $angka1/$gcd; ?>, 
                            <?php echo $angka2; ?> ÷ <?php echo $gcd; ?> = <?php echo $angka2/$gcd; ?>
                        </div>
                    </div>
                    
                    <?php
                }
            }
            ?>
            
            <div class="example-buttons">
                <h4>💡 Contoh Cepat:</h4>
                <button onclick="setExample(48, 18)">48 dan 18</button>
                <button onclick="setExample(56, 98)">56 dan 98</button>
                <button onclick="setExample(17, 13)">17 dan 13 (Prima)</button>
                <button onclick="setExample(100, 75)">100 dan 75</button>
            </div>
        </div>
    </div>
    
    <script src="assets/fpb.js"></script>
</body>
</html>