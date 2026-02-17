# Cart Page & Guest ID Fix Report

The following issues have been resolved:

1.  **Guest User ID Regeneration**:
    *   **Issue**: Visiting `/cart` was generating a new `guest_user_id` because the encrypted cookie from Web routes conflicted with the raw cookie expected by API routes (or vice-versa).
    *   **Fix**: Updated `bootstrap/app.php` to exclude `guest_user_id` from encryption. This ensures all routes (Web and API) read the same raw UUID value consistently.

2.  **"Failed to load cart" Error**:
    *   **Issue**: The `/cart` page was trying to fetch data via `fetch('/cart')` which returns HTML, causing a JSON parse error. Also, the view file was named `carts/index.blade.php` but the controller sometimes pointed to `cart.index`.
    *   **Fix**:
        *   Refactored `resources/views/carts/index.blade.php` to use server-side data (`$cart`) passed from the controller.
        *   Corrected `CartController.php` to always return `view('carts.index')`.
        *   Added null checks in the view to prevent "Undefined array key 'items'" errors when the cart is empty.

3.  **Frontend Cleanup**:
    *   `addToCart` and `buyNow` functions are now centralized in `resources/views/partials/cart-scripts.blade.php`.
    *   All API calls include `credentials: 'include'` to persist the session/cookie.

## Verification
1.  **Clear Cookies**: Clear your browser cookies for `127.0.0.1`.
2.  **Add to Cart**: Go to `/products` and add an item.
3.  **Check Cart**: Go to `/cart`. The item should be there, and the `guest_user_id` cookie should remain the same.
4.  **Edit Cart**: Use the + / - buttons or Delete icon. The page should reload with updated totals.
