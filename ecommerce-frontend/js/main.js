// Main JavaScript for E-Commerce Frontend

// Global variables
let currentUser = null;
let cart = [];

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    checkAuthStatus();
    loadCart();
});

// Check if user is authenticated
function checkAuthStatus() {
    const token = localStorage.getItem('authToken');
    if (token) {
        // Verify token and get user info
        verifyToken(token);
    } else {
        updateNavigation(false);
    }
}

// Verify JWT token
async function verifyToken(token) {
    try {
        const response = await fetch('http://localhost/ecommerce-api/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ token: token })
        });
        
        if (response.ok) {
            const data = await response.json();
            currentUser = data.user;
            updateNavigation(true);
        } else {
            localStorage.removeItem('authToken');
            updateNavigation(false);
        }
    } catch (error) {
        console.error('Token verification failed:', error);
        localStorage.removeItem('authToken');
        updateNavigation(false);
    }
}

// Update navigation based on auth status
function updateNavigation(isAuthenticated) {
    const authLinks = document.querySelectorAll('.auth-required');
    const guestLinks = document.querySelectorAll('.guest-only');
    
    if (isAuthenticated) {
        authLinks.forEach(link => link.style.display = 'inline');
        guestLinks.forEach(link => link.style.display = 'none');
        document.getElementById('userInfo').textContent = `Welcome, ${currentUser.name}`;
    } else {
        authLinks.forEach(link => link.style.display = 'none');
        guestLinks.forEach(link => link.style.display = 'inline');
        document.getElementById('userInfo').textContent = '';
    }
}

// Load cart from localStorage
function loadCart() {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
        updateCartDisplay();
    }
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Update cart display
function updateCartDisplay() {
    const cartCount = document.getElementById('cartCount');
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    
    if (cartCount) {
        cartCount.textContent = totalItems;
        cartCount.style.display = totalItems > 0 ? 'inline' : 'none';
    }
}

// Add to cart
function addToCart(productId, productName, price) {
    const existingItem = cart.find(item => item.productId === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            productId: productId,
            name: productName,
            price: price,
            quantity: 1
        });
    }
    
    saveCart();
    updateCartDisplay();
    showNotification('Product added to cart!', 'success');
}

// Remove from cart
function removeFromCart(productId) {
    cart = cart.filter(item => item.productId !== productId);
    saveCart();
    updateCartDisplay();
    showNotification('Product removed from cart!', 'success');
}

// Update cart item quantity
function updateCartQuantity(productId, quantity) {
    const item = cart.find(item => item.productId === productId);
    if (item) {
        if (quantity <= 0) {
            removeFromCart(productId);
        } else {
            item.quantity = quantity;
            saveCart();
            updateCartDisplay();
        }
    }
}

// Get cart total
function getCartTotal() {
    return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Logout function
function logout() {
    localStorage.removeItem('authToken');
    currentUser = null;
    cart = [];
    saveCart();
    updateNavigation(false);
    showNotification('Logged out successfully!', 'success');
    window.location.href = 'index.html';
}

// Format price
function formatPrice(price) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(price);
}

// Loading spinner
function showLoading(element) {
    element.innerHTML = '<div class="spinner mx-auto"></div>';
}

function hideLoading(element) {
    element.innerHTML = '';
}

// Error handling
function handleError(error, element) {
    console.error('Error:', error);
    element.innerHTML = `<p class="text-red-500">Error: ${error.message}</p>`;
}
