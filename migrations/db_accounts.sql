CREATE TABLE db_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    website_id INT,
    name VARCHAR(255) NOT NULL,
    host VARCHAR(255) NOT NULL,
    user VARCHAR(255) NOT NULL,
    pass VARCHAR(255),
    FOREIGN KEY (website_id) REFERENCES websites(id)
);