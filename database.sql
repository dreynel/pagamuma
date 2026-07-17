-- --------------------------------------------------------
-- PAG-AMUMA Database Setup Script
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `pagamuma_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pagamuma_db`;

-- --------------------------------------------------------
-- Table Structure for `users`
-- Note: oauth_provider and oauth_id columns are prepared for 
-- future Google/Facebook login integration as requested.
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `system_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text NOT NULL,
  `status` enum('pending','approved') DEFAULT 'pending',
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NULL, -- Nullable to support OAuth-only users later
  `oauth_provider` ENUM('local', 'google', 'facebook') DEFAULT 'local',
  `oauth_id` VARCHAR(255) NULL,
  `profile_picture` VARCHAR(255) NULL,
  `role` ENUM('mother', 'admin') DEFAULT 'mother',
  `preferred_language` ENUM('en', 'hil', 'tl') DEFAULT 'en',
  `address` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- Table Structure for `pregnancy_profiles`
-- Stores mother's specific pregnancy tracking data
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pregnancy_profiles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `expected_due_date` DATE NULL,
  `pregnancy_start_date` DATE NULL,
  `blood_type` VARCHAR(10) NULL,
  `medical_history` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Table Structure for `health_logs`
-- Allows mothers to track daily/weekly health data in the dashboard
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `health_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `log_date` DATE NOT NULL,
  `weight_kg` DECIMAL(5,2) NULL,
  `blood_pressure` VARCHAR(20) NULL,
  `symptoms` TEXT NULL,
  `prescription` TEXT NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Table Structure for `chat_sessions`
-- For grouping chatbot conversations per user
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `chat_sessions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `started_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `ended_at` TIMESTAMP NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Table Structure for `chat_messages`
-- Stores the actual messages between the mother and the PAG-AMUMA bot
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `session_id` INT NOT NULL,
  `sender_type` ENUM('user', 'bot') NOT NULL,
  `message_text` TEXT NOT NULL,
  `sent_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`session_id`) REFERENCES `chat_sessions`(`id`) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Table Structure for `educational_modules`
-- Dynamic storage for learning materials to support English/Hiligaynon/Tagalog
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `educational_modules` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category` ENUM('health', 'parenting', 'emotional') NOT NULL,
  `title_en` VARCHAR(255) NOT NULL,
  `title_hil` VARCHAR(255) NOT NULL,
  `title_tl` VARCHAR(255) NOT NULL,
  `content_en` MEDIUMTEXT NOT NULL,
  `content_hil` MEDIUMTEXT NOT NULL,
  `content_tl` MEDIUMTEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
