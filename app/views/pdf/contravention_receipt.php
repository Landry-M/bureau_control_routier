<?php
// Expected variables: $cvData (array), $logo (string|null)
// Minimal, print-safe HTML template for contravention receipt
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Contravention #<?php echo htmlspecialchars($cvData['id'] ?? ''); ?></title>
    <style>
        @page { margin: 24mm 18mm; }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #111; }
        .header { text-align:center; margin-bottom: 18px; }
        .logo { height: 64px; margin-bottom: 8px; }
        .org { font-size: 14px; font-weight: 600; margin-bottom: 4px; }
        .title { font-size: 18px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
        .meta { margin: 10px 0 18px; }
        .meta table { width: 100%; border-collapse: collapse; }
        .meta th, .meta td { text-align: left; padding: 6px 8px; border: 1px solid #ddd; vertical-align: top; }
        .section-title { margin: 18px 0 8px; font-weight: 700; text-transform: uppercase; font-size: 13px; }
        .content { border: 1px solid #ddd; padding: 10px 12px; }
        .footer { margin-top: 40px; display: flex; justify-content: space-between; }
        .sig { width: 48%; border-top: 1px solid #999; text-align: center; padding-top: 6px; }
        .small { color: #666; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <?php if (!empty($logo)): ?>
            <img class="logo" src="<?php echo htmlspecialchars($logo); ?>" alt="Logo" style="height: 84px;" />
        <?php endif; ?>
        <div class="title">Republique Democratique du Congo</div>
        <div class="title">Bureau de Control Routier</div>
        <br>
        <div class="org">Avis de contravention</div>
    </div>

    <div class="meta">
        <table>
            <tr>
                <th style="width: 35%">Type de dossier</th>
                <td><?php echo htmlspecialchars($cvData['type_dossier'] ?? ''); ?></td>
            </tr>
            <tr>
                <th>Nom</th>
                <td>
                    <?php
                        $name = $cvData['nom']
                            ?? $cvData['nom_complet']
                            ?? $cvData['nom_particulier']
                            ?? $cvData['name']
                            ?? '';
                        echo htmlspecialchars($name);
                    ?>
                </td>
            </tr>
            <tr>
                <th>Date de l'infraction</th>
                <td><?php echo htmlspecialchars($cvData['date_infraction'] ?? ''); ?></td>
            </tr>
            <tr>
                <th>Lieu</th>
                <td><?php echo htmlspecialchars($cvData['lieu'] ?? ''); ?></td>
            </tr>
            <tr>
                <th>Type d'infraction</th>
                <td><?php echo htmlspecialchars($cvData['type_infraction'] ?? ''); ?></td>
            </tr>
            <tr>
                <th>Référence de loi</th>
                <td><?php echo htmlspecialchars($cvData['reference_loi'] ?? ''); ?></td>
            </tr>
            <tr>
                <th>Montant de l'amende</th>
                <td><?php echo htmlspecialchars($cvData['amende'].' Fc' ?? ''); ?></td>
            </tr>
            <tr>
                <th>Payé</th>
                <td><?php echo ((int)($cvData['payed'] ?? 0) === 1) ? 'Oui' : 'Non'; ?></td>
            </tr>
        </table>
    </div>

    <div class="section-title">Description</div>
    <div class="content">
        <div><?php echo nl2br(htmlspecialchars($cvData['description'] ?? '')); ?></div>
    </div>

    <?php if (!empty($cvData['photos'])): ?>
    <div class="section-title">Photos de preuve</div>
    <div class="content">
        <div style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: flex-start;">
            <?php foreach ($cvData['photos'] as $index => $photo): ?>
                <?php 
                // Resolve filesystem path relative to app/ (this file is in app/views/pdf)
                $appRoot = realpath(__DIR__ . '/../../'); // points to .../app
                $relativePath = ltrim($photo['file_path'] ?? '', '/'); // expected: assets/images/contraventions/...
                $photoPath = $appRoot . '/' . $relativePath;
                // Minimal debug
                error_log('PDF photo resolved path: ' . $photoPath . ' exists=' . (file_exists($photoPath) ? 'YES' : 'NO'));
                
                if (file_exists($photoPath)):
                    $imageData = base64_encode(file_get_contents($photoPath));
                    $mimeType = $photo['mime_type'] ?? 'image/jpeg';
                ?>
                <div style="flex: 0 0 calc(33.333% - 10px); margin-bottom: 15px; display: flex; flex-direction: column; align-items: center;">
                    <div style="width: 160px; height: 120px; border: 1px solid #ddd; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f9f9f9;">
                        <img src="data:<?php echo $mimeType; ?>;base64,<?php echo $imageData; ?>" 
                             style="max-width: 100%; max-height: 100%; object-fit: contain;" 
                             alt="Photo <?php echo $index + 1; ?>">
                    </div>
                </div>
                <?php else: ?>
                    <div style="flex: 0 0 calc(33.333% - 10px); margin-bottom: 15px; border: 1px solid red; padding: 10px; display: flex; align-items: center; justify-content: center;">
                        <p style="text-align: center; font-size: 12px;">Photo non trouvée</p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="footer">
        <div class="sig">
            Signature de l'agent
        </div>
        <br>
        <br>
        <div class="sig">
            Date: ____ / ____ / ______
        </div>
    </div>

    <!-- <div class="small" style="margin-top: 24px;">
        Document généré automatiquement. Les informations proviennent de l'enregistrement de la contravention.
    </div> -->
</body>
</html>
