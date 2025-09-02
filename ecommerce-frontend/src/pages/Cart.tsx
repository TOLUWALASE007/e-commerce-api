import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { Cart as CartType } from '../types';
import apiService from '../services/api';

const Cart: React.FC = () => {
  const [cart, setCart] = useState<CartType | null>(null);
  const [loading, setLoading] = useState(true);
  const [updating, setUpdating] = useState<number | null>(null);

  useEffect(() => {
    loadCart();
  }, []);

  const loadCart = async () => {
    try {
      const cartData = await apiService.getCart();
      setCart(cartData);
    } catch (error) {
      console.error('Error loading cart:', error);
    } finally {
      setLoading(false);
    }
  };

  const updateQuantity = async (itemId: number, newQuantity: number) => {
    if (newQuantity < 1) return;
    
    try {
      setUpdating(itemId);
      await apiService.updateCartItem(itemId, newQuantity);
      await loadCart();
    } catch (error) {
      console.error('Error updating cart item:', error);
      alert('Failed to update cart item');
    } finally {
      setUpdating(null);
    }
  };

  const removeItem = async (itemId: number) => {
    if (!window.confirm('Are you sure you want to remove this item from your cart?')) {
      return;
    }

    try {
      await apiService.removeFromCart(itemId);
      await loadCart();
    } catch (error) {
      console.error('Error removing cart item:', error);
      alert('Failed to remove item from cart');
    }
  };

  const clearCart = async () => {
    if (!window.confirm('Are you sure you want to clear your cart?')) {
      return;
    }

    try {
      await apiService.clearCart();
      await loadCart();
    } catch (error) {
      console.error('Error clearing cart:', error);
      alert('Failed to clear cart');
    }
  };

  const checkout = () => {
    alert('Checkout functionality coming soon!');
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  if (!cart || cart.items.length === 0) {
    return (
      <div className="max-w-7xl mx-auto px-4 py-8">
        <h1 className="text-3xl font-bold mb-8">Shopping Cart</h1>
        <div className="text-center py-12">
          <h3 className="text-xl text-gray-600 mb-4">Your cart is empty</h3>
          <p className="text-gray-500 mb-6">Start shopping to add items to your cart</p>
          <Link
            to="/products"
            className="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors"
          >
            Continue Shopping
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="max-w-7xl mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold mb-8">Shopping Cart</h1>
      
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Cart Items */}
        <div className="lg:col-span-2">
          <div className="bg-white rounded-lg shadow-md p-6">
            <div className="space-y-4">
              {cart.items.map((item) => (
                <div key={item.id} className="flex items-center justify-between border-b pb-4 last:border-b-0">
                  <div className="flex items-center space-x-4">
                    <img
                      src={item.image_url || '/placeholder-product.jpg'}
                      alt={item.name}
                      className="w-16 h-16 object-cover rounded"
                    />
                    <div>
                      <h4 className="font-semibold">{item.name}</h4>
                      <p className="text-gray-600">${item.price} each</p>
                    </div>
                  </div>
                  
                  <div className="flex items-center space-x-3">
                    <div className="flex items-center border rounded-lg">
                      <button
                        onClick={() => updateQuantity(item.id, item.quantity - 1)}
                        disabled={updating === item.id}
                        className="px-3 py-1 text-gray-600 hover:bg-gray-100 disabled:opacity-50"
                      >
                        -
                      </button>
                      <span className="w-12 text-center">{item.quantity}</span>
                      <button
                        onClick={() => updateQuantity(item.id, item.quantity + 1)}
                        disabled={updating === item.id || item.quantity >= item.stock_quantity}
                        className="px-3 py-1 text-gray-600 hover:bg-gray-100 disabled:opacity-50"
                      >
                        +
                      </button>
                    </div>
                    
                    <span className="font-semibold w-20 text-right">
                      ${item.item_total.toFixed(2)}
                    </span>
                    
                    <button
                      onClick={() => removeItem(item.id)}
                      className="text-red-600 hover:text-red-800 ml-4"
                    >
                      Remove
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Cart Summary */}
        <div className="lg:col-span-1">
          <div className="bg-white rounded-lg shadow-md p-6">
            <h2 className="text-xl font-semibold mb-4">Cart Summary</h2>
            
            <div className="space-y-2 mb-6">
              <div className="flex justify-between">
                <span>Items ({cart.summary.total_items})</span>
                <span>${cart.summary.total_price.toFixed(2)}</span>
              </div>
              <div className="flex justify-between font-semibold text-lg">
                <span>Total</span>
                <span>${cart.summary.total_price.toFixed(2)}</span>
              </div>
            </div>
            
            <div className="space-y-3">
              <button
                onClick={checkout}
                className="w-full bg-green-500 text-white py-3 px-6 rounded-lg hover:bg-green-600 transition-colors"
              >
                Proceed to Checkout
              </button>
              
              <button
                onClick={clearCart}
                className="w-full bg-red-500 text-white py-3 px-6 rounded-lg hover:bg-red-600 transition-colors"
              >
                Clear Cart
              </button>
              
              <Link
                to="/products"
                className="w-full bg-gray-500 text-white py-3 px-6 rounded-lg hover:bg-gray-600 transition-colors text-center block"
              >
                Continue Shopping
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Cart;
