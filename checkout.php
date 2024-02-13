<?php
 
session_start();
	
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Clear cart session variable
    $_SESSION['cart'] = array();
}
  require 'config.php';
  $grand_total =0;
  $allItems = '';
  $items = array();
  
  $sql = "SELECT CONCAT(product_name, '(',qty,')') AS ItemQty,total_price FROM cart";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()){
		$grand_total +=$row['total_price'];
		$items[] = $row['ItemQty'];
		
	}
	$allItems = implode(", ", $items); // Concatenate items with a comma and space

	echo $grand_total;
	?>
	
	
	<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
	
    <title>Shopping Cart System</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>


<nav class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
  <!-- Brand -->
  <a class="navbar-brand" href="index.php">Piggybanks</a>

  <!-- Toggler/collapsible Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="pinatasindex.php">Pinatas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="decorindex.php">Decor</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="checkout.php">Checkout</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="cart.php"><i class="bi bi-cart"></i>  <span id="cart-item" class="badge badge-danger"></span></a>
      </li>
    </ul>
  </div>
</nav>
<div class="container">
 <div class="row justify-content-center">
   <div class="col-lg-6 px-4" id="order">
      <h4 class="text-center text-info p-2">Complete your order.</h4>
         <div class="jumbotron p-3 mb-2 text-center">
             <h6 class="lead"><b>Product(s) : </b><?= $allItems; ?></h6>
			 <h5><b>Total Amount: </b><?= number_format($grand_total,2) ?></h5>
			 </div>
<form action="" method="post" id="placeOrder">
    <input type="hidden" name="products" value="<?= $allItems; ?>">
    <input type="hidden" name="grand_total" value="<?= $grand_total; ?>">
    
    <div class="form-group">
        <input type="text" name="name" class="form-control" placeholder="Enter Name" required>
    </div>

    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Enter E-mail" required>
    </div>

    <div class="form-group">
        <input type="tel" name="phone" class="form-control" placeholder="Enter Phonenumber" required>
    </div>

    <div class="form-group">
        <textarea name="address" class="form-control" rows="3" cols="10" placeholder="Enter Company Name and Address"></textarea>
    </div>

    <h6 class="text-center lead">Select Payment Mode</h6>

    <div class="form-group">
        <select name="pmode" class="form-control">
            <option value="" selected disabled>Select Payment Mode</option>
            <option value="cod">Cash On Delivery</option>
            <option value="cards">Debit/Credit Card</option>
        </select>
    </div>

    <div class="form-group">
        <input type="submit" name="submit" value="Place Order" class="btn btn-danger btn-block">
    </div>
</form>

	
	
	
</form>

			   
			 
             </div>
         </div>
	</div>	 
</div>

<div class=""></div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


<script type="text/javascript">
$(document).ready(function(){
	
$("#placeOrder").submit(function(e){
    e.preventDefault();
    $.ajax({
        url: 'action.php',
        method: 'post',
        data: $('form').serialize() + "&action=order",
        success: function(response){
            $("#order").html(response);
        }
    });
});


    load_cart_item_number();

    function load_cart_item_number(){
        $.ajax({
            url: 'action.php',
            method: 'get',
            data: {cartItem: "cart_item"},
            success: function(response){
                $("#cart-item").html(response);
            }
        });
    }
});

		  
		
	</script>	
</body>
</html>
	