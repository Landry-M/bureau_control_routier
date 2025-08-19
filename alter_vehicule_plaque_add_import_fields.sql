-- Alter table vehicule_plaque to add import-related fields

ALTER TABLE `vehicule_plaque`
  ADD COLUMN `frontiere_entree` VARCHAR(191) NULL AFTER `numero_chassis`,
  ADD COLUMN `date_importation` DATE NULL AFTER `frontiere_entree`;
