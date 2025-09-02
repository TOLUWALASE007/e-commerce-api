# E-Commerce Platform

A full-stack e-commerce application with a robust PHP REST API backend and a modern React frontend. Built with PHP, MySQL, JWT authentication, React.js, TypeScript, and Tailwind CSS.

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

### Frontend Interface (React + TypeScript + Tailwind CSS)
- **Modern React Frontend**
  - Built with React.js 18 and TypeScript
  - Styled with Tailwind CSS for modern, responsive design
  - React Router for seamless navigation
  - Context API for state management
  - Axios for API communication
  - Protected routes and authentication
  - Component-based architecture

### Testing & Development
- **Comprehensive Test Interfaces**
  - Authentication testing
  - Product management testing
  - Cart functionality testing
  - Enhanced features testing
  - Profile management testing

## 🛠️ Quick Start

### Backend Setup (PHP API)

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

4. **Install PHP dependencies**
   ```bash
   composer install
   ```

5. **Configure database**
   - Update `config/database.php` with your credentials
   - Test connection with `test_db.php`

6. **Test the API**
   - Visit `http://localhost/ecommerce-api/`
   - Should see: `{"message":"Welcome to E-Commerce API","version":"1.0","database":"Connected successfully"}`

### Frontend Setup (React App)

1. **Navigate to frontend directory**
   ```bash
   cd ecommerce-frontend
   ```

2. **Install Node.js dependencies**
   ```bash
   npm install
   ```

3. **Start the React development server**
   ```bash
   npm start
   ```

4. **Access the application**
   - Frontend: `http://localhost:3000`
   - Backend API: `http://localhost/ecommerce-api`

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

## 🎨 Frontend Features (React App)

### Pages & Components
- **Home Page** - Hero section and featured products
- **Products Page** - Product catalog with search and filtering
- **Product Detail** - Individual product view with reviews
- **Cart Page** - Shopping cart management
- **Login/Register** - User authentication
- **Profile Page** - User profile and password management
- **Admin Dashboard** - Admin-only management interface

### Key Features
- **Authentication System**
  - JWT token-based authentication
  - Protected routes for authenticated users
  - Admin role-based access control
  - Automatic token refresh

- **Product Management**
  - Product listing with pagination
  - Search and category filtering
  - Product detail views with reviews
  - Stock quantity display

- **Shopping Cart**
  - Add/remove items
  - Quantity management
  - Persistent cart state
  - Real-time updates

- **User Experience**
  - Responsive design (mobile-first)
  - Loading states and error handling
  - Form validation
  - Smooth navigation

### Technology Stack
- **React.js 18** - Modern React with hooks
- **TypeScript** - Type-safe development
- **Tailwind CSS** - Utility-first styling
- **React Router** - Client-side routing
- **Axios** - HTTP client for API calls
- **Context API** - Global state management

## 🔧 Requirements

### Backend Requirements
- **Server**: PHP 7.4+ with Apache/Nginx
- **Database**: MySQL 5.7+ or MariaDB
- **PHP Extensions**: PDO, JSON, OpenSSL
- **Web Server**: mod_rewrite enabled
- **Package Manager**: Composer

### Frontend Requirements
- **Node.js**: 16.0+ (recommended: 18.0+)
- **npm**: 8.0+ or yarn
- **Modern Browser**: Chrome, Firefox, Safari, Edge

## 📁 Project Structure

```
ecommerce-api/
├── config/                 # PHP configuration files
├── controllers/            # API controllers
├── middleware/             # Authentication middleware
├── models/                 # Data models
├── ecommerce-frontend/     # React frontend application
│   ├── src/
│   │   ├── components/     # React components
│   │   ├── pages/          # Page components
│   │   ├── contexts/       # React contexts
│   │   ├── services/       # API service layer
│   │   ├── types/          # TypeScript type definitions
│   │   └── App.tsx         # Main app component
│   ├── public/             # Static assets
│   ├── package.json        # Node.js dependencies
│   ├── tailwind.config.js  # Tailwind CSS config
│   └── tsconfig.json       # TypeScript config
├── test_*.html            # Legacy test interfaces
├── database_schema.sql     # Database structure
├── composer.json          # PHP dependencies
└── README.md             # This file
```

## 🧪 Testing

### Frontend Testing (React App)
- **Live Development Server**: `http://localhost:3000`
- **Hot Reload**: Automatic refresh on code changes
- **TypeScript Compilation**: Real-time type checking
- **ESLint Integration**: Code quality and style checking

### Backend Testing
- **API Endpoint**: `http://localhost/ecommerce-api/`
- **Legacy Test Interfaces**: `test_*.html` files for manual testing
- **Postman Collection**: Import API endpoints for testing
- **JWT Token Testing**: Include token in Authorization header

### Development Workflow
1. Start PHP backend: Ensure XAMPP is running
2. Start React frontend: `npm start` in `ecommerce-frontend/`
3. Test full-stack integration
4. Use browser dev tools for debugging

## 🚀 Deployment

### Local Development
- **Backend**: XAMPP/WAMP for PHP and MySQL
- **Frontend**: React development server (`npm start`)
- **Database**: Local MySQL/MariaDB instance
- **Debug Mode**: Enabled for both frontend and backend

### Production Deployment
- **Backend**: 
  - Secure hosting environment (Apache/Nginx)
  - SSL certificate required
  - Environment variables for sensitive data
  - Database backup procedures
- **Frontend**:
  - Build React app: `npm run build`
  - Deploy to CDN or static hosting
  - Configure API base URL for production
  - Enable production optimizations

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

- Payment gateway integration (Stripe)
- Email notifications
- Advanced search and filtering
- Mobile app API endpoints
- Analytics and reporting
- Product image upload
- Order tracking system
- Wishlist functionality

## 🎯 Getting Started

1. **Clone and setup backend** (PHP API)
2. **Install and start frontend** (React app)
3. **Access the application**:
   - Frontend: `http://localhost:3000`
   - Backend API: `http://localhost/ecommerce-api`

## 📞 Support

For issues or questions:
- Check the setup guides in the documentation
- Review the API endpoints documentation
- Test with the provided test interfaces

---

**Built with ❤️ using PHP, MySQL, React.js, TypeScript, and Tailwind CSS**
