<?php
// api.php — handles all frontend requests
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {

    // ── GET all inventory with product + tank info ──────────
    case 'get_inventory':
        $db = getDB();
        $result = $db->query("
            SELECT i.inventory_id, i.product_id, p.product_name, p.product_type,
                   i.quantity, p.unit, p.reorder_level, t.capacity,
                   i.tank_id, t.tank_name, t.location,
                   i.source, i.last_updated
            FROM inventory i
            JOIN products p ON i.product_id = p.product_id
            JOIN storage_tanks t ON i.tank_id = t.tank_id
            ORDER BY p.product_type, p.product_name
        ");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        $db->close();
        break;

    // ── GET recent transactions ─────────────────────────────
    case 'get_transactions':
        $db = getDB();
        $result = $db->query("
            SELECT t.txn_id, t.operation, p.product_name, t.quantity_delta,
                   t.source_system, t.destination, t.txn_timestamp, t.status
            FROM transactions t
            JOIN products p ON t.product_id = p.product_id
            ORDER BY t.txn_timestamp DESC
            LIMIT 20
        ");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        $db->close();
        break;

    // ── GET audit log ────────────────────────────────────────
    case 'get_audit':
        $db = getDB();
        $result = $db->query("
            SELECT a.log_id, a.product_id, p.product_name,
                   a.old_quantity, a.new_quantity, a.delta,
                   a.operation, a.changed_at
            FROM audit_log a
            JOIN products p ON a.product_id = p.product_id
            ORDER BY a.changed_at DESC
            LIMIT 30
        ");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        $db->close();
        break;

    // ── GET tank list ────────────────────────────────────────
    case 'get_tanks':
        $db = getDB();
        $result = $db->query("
            SELECT t.tank_id, t.tank_name, t.location, t.capacity, t.status,
                   i.quantity, i.product_id, p.product_name
            FROM storage_tanks t
            LEFT JOIN inventory i ON t.tank_id = i.tank_id
            LEFT JOIN products p ON i.product_id = p.product_id
        ");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
        $db->close();
        break;

    // ── RECEIVE STOCK (INBOUND) ──────────────────────────────
    case 'receive_stock':
        $db = getDB();
        $product_id = $db->real_escape_string($_POST['product_id']);
        $quantity   = (float) $_POST['quantity'];
        $source     = $db->real_escape_string($_POST['source']);

        if ($quantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'Quantity must be greater than 0']);
            break;
        }

        // BEGIN transaction
        $db->begin_transaction();
        try {
            // Lock the row before updating
            $db->query("SELECT quantity FROM inventory 
                        WHERE product_id = '$product_id' FOR UPDATE");

            // Update inventory
            $db->query("UPDATE inventory 
                        SET quantity = quantity + $quantity, source = '$source'
                        WHERE product_id = '$product_id'");

            // Log to transactions table
            $db->query("INSERT INTO transactions (product_id, operation, quantity_delta, source_system)
                        VALUES ('$product_id', 'INBOUND', $quantity, '$source')");

            $db->commit();
            echo json_encode(['success' => true, 'message' => 'Stock received successfully']);
        } catch (Exception $e) {
            $db->rollback();
            echo json_encode(['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()]);
        }
        $db->close();
        break;

    // ── DISPATCH STOCK (OUTBOUND) ────────────────────────────
    case 'dispatch_stock':
        $db = getDB();
        $product_id  = $db->real_escape_string($_POST['product_id']);
        $quantity    = (float) $_POST['quantity'];
        $destination = $db->real_escape_string($_POST['destination']);

        if ($quantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'Quantity must be greater than 0']);
            break;
        }

        $db->begin_transaction();
        try {
            // Lock row and check current stock
            $check = $db->query("SELECT quantity FROM inventory 
                                 WHERE product_id = '$product_id' FOR UPDATE");
            $row = $check->fetch_assoc();

            if (!$row || $row['quantity'] < $quantity) {
                $db->rollback();
                echo json_encode(['success' => false, 'message' => 'Insufficient stock! Available: ' . ($row['quantity'] ?? 0)]);
                break;
            }

            // Deduct stock
            $db->query("UPDATE inventory 
                        SET quantity = quantity - $quantity
                        WHERE product_id = '$product_id'");

            // Log transaction
            $db->query("INSERT INTO transactions (product_id, operation, quantity_delta, destination)
                        VALUES ('$product_id', 'OUTBOUND', $quantity, '$destination')");

            $db->commit();
            echo json_encode(['success' => true, 'message' => 'Dispatch created successfully']);
        } catch (Exception $e) {
            $db->rollback();
            echo json_encode(['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()]);
        }
        $db->close();
        break;

    default:
        echo json_encode(['error' => 'Unknown action: ' . $action]);
}
?>
