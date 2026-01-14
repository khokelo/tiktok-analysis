-- ============================================
-- TikTok Analysis Database Schema
-- Auto-generated from Laravel Migrations
-- Last Updated: 2026-01-14
-- ============================================

-- ============================================================================
-- USERS TABLE (0001_01_01_000000_create_users_table + migration adds role)
-- ============================================================================
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- PASSWORD RESET TOKENS TABLE (0001_01_01_000000_create_users_table)
-- ============================================================================
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- SESSIONS TABLE (0001_01_01_000000_create_users_table)
-- ============================================================================
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- CACHE TABLE (0001_01_01_000001_create_cache_table)
-- ============================================================================
CREATE TABLE IF NOT EXISTS cache (
    key VARCHAR(255) PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- CACHE LOCKS TABLE (0001_01_01_000001_create_cache_table)
-- ============================================================================
CREATE TABLE IF NOT EXISTS cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- JOBS TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    INDEX idx_queue (queue)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- JOB BATCHES TABLE (0001_01_01_000002_create_jobs_table)
-- ============================================================================
CREATE TABLE IF NOT EXISTS job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INT NOT NULL,
    pending_jobs INT NOT NULL,
    failed_jobs INT NOT NULL,
    failed_job_ids LONGTEXT NOT NULL,
    options MEDIUMTEXT NULL,
    cancelled_at INT NULL,
    created_at INT NOT NULL,
    finished_at INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- FAILED JOBS TABLE (0001_01_01_000002_create_jobs_table)
-- ============================================================================
CREATE TABLE IF NOT EXISTS failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) UNIQUE NOT NULL,
    connection LONGTEXT NOT NULL,
    queue LONGTEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- SALES TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS sales (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    campaign VARCHAR(255) NULL,
    day VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    direct_gmv DECIMAL(15, 2) NOT NULL DEFAULT 0,
    items_sold INT NOT NULL DEFAULT 0,
    customers INT NOT NULL DEFAULT 0,
    sku_orders INT NOT NULL DEFAULT 0,
    main_orders INT NOT NULL DEFAULT 0,
    viewers INT NOT NULL DEFAULT 0,
    views INT NOT NULL DEFAULT 0,
    product_impressions INT NOT NULL DEFAULT 0,
    click_through_rate DECIMAL(15, 3) NOT NULL DEFAULT 0,
    enter_room_rate DECIMAL(15, 3) NOT NULL DEFAULT 0,
    product_clicks INT NOT NULL DEFAULT 0,
    impressions INT NOT NULL DEFAULT 0,
    new_followers INT NOT NULL DEFAULT 0,
    shares INT NOT NULL DEFAULT 0,
    comments INT NOT NULL DEFAULT 0,
    likes INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_date (date),
    INDEX idx_campaign (campaign)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TIKTOK SALES TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS tiktok_sales (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    campaign VARCHAR(255) NULL,
    date DATE NULL,
    time VARCHAR(255) NULL,
    direct_gmv DECIMAL(15, 2) NOT NULL DEFAULT 0,
    items_sold INT NOT NULL DEFAULT 0,
    customers INT NOT NULL DEFAULT 0,
    viewers INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_date (date),
    INDEX idx_campaign (campaign)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- UPLOADED FILES TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS uploaded_files (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_size BIGINT NOT NULL,
    row_count INT NOT NULL DEFAULT 0,
    status ENUM('pending', 'processed', 'failed') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_uploaded_files_user_id FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- MIGRATIONS TABLE (Laravel internal - for tracking applied migrations)
-- ============================================================================
CREATE TABLE IF NOT EXISTS migrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
