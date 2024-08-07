<?php
    session_start();
    include('connect.php');
    //include('shoppingPage.php');
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    //require 'vendor/autoload.php';
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    
    $user_id = $_SESSION['user_id'];
    if(!isset($user_id)){
        //header("Location: login.php");
        echo "<script> location.href='login.php'; </script>";
        exit;
    }

    if(isset($_GET['logout'])){
        unset($_SESSION['user_id']);
        session_destroy();
        //header("Location: login.php");
        echo "<script> location.href='login.php'; </script>";
        exit;
    }

    if(isset($_POST['update_cart'])){
        $update_id = $_POST['cart_id'];
        $update_quantity = $_POST['cart_quantity'];
        mysqli_query($conn, "UPDATE cart SET quantity = '$update_quantity' WHERE cart_id = '$update_id'") 
        or die('Query failed');
        $message[] = "Cart quantity updated";
    }

    if(isset($_GET['remove'])){
        $remove_id = $_GET['remove'];
        mysqli_query($conn, "DELETE FROM cart WHERE cart_id = '$remove_id'") or die('Query failed');
        $message[] = "Item removed from cart";
        //header("Location: shoppingCart.php");
        echo "<script> location.href='shoppingCart.php'; </script>";
        exit;
    }

    if(isset($_GET['delete_all'])){
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'") or die('Query failed');
        $message[] = "All items removed from cart";
        //header("Location: shoppingCart.php");
        echo "<script> location.href='shoppingCart.php'; </script>";
        exit;
    }

    if(isset($_GET['send_receipt'])){
        $user_data = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$user_id'") or die('Query failed');
        if(mysqli_num_rows($user_data) > 0){
            $fetch_user_data = mysqli_fetch_assoc($user_data);
        }
        $user_name = $fetch_user_data['firstname'];
        $user_email = $fetch_user_data['email'];
        $receipt_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('Query failed');
        $receipt_grand_total = 0;
        
        $receipt_message = '<table style="border-collapse: collapse; width: 100%;"
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 10px;"> Name </th>
                        <th style="border: 1px solid #ddd; padding: 10px;"> Price </th>
                        <th style="border: 1px solid #ddd; padding: 10px;"> Quantity </th>
                        <th style="border: 1px solid #ddd; padding: 10px;"> Subtotal </th>';
        if(mysqli_num_rows($receipt_query) > 0){
            while($fetch_receipt = mysqli_fetch_assoc($receipt_query)){
                $receipt_name = $fetch_receipt['prod_name'];
                $receipt_price = $fetch_receipt['prod_price'];
                $receipt_quantity = $fetch_receipt['quantity'];
                $receipt_total = $fetch_receipt['prod_price'] * $fetch_receipt['quantity'];
                $receipt_grand_total += $receipt_total;
                $receipt_message .= '<tr>
                                        <td style="border: 1px solid #ddd; padding: 8px;">' . $receipt_name . '</td>
                                        <td style="border: 1px solid #ddd; padding: 8px;">' . $receipt_price . '</td>
                                        <td style="border: 1px solid #ddd; padding: 8px;">' . $receipt_quantity . '</td>
                                        <td style="border: 1px solid #ddd; padding: 8px;">' . $receipt_total . '</td>
                                    </tr>';
            }
        }
        $receipt_message .= '<tr>
                                <td colspan="3">Grand Total</td>
                                <td>' . $receipt_grand_total . ' </td>
                            </tr>';
        $subject = 'Your Receipt';
        $email_message = '<html>
                        <head>
                            <title>Your Receipt</title>
                        </head>
                        <body>
                            <h3>Hi ' . $user_name . ', here is your receipt</h3>'
                                    . $receipt_message .
                        '</body>
                    </html>';
        //$headers = "From: Best Tech Store";
        //ini_set('SMTP', "in-v3.mailjet.com");
        //ini_set('smtp_port', 25);
        //ini_set('sendmail_from', "cuzowuru@email.essex.edu");
        //mail($user_email, $subject, $email_message, $headers);
        //exit();

        // Confirming receipt sent
        //$message[] = "Receipt sent to $user_email";
        //header("Location: shoppingCart.php");
        try{
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'uzochino5@gmail.com';
            $mail->Password = 'ekfnwrjsoiyoaqje';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress($user_email, $user_name);
            $mail->isHTML(true);
            $mail->Subject = 'Your Receipt';
            $mail->Body = $email_message;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            if($mail->send()){
                $message[] = "Receipt sent to $user_email";
                $delete_query = "DELETE FROM cart WHERE user_id = $user_id";
                mysqli_query($conn, $delete_query);
                #redirect to shoppingCart.php
                header("Location: shoppingCart.php");
            }
            
            //echo "<script> location.href='shoppingCart.php'; </script>";
            //exit;
        } catch(Exception $e){
            $message[] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    ?>
    
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Your Shopping Cart</title>
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
        </div>
        <div class="cart-container">
            <h1 class="heading"> Your Shopping Cart</h1>

            <table>
                <thead>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Remove Item</th>
                </thead>
                <tbody>
                    <?php
                        $grand_total = 0;
                        $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") 
                        or die('Query failed');    
                        if(mysqli_num_rows($cart_query) > 0){
                            while($fetch_cart = mysqli_fetch_assoc($cart_query)){
                    ?>
                    <tr>
                        <td><img src="images/<?php echo $fetch_cart['prod_img']; ?>" height="100" alt=""></td>
                        <td><?php echo $fetch_cart['prod_name']; ?></td>
                        <td><?php echo $fetch_cart['prod_price']; ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['cart_id']; ?>">
                                <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                                <input type="submit" name="update_cart" value="Update" class="option-btn">
                            </form>
                        </td>
                        <td>$<?php echo $subtotal = $fetch_cart['prod_price'] * $fetch_cart['quantity']; ?></td>
                        <td><a href="shoppingCart.php?remove=<?php echo $fetch_cart['cart_id']; ?>" class="delete-btn" 
                        onClick="return confirm('Remove item from cart?');">Remove</a></td>
                    </tr>
                    <?php
                        $grand_total += $subtotal;
                            }
                        } else{
                            echo '<tr> <td colspan="6" align="center"> No Items in Cart </td> </tr>';
                        }
                    ?>
                    <tr class="table-bottom">
                        <td colspan="4">Grand Total</td>
                        <td>$<?php echo number_format($grand_total, 2); ?></td>
                        <td><a href="shoppingCart.php?delete_all" onClick="return confirm('Remove all items from cart?');" 
                        class="delete-btn <?php echo ($grand_total > 1)?'': 'disabled'; ?>">Remove All</a></td>
                    </tr>
                </tbody>
            </table>
            <div class="cart-btn">
                <a href="shoppingPage.php" class="btn">Continue Shopping</a>
            </div>
            <div class="cart-btn">
                <a href="shoppingCart.php?send_receipt" class="btn <?php echo ($grand_total > 1)?'': 'disabled'; ?>">Checkout</a>
            </div>
        </div>