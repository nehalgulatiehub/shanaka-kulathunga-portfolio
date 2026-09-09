CREATE DATABASE IF NOT EXISTS shanaka_portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shanaka_portfolio;

CREATE TABLE admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE artworks (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(190) NOT NULL,
  category ENUM('oil','charcoal','commission','conceptual') NOT NULL,
  medium VARCHAR(190) NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE enquiries (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(190) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(80) NOT NULL DEFAULT '',
  subject VARCHAR(255) NOT NULL DEFAULT '',
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX created_at_index (created_at)
) ENGINE=InnoDB;

CREATE TABLE newsletter_subscribers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Create the first administrator after importing this file. Run in Hostinger's PHP terminal:
-- php -r "echo password_hash('choose-a-long-unique-password', PASSWORD_DEFAULT), PHP_EOL;"
-- Then run this in phpMyAdmin, replacing the hash:
-- INSERT INTO admins (email, password_hash) VALUES ('your-email@example.com', 'PASTE_THE_GENERATED_HASH_HERE');
