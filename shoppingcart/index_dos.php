<!--apertura de php-->
<?php
#declaramos las variables de sesion y de conexion a la BD
	session_start();
	$server = "localhost";
	$username = "root";
	$password = "";
	$db = "shoppingcart";

	$connect = mysqli_connect($server, $username, $password, $db);

	if (isset($_POST["add_to_cart"])) {
		if (isset($_SESSION["shopping_cart"])) {
			$item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
			if(!in_array($_GET["id"], $item_array_id)){
				$count = count($_SESSION["shopping_cart"]);
				$item_array = array(
					'item_id' => $_GET["id"],
					'item_name' => $_POST['hidden_name'],
					'item_price' => $_POST["hidden_price"],
					'item_quantity' => $_POST["quantity"]
				);
				$_SESSION["shopping_cart"][$count] = $item_array;
			}
			else{
				echo '<script>alert("Item Already Added")</script>';
				echo '<script>window.location="index_dos.php"</script>';
			}
		}
		else{
			$item_array = array(
				'item_id' => $_GET["id"],
				'item_name' => $_POST['hidden_name'],
				'item_price' => $_POST["hidden_price"],
				'item_quantity' => $_POST["quantity"]
			);

			$_SESSION["shopping_cart"][0] = $item_array;
		}
	}
#configuramos el boton de borrado
	if (isset($_GET["action"])) {
		if($_GET["action"] == "delete"){
			foreach ($_SESSION["shopping_cart"] as $keys => $values) {
				if($values["item_id"] == $_GET["id"]){
					unset($_SESSION["shopping_cart"][$keys]);
					echo '<script>alert("Item Removed")</script>';
					echo '<script>window.location="index_dos.php"</script>';
				}
			}
		}
	}

?>
<!--cierre de php-->

<!DOCTYPE html>
<html>
<head>
	<title>Shopping Cart</title>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./css/css/bootstrap.min.css">
</head>
<body>

	<br>
	<div class="container" style="width: 700px;">
		<h3 align="center">Shopping Cart</h3><br>

		<!--apertura de php-->
		<?php
			$query = "SELECT * FROM tbl_product ORDER BY id ASC";
			$result = mysqli_query($connect, $query);
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_array($result)) {
		?>
		<!--cierre de php-->

		<div class="col-md-4">
			<form method="POST" action="index_dos.php?action=add&id=<?php echo $row["id"];?>">
				<div style="border:1px solid #333; background-color:#f1f1f1; border-radius:5px; padding: 16px;">
					<img src="./imgs/<?php echo $row["image"];?>" class="img-responsive"/>
					<h4 class="text-info"><?php echo $row["name"];?></h4>
					<h4 class="text-danger">$ <?php echo $row["price"];?></h4>
					<input type="text" name="quantity" class="form-control" value="1">
					<input type="hidden" name="hidden_name" value="<?php echo $row["name"];?>"/>
					<input type="hidden" name="hidden_price" value="<?php echo $row["price"];?>"/>
					<input type="submit" name="add_to_cart" value="Add to Cart" style="margin-top: 5px;" class="btn btn-success">
				</div>
			</form>			
		</div>
	
		<!--apertura de php-->
		<?php
				}//cierre del while
				# code...
			}//cierre del if
		?>
		<!--cierre de php-->

		<div style="clear:both"></div>
		<br>
		<h3>Order Details</h3>
		<div class="table-responsive">
			<table class="table table-bordered">
				<tr>
					<th width="40%">Item Name</th>
					<th width="10%">Quantity</th>
					<th width="20%">Price</th>
					<th width="15%">Total</th>
					<th width="5%">Action</th>
				</tr>

		<!--apertura de php-->
		<?php
			if (!empty($_SESSION["shopping_cart"])) {
				$total = 0;
				foreach ($_SESSION["shopping_cart"] as $keys => $values) {
		?>
		<!--cierre de php-->
		<<tr>
			<td><?php echo $values["item_name"];?></td>
			<td><?php echo $values["item_quantity"];?></td>
			<td>$ <?php echo $values["item_price"];?></td>
			<td>$ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2 );?></td>
			<td><a href="index_dos.php?action=delete&id=<?php echo $values["item_id"];?>"><span class="text-danger">Remove</span></a></td>
		</tr>
		<!--apertura de php-->
		<?php
				$total = $total + ($values["item_quantity"] * $values["item_price"]);
				}
		?>
		<!--cierre de php-->
		<tr>
			<td colspan="3" align="right"></td>
			<td align="right">$ <?php echo number_format($total, 2);?></td>
			<td></td>
		</tr>
		<!--apertura de php-->
		<?php
			}

		?>
		<!--cierre de php-->

			</table>
		</div>
	</div>

	

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="./js/js/bootstrap.min.js"></script>

</body>
</html>