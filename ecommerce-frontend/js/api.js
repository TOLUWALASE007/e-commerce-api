// API functions for E-Commerce Frontend

const API_BASE = 'http://localhost/ecommerce-api';

// Generic API request function
async function apiRequest(endpoint, options = {}) {
    const token = localStorage.getItem('authToken');
    
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            ...(token && { 'Authorization': `Bearer ${token}` })
        }
    };
    
    const finalOptions = { ...defaultOptions, ...options };
    
    try {
        const response = await fetch(`${API_BASE}${endpoint}`, finalOptions);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('API request failed:', error);
        throw error;
    }
}

// Authentication API functions
const authAPI = {
    async register(userData) {
        return apiRequest('/register', {
            method: 'POST',
            body: JSON.stringify(userData)
        });
    },
    
    async login(credentials) {
        return apiRequest('/login', {
            method: 'POST',
            body: JSON.stringify(credentials)
        });
    },
    
    async verifyToken(token) {
        return apiRequest('/verify', {
            method: 'POST',
            body: JSON.stringify({ token })
        });
    }
};

// Products API functions
const productsAPI = {
    async getAll() {
        return apiRequest('/products');
    },
    
    async getById(id) {
        return apiRequest(`/products/${id}`);
    },
    
    async create(productData) {
        return apiRequest('/products', {
            method: 'POST',
            body: JSON.stringify(productData)
        });
    },
    
    async update(id, productData) {
        return apiRequest(`/products/${id}`, {
            method: 'PUT',
            body: JSON.stringify(productData)
        });
    },
    
    async delete(id) {
        return apiRequest(`/products/${id}`, {
            method: 'DELETE'
        });
    }
};

// Cart API functions
const cartAPI = {
    async getCart() {
        return apiRequest('/cart');
    },
    
    async addToCart(cartData) {
        return apiRequest('/cart', {
            method: 'POST',
            body: JSON.stringify(cartData)
        });
    },
    
    async updateCartItem(id, cartData) {
        return apiRequest(`/cart/${id}`, {
            method: 'PUT',
            body: JSON.stringify(cartData)
        });
    },
    
    async removeFromCart(id) {
        return apiRequest(`/cart/${id}`, {
            method: 'DELETE'
        });
    },
    
    async clearCart() {
        return apiRequest('/cart', {
            method: 'DELETE'
        });
    }
};

// Categories API functions
const categoriesAPI = {
    async getAll() {
        return apiRequest('/categories');
    },
    
    async create(categoryData) {
        return apiRequest('/categories', {
            method: 'POST',
            body: JSON.stringify(categoryData)
        });
    },
    
    async update(id, categoryData) {
        return apiRequest(`/categories/${id}`, {
            method: 'PUT',
            body: JSON.stringify(categoryData)
        });
    },
    
    async delete(id) {
        return apiRequest(`/categories/${id}`, {
            method: 'DELETE'
        });
    }
};

// Reviews API functions
const reviewsAPI = {
    async getProductReviews(productId) {
        return apiRequest(`/reviews/${productId}`);
    },
    
    async createReview(reviewData) {
        return apiRequest('/reviews', {
            method: 'POST',
            body: JSON.stringify(reviewData)
        });
    }
};

// Export all API functions
window.authAPI = authAPI;
window.productsAPI = productsAPI;
window.cartAPI = cartAPI;
window.categoriesAPI = categoriesAPI;
window.reviewsAPI = reviewsAPI;
