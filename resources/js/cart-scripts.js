/**
 * redirectBuyNow - Redirects to the checkout page with buyNow params.
 * @param {number|string} productId - The product ID to buy now.
 * @param {number} quantity - The quantity to buy now (default 1).
 */
export function redirectBuyNow(productId, quantity = 1) {
    const params = new URLSearchParams({ buyNow: 1, product_id: productId, quantity });
    window.location.href = `/checkout?${params.toString()}`;
}
// cart-scripts.js
// Utility for buyNow functionality

import axios from 'axios';

/**
 * buyNow - Sends a buy now request to the backend API.
 * @param {Object} payload - The payload for the buy now API (see backend docs for structure).
 * @returns {Promise<Object>} - The API response.
 */
export async function buyNow(payload) {
    try {
        const response = await axios.post('/api/cart/buy-now', payload);
        return response.data;
    } catch (error) {
        if (error.response) {
            return error.response.data;
        }
        return { status: false, message: 'Network or server error.' };
    }
}
