-- Add en_circulation flag to indicate if a vehicle is currently in circulation
-- MySQL
ALTER TABLE `vehicule_plaque`
  ADD COLUMN `en_circulation` TINYINT(1) NOT NULL DEFAULT 1 AFTER `plaque_expire_le`;

-- SQLite equivalent (commented)
-- ALTER TABLE vehicule_plaque ADD COLUMN en_circulation INTEGER NOT NULL DEFAULT 1;
