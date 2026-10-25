-- ============================================================
--  Product & Manufacturer Management
--  DB      : product_evF
--  Engine  : InnoDB  (FK support)
--  Charset : utf8mb4 (Bangla + emoji)
-- ============================================================

DROP DATABASE IF EXISTS product_evF;
CREATE DATABASE product_evF CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE product_evF;

-- ============================================================
--  TABLES
-- ============================================================

CREATE TABLE manufacturers (
    id      INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name    VARCHAR(100) NOT NULL,
    contact VARCHAR(30)  NOT NULL DEFAULT '',
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE products (
    id              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    name            VARCHAR(100)  NOT NULL,
    price           DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    manufacturer_id INT UNSIGNED  NOT NULL,
    PRIMARY KEY (id),
    KEY idx_mfg (manufacturer_id),
    CONSTRAINT fk_products_mfg
        FOREIGN KEY (manufacturer_id) REFERENCES manufacturers(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
--  VIEW : view_products
-- ============================================================
CREATE OR REPLACE VIEW view_products AS
SELECT p.id,
       p.name,
       p.price,
       m.name AS manufacturer,
       p.manufacturer_id
FROM products p
JOIN manufacturers m ON m.id = p.manufacturer_id;

-- ============================================================
--  TRIGGERS : price < 0  →  set to 0
-- ============================================================
DROP TRIGGER IF EXISTS before_product_insert;
DROP TRIGGER IF EXISTS before_product_update;

DELIMITER //

CREATE TRIGGER before_product_insert
BEFORE INSERT ON products
FOR EACH ROW
BEGIN
    IF NEW.price IS NULL OR NEW.price < 0 THEN
        SET NEW.price = 0;
    END IF;
END//

CREATE TRIGGER before_product_update
BEFORE UPDATE ON products
FOR EACH ROW
BEGIN
    IF NEW.price IS NULL OR NEW.price < 0 THEN
        SET NEW.price = 0;
    END IF;
END//

DELIMITER ;

-- ============================================================
--  STORED PROCEDURES (used by PHP)
-- ============================================================
DROP PROCEDURE IF EXISTS add_manufacturer;
DROP PROCEDURE IF EXISTS add_product;

DELIMITER //

CREATE PROCEDURE add_manufacturer(IN _name VARCHAR(100), IN _contact VARCHAR(30))
BEGIN
    INSERT INTO manufacturers(name, contact) VALUES (_name, _contact);
END//

CREATE PROCEDURE add_product(IN _name VARCHAR(100), IN _price DECIMAL(10,2), IN _mfg_id INT)
BEGIN
    INSERT INTO products(name, price, manufacturer_id)
    VALUES (_name, _price, _mfg_id);
END//

DELIMITER ;

-- ============================================================
--  DEMO DATA
-- ============================================================
INSERT INTO manufacturers(name, contact) VALUES
('Samsung', '01711111111'),
('Apple',   '01722222222'),
('Walton',  '01733333333');

INSERT INTO products(name, price, manufacturer_id) VALUES
('TV',     50000.00, 1),
('Phone',  30000.00, 2),
('Fridge', 40000.00, 3),
('Laptop', 80000.00, 2);

-- ============================================================
--  DONE ✅
-- ============================================================