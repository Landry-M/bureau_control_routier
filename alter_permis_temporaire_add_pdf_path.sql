-- Ajouter le champ pdf_path à la table permis_temporaire
-- Ce champ stockera le chemin relatif vers le fichier PDF généré

ALTER TABLE `permis_temporaire` 
ADD COLUMN `pdf_path` VARCHAR(255) NULL COMMENT 'Chemin relatif vers le fichier PDF généré' 
AFTER `statut`;

-- Ajouter un index pour optimiser les recherches par pdf_path
ALTER TABLE `permis_temporaire` 
ADD INDEX `idx_permis_temporaire_pdf_path` (`pdf_path`);
