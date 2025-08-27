-- Add technical fields fetched from DGI page to vehicule_plaque table
ALTER TABLE `vehicule_plaque`
  ADD COLUMN `genre` VARCHAR(100) NULL AFTER `societe_assurance`,
  ADD COLUMN `usage` VARCHAR(150) NULL AFTER `genre`,
  ADD COLUMN `numero_declaration` VARCHAR(150) NULL AFTER `usage`,
  ADD COLUMN `num_moteur` VARCHAR(150) NULL AFTER `numero_declaration`,
  ADD COLUMN `origine` VARCHAR(150) NULL AFTER `num_moteur`,
  ADD COLUMN `source` VARCHAR(150) NULL AFTER `origine`,
  ADD COLUMN `annee_fab` VARCHAR(10) NULL AFTER `source`,
  ADD COLUMN `annee_circ` VARCHAR(10) NULL AFTER `annee_fab`,
  ADD COLUMN `type_em` VARCHAR(100) NULL AFTER `annee_circ`;
