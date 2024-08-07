<?php
session_start();
include('connect.php');

$user_id = $_SESSION['user_id'];

//$user_id = $_SESSION['user_id'];
//echo "$user_id";
if(!isset($user_id)){
    header("Location: login.php");
    //echo "<script> location.href='login.php'; </script>";
    //exit;
}

if(isset($_POST['add-to-cart'])){
        
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];
$product_image= $_POST['product_image'];
$product_quantity = $_POST['quantity'];

$select_cart = mysqli_query($conn, "SELECT  *  FROM cart 
WHERE prod_name = '$product_name' AND user_id = '$user_id'") or die('Query failed');

if(mysqli_num_rows($select_cart) > 0){
    $message[] = "Product already in cart";
}else{
    mysqli_query($conn, "INSERT INTO cart (user_id, prod_name, prod_price, prod_img, quantity) 
    VALUES ('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')")
    or die('Query failed');
    $message[] = "Product added to cart";
}
}

if(isset($_GET['logout'])){
    unset($_SESSION['user_id']);
    session_destroy();
    //header("Location: login.php");
    echo "<script> location.href='login.php'; </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>The Shop</title>
        <!-- Bootstrap core CSS -->
        <link href="style.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <!-- <link href="signin.css" rel="stylesheet"> -->
    </head>
    <body>
    <?php
        if(isset($message)){
            foreach($message as $message){
                echo '<div class="message" onclick="this.remove()">'.$message.'</div>';
            }
        }
    ?>
    <!--<form action="shoppingCart.php" method="get">-->
        <div class="big-container">
            
            <div class="user-profile">
                <?php
                $user_data = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$user_id'") or die('Query failed');
                if(mysqli_num_rows($user_data) > 0){
                    $fetch_user_data = mysqli_fetch_assoc($user_data);
                }
                ?>
    
                <p> Welcome <span><?php echo $fetch_user_data['firstname']; ?></span> </p>
                <p> Email: <span><?php echo $fetch_user_data['email']; ?><span> </p>
                <div class="flex">
                    <a href="login.php" class="btn">Login</a>
                    <a href="index.php" class="option-btn">Register</a>
                    <a href="shoppingPage.php?logout=<?php echo $user_id; ?>" 
                    onClick="return confirm('Are you sure you want to logout?');" class="delete-btn">Logout</a>
                </div>
            </div>
            
            <div class="products">
                <h1 class="heading"> Latest Products </h1>
                <div class="container">
                    <?php 
                    $SELECT = mysqli_query($conn, "SELECT * FROM products");
                    if(mysqli_num_rows($SELECT) > 0){
                        while($fetch_product = mysqli_fetch_assoc($SELECT)){
                    ?>
                    <form method="post" class="box" action="">
                        <img src="images/<?php echo $fetch_product['prod_img']; ?>">
                        <div class="name">
                            <?php echo $fetch_product['prod_name']; ?>
                        </div>
                        <div class="price"> 
                            $<?php echo $fetch_product['prod_price']; ?>
                        </div>
                        <input type="number" min="1" name="quantity" value="1">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_product['prod_img']; ?>">  
                        <input type="hidden" name="product_name" value="<?php echo $fetch_product['prod_name']; ?>">  
                        <input type="hidden" name="product_price" value="<?php echo $fetch_product['prod_price']; ?>">  
                        <input type="submit" value="Add To Cart" name="add-to-cart" class="btn">
                    </form>
                    <?php
                        }
                    }
                    ?>
                    
                    
                </div>
                <a href="shoppingCart.php" class="btn" style="align-items: center">View Cart</a>
            </div>
        </div>
    <!--</form>-->
</body>
</html>