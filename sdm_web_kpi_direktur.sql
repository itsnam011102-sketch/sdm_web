-- =========================================================
-- DATABASE: sdm_web
-- Modul: Form KPI Direktur
-- Untuk: XAMPP (MySQL/MariaDB via phpMyAdmin)
-- =========================================================

CREATE DATABASE IF NOT EXISTS sdm_web
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE sdm_web;

-- ---------------------------------------------------------
-- 1. TABEL MASTER: perspektif
-- Berisi 4 perspektif Balanced Scorecard (data tetap)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS perspektif;

CREATE TABLE perspektif (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_perspektif VARCHAR(100) NOT NULL,
    urutan INT NOT NULL,
    deskripsi VARCHAR(255)
) ENGINE=InnoDB;

INSERT INTO perspektif (nama_perspektif, urutan, deskripsi) VALUES
('Keuangan', 1, 'Target dan pencapaian finansial organisasi'),
('Pelanggan', 2, 'Kepuasan dan retensi layanan pelanggan'),
('Proses Bisnis Internal', 3, 'Efisiensi operasional dan kualitas tata kelola internal'),
('Pembelajaran & Pertumbuhan', 4, 'Pengembangan SDM, kapabilitas, dan inovasi');

-- ---------------------------------------------------------
-- 2. TABEL HEADER: kpi_penilaian
-- Satu baris = satu submit form (per divisi + periode)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS kpi_penilaian;

CREATE TABLE kpi_penilaian (
    id INT AUTO_INCREMENT PRIMARY KEY,
    divisi VARCHAR(100) NOT NULL,
    periode VARCHAR(20) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unik_divisi_periode (divisi, periode)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 3. TABEL DETAIL: kpi_detail
-- Semua baris tabel KPI dari 4 perspektif, dibedakan
-- lewat kolom perspektif_id
-- ---------------------------------------------------------
DROP TABLE IF EXISTS kpi_detail;

CREATE TABLE kpi_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kpi_penilaian_id INT NOT NULL,
    perspektif_id INT NOT NULL,
    no_urut INT NOT NULL DEFAULT 1,

    sasaran_strategis VARCHAR(255),
    program_inisiatif VARCHAR(255),
    indikator_kpi VARCHAR(255),
    formula VARCHAR(255),
    satuan VARCHAR(20) DEFAULT 'Rp',
    polaritas ENUM('Max','Min') DEFAULT 'Max',

    bobot DECIMAL(6,2) DEFAULT 0,
    target DECIMAL(18,2) DEFAULT 0,
    realisasi DECIMAL(18,2) DEFAULT 0,
    capaian DECIMAL(8,2) DEFAULT 0,
    rating DECIMAL(6,2) DEFAULT 0,
    nilai_kpi DECIMAL(8,2) DEFAULT 0,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (kpi_penilaian_id) REFERENCES kpi_penilaian(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (perspektif_id) REFERENCES perspektif(id) ON DELETE RESTRICT ON UPDATE CASCADE,

    INDEX idx_kpi_penilaian (kpi_penilaian_id),
    INDEX idx_perspektif (perspektif_id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Contoh view: rekap total bobot per perspektif per penilaian
-- (opsional, tapi berguna buat nampilin "Total Bobot" di tiap section)
-- ---------------------------------------------------------
CREATE OR REPLACE VIEW v_total_bobot AS
SELECT
    d.kpi_penilaian_id,
    d.perspektif_id,
    p.nama_perspektif,
    SUM(d.bobot) AS total_bobot
FROM kpi_detail d
JOIN perspektif p ON p.id = d.perspektif_id
GROUP BY d.kpi_penilaian_id, d.perspektif_id;

-- =========================================================
-- SELESAI
-- Cara pakai di XAMPP:
-- 1. Buka phpMyAdmin (http://localhost/phpmyadmin)
-- 2. Klik tab "Import" -> pilih file ini -> Go
--    (atau jalankan lewat tab SQL, copy-paste isi file ini)
-- =========================================================
