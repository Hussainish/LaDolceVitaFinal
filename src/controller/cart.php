<?php 
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/utils.php';

checkLogin(); // make sure user is logged in.

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$total_price = 0;

// handle form submission (placing an order)
// please note that this just places an order
// it doesnt include actualp payment or payment verification
if (isset($_POST['place_order'])) {
    $credit_card_number = $_POST['credit_card'];

    if (!preg_match("/^\d{16}$/", $credit_card_number)) {
        echo '<script>alert("Invalid credit card number. Please enter a 16-digit card."); window.history.back();</script>';
        exit;
    }

    if (empty($_SESSION['cart'])) {
        echo '<script>alert("Your cart is empty! Please add items before placing an order."); window.history.back();</script>';
        exit;
    }

    $last4 = substr($credit_card_number, -4);

    $order_details = [];
    foreach ($_SESSION['cart'] as $item) {
        $order_details[] = "{$item['name']} x {$item['quantity']}";
        $total_price += $item['price'] * $item['quantity'];
    }
    $order_details_string = implode(', ', $order_details);

    $username = $_SESSION['username'];
    list($stmt, $result) = executePreparedQuery($mysqli, "SELECT AccountID FROM accounts WHERE Username = ?", "s", [$username]);
    
    if ($result->num_rows > 0) {
        $customer_row = $result->fetch_assoc();
        $customer_id = $customer_row['AccountID'];

        $sql = "INSERT INTO orders (CustomerID, OrderDetails, TotalPrice, CreditCardLast4) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("isss", $customer_id, $order_details_string, $total_price, $last4);
        
        if ($stmt->execute()) {
            $_SESSION['cart'] = [];
            echo '<script>alert("Your order has been placed successfully!"); window.location.href = "index.php?page=menu";</script>';
        } else {
            echo "Error: " . $mysqli->error;
        }
    } else {
        echo "Error: Unable to retrieve customer information.";
    }
}

// adding items to the cart
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $id = $_GET['id'];
    $stmt = $mysqli->prepare("SELECT MenuItemID, Name, Price, Image FROM menu WHERE MenuItemID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    if ($item) {
        $item_in_cart = false;
        foreach ($_SESSION['cart'] as &$cart_item) {
            if ($cart_item['id'] == $id) {
                $cart_item['quantity']++;
                $item_in_cart = true;
                break;
            }
        }
        if (!$item_in_cart) {
            $image = isset($item['Image']) ? $item['Image'] : "default.jpg";
            $_SESSION['cart'][] = [
                'image'    => $image,
                'id'       => $item['MenuItemID'],
                'name'     => $item['Name'],
                'price'    => $item['Price'],
                'quantity' => 1
            ];
        }
    }
    header('Location: index.php?page=menu');
    exit();
}

