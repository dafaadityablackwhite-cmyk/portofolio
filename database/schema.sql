-- Jalankan di database `khalifaxxjamesxx` (phpMyAdmin > Import / tab SQL)
-- Catatan: tabel juga dibuat otomatis oleh includes/projects.php bila belum ada.

CREATE TABLE IF NOT EXISTS projects (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(100) NOT NULL,
  description VARCHAR(255) NOT NULL,
  detail      TEXT NULL,
  category    ENUM('app','web','game') NOT NULL DEFAULT 'web',
  status      VARCHAR(30)  NOT NULL DEFAULT 'Pengembangan',
  icon        VARCHAR(40)  NOT NULL DEFAULT 'fa-code',
  url         VARCHAR(255) NULL,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
