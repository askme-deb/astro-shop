<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Api\AddressApiService;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    protected $addressApiService;

    public function __construct(AddressApiService $addressApiService)
    {
        $this->addressApiService = $addressApiService;
    }

    public function index(Request $request)
    {
        try {
            $token = session('auth.api_token') ?? '';
            $result = $this->addressApiService->getUserAddresses($token);
            return view('account.address', [
                'addresses' => $result['addresses'] ?? [],
                'error' => !$result['status'] ? ($result['message'] ?? 'Unable to fetch addresses.') : null,
            ]);
        } catch (\Exception $e) {
            Log::error('AddressController@index: ' . $e->getMessage());
            return view('account.address', [
                'addresses' => [],
                'error' => 'Unable to fetch addresses.'
            ]);
        }
    }

    public function save(Request $request)
    {
        $data = $request->all();
        $token = session('auth.api_token') ?? '';
        try {
            $result = $this->addressApiService->saveUserAddress($data, $token);
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('AddressController@save: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to save address.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $token = session('auth.api_token') ?? '';
        // Map form fields to API payload
        $payload = [
            'id' => $id,
            'shipping_first_name' => $data['first_name'] ?? '',
            'shipping_last_name' => $data['last_name'] ?? '',
            'shipping_phone_number' => $data['phone'] ?? '',
            'shipping_zip_code' => $data['pincode'] ?? '',
            'shipping_address' => $data['address'] ?? '',
            'landmark' => $data['landmark'] ?? '',
            'shipping_state' => $data['state'] ?? '',
            'shipping_city' => $data['city'] ?? '',
            'address_type' => $data['address_type'] ?? 'home',
        ];
        try {
            $result = $this->addressApiService->updateUserAddress($payload, $token);
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('AddressController@update: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to update address.'], 500);
        }
    }

    public function delete(Request $request, $id)
    {
        $token = session('auth.api_token') ?? '';
        $payload = ['id' => $id];
        try {
            $result = $this->addressApiService->deleteUserAddress($payload, $token);
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('AddressController@delete: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to delete address.'], 500);
        }
    }

    public function setDefault(Request $request, $id)
    {
        $token = session('auth.api_token') ?? '';
        $payload = ['id' => $id];
        try {
            $result = $this->addressApiService->setDefaultAddress($payload, $token);
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('AddressController@setDefault: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to set default address.'], 500);
        }
    }

    /**
     * AJAX: Return state list for address modal
     */
    public function stateList(Request $request)
    {
        $token = session('auth.api_token') ?? '';
        if ($token === '') {
            return response()->json([
                'status' => false,
                'states' => [],
                'message' => 'You must be logged in to load states.',
            ], 401);
        }
        $countryId = $request->input('country_id', 101);
        try {
            $result = $this->addressApiService->getStates($token, $countryId);
        } catch (\Throwable $exception) {
            Log::error('Failed to fetch state list for address modal', [
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
     * AJAX: Return city list for address modal
     */
    public function cityList(Request $request)
    {
        $token = session('auth.api_token') ?? '';
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
            $result = $this->addressApiService->getCities($token, $data['state_id']);
        } catch (\Throwable $exception) {
            Log::error('Failed to fetch city list for address modal', [
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