if (isset($_POST['remove_item'])) {
    $item_index = $_POST['item_index'];
    unset($_SESSION['cart'][$item_index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="base-url" content="/project/">
  <title>Shopping Cart - La Dolce Vita</title>
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/styles.css">
  <style>
    body {
      background-color: #f4f4f4;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }
    h1 {
      margin-top: 30px;
      margin-bottom: 30px;
      font-weight: bold;
      text-align: center;
    }
    
    .cart-container {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
    }
    table img {
      max-width: 80px;
      height: auto;
    }
    td {
      white-space: normal;
    }
    table.table {
      margin-bottom: 0;
    }
    table.table th, table.table td {
      vertical-align: middle;
    }
    .quantity-input {
      width: 80px;
      text-align: center;
    }
    .subtotal {
      font-weight: bold;
    }
    /* checkout button */
    .checkout-btn {
      font-size: 1.2rem;
      padding: 10px 20px;
      border-radius: 30px;
    }
    /* menu card */
    .menu-card {
      height: 450px;
      overflow: hidden;
      position: relative;
      margin-bottom: 30px;
      border: none;
      border-radius: 8px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .menu-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .menu-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
    }
    .menu-card .card-body {
      padding: 15px;
      position: relative;
    }
    .menu-card .card-title {
      font-size: 1.4rem;
      margin-bottom: 10px;
    }
    .menu-card .card-text {
      max-height: 100px;
      overflow-y: auto;
      margin-bottom: 10px;
    }
    .menu-card .price {
      position: absolute;
      bottom: 10px;
      left: 15px;
      font-size: 1.2rem;
      font-weight: bold;
    }
    .menu-card .add-to-cart {
      position: absolute;
      bottom: 10px;
      right: 15px;
    }
    /* modal */
    .modal-content {
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .payment-logos img {
      margin: 0 10px;
      width: 60px;
    }
  </style>
</head>
<body>
  <div id="navbar">
  <?php require_once realpath(__DIR__ . '/../views/header.php');?>
  </div>
  
  <div class="container mt-5">
    <h1 class="text-center">Shopping Cart</h1>
    <div class="cart-container table-responsive">
      <table class="table table-striped table-hover">
        <thead class="thead-dark">
          <tr>
            <th>Image</th>
            <th>Item</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($_SESSION['cart'])): ?>
            <?php foreach ($_SESSION['cart'] as $index => $item): ?>
              <tr>
                <td class="text-center">
                  <img src="<?php echo htmlspecialchars($item['image']); ?>" width="100" height="100" alt="Item Image" class="img-thumbnail">
                </td>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td>$<?php echo number_format($item['price'], 2); ?></td>
                <td>
                  <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control quantity-input" data-index="<?php echo $index; ?>" data-price="<?php echo $item['price']; ?>">
                </td>
                <td class="subtotal" data-index="<?php echo $index; ?>">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                <td>
                  <form action="index.php?page=cart" method="POST">
                    <input type="hidden" name="item_index" value="<?php echo $index; ?>">
                    <button type="submit" name="remove_item" class="btn btn-danger btn-sm">Remove</button>
                  </form>
                </td>
              </tr>
              <?php $total_price += $item['price'] * $item['quantity']; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center">Your cart is empty.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="text-right mb-5">
      <h4>Total: $<span id="total-price"><?php echo number_format($total_price, 2); ?></span></h4>
      <button class="btn btn-success checkout-btn" data-toggle="modal" data-target="#checkoutModal">Proceed to Checkout</button>
    </div>
  </div>
  
  <!-- checkout modal -->
  <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
              <h5 class="modal-title" id="checkoutModalLabel">Enter Payment Information</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="index.php?page=cart" method="POST">
              <div class="modal-body">
                <div class="payment-logos text-center mb-4">
                  <img src="assets/images/Visa.png" alt="Visa">
                  <img src="assets/images/Mastercard.png" alt="Mastercard">
                </div>
                <div class="form-group">
                  <label for="credit_card">Credit Card Number:</label>
                  <input type="text" class="form-control" id="credit_card" name="credit_card" pattern="\d{16}" placeholder="Enter 16-digit card number" required>
                </div>
                <div class="form-group">
                  <label for="holder_name">Card Holder Name:</label>
                  <input type="text" class="form-control" id="holder_name" name="holder_name" placeholder="Name on the card" required>
                </div>
                <div class="form-group">
                  <label for="expiry_date">Expiry Date (MM/YY):</label>
                  <input type="text" class="form-control" id="expiry_date" name="expiry_date" pattern="(0[1-9]|1[0-2])\/?([0-9]{2})" placeholder="MM/YY" required>
                </div>
                <div class="form-group">
                  <label for="cvc">CVC:</label>
                  <input type="text" class="form-control" id="cvc" name="cvc" pattern="\d{3,4}" placeholder="3 or 4-digit code" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                
                <button type="submit" name="place_order" class="btn btn-primary">Checkout</button>
              </div>
            </form>
          </div>
      </div>
  </div>
  
  <script>
    // update subtotal and total price dynamically apon ammount change
    document.querySelectorAll('.quantity-input').forEach(input => {
      input.addEventListener('input', function() {
        const index = this.dataset.index;
        const price = parseFloat(this.dataset.price);
        const quantity = parseInt(this.value);
        const subtotalElement = document.querySelector(`.subtotal[data-index="${index}"]`);
        const subtotal = price * quantity;
        subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
        let total = 0;
        document.querySelectorAll('.quantity-input').forEach(input => {
          const itemPrice = parseFloat(input.dataset.price);
          const itemQuantity = parseInt(input.value);
          total += itemPrice * itemQuantity;
        });
        document.getElementById('total-price').textContent = total.toFixed(2);
      });
    });
  </script>
  
  <footer class="footer">
    <div class="container text-center">
        <p>&copy; 2024 La Dolce Vita. All rights Reserved. </p>
    </div>
  </footer>
  
  <script src="/assets/js/scripts.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
