CREATE DATABASE converter_db;

USE converter_db;

CREATE TABLE conversions (
                             id INT AUTO_INCREMENT PRIMARY KEY,
                             user_id BIGINT NOT NULL,
                             amount FLOAT NOT NULL,
                             from_currency VARCHAR(3) NOT NULL,
                             to_currency VARCHAR(3) NOT NULL,
                             converted_amount FLOAT NOT NULL,
                             conversion_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

