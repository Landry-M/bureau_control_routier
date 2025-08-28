-- Table pour stocker les photos de preuve des contraventions
CREATE TABLE IF NOT EXISTS contravention_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contravention_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT DEFAULT 0,
    mime_type VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (contravention_id) REFERENCES contraventions(id) ON DELETE CASCADE,
    INDEX idx_contravention_id (contravention_id)
);
