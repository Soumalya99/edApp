-- ===========================
-- Educational Platform Schema
-- ===========================

CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE founders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    profile_image VARCHAR(255) NOT NULL,
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(170) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    batch_image_path VARCHAR(255),
    track VARCHAR(100),
    level VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE demo_classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    video_link VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE admins(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
);

CREATE TABLE resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pdf_path VARCHAR(255) NOT NULL,
    title VARCHAR(150),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);

CREATE TABLE resource_folder(
    id INT AUTO_INCREMENT PRIMARY KEY,
    folder_name VARCHAR(100) NOT NULL DEFAULT 'General',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE resources ADD COLUMN folder_id INT NULL;
ALTER TABLE resources ADD FOREIGN KEY (folder_id) 
REFERENCES resource_folder(id) ON DELETE CASCADE;


/** Create index for faster lookup*/
CREATE INDEX idx_courses_track_level ON courses(track, level);
CREATE INDEX idx_resources_folder ON resources(folder_id);
CREATE INDEX idx_resources_title ON resources(title);
