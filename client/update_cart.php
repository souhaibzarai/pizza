<?php
// Set content type for AJAX responses
header('Content-Type: application/json');

// Get JSON data from POST request
$input = json_decode(file_get_contents('php://input'), true);

if (! $input || ! isset($input['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Get current cart from cookie
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

switch ($input['action']) {
    case 'update':
        // Validate input
        if (! isset($input['product_id']) || ! isset($input['quantity'])) {
            echo json_encode(['success' => false, 'message' => 'Missing product ID or quantity']);
            exit;
        }

        $product_id = (int) $input['product_id'];
        $quantity   = (int) $input['quantity'];

        // Ensure quantity is positive
        if ($quantity <= 0) {
            // Remove item if quantity is 0 or negative
            if (isset($cart[$product_id])) {
                unset($cart[$product_id]);
            }
        } else {
            // Check if product exists in database before adding to cart
            require_once 'db.php';

            $stmt = $pdo->prepare("SELECT id, price FROM menu_items WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (! $product) {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
                exit;
            }

            // Update quantity
            if (isset($cart[$product_id])) {
                // If 'replace' is explicitly set, override the quantity
                if (isset($input['replace']) && $input['replace'] === true) {
                    $cart[$product_id] = $quantity;
                } else {
                    // Compare current quantity with requested one
                    if ($quantity > $cart[$product_id]) {
                        $cart[$product_id] += 1; // Increment by 1
                    } elseif ($quantity < $cart[$product_id]) {
                        $cart[$product_id] -= 1; // Decrement by 1
                        if ($cart[$product_id] <= 0) {
                            unset($cart[$product_id]); // Remove if it goes to 0
                        }
                    }
                    // If equal, no change needed
                }
            } else {
                // New item
                $cart[$product_id] = $quantity;
            }
        }
        break;

    case 'remove':
        // Validate input
        if (! isset($input['product_id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing product ID']);
            exit;
        }

        $product_id = (int) $input['product_id'];

        // Remove item from cart
        if (isset($cart[$product_id])) {
            unset($cart[$product_id]);
        }
        break;

    case 'clear':
        // Clear all items
        $cart = [];
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
}

// Update cookie with new cart data (30 days expiry)
$expiry = time() + (86400 * 30);
setcookie('cart', json_encode($cart), [
    'expires'  => $expiry,
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Strict',
]);

// Return success response
echo json_encode([
    'success' => true,
    'message' => 'Cart updated successfully',
    'cart'    => $cart,
]);
