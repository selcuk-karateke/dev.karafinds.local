CREATE TABLE ftp_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    website_id INT,
    user VARCHAR(255) NOT NULL,
    pass VARCHAR(255),
    FOREIGN KEY (website_id) REFERENCES websites(id)
);