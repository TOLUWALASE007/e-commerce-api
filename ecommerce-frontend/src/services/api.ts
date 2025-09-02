import axios, { AxiosInstance, AxiosResponse } from 'axios';
import {
  User,
  LoginRequest,
  RegisterRequest,
  AuthResponse,
  Product,
  ProductCreateRequest,
  ProductUpdateRequest,
  Category,
  CategoryCreateRequest,
  Cart,
  AddToCartRequest,
  Order,
  OrderDetails,
  Review,
  ReviewCreateRequest,
  ApiResponse,
  ApiError
} from '../types';

class ApiService {
  private api: AxiosInstance;
  private token: string | null = null;

  constructor() {
    this.api = axios.create({
      baseURL: 'http://localhost/ecommerce-api',
      headers: {
        'Content-Type': 'application/json',
      },
    });

    // Load token from localStorage
    this.token = localStorage.getItem('jwt_token');
    if (this.token) {
      this.setAuthToken(this.token);
    }

    // Request interceptor
    this.api.interceptors.request.use(
      (config) => {
        if (this.token) {
          config.headers.Authorization = `Bearer ${this.token}`;
        }
        return config;
      },
      (error) => {
        return Promise.reject(error);
      }
    );

    // Response interceptor
    this.api.interceptors.response.use(
      (response) => response,
      (error) => {
        if (error.response?.status === 401) {
          this.clearAuth();
          window.location.href = '/login';
        }
        return Promise.reject(error);
      }
    );
  }

  setAuthToken(token: string) {
    this.token = token;
    localStorage.setItem('jwt_token', token);
    this.api.defaults.headers.Authorization = `Bearer ${token}`;
  }

  clearAuth() {
    this.token = null;
    localStorage.removeItem('jwt_token');
    localStorage.removeItem('user');
    delete this.api.defaults.headers.Authorization;
  }

