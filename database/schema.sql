CREATE TABLE IF NOT EXISTS admins (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  display_name VARCHAR(120) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS applications (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reference VARCHAR(32) NULL UNIQUE,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  birth_date DATE NULL,
  gender VARCHAR(40) NULL,
  nationality VARCHAR(100) NULL,
  country VARCHAR(100) NOT NULL,
  region VARCHAR(100) NULL,
  city VARCHAR(100) NULL,
  neighborhood VARCHAR(120) NULL,
  address VARCHAR(255) NULL,
  phone VARCHAR(40) NOT NULL,
  whatsapp VARCHAR(40) NULL,
  email VARCHAR(190) NOT NULL,
  marital_status VARCHAR(80) NULL,
  professional_status VARCHAR(120) NULL,
  education_level VARCHAR(120) NULL,
  has_activity TINYINT(1) NOT NULL DEFAULT 0,
  business_name VARCHAR(190) NULL,
  business_start_date DATE NULL,
  business_sector VARCHAR(120) NULL,
  business_description TEXT NULL,
  business_location VARCHAR(190) NULL,
  employee_count INT UNSIGNED NULL,
  annual_revenue VARCHAR(120) NULL,
  registration_number VARCHAR(120) NULL,
  promoter_experience TEXT NULL,
  project_name VARCHAR(190) NOT NULL,
  sector VARCHAR(120) NOT NULL,
  project_country VARCHAR(100) NULL,
  project_region VARCHAR(100) NULL,
  project_city VARCHAR(100) NULL,
  project_location VARCHAR(190) NULL,
  project_description TEXT NOT NULL,
  project_stage VARCHAR(60) NOT NULL,
  amount_requested DECIMAL(15,2) NOT NULL,
  amount_approved DECIMAL(15,2) NULL,
  currency CHAR(3) NOT NULL,
  personal_contribution DECIMAL(15,2) NOT NULL DEFAULT 0,
  total_cost DECIMAL(15,2) NOT NULL DEFAULT 0,
  funding_use JSON NOT NULL,
  status VARCHAR(40) NOT NULL DEFAULT 'new',
  internal_note TEXT NULL,
  consent_accuracy TINYINT(1) NOT NULL DEFAULT 0,
  consent_privacy TINYINT(1) NOT NULL DEFAULT 0,
  consent_processing TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_application_status_created (status, created_at),
  INDEX idx_application_email (email),
  INDEX idx_application_phone (phone)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS application_events (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  application_id BIGINT UNSIGNED NOT NULL,
  status VARCHAR(40) NOT NULL,
  note VARCHAR(255) NULL,
  admin_id BIGINT UNSIGNED NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_event_application FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
  CONSTRAINT fk_event_admin FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL,
  INDEX idx_event_application_created (application_id, created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS settings (
  setting_key VARCHAR(80) PRIMARY KEY,
  setting_value VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contact_messages (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS testimonials (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  display_name VARCHAR(120) NOT NULL,
  role VARCHAR(150) NOT NULL DEFAULT '',
  quote TEXT NOT NULL,
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  sort_order INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_testimonials_public_order (is_published, sort_order)
) ENGINE=InnoDB;
