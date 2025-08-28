-- Remove vehicle-related fields from entreprises table
ALTER TABLE `entreprises`
  DROP COLUMN IF EXISTS `marque_vehicule`,
  DROP COLUMN IF EXISTS `plaque_vehicule`;
