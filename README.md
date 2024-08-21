# E-commerce Site Project
Simple E-commerce Website with email receipt functionality after checking out

### How To Run Files Locally
The website is written in PHP, so in order to run locally, you must download XAMPP.
1. If you don't already have PHP installed, install it from [link](https://www.apachefriends.org). Click the download button for your operating system.
2. Open Command prompt or Terminal and change into the directory of the project. Alternatively you can use the terminal in Visual Studio Code.
3. Type the command `php -S localhost:8000` to run your site on port 8000.
   > Note: If you get an error that 'php' is not recognized, you may need to add it to your path locally.
4. Go to [link](http://localhost:8000/ecommerce-site/public_html/index.php) in your browser to see site live.

- You will also need a database with 'users' table (with columns user_id, firstname, email, and user_password), 'cart' table (with columns cart_id, user_id, cart_quantity, and prod_id), and 'product' table (with columns prod_id, prod_name, prod_price, and quantity).

Project was originally deployed on 000webhost.
- User is prompted to create an account and log in.
- Full user shopping experience, user can add products to cart.
- When user checks out, an email receipt is sent to the registered email.
