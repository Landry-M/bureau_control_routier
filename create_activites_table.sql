-- Table pour enregistrer toutes les activités des utilisateurs
CREATE TABLE IF NOT EXISTS `activites` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_user` int(11) DEFAULT NULL,
    `action` varchar(255) NOT NULL,
    `details_operation` text,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text,
    `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user` (`id_user`),
    KEY `idx_action` (`action`),
    KEY `idx_date` (`date_creation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
