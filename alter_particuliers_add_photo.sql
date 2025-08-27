-- Adds optional photo path for particuliers
ALTER TABLE `particuliers`
  ADD COLUMN `photo` VARCHAR(255) NULL AFTER `observations`;
