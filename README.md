# E-Commerce API

A comprehensive RESTful API for e-commerce applications built with PHP, MySQL, and JWT authentication. Includes a complete frontend interface for testing and demonstration.

## 🚀 Features

### Core Functionality
- **User Authentication & Authorization**
  - JWT token-based authentication
  - User registration and login
  - Admin role management
  - Secure password handling

- **Product Management**
  - Full CRUD operations for products
  - Product categories and organization
  - Stock quantity tracking
  - Image URL support

- **Shopping Cart System**
  - Add/remove items
  - Update quantities
  - Cart persistence
  - Stock validation

- **Order Management**
  - Order creation and tracking
  - Order status management
  - Order history
  - Payment integration

- **Enhanced Features**
  - Product categories with descriptions
  - User reviews and ratings (1-5 stars)
  - Average rating calculations
  - Category-based product organization

### Frontend Interface
- **Complete E-commerce Frontend**
  - Modern, responsive design with Tailwind CSS
  - User authentication pages
  - Product catalog and management
  - Shopping cart interface
  - Admin dashboard
  - User profile management

### Testing & Development
- **Comprehensive Test Interfaces**
  - Authentication testing
  - Product management testing
  - Cart functionality testing
  - Enhanced features testing
  - Profile management testing

## 🛠️ Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/TOLUWALASE007/e-commerce-api.git
   cd e-commerce-api
   ```

2. **Set up your environment**
   - Install XAMPP or similar local server
   - Place project in `htdocs` folder
   - Start Apache and MySQL services

3. **Database setup**
   - Open phpMyAdmin
   - Create new database
   - Import `database_schema.sql`

4. **Install dependencies**
   ```bash
   composer install
   ```

5. **Configure database**
   - Update `config/database.php` with your credentials
   - Test connection with `test_db.php`

6. **Test the API**
   - Visit `http://localhost/ecommerce-api/`
   - Use test interfaces for functionality testing

## 📡 API Endpoints

### Authentication
- `POST /register` - User registration
- `POST /login` - User login
- `POST /verify` - Verify JWT token

### Products
- `GET /products` - List all products
- `GET /products/{id}` - Get single product
- `POST /products` - Create product (Admin only)
- `PUT /products/{id}` - Update product (Admin only)
- `DELETE /products/{id}` - Delete product (Admin only)

### Shopping Cart
- `GET /cart` - View cart (Auth required)
- `POST /cart` - Add item to cart (Auth required)
- `PUT /cart/{id}` - Update cart item (Auth required)
- `DELETE /cart/{id}` - Remove item from cart (Auth required)
- `DELETE /cart` - Clear entire cart (Auth required)

### Categories
- `GET /categories` - List all categories
- `POST /categories` - Create category (Admin only)
- `PUT /categories/{id}` - Update category (Admin only)
- `DELETE /categories/{id}` - Delete category (Admin only)

### Reviews
- `GET /reviews/{product_id}` - Get product reviews
- `POST /reviews` - Create review (Auth required)

## 🎨 Frontend Features

### User Interface
- **Authentication Pages**
  - Login and registration forms
  - JWT token management
  - Session handling

- **Product Management**
  - Product listing with search
  - Add/edit/delete products
  - Category management
  - Stock tracking

- **Shopping Experience**
  - Product catalog
  - Shopping cart interface
  - Checkout process
  - Order history

- **Admin Panel**
  - Dashboard with statistics
  - User management
  - Sales reports
  - System monitoring

### Design & UX
- **Modern UI/UX**
  - Responsive design
  - Tailwind CSS styling
  - Interactive components
  - Mobile-friendly interface

## 🔧 Requirements

- **Server**: PHP 7.4+ with Apache/Nginx
- **Database**: MySQL 5.7+ or MariaDB
- **PHP Extensions**: PDO, JSON, OpenSSL
- **Web Server**: mod_rewrite enabled
- **Package Manager**: Composer

## 📁 Project Structure

```
ecommerce-api/
├── config/                 # Configuration files
├── controllers/            # API controllers
├── middleware/             # Authentication middleware
├── models/                 # Data models
├── ecommerce-frontend/     # Frontend interface
├── test_*.html            # Test interfaces
├── database_schema.sql     # Database structure
├── composer.json          # Dependencies
└── README.md             # This file
```

## 🧪 Testing

### Test Interfaces
- `test_auth_form.html` - Authentication testing
- `test_products.html` - Product management
- `test_cart.html` - Shopping cart testing
- `test_enhanced_features.html` - Categories & reviews
- `test_profile_management.html` - User profiles

### API Testing
- Use Postman or similar tools
- Include JWT token in Authorization header
- Test all CRUD operations
- Verify error handling

## 🚀 Deployment

### Local Development
- XAMPP/WAMP for local testing
- Development database
- Debug mode enabled

### Production
- Secure hosting environment
- SSL certificate required
- Environment variables for sensitive data
- Database backup procedures

## 📚 Documentation

- `SETUP_GUIDE.md` - Detailed setup instructions
- `AUTHENTICATION_GUIDE.md` - Auth system guide
- `database_schema.sql` - Database structure
- Test interfaces for hands-on learning

## 🤝 Contributing

1. Fork the repository
2. Create feature branch
3. Make your changes
4. Test thoroughly
5. Submit pull request

## 📄 License

This project is open source and available under the MIT License.

## 🌟 Features in Development

- Payment gateway integration
- Email notifications
- Advanced search and filtering
- Mobile app API endpoints
- Analytics and reporting

---

**Built with ❤️ using PHP, MySQL, and modern web technologies**
