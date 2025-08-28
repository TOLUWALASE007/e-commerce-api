# E-Commerce API Setup Guide

## Prerequisites
- XAMPP or WAMP server installed and running
- PHP 7.4 or higher
- MySQL/MariaDB
- Composer (for dependency management)

## Step 1: Database Setup with phpMyAdmin

1. **Start your XAMPP/WAMP server**
2. **Open phpMyAdmin**: Go to `http://localhost/phpmyadmin`
3. **Create database**:
   - Click "New" in left sidebar
   - Enter database name: `ecommerce_db`
   - Select collation: `utf8_general_ci`
   - Click "Create"

4. **Create tables**:
   - Click on "SQL" tab
   - Copy and paste the contents of `database_schema.sql`
   - Click "Go" to execute

5. **Verify tables created**:
   - You should see 5 tables: `users`, `products`, `cart_items`, `orders`, `order_items`

## Step 2: Install Dependencies

1. **Install Composer** (if not already installed):
   - Download from: https://getcomposer.org/download/
   - Follow installation instructions

2. **Install PHP dependencies**:
   ```bash
   cd C:\xampp\htdocs\ecommerce-api
   composer install
   ```

## Step 3: Test the Setup

1. **Test database connection**:
   - Visit: `http://localhost/ecommerce-api/test_db.php`
   - You should see "Database connection successful!" and list of tables

2. **Test main API**:
   - Visit: `http://localhost/ecommerce-api/`
   - You should see JSON response with "Connected successfully"

## Step 4: Troubleshooting

### Common Issues:

1. **"Connection failed"**:
   - Check if MySQL is running in XAMPP/WAMP
   - Verify database name is exactly `ecommerce_db`
   - Check MySQL username/password in `config/database.php`

2. **Tables not showing**:
   - Go back to phpMyAdmin and check for SQL errors
   - Verify the database was created successfully

3. **Composer errors**:
   - Ensure Composer is installed globally
   - Try `composer update` instead of `composer install`

4. **404 errors**:
   - Ensure Apache mod_rewrite is enabled
   - Check if `.htaccess` file is in project root

## File Structure

```
/ecommerce-api
  /config
    - database.php (database connection)
    - stripe.php (Stripe configuration)
  /controllers
    - AuthController.php (user authentication)
    - ProductController.php (product management)
    - CartController.php (shopping cart)
    - PaymentController.php (Stripe payments)
  /models
    - User.php (user data model)
    - Product.php (product data model)
    - Cart.php (cart data model)
    - Order.php (order data model)
  /middleware
    - AuthMiddleware.php (JWT authentication)
  - index.php (main API entry point)
  - .htaccess (URL rewriting)
  - composer.json (dependencies)
  - database_schema.sql (database structure)
  - test_db.php (database test)
```

## Next Steps

After successful setup, you can:
1. Implement user authentication (JWT tokens)
2. Create product management endpoints
3. Implement shopping cart functionality
4. Add payment processing with Stripe
5. Build order management system

## Testing Your API

Use tools like:
- **Postman** for API testing
- **Insomnia** for REST client testing
- **Browser Developer Tools** for simple GET requests

## Security Notes

- Change default MySQL password in production
- Use environment variables for sensitive data
- Enable HTTPS in production
- Implement proper input validation and sanitization
