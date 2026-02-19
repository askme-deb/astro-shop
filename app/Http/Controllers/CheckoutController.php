<?php

namespace App\Http\Controllers;

use App\Services\Api\AddressApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct(protected AddressApiService $addressApi)
    {
    }

    /**
     * Show the checkout page.
     */
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        $total = 0;
        foreach ($cart as $item) {
            $price = $item['price'] ?? 0;
            $qty = $item['qty'] ?? 1;
            $total += $price * $qty;
        }

        return view('checkout.index', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    /**
     * Handle checkout form submission.
     */
    public function store(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'pincode' => 'required|string|max:20',
            'payment_method' => 'required|string|in:cod,online',
        ]);

        $total = 0;
        foreach ($cart as $item) {
            $price = $item['price'] ?? 0;
            $qty = $item['qty'] ?? 1;
            $total += $price * $qty;
        }

        $orderId = 'ORD-' . strtoupper(uniqid());

        $request->session()->put('last_order', [
            'id' => $orderId,
            'total' => $total,
            'items' => $cart,
            'customer' => $data,
        ]);

        $request->session()->forget('cart');

        return redirect()
            ->route('checkout.index')
            ->with('success', 'Order placed successfully. Your order ID is ' . $orderId . '.');
    }

    /**
     * Return the authenticated user's saved addresses for checkout via AJAX.
     */
    public function userAddresses(Request $request): JsonResponse
    {
        $token = (string) $request->session()->get('auth.api_token', '');
      //  dd($token);
        if ($token === '') {
            return response()->json([
                'status' => false,
                'addresses' => [],
                'message' => 'You must be logged in to view saved addresses.',
            ], 401);
        }

        try {
            $result = $this->addressApi->getUserAddresses($token);
            //dd($result);
        } catch (\Throwable $exception) {
            Log::error('Failed to fetch user addresses for checkout', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'addresses' => [],
                'message' => 'Unable to load addresses at the moment.',
            ], 502);
        }

        $httpStatus = ($result['status'] ?? false) ? 200 : 502;

        return response()->json([
            'status' => (bool) ($result['status'] ?? false),
            'addresses' => $result['addresses'] ?? [],
            'message' => $result['message'] ?? 'Unable to load addresses.',
        ], $httpStatus);
    }

    /**
     * Update an address via AJAX during checkout.
     */
    public function updateAddress(Request $request): JsonResponse
    {
        $token = (string) $request->session()->get('auth.api_token', '');

        if ($token === '') {
            return response()->json([
                'status' => false,
                'message' => 'You must be logged in to update an address.',
            ], 401);
        }

        $data = $request->validate([
            'address_id' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pincode' => 'required|string|max:20',
                'address' => 'required|string|max:1000',
                'landmark' => 'nullable|string|max:255',
                'type' => 'nullable|in:home,office,others',
        ]);

        $payload = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'country' => $data['country'],
            'state' => $data['state'],
            'city' => $data['city'],
            'pincode' => $data['pincode'],
            'address' => $data['address'],
            'landmark' => $data['landmark'] ?? '',
                'type' => $data['type'] ?? null,
            'address_id' => $data['address_id'],
        ];

        try {
            $result = $this->addressApi->updateUserAddress($payload, $token);
        } catch (\Throwable $exception) {
            Log::error('Failed to update user address for checkout', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to update address at the moment.',
            ], 502);
        }

        $httpStatus = ($result['status'] ?? false) ? 200 : 422;

        return response()->json([
            'status' => (bool) ($result['status'] ?? false),
            'message' => $result['message'] ?? 'Unable to update address.',
        ], $httpStatus);
    }

    /**
     * Delete an address via AJAX during checkout.
     */
    public function deleteAddress(Request $request): JsonResponse
    {
        $token = (string) $request->session()->get('auth.api_token', '');

        if ($token === '') {
            return response()->json([
                'status' => false,
                'message' => 'You must be logged in to delete an address.',
            ], 401);
        }

        $data = $request->validate([
            'address_id' => 'required',
        ]);

        $payload = [
            'address_id' => $data['address_id'],
        ];

        try {
            $result = $this->addressApi->deleteUserAddress($payload, $token);
        } catch (\Throwable $exception) {
            Log::error('Failed to delete user address for checkout', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to delete address at the moment.',
            ], 502);
        }

        $httpStatus = ($result['status'] ?? false) ? 200 : 422;

        return response()->json([
            'status' => (bool) ($result['status'] ?? false),
            'message' => $result['message'] ?? 'Unable to delete address.',
        ], $httpStatus);
    }

    /**
     * Mark an address as the default via AJAX during checkout.
     */
    public function setDefaultAddress(Request $request): JsonResponse
    {
        $token = (string) $request->session()->get('auth.api_token', '');

        if ($token === '') {
            return response()->json([
                'status' => false,
                'message' => 'You must be logged in to update default address.',
            ], 401);
        }

        $data = $request->validate([
            'address_id' => 'required',
        ]);

        $payload = [
            'address_id' => $data['address_id'],
        ];

        try {
            $result = $this->addressApi->setDefaultAddress($payload, $token);
        } catch (\Throwable $exception) {
            Log::error('Failed to set default user address for checkout', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to update default address at the moment.',
            ], 502);
        }

        $httpStatus = ($result['status'] ?? false) ? 200 : 422;

        return response()->json([
            'status' => (bool) ($result['status'] ?? false),
            'message' => $result['message'] ?? 'Unable to update default address.',
        ], $httpStatus);
    }

    /**
     * Save a new address via AJAX during checkout.
     */
    public function saveAddress(Request $request): JsonResponse
    {
        $token = (string) $request->session()->get('auth.api_token', '');

        if ($token === '') {
            return response()->json([
                'status' => false,
                'message' => 'You must be logged in to add an address.',
            ], 401);
        }

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:50',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'pincode' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'landmark' => 'nullable|string|max:255',
            'type' => 'nullable|in:home,office,others',
        ]);

        $payload = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'],
            'country' => $data['country'],
            'state' => $data['state'],
            'city' => $data['city'],
            'pincode' => $data['pincode'],
            'address' => $data['address'],
            'landmark' => $data['landmark'] ?? '',
            'type' => $data['type'] ?? null,
        ];

        try {
            $result = $this->addressApi->saveUserAddress($payload, $token);
        } catch (\Throwable $exception) {
            Log::error('Failed to save user address for checkout', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to add address at the moment.',
            ], 502);
        }

        $httpStatus = ($result['status'] ?? false) ? 200 : 422;

        return response()->json([
            'status' => (bool) ($result['status'] ?? false),
            'message' => $result['message'] ?? 'Unable to add address.',
        ], $httpStatus);
    }

    /**
     * Fetch state list for the checkout address forms via AJAX.
     */
    public function stateList(Request $request): JsonResponse
    {
        $token = (string) $request->session()->get('auth.api_token', '');

        if ($token === '') {
            return response()->json([
                'status' => false,
                'states' => [],
                'message' => 'You must be logged in to load states.',
            ], 401);
        }

        $countryId = $request->input('country_id', 101);

        try {
            $result = $this->addressApi->getStates($token, $countryId);
        } catch (\Throwable $exception) {
            Log::error('Failed to fetch state list for checkout', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'states' => [],
                'message' => 'Unable to load states at the moment.',
            ], 502);
        }

        $httpStatus = ($result['status'] ?? false) ? 200 : 502;

        return response()->json([
            'status' => (bool) ($result['status'] ?? false),
            'states' => $result['states'] ?? [],
            'message' => $result['message'] ?? 'Unable to load states.',
        ], $httpStatus);
    }

    /**
     * Fetch city list for the checkout address forms via AJAX.
     */
    public function cityList(Request $request): JsonResponse
    {
        $token = (string) $request->session()->get('auth.api_token', '');

        if ($token === '') {
            return response()->json([
                'status' => false,
                'cities' => [],
                'message' => 'You must be logged in to load cities.',
            ], 401);
        }

        $data = $request->validate([
            'state_id' => 'required',
        ]);

        try {
            $result = $this->addressApi->getCities($token, $data['state_id']);
        } catch (\Throwable $exception) {
            Log::error('Failed to fetch city list for checkout', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'cities' => [],
                'message' => 'Unable to load cities at the moment.',
            ], 502);
        }

        $httpStatus = ($result['status'] ?? false) ? 200 : 502;

        return response()->json([
            'status' => (bool) ($result['status'] ?? false),
            'cities' => $result['cities'] ?? [],
            'message' => $result['message'] ?? 'Unable to load cities.',
        ], $httpStatus);
    }
}
