# E-Commerce API

A RESTful API for e-commerce applications built with PHP, MySQL, and JWT authentication.

## Features

- User authentication with JWT tokens
- Product management
- Shopping cart functionality
- Order processing
- Stripe payment integration
- RESTful API design

## Quick Start

1. **Clone or download** this project to your web server directory
2. **Follow the setup guide**: See `SETUP_GUIDE.md` for detailed instructions
3. **Create database**: Use `database_schema.sql` in phpMyAdmin
4. **Install dependencies**: Run `composer install`
5. **Test setup**: Visit `http://localhost/ecommerce-api/`

## API Endpoints

Coming soon! This will include:
- `POST /auth/register` - User registration
- `POST /auth/login` - User login
- `GET /products` - List products
- `POST /cart/add` - Add to cart
- `POST /orders/create` - Create order
- `POST /payment/process` - Process payment

## Requirements

- PHP 7.4+
- MySQL 5.7+
- Apache with mod_rewrite enabled
- Composer

## License

This project is open source and available under the MIT License.
