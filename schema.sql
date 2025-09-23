CREATE TABLE pages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(100) NOT NULL UNIQUE,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  is_home BOOLEAN DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(100) NOT NULL UNIQUE,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Initial content
INSERT INTO pages (slug, title, content, is_home) VALUES
('home', 'Welcome to My Custom Blog', '<p>This is your home page. Edit it in the admin area.</p>', 1),
('about', 'About', '<p>This technology blog covers Microsoft 365, Azure, Linux, and Copilot news, tips, and tutorials.</p>', 0),
('contact', 'Contact', '<p>Contact us at <a href="mailto:info@andykemp.cloud">info@andykemp.cloud</a></p>', 0);

INSERT INTO posts (slug, title, content) VALUES
('hello-m365', 'Getting Started with Microsoft 365', 'This is your first post about Microsoft 365. Edit or delete it, then start blogging!'),
('azure-automation', 'Automating with Azure', 'An introduction to Azure automation for IT pros, with tips for integrating with Microsoft 365 and Linux.');