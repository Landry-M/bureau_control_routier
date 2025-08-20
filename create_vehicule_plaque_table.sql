-- Table: vehicule_plaque (MySQL)
CREATE TABLE IF NOT EXISTS `vehicule_plaque` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `images` JSON NULL,
  `marque` VARCHAR(100) NOT NULL,
  `modele` VARCHAR(100) NULL,
  `annee` VARCHAR(10) NULL,
  `couleur` VARCHAR(50) NULL,
  `numero_chassis` VARCHAR(100) NULL,
  -- Champs d'importation
  `frontiere_entree` VARCHAR(100) NULL,
  `date_importation` DATE NULL,
  -- Plaque
  `plaque` VARCHAR(50) NULL,
  `plaque_valide_le` DATE NULL,
  `plaque_expire_le` DATE NULL,
  `en_circulation` TINYINT(1) NOT NULL DEFAULT 1,
  -- Assurance
  `nume_assurance` VARCHAR(100) NULL,
  `date_expire_assurance` DATE NULL,
  `date_valide_assurance` DATE NULL,
  `societe_assurance` VARCHAR(150) NULL,
  PRIMARY KEY (`id`),
  KEY `idx_plaque` (`plaque`),
  KEY `idx_plaque_dates` (`plaque_valide_le`,`plaque_expire_le`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- NOTE (SQLite équivalent)
-- CREATE TABLE IF NOT EXISTS vehicule_plaque (
--   id INTEGER PRIMARY KEY AUTOINCREMENT,
--   images TEXT NULL,
--   marque TEXT NOT NULL,
--   modele TEXT NULL,
--   annee TEXT NULL,
--   couleur TEXT NULL,
--   numero_chassis TEXT NULL,
--   frontiere_entree TEXT NULL,
--   date_importation TEXT NULL,
--   plaque TEXT NULL,
--   plaque_valide_le TEXT NULL,
--   plaque_expire_le TEXT NULL,
--   en_circulation INTEGER NOT NULL DEFAULT 1,
--   nume_assurance TEXT NULL,
--   date_expire_assurance TEXT NULL,
--   date_valide_assurance TEXT NULL,
--   societe_assurance TEXT NULL
-- );
