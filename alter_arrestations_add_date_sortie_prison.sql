-- Add optional release date column to arrestations
ALTER TABLE `arrestations`
  ADD COLUMN `date_sortie_prison` DATETIME NULL AFTER `date_arrestation`;
