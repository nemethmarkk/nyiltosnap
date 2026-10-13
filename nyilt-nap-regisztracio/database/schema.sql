CREATE DATABASE IF NOT EXISTS nyilt_nap
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_hungarian_ci;
USE nyilt_nap;

CREATE TABLE IF NOT EXISTS specialties (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(160) NOT NULL,
  slug VARCHAR(180) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY uq_specialty_name (name),
  UNIQUE KEY uq_specialty_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

CREATE TABLE IF NOT EXISTS registrations (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name VARCHAR(160) NOT NULL,
  school_name VARCHAR(200) NOT NULL,
  accommodation ENUM('dormitory', 'commuter') NOT NULL,
  is_graduating TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_registrations_created_at (created_at),
  KEY idx_registrations_full_name (full_name),
  KEY idx_registrations_school_name (school_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

CREATE TABLE IF NOT EXISTS registration_specialties (
  registration_id BIGINT UNSIGNED NOT NULL,
  specialty_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (registration_id, specialty_id),
  KEY idx_registration_specialties_specialty (specialty_id),
  CONSTRAINT fk_rs_registration FOREIGN KEY (registration_id)
    REFERENCES registrations (id) ON DELETE CASCADE,
  CONSTRAINT fk_rs_specialty FOREIGN KEY (specialty_id)
    REFERENCES specialties (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- Minta szakmalista: import előtt vagy phpMyAdminban írd át a saját iskolátok kínálatára.
INSERT IGNORE INTO specialties (name, slug, sort_order) VALUES
('CNC Programozó', 'cnc-programozo', 10),
('Mechatronikai technikus', 'mechatronikai-technikus', 20),
('Mechatronikus karbantartó', 'mechatronikus-karbantarto', 30),
('Pénzügyi-számviteli ügyintéző', 'penzugyi-szamviteli-ugyintezo', 40),
('Szerszám- és készülékgyártó', 'szerszam-es-keszulekgyarto', 50),
('Szoftverfejlesztő és -tesztelő', 'szoftverfejleszto-es-tesztelo', 60);
