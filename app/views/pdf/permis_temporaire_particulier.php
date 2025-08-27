<?php
/* Variables expected:
 * $numero (string)
 * $date_debut (string Y-m-d)
 * $date_fin (string Y-m-d)
 * $part (array from table 'particuliers')
 */
$fmt = function($d){ if(!$d) return ''; try { $t=strtotime($d); return $t?date('d/m/Y',$t):$d; } catch(Throwable $e){ return $d; } };
$nom = htmlspecialchars((string)($part['nom'] ?? ''), ENT_QUOTES);
$prenom = '';
// If your schema has a first name stored inside nom, you can split; otherwise leave blank
if ($nom && strpos($nom, ' ') !== false) {
    $pieces = preg_split('/\s+/', $nom, 2);
    if (is_array($pieces)) { $nom = htmlspecialchars($pieces[0] ?? '', ENT_QUOTES); $prenom = htmlspecialchars($pieces[1] ?? '', ENT_QUOTES); }
}
$numero_national = htmlspecialchars((string)($part['numero_national'] ?? ''), ENT_QUOTES);
$adresse = htmlspecialchars((string)($part['adresse'] ?? ''), ENT_QUOTES);
$nationalite = htmlspecialchars((string)($part['nationalite'] ?? ''), ENT_QUOTES);
$date_naissance = htmlspecialchars((string)($part['date_naissance'] ?? ''), ENT_QUOTES);
$lieu_naissance = htmlspecialchars((string)($part['lieu_naissance'] ?? ''), ENT_QUOTES);
$observations = htmlspecialchars((string)($part['observations'] ?? ''), ENT_QUOTES);
$photoRel = (string)($part['photo'] ?? '');
$photoSrc = '';
if ($photoRel !== '') {
    $fsPath = __DIR__ . '/../../' . ltrim($photoRel, '/');
    if (is_file($fsPath)) {
        $mime = 'image/jpeg';
        $ext = strtolower(pathinfo($fsPath, PATHINFO_EXTENSION));
        if ($ext === 'png') $mime = 'image/png'; elseif ($ext === 'gif') $mime = 'image/gif';
        $data = @file_get_contents($fsPath);
        if ($data !== false) { $photoSrc = 'data:' . $mime . ';base64,' . base64_encode($data); }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permis de Conduire National - République Démocratique du Congo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .license-container {
            width: 600px;
            height: 380px;
            background: linear-gradient(135deg, #f8e6d8 0%, #e8d5c4 100%);
            border: 2px solid #8B4513;
            border-radius: 15px;
            position: relative;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .header {
            text-align: center;
            padding: 10px 20px;
            background: rgba(255,255,255,0.1);
            border-bottom: 1px solid #8B4513;
        }

        .country-title {
            font-size: 16px;
            font-weight: bold;
            color: #8B4513;
            margin: 0;
            letter-spacing: 1px;
        }

        .license-title {
            font-size: 14px;
            color: #D2691E;
            margin: 2px 0;
            font-weight: bold;
        }

        .flag {
            position: absolute;
            top: 15px;
            left: 20px;
            width: 50px;
            height: 35px;
            background-image: url('pays.jpg');
            background-size: cover;
            background-position: center;
            border: 1px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .flag::after {
            content: "★";
            color: #FFDD00;
            font-size: 12px;
            position: absolute;
            left: 8px;
        }

        .categories-box {
            position: absolute;
            top: 15px;
            right: 20px;
        }

        .categories-header {
            display: flex;
            font-size: 10px;
            font-weight: bold;
        }

        .category-cell {
            width: 20px;
            height: 15px;
            border: 2px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }

        .main-content {
            display: flex;
            padding: 20px;
            gap: 20px;
            height: calc(100% - 80px);
        }

        .photo-section {
            width: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .photo-placeholder {
            width: 100px;
            height: 170px;
            border: 2px solid #333;
            background: #f0f0f0;
            margin-bottom: 10px;
        }

        .signature-box {
            width: 100px;
            height: 60px;
            border: 1px solid #333;
            background: white;
            position: relative;
            border-radius: 8px;
        }

        .signature-label {
            font-size: 8px;
            position: absolute;
            bottom: -12px;
            left: 0;
            right: 0;
            text-align: center;
        }

        .info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 11px;
        }

        .info-row {
            display: flex;
            flex-direction: column;
            margin-bottom: 3px;
        }

        .info-label {
            font-weight: bold;
            min-width: 80px;
            font-size: 9px;
            text-transform: uppercase;
        }

        .info-value {
            font-weight: bold;
            font-size: 11px;
        }

        .right-section {
            width: 150px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 11px;
        }

        .map-outline {
            display: none;
        }

        .license-number {
            position: absolute;
            top: 180px;
            right: 30px;
            font-size: 12px;
            font-weight: bold;
        }

        .dates-section {
            position: absolute;
            bottom: 20px;
            left: 140px;
            right: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }

        .date-item {
            display: flex;
            flex-direction: column;
            margin-bottom: 3px;
        }

        .date-label {
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
        }

        .date-value {
            font-weight: bold;
            font-size: 11px;
        }

        .serial-number {
            position: absolute;
            bottom: 5px;
            right: 20px;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="license-container">
        <!-- Header -->
        <div class="header">
            <h1 class="country-title">RÉPUBLIQUE DÉMOCRATIQUE DU CONGO</h1>
            <h2 class="license-title">PERMIS DE CONDUIRE NATIONAL DRIVING LICENCE</h2>
        </div>

        <!-- Flag -->
        <div class="flag"></div>

        <!-- Categories Box -->
        <div class="categories-box">
            <div class="categories-header">
                <div class="category-cell">A</div>
                <div class="category-cell">B</div>
                <div class="category-cell">C</div>
                <div class="category-cell">D</div>
            </div>
            <div style="font-size: 8px; text-align: center; padding: 2px;">CATÉGORIES</div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Photo Section -->
            <div class="photo-section">
                <?php if ($photoSrc): ?>
                    <img class="photo-placeholder" src="<?= $photoSrc ?>" alt="Photo" style="object-fit: cover;" />
                <?php else: ?>
                    <div class="photo-placeholder"></div>
                <?php endif; ?>
                <div class="signature-box">
                    <div class="signature-label">Signature du titulaire</div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">NOM/NAME</span>
                    <span class="info-value"><?= $nom ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">PRÉNOM/FIRST NAME</span>
                    <span class="info-value"><?= $prenom ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">DATE ET LIEU NAISSANCE/& PLACE OF BIRTH</span>
                    <span class="info-value"><?= $fmt($date_naissance) ?><?= $lieu_naissance ? ' ' . $lieu_naissance : '' ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">NATIONALITÉ/NATIONALITY</span>
                    <span class="info-value"><?= $nationalite ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">N° D'IDENTITÉ/NATIONAL ID N°</span>
                    <span class="info-value"><?= $numero_national ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">ADRESSE/HOME</span>
                    <span class="info-value"><?= $adresse ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">REMARQUES ET RESTRICTIONS/REMARKS & RESTRICTIONS</span>
                    <span class="info-value"><?= $observations ?></span>
                </div>
            </div>

            <!-- Right Section -->
            <div class="right-section">
                <!-- Map outline of Congo -->
                <div class="map-outline"></div>
                
                <div class="license-number">
                    <div style="font-size: 9px;">PERMIS N°/LICENCE</div>
                    <div style="font-weight: bold;"><?= htmlspecialchars($numero, ENT_QUOTES) ?></div>
                </div>
            </div>
        </div>

        <!-- Dates Section -->
        <div class="dates-section">
            <div class="date-item">
                <div class="date-label">DATE DE DÉLIVRANCE/DATE OF DELIVERY</div>
                <div class="date-value"><?= $fmt($date_debut) ?></div>
            </div>
            <div class="date-item">
                <div class="date-label">DATE D'ÉCHÉANCE/DATE OF EXPIRY</div>
                <div class="date-value"><?= $fmt($date_fin) ?></div>
            </div>
        </div>

        <!-- Serial Number -->
        <!-- <div class="serial-number">N° 000065320</div> -->
    </div>
</body>
</html>
