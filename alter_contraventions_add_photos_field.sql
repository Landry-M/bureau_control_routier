-- Ajouter un champ photos à la table contraventions pour stocker les chemins des images
ALTER TABLE contraventions ADD COLUMN photos TEXT DEFAULT NULL COMMENT 'Chemins des photos séparés par des virgules';
