-- Migration pour renommer la colonne id_user en username
-- Bureau de Contrôle Routier - Table activites

-- Étape 1: Renommer la colonne id_user en username et changer le type
ALTER TABLE `activites` 
CHANGE COLUMN `id_user` `username` VARCHAR(100) NULL DEFAULT NULL;

-- Optionnel: Ajouter un index sur username pour améliorer les performances
CREATE INDEX `idx_activites_username` ON `activites` (`username`);

-- Vérifier la structure de la table après modification
DESCRIBE `activites`;