  // Auth endpoints
  async login(credentials: LoginRequest): Promise<AuthResponse> {
    const response: AxiosResponse<AuthResponse> = await this.api.post('/login', credentials);
    if (response.data.token) {
      this.setAuthToken(response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user));
    }
    return response.data;
  }

  async register(userData: RegisterRequest): Promise<ApiResponse<User>> {
    const response: AxiosResponse<ApiResponse<User>> = await this.api.post('/register', userData);
    return response.data;
  }

  // Product endpoints
  async getProducts(page: number = 1, search: string = ''): Promise<ApiResponse<Product>> {
    const params = new URLSearchParams();
    if (page > 1) params.append('page', page.toString());
    if (search) params.append('search', search);
    
    const response: AxiosResponse<ApiResponse<Product>> = await this.api.get(`/products?${params}`);
    return response.data;
  }

  async getProduct(id: number): Promise<Product> {
    const response: AxiosResponse<Product> = await this.api.get(`/products/${id}`);
    return response.data;
  }

  async createProduct(productData: ProductCreateRequest): Promise<ApiResponse<Product>> {
    const response: AxiosResponse<ApiResponse<Product>> = await this.api.post('/products', productData);
    return response.data;
  }

  async updateProduct(id: number, productData: ProductUpdateRequest): Promise<ApiResponse<Product>> {
    const response: AxiosResponse<ApiResponse<Product>> = await this.api.put(`/products/${id}`, productData);
    return response.data;
  }

  async deleteProduct(id: number): Promise<ApiResponse<void>> {
    const response: AxiosResponse<ApiResponse<void>> = await this.api.delete(`/products/${id}`);
    return response.data;
  }

  // Category endpoints
  async getCategories(): Promise<ApiResponse<Category>> {
    const response: AxiosResponse<ApiResponse<Category>> = await this.api.get('/categories');
    return response.data;
  }

  async getCategory(id: number): Promise<Category> {
    const response: AxiosResponse<Category> = await this.api.get(`/categories/${id}`);
    return response.data;
  }

  async createCategory(categoryData: CategoryCreateRequest): Promise<ApiResponse<Category>> {
    const response: AxiosResponse<ApiResponse<Category>> = await this.api.post('/categories', categoryData);
    return response.data;
  }

  async updateCategory(id: number, categoryData: CategoryCreateRequest): Promise<ApiResponse<Category>> {
    const response: AxiosResponse<ApiResponse<Category>> = await this.api.put(`/categories/${id}`, categoryData);
    return response.data;
  }

  async deleteCategory(id: number): Promise<ApiResponse<void>> {
    const response: AxiosResponse<ApiResponse<void>> = await this.api.delete(`/categories/${id}`);
    return response.data;
  }

  async getProductsByCategory(categoryId: number, page: number = 1): Promise<ApiResponse<Product>> {
    const params = new URLSearchParams();
    if (page > 1) params.append('page', page.toString());
    
    const response: AxiosResponse<ApiResponse<Product>> = await this.api.get(`/categories/${categoryId}/products?${params}`);
    return response.data;
  }

  // Cart endpoints
  async getCart(): Promise<Cart> {
    const response: AxiosResponse<Cart> = await this.api.get('/cart');
    return response.data;
  }

  async addToCart(cartData: AddToCartRequest): Promise<ApiResponse<void>> {
    const response: AxiosResponse<ApiResponse<void>> = await this.api.post('/cart', cartData);
    return response.data;
  }

  async updateCartItem(cartItemId: number, quantity: number): Promise<ApiResponse<void>> {
    const response: AxiosResponse<ApiResponse<void>> = await this.api.put(`/cart/${cartItemId}`, { quantity });
    return response.data;
  }

  async removeFromCart(cartItemId: number): Promise<ApiResponse<void>> {
    const response: AxiosResponse<ApiResponse<void>> = await this.api.delete(`/cart/${cartItemId}`);
    return response.data;
  }

  async clearCart(): Promise<ApiResponse<void>> {
    const response: AxiosResponse<ApiResponse<void>> = await this.api.delete('/cart');
    return response.data;
  }

  // Order endpoints
  async createOrder(): Promise<ApiResponse<Order>> {
    const response: AxiosResponse<ApiResponse<Order>> = await this.api.post('/checkout');
    return response.data;
  }

  async getOrders(): Promise<ApiResponse<Order>> {
    const response: AxiosResponse<ApiResponse<Order>> = await this.api.get('/orders');
    return response.data;
  }

  async getOrder(id: number): Promise<OrderDetails> {
    const response: AxiosResponse<OrderDetails> = await this.api.get(`/orders/${id}`);
    return response.data;
  }

  // Review endpoints
  async getProductReviews(productId: number): Promise<ApiResponse<Review>> {
    const response: AxiosResponse<ApiResponse<Review>> = await this.api.get(`/products/${productId}/reviews`);
    return response.data;
  }

  async createReview(productId: number, reviewData: ReviewCreateRequest): Promise<ApiResponse<Review>> {
    const response: AxiosResponse<ApiResponse<Review>> = await this.api.post(`/reviews/${productId}`, reviewData);
    return response.data;
  }

  async updateReview(productId: number, reviewData: ReviewCreateRequest): Promise<ApiResponse<Review>> {
    const response: AxiosResponse<ApiResponse<Review>> = await this.api.put(`/reviews/${productId}`, reviewData);
    return response.data;
  }

  async deleteReview(productId: number): Promise<ApiResponse<void>> {
    const response: AxiosResponse<ApiResponse<void>> = await this.api.delete(`/reviews/${productId}`);
    return response.data;
  }

  // Profile endpoints
  async getProfile(): Promise<User> {
    const response: AxiosResponse<User> = await this.api.get('/profile');
    return response.data;
  }

  async updateProfile(profileData: Partial<User>): Promise<ApiResponse<User>> {
    const response: AxiosResponse<ApiResponse<User>> = await this.api.put('/profile', profileData);
    return response.data;
  }

  async changePassword(currentPassword: string, newPassword: string): Promise<ApiResponse<void>> {
    const response: AxiosResponse<ApiResponse<void>> = await this.api.put('/profile-password', {
      current_password: currentPassword,
      new_password: newPassword
    });
    return response.data;
  }

  // Shipping addresses endpoints
  async getAddresses(): Promise<ApiResponse<any>> {
    const response: AxiosResponse<ApiResponse<any>> = await this.api.get('/addresses');
    return response.data;
  }

  async createAddress(addressData: any): Promise<ApiResponse<any>> {
    const response: AxiosResponse<ApiResponse<any>> = await this.api.post('/addresses', addressData);
    return response.data;
  }

  async updateAddress(id: number, addressData: any): Promise<ApiResponse<any>> {
    const response: AxiosResponse<ApiResponse<any>> = await this.api.put(`/addresses/${id}`, addressData);
    return response.data;
  }

  async deleteAddress(id: number): Promise<ApiResponse<void>> {
    const response: AxiosResponse<ApiResponse<void>> = await this.api.delete(`/addresses/${id}`);
    return response.data;
  }

  // Admin endpoints
  async getAdminStats(): Promise<any> {
    const response: AxiosResponse<any> = await this.api.get('/admin/stats');
    return response.data;
  }

  async getAdminUsers(): Promise<ApiResponse<User>> {
    const response: AxiosResponse<ApiResponse<User>> = await this.api.get('/admin/users');
    return response.data;
  }

  async getAdminOrders(): Promise<ApiResponse<Order>> {
    const response: AxiosResponse<ApiResponse<Order>> = await this.api.get('/admin/orders');
    return response.data;
  }
}

export const apiService = new ApiService();
export default apiService;
