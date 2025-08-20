-- Create table for arrestations/interpellations
CREATE TABLE IF NOT EXISTS `arrestations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `particulier_id` INT UNSIGNED NOT NULL,
  `motif` TEXT NOT NULL,
  `lieu` VARCHAR(255) NULL,
  `date_arrestation` DATETIME NULL,
  `date_sortie_prison` DATETIME NULL,
  `created_by` VARCHAR(100) NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_particulier` (`particulier_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
