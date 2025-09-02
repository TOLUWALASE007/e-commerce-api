// User types
export interface User {
  id: number;
  name: string;
  email: string;
  is_admin: boolean;
  created_at: string;
}

export interface LoginRequest {
  email: string;
  password: string;
}

export interface RegisterRequest {
  name: string;
  email: string;
  password: string;
}

export interface AuthResponse {
  message: string;
  token: string;
  expires: number;
  user: User;
}

// Product types
export interface Product {
  id: number;
  name: string;
  description: string;
  price: number;
  image_url: string;
  stock_quantity: number;
  average_rating: number;
  review_count: number;
  created_at: string;
}

export interface ProductCreateRequest {
  name: string;
  description: string;
  price: number;
  image_url?: string;
  stock_quantity: number;
}

export interface ProductUpdateRequest {
  name?: string;
  description?: string;
  price?: number;
  image_url?: string;
  stock_quantity?: number;
}

// Category types
export interface Category {
  id: number;
  name: string;
  description: string;
  parent_id: number | null;
  created_at: string;
}

export interface CategoryCreateRequest {
  name: string;
  description: string;
  parent_id?: number;
}

// Cart types
export interface CartItem {
  id: number;
  product_id: number;
  name: string;
  price: number;
  image_url: string;
  quantity: number;
  item_total: number;
  stock_quantity: number;
}

export interface Cart {
  items: CartItem[];
  summary: {
    total_items: number;
    total_price: number;
  };
}

export interface AddToCartRequest {
  product_id: number;
  quantity: number;
}

// Order types
export interface Order {
  id: number;
  user_id: number;
  user_name: string;
  total_amount: number;
  status: string;
  payment_status: string;
  shipping_address: string;
  created_at: string;
}

export interface OrderItem {
  id: number;
  order_id: number;
  product_id: number;
  product_name: string;
  product_price: number;
  quantity: number;
  subtotal: number;
}

export interface OrderDetails extends Order {
  items: OrderItem[];
}

// Review types
export interface Review {
  id: number;
  user_id: number;
  product_id: number;
  rating: number;
  review_text: string;
  created_at: string;
  user_name: string;
}

export interface ReviewCreateRequest {
  rating: number;
  review_text: string;
}

// API Response types
export interface ApiResponse<T> {
  message: string;
  data?: T;
  records?: T[];
  paging?: {
    current_page: number;
    per_page: number;
    total_rows: number;
    total_pages: number;
  };
}

export interface ApiError {
  message: string;
  status?: number;
}
