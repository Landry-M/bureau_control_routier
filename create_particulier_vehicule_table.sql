-- Pivot table for association Particulier <-> Vehicule (proprietaire only)
CREATE TABLE IF NOT EXISTS `particulier_vehicule` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `particulier_id` BIGINT NOT NULL,
  `vehicule_plaque_id` BIGINT NOT NULL,
  `role` VARCHAR(50) NOT NULL DEFAULT 'proprietaire',
  `date_assoc` DATETIME NULL,
  `notes` TEXT NULL,
  `created_at` DATETIME NULL,
  `created_by` VARCHAR(100) NULL,
  PRIMARY KEY (`id`),
  KEY `idx_particulier` (`particulier_id`),
  KEY `idx_vehicule` (`vehicule_plaque_id`),
  UNIQUE KEY `uniq_particulier_vehicule` (`particulier_id`,`vehicule_plaque_id`),
  CONSTRAINT `fk_pv_particulier` FOREIGN KEY (`particulier_id`) REFERENCES `particuliers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pv_vehicule` FOREIGN KEY (`vehicule_plaque_id`) REFERENCES `vehicule_plaque` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
