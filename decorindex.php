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
        <a class="nav-link" href="cart.php"><i class="bi bi-cart"></i> <span id="cart-item" class="badge badge-danger"></span></a>
      </li>
    </ul>
  </div>
</nav>
<div class="container">
    <div id="message"></div>
    <div class="row mt-4 pb-3">
        <?php
        include 'config.php';
        $stmt = $conn->prepare("SELECT * FROM home_decor");
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()):
        ?>
        <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="card p-2 border-secondary mb-2">
                <img src="<?= $row['product_image'] ?>" class="card-img-top" width="100%" height="250">
                <div class="card-body p-1">
                    <h4 class="card-title text-center text-info"><?= $row['product_name'] ?></h4>
                    <h5 class="card-text text-center text-danger"><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;<?= number_format($row['product_price'], 2) ?>/-</h5>
                    
			<form action="" class="form-submit">
                 </form>
                </div>
				<form action="" class="form-submit">
				<input type="hidden" class="pid" value="<?= $row['id'] ?>">
				<input type="hidden" class="pname" value="<?= $row['product_name'] ?>">
				<input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
				<input type="hidden" class="pimage" value="<?= $row['product_image'] ?>">
				<input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">
				<button class="btn btn-info btn-block addItemBtn"><i class="bi bi-cart"></i>&nbsp;&nbsp;Add to cart</button>
</form>
				
				
				
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>


<div class=""></div>
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
		var newQty = parseInt($form.find(".quantity-input").val()) || 1;
		

        $.ajax({
            url: 'action.php',
            method: 'post',
            data: {pid: pid, pname: pname, pprice: pprice, pimage: pimage, pcode: pcode,newQty},
            success: function(response){
                $("#message").html(response);
                load_cart_item_number();
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