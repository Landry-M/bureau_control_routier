<?php
// Expected: $numero (string), optional $date_debut, $date_fin
// Compute flag URL just like contravention logo
$flagPath = __DIR__ . '/../../assets/images/drapeau.png';
$flag = null;
if (file_exists($flagPath)) {
    if (isset($_SERVER['HTTP_HOST'])) {
        $scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $flag = $scheme . '://' . $_SERVER['HTTP_HOST'] . $base . '/assets/images/drapeau.png';
    } else {
        $flag = 'assets/images/drapeau.png';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Plaque/Permis Temporaire</title>
    <style>
        @page { size: A4 landscape; margin: 18mm; }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #111; }
        .wrap { text-align: center; }
        .heading { font-size: 16px; font-weight: 700; margin-bottom: 16px; text-transform: uppercase; letter-spacing: .5px; }
        .row { display: inline-flex; align-items: center; }
        .flagbox { display: inline-flex; flex-direction: row; align-items: center; gap: 8px; }
        .flag { height: 64px; }
        .flaglabel { font-size: 18px; font-weight: 700; letter-spacing: 1px; }
        .numero {
            font-size: 64px;
            font-weight: 800;
            border: 4px solid #111;
            display: inline-flex;
            align-items: center;
            gap: 18px;
            padding: 18px 28px;
            letter-spacing: 2px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .numtext { display: inline-block; }
        .dates { margin-top: 10px; font-size: 14px; }
        .muted { color: #666; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="heading">Bureau de Contrôle Routier — Permis/Plaque Temporaire</div>
        <div class="row">
            <div class="numero">
                <div class="flagbox">
                    <?php if (!empty($flag)): ?>
                        <img class="flag" src="<?php echo htmlspecialchars($flag); ?>" alt="Drapeau">
                    <?php endif; ?>
                    <span class="flaglabel">CGO</span>
                </div>
                <span class="numtext"><?php echo htmlspecialchars($numero ?? ''); ?></span>
            </div>
        </div>
        <div class="dates">
            <?php if (!empty($date_debut) || !empty($date_fin)) : ?>
                <span class="muted">Valide du</span>
                <strong><?php echo htmlspecialchars($date_debut ?? '—'); ?></strong>
                <span class="muted">au</span>
                <strong><?php echo htmlspecialchars($date_fin ?? '—'); ?></strong>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
