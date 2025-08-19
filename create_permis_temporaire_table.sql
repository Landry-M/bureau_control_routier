-- Table: permis_temporaire
CREATE TABLE IF NOT EXISTS `permis_temporaire` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cible_type` ENUM('particulier','conducteur') NOT NULL DEFAULT 'particulier',
  `cible_id` INT UNSIGNED NOT NULL,
  `numero` VARCHAR(50) NOT NULL,
  `motif` TEXT NULL,
  `date_debut` DATE NOT NULL,
  `date_fin` DATE NOT NULL,
  `statut` ENUM('actif','clos') NOT NULL DEFAULT 'actif',
  `created_by` VARCHAR(100) NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_permis_temporaire_cible` (`cible_type`, `cible_id`),
  KEY `idx_permis_temporaire_statut` (`statut`),
  UNIQUE KEY `uniq_permis_temporaire_numero` (`numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
