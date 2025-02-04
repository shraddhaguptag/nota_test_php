CREATE TABLE wiki_sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
    title VARCHAR(230) NOT NULL,
    url VARCHAR(240) NOT NULL UNIQUE,
    picture VARCHAR(240) UNIQUE,
    abstract VARCHAR(256) UNIQUE
);
