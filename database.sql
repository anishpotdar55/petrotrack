-- ============================================================
--  PetroTrack · Oil & Gas Inventory System
--  Run this file once in MySQL to set up the database
--  Command: mysql -u root -p < database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS petrotrack;
USE petrotrack;

-- ── PRODUCTS ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS products (
    product_id      VARCHAR(20)     PRIMARY KEY,
    product_name    VARCHAR(100)    NOT NULL,
    product_type    ENUM('Crude','Refined','Gas') NOT NULL,
    unit            VARCHAR(10)     NOT NULL,
    reorder_level   DECIMAL(12,2)   NOT NULL,
    price_per_unit  DECIMAL(10,2)   DEFAULT 0
);

-- ── STORAGE TANKS ───────────────────────────────────────────
CREATE TABLE IF NOT EXISTS storage_tanks (
    tank_id         VARCHAR(20)     PRIMARY KEY,
    tank_name       VARCHAR(100)    NOT NULL,
    location        VARCHAR(100)    NOT NULL,
    capacity        DECIMAL(12,2)   NOT NULL,
    status          ENUM('Active','Maintenance','Inactive') DEFAULT 'Active'
);

-- ── INVENTORY ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS inventory (
    inventory_id    INT             AUTO_INCREMENT PRIMARY KEY,
    product_id      VARCHAR(20)     NOT NULL,
    tank_id         VARCHAR(20)     NOT NULL,
    quantity        DECIMAL(12,2)   NOT NULL DEFAULT 0,
    last_updated    TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    source          VARCHAR(50),
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    FOREIGN KEY (tank_id)    REFERENCES storage_tanks(tank_id),
    CONSTRAINT chk_qty CHECK (quantity >= 0)
);

-- ── TRANSACTIONS ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS transactions (
    txn_id          INT             AUTO_INCREMENT PRIMARY KEY,
    product_id      VARCHAR(20)     NOT NULL,
    operation       ENUM('INBOUND','OUTBOUND','TRANSFER') NOT NULL,
    quantity_delta  DECIMAL(12,2)   NOT NULL,
    source_system   VARCHAR(50),
    destination     VARCHAR(100),
    txn_timestamp   TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    status          ENUM('Completed','Pending','Failed') DEFAULT 'Completed',
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- ── AUDIT LOG (auto-filled by trigger) ──────────────────────
CREATE TABLE IF NOT EXISTS audit_log (
    log_id          INT             AUTO_INCREMENT PRIMARY KEY,
    product_id      VARCHAR(20)     NOT NULL,
    tank_id         VARCHAR(20)     NOT NULL,
    old_quantity    DECIMAL(12,2),
    new_quantity    DECIMAL(12,2),
    delta           DECIMAL(12,2),
    operation       VARCHAR(20),
    changed_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP
);

-- ── TRIGGER: auto-log every inventory change ─────────────────
DROP TRIGGER IF EXISTS trg_audit_inventory;

DELIMITER $$
CREATE TRIGGER trg_audit_inventory
AFTER UPDATE ON inventory
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (product_id, tank_id, old_quantity, new_quantity, delta, operation)
    VALUES (
        OLD.product_id,
        OLD.tank_id,
        OLD.quantity,
        NEW.quantity,
        NEW.quantity - OLD.quantity,
        CASE
            WHEN NEW.quantity > OLD.quantity THEN 'INBOUND'
            WHEN NEW.quantity < OLD.quantity THEN 'OUTBOUND'
            ELSE 'NO_CHANGE'
        END
    );
END$$
DELIMITER ;

-- ── SEED DATA ────────────────────────────────────────────────
INSERT INTO products VALUES
('CRUDE-001', 'Crude Oil (Brent)', 'Crude',   'bbl', 15000, 82.40),
('CRUDE-002', 'Crude Oil (WTI)',   'Crude',   'bbl', 15000, 78.10),
('DIST-001',  'Diesel EN590',      'Refined', 'MT',  5000,  920.00),
('DIST-002',  'Aviation Fuel JA1', 'Refined', 'MT',  4000,  1100.00),
('GAS-001',   'LPG Propane Mix',   'Gas',     'MT',  3000,  650.00),
('OIL-001',   'Lubricant Base Oil','Refined', 'MT',  3500,  1200.00);

INSERT INTO storage_tanks VALUES
('TK-01', 'Tank Farm A · T1',   'Zone A, North', 120000, 'Active'),
('TK-02', 'Tank Farm A · T2',   'Zone A, South', 100000, 'Active'),
('TK-03', 'Block 3 · East',     'Zone C, East',   35000, 'Active'),
('TK-04', 'Block 3 · West',     'Zone C, West',   10000, 'Active'),
('TK-05', 'Gas Sphere GS-1',    'Zone D',         25000, 'Active'),
('TK-06', 'Jet Fuel Store JF',  'Zone B',         15000, 'Active');

INSERT INTO inventory (product_id, tank_id, quantity, source) VALUES
('CRUDE-001', 'TK-01', 84200, 'PIPELINE-A'),
('CRUDE-002', 'TK-02', 62400, 'TANKER-B7'),
('DIST-001',  'TK-03', 4200,  'REFINERY-1'),
('DIST-002',  'TK-06', 3800,  'REFINERY-1'),
('GAS-001',   'TK-05', 11200, 'PLANT-3'),
('OIL-001',   'TK-04', 2900,  'REFINERY-2');
