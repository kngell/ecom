CREATE TABLE user(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

INSERT INTO products (name,description,price,created_at) VALUES ("PHP eCommerce Project #22: ", "Il est nécessaire si vous  ",94400,CURRENT_TIMESTAMP);