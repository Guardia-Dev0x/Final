USE guardia;



-- Création de la table 'users'
-- Assurez-vous d'avoir sélectionné la bonne base de données avant d'exécuter ce script.

CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `fullname` VARCHAR(255) NOT NULL COMMENT 'Nom complet de l''utilisateur',
    `email` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Adresse e-mail unique',
    `password_hash` VARCHAR(255) NOT NULL COMMENT 'Hachage sécurisé du mot de passe',
    `phone` VARCHAR(25) NULL DEFAULT NULL COMMENT 'Numéro de téléphone (optionnel)',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date et heure de l''inscription',
    `is_verified` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Statut de vérification (0=non, 1=oui)',
    
    -- Optionnel : Index pour une recherche rapide par email
    INDEX `idx_email` (`email`)
) 
ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci 
COMMENT='Table des utilisateurs pour l''inscription et la connexion';