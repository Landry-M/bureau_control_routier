-- Table: avis_recherche
CREATE TABLE IF NOT EXISTS avis_recherche (
  id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cible_type VARCHAR(50) NOT NULL, -- ex: 'particulier', 'vehicule', 'entreprise'
  cible_id BIGINT NOT NULL,
  motif TEXT NOT NULL,
  niveau VARCHAR(20) NOT NULL DEFAULT 'moyen', -- 'faible' | 'moyen' | 'eleve'
  statut VARCHAR(20) NOT NULL DEFAULT 'actif', -- 'actif' | 'clos'
  created_by VARCHAR(100) NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  INDEX idx_cible_statut (cible_type, cible_id, statut),
  INDEX idx_statut (statut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
