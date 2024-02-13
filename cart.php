<?php
session_start();
$_SESSION['message'] = "Your message goes here";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- Example icon usage -->
<i class="bi bi-heart-fill"></i>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
        <a class="nav-link active" href="cart.php">
		<i class="bi bi-cart"></i>
		<span id="cart-item" class="badge badge-danger">
		</span></a>
      </li>
    </ul>
  </div>
</nav>

 <div class="container">
   <div class="row justify-content-center">
    <div class="col-lg-10">
	
	   <div style="display:<?php
	   if(isset($_SESSION['showAlert'])) {
    	echo $_SESSION['showAlert']; 
	    }else { echo 'none';}
        unset($_SESSION['showAlert']); ?>" class="alert alert-success alert-dismissable
    mt-3"> 	
	
	 <button type="button" class="close" data-dismiss="alert">&times;</button>
     <strong><?php if(isset($_SESSION['message']))
	 {echo $_SESSION['message'];
     }?></strong>
     </div>
      <div class="table-responsive mt-2">
       <table class="table table-bordered table striped text-center">
        <tr>
          <td colspan="7">
            <h4 class="text-center text-info m-0">Products in you cart!
            </h4>
          </td>
    </tr>
		<tr>
		  <th>ID</th>
		  <th>Image</th>
		  <th>Product</th>
		  <th>Price</th>
		  <th>Quantity</th>
		  <th>Total Price</th>
		  <th>
		  <a href="action.php?clear=all" class="btn btn-danger" onclick="return confirm('Are you sure you want to clear your cart?');">
    <i class="bi bi-trash"></i>&nbsp;&nbsp;Clear Cart</a>
          
	</tr>
		  </thead>
		  <tbody>
		  <?php
require 'config.php';
$stmt = $conn->prepare("SELECT * FROM cart");
$stmt->execute();
$result = $stmt->get_result();
$grand_total = 0;
while ($row = $result->fetch_assoc()):
?>
<tr>
    <td><?= $row['id'] ?></td>
	<input type="hidden" class="pid" value="<?= $row['id'] ?>">
    <td>
	<img src="<?= $row['product_image'] ?>" width="50"></td>
	<td><?= $row['product_name'] ?></td>
	<td>
	  <i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;
    	<?= number_format($row['product_price'], 2); ?>
	</td>
	<input type="hidden" class="pprice" value="<? $row['product_price']?>"> 
	<td>
	<div class="input-group">
    <input type="number" class="form-control itemQty" value="<?= $row['qty'] ?>" style="width:75px;" readonly>
    <div class="input-group-append">
        <button class="btn btn-primary addToCartBtn addItemBtn" type="button" data-pid="<?= $row['id'] ?>">Add</button>
    </div>
</div>



	</td>
	<td>&#36;&nbsp;&nbsp;<?=
	number_format($row['total_price'],2); ?></td>
	<td>
		<a href="action.php?remove=<?php echo $row['id']; ?>" class="text-danger lead" 
		onclick="return confirm('Are you sure you want to remove this item?');">
		<i class="bi bi-trash"></i></a>
	 </td>	
</tr>


<?php $grand_total += $row['total_price']; ?>
<?php endwhile; ?>
<tr>
    <td colspan="3">
        <a href="index.php" class="btn btn-success"><i class="bi bi-cart"></i>&nbsp;&nbsp;Continue Shopping</a>
    </td>
    <td colspan="2"><b>Grand Total</b></td>
    <td><b><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;<?= number_format($grand_total, 2); ?></b></td>
    <td>
        <a href="checkout.php" class="btn btn-info <?= ($grand_total > 1) ?"":"disabled"; ?>">
            <i class="far fa-credit-card"></i>&nbsp;&nbsp;Checkout
        </a>
    </td>
</tr>

		  </tbody>
      </table>
     </div>
    </div>
   </div>
 </div>


<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>



<script type="text/javascript">
$(document).ready(function(){
    $(".addItemBtn").click(function(e){
        e.preventDefault();
        var $form = $(this).closest(".form-submit");
        var pid = $form.find(".pid").val();
        var pname = $form.find(".pname").val();
        var pprice = $form.find(".pprice").val();
        var pimage = $form.find(".pimage").val();
        var pcode = $form.find(".pcode").val();
        $.ajax({
            url: 'action.php',
            method: 'post',
            data: {pid: pid, pname: pname, pprice: pprice, pimage: pimage, pcode: pcode},
            success: function(response){
                $("#message").html(response);
                load_cart_item_number();
            }
        });
    });


    

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