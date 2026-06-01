-- Rigel Foundation AI Career Portal Database Schema

-- Create and use database
CREATE DATABASE IF NOT EXISTS rigel_db;
USE rigel_db;

-- 1. Users Table (FR1: Authentication and User Management)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    account_type ENUM('student', 'fresher', 'professional', 'admin') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- 2. Preset Question Bank (FR4: Preset Question and Answer Module)
CREATE TABLE IF NOT EXISTS preset_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL, -- e.g., 'Technical', 'HR', 'Behavioral'
    job_role VARCHAR(100) NOT NULL, -- e.g., 'Software Engineer', 'Data Scientist'
    question_text TEXT NOT NULL,
    detailed_answer TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Resumes Analysis Table (FR6: Resume Analysis Module)
CREATE TABLE IF NOT EXISTS resumes_analysis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    extracted_skills TEXT, -- Structured JSON or CSV containing skills extracted by AI
    ai_career_recommendations TEXT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 4. Mock Interview Sessions & History (FR5: Mock Interview Module)
CREATE TABLE IF NOT EXISTS interview_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_role VARCHAR(100) NOT NULL,
    interview_type ENUM('technical', 'hr', 'mixed') NOT NULL,
    overall_score DECIMAL(5,2),
    strengths TEXT,
    improvement_areas TEXT,
    detailed_summary TEXT, -- Final AI report
    session_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 5. Individual Interview QA Storage (For Analytics & History)
CREATE TABLE IF NOT EXISTS interview_qa_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    question_text TEXT NOT NULL,
    user_answer TEXT NOT NULL,
    ai_evaluation TEXT NOT NULL,
    score DECIMAL(5,2),
    FOREIGN KEY (session_id) REFERENCES interview_sessions(id) ON DELETE CASCADE
);

-- 6. Skill Assessments (FR7: Skill Assessment Module)
CREATE TABLE IF NOT EXISTS skill_assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    assessment_domain VARCHAR(100) NOT NULL,
    final_score INT NOT NULL,
    strengths TEXT,
    weaknesses TEXT,
    taken_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert a default Admin user (Password is 'Admin123' -- hashed via standard PHP password_hash)
INSERT INTO users (full_name, email, password_hash, account_type) 
VALUES ('System Admin', 'admin@rigel.ai', '$2y$10$YourHashedPasswordHere...', 'admin')
ON DUPLICATE KEY UPDATE email=email;
