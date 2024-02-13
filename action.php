<?php
session_start();
require 'config.php';
// Reset the cart quantity session variable
$_SESSION['cartQty'] = 0;



if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $pname = $_POST['pname'];
    $pprice = $_POST['pprice'];
    $pimage = $_POST['pimage'];
    $pcode = $_POST['pcode'];
	
    // Check if the product already exists in the cart
    $stmt = $conn->prepare("SELECT id, qty FROM cart WHERE product_code = ?");
    $stmt->bind_param("s", $pcode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the product exists, increment the quantity
        $row = $result->fetch_assoc();
        $newQty = $row['qty'] + 1;
        $stmt = $conn->prepare("UPDATE cart SET qty = ? WHERE id = ?");
        $stmt->bind_param("ii", $newQty, $row['id']);
        $stmt->execute();
        $_SESSION['cartQty'] = $newQty; // Update session variable
    } else {
        // If the product doesn't exist, insert it into the cart
        $pqty = 1; // Set initial quantity to 1
        $stmt = $conn->prepare("INSERT INTO cart (product_name, product_price, product_image, qty, total_price, product_code) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiss", $pname, $pprice, $pimage, $pqty, $pprice, $pcode);
        $stmt->execute();
        $_SESSION['cartQty'] = $pqty; // Update session variable
    }

    // Set a session alert to indicate that the item was added to the cart
    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'Item added to the cart.';
}

// Update the cart quantity in the session variable
if (isset($_SESSION['cartQty'])) {
    echo $_SESSION['cartQty'];
} else {
    echo 0;
}


if(isset($_GET['cartItem']) && $_GET['cartItem'] == 'cart_item'){
    $stmt = $conn->prepare("SELECT * FROM cart");
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows;
    echo $rows;
}

if(isset($_GET['remove'])){
	$id = $_GET['remove'];
	
	$stmt = $conn->prepare("DELETE FROM cart WHERE id=?");
	$stmt->bind_param("i",$id);
	$stmt->execute();
	
	$_SESSION['showAlert'] = 'block';
	$_SESSION['message'] = 'Item removed from the cart.';

	header('location:cart.php');
}

	if(isset($_GET['clear'])){
		$stmt = $conn->prepare("DELETE FROM cart");
		$stmt->execute();
		$_SESSION['showAlert'] = 'block';
	    $_SESSION['message'] = 'All items removed from the cart.';
	    header('location:cart.php');
	  }
	  
if(isset($_POST['qty'])){
    $qty = $_POST['qty'];
    $pid = $_POST['pid'];
    $pprice= $_POST['pprice'];
    
    $tprice = $qty * $pprice;
    
    $stmt = $conn->prepare("UPDATE cart SET qty=?, total_price=? WHERE id=?");
    $stmt->bind_param("isi", $qty, $tprice, $pid); // corrected "idi" parameter types
    $stmt->execute();
	
	
}

	if (isset($_POST['action']) && $_POST['action'] == 'order') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $products = $_POST['products'];
    $grand_total = $_POST['grand_total'];
    $address = $_POST['address'];
    $pmode = $_POST['pmode'];

    $data = '';

    $stmt = $conn->prepare("INSERT INTO orders (name, email, phone, address, pmode, products, amount_paid) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $phone, $address, $pmode, $products, $grand_total);
    $stmt->execute();

    $data.= '<div class="text-center">
                <h1 class="display-4 mt-2 text-danger">Thank You!</h1>
                <h2 class="text-success">Your Order Placed Successfully!</h2>
                <h4 class="bg-danger text-light rounded p-2">Items Purchased: '.$products.'</h4>
                <h4>Your Name: '.$name. '</h4>
                <h4>Your E-mail: '.$email. '</h4>
                <h4>Your Phone: '.$phone. '</h4>
                <h4>Your Total Amount Paid : '.number_format($grand_total,2). '</h4>
            </div>';
    echo $data;
}
	
	?>