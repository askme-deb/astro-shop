<?php

namespace App\Services\Api;

use App\Services\Api\Clients\BaseApiClient;
use Illuminate\Support\Facades\Log;

class AddressApiService extends BaseApiClient
{
    /**
     * Astro API endpoint for fetching user addresses.
     *
     * @var string
     */
    protected string $addressEndpoint = 'address-get';

    /**
     * Astro API endpoint for updating a user address.
     *
     * @var string
     */
    protected string $updateEndpoint = 'address-update';

    /**
     * Astro API endpoint for deleting a user address.
     *
     * @var string
     */
    protected string $deleteEndpoint = 'address-delete';

    /**
     * Astro API endpoint for creating a new user address.
     *
     * @var string
     */
    protected string $saveEndpoint = 'address-save';

    /**
     * Astro API endpoint for fetching state list.
     *
     * @var string
     */
    protected string $stateListEndpoint = 'get-state-list';

    /**
     * Astro API endpoint for fetching city list.
     *
     * @var string
     */
    protected string $cityListEndpoint = 'get-city-list';

    /**
     * Astro API endpoint for setting an address as default.
     *
     * @var string
     */
    protected string $defaultEndpoint = 'address-is-default';

    /**
     * Fetch and normalize the authenticated user's addresses.
     *
     * @param string $token Bearer token from session('auth.api_token').
     * @return array{status: bool, addresses: array<int, array<string, mixed>>, message: string}
     */
    public function getUserAddresses(string $token): array
    {
        if ($token === '') {
            return [
                'status' => false,
                'addresses' => [],
                'message' => 'Missing authentication token.',
            ];
        }

        $originalToken = $this->token;
        $this->token = $token;
        try {
            $data = $this->request('GET', $this->addressEndpoint);

        } catch (\Throwable $exception) {
            Log::error('Address API call failed', [
                'service' => static::class,
                'endpoint' => $this->addressEndpoint,
                'message' => $exception->getMessage(),
            ]);

            $this->token = $originalToken;

            return [
                'status' => false,
                'addresses' => [],
                'message' => 'Address service temporarily unavailable.',
            ];
        }

        // Restore the original token for any subsequent calls.
        $this->token = $originalToken;

        if (! is_array($data)) {
            return [
                'status' => false,
                'addresses' => [],
                'message' => 'Unexpected address response.',
            ];
        }

        $status = (bool) ($data['status'] ?? false);
        $message = (string) ($data['message'] ?? ($status ? 'Addresses fetched successfully.' : 'Failed to load addresses.'));

        $rawAddresses = $data['address'] ?? $data['addresses'] ?? [];
        $normalized = [];

        if (is_array($rawAddresses)) {
            foreach ($rawAddresses as $address) {
                if (! is_array($address)) {
                    continue;
                }

                $normalized[] = $this->normalizeAddress($address);
            }
        }

        return [
            'status' => $status,
            'addresses' => $normalized,
            'message' => $message,
        ];
    }

    /**
     * Update a single user address.
     *
     * @param array<string, mixed> $payload
     * @param string $token
     * @return array{status: bool, message: string}
     */
    public function updateUserAddress(array $payload, string $token): array
    {
        if ($token === '') {
            return [
                'status' => false,
                'message' => 'Missing authentication token.',
            ];
        }

        $originalToken = $this->token;
        $this->token = $token;

        try {
            $data = $this->request('POST', $this->updateEndpoint, [
                'json' => $payload,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Address update API call failed', [
                'service' => static::class,
                'endpoint' => $this->updateEndpoint,
                'message' => $exception->getMessage(),
            ]);

            $this->token = $originalToken;

            return [
                'status' => false,
                'message' => 'Unable to update address at the moment.',
            ];
        }

        $this->token = $originalToken;

        if (! is_array($data)) {
            return [
                'status' => false,
                'message' => 'Unexpected address update response.',
            ];
        }

        $status = (bool) ($data['status'] ?? false);
        $message = (string) ($data['message'] ?? ($status ? 'Address updated successfully.' : 'Failed to update address.'));

        return [
            'status' => $status,
            'message' => $message,
        ];
    }

    /**
     * Delete a single user address.
     *
     * @param array<string, mixed> $payload
     * @param string $token
     * @return array{status: bool, message: string}
     */
    public function deleteUserAddress(array $payload, string $token): array
    {
        if ($token === '') {
            return [
                'status' => false,
                'message' => 'Missing authentication token.',
            ];
        }

        $originalToken = $this->token;
        $this->token = $token;

        try {
            $data = $this->request('POST', $this->deleteEndpoint, [
                'json' => $payload,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Address delete API call failed', [
                'service' => static::class,
                'endpoint' => $this->deleteEndpoint,
                'message' => $exception->getMessage(),
            ]);

            $this->token = $originalToken;

            return [
                'status' => false,
                'message' => 'Unable to delete address at the moment.',
            ];
        }

        $this->token = $originalToken;

        if (! is_array($data)) {
            return [
                'status' => false,
                'message' => 'Unexpected address delete response.',
            ];
        }

        $status = (bool) ($data['status'] ?? false);
        $message = (string) ($data['message'] ?? ($status ? 'Address deleted successfully.' : 'Failed to delete address.'));

        return [
            'status' => $status,
            'message' => $message,
        ];
    }

    /**
     * Create a new user address.
     *
     * @param array<string, mixed> $payload
     * @param string $token
     * @return array{status: bool, message: string}
     */
    public function saveUserAddress(array $payload, string $token): array
    {
        if ($token === '') {
            return [
                'status' => false,
                'message' => 'Missing authentication token.',
            ];
        }

        $originalToken = $this->token;
        $this->token = $token;

        try {
            $data = $this->request('POST', $this->saveEndpoint, [
                'json' => $payload,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Address save API call failed', [
                'service' => static::class,
                'endpoint' => $this->saveEndpoint,
                'message' => $exception->getMessage(),
            ]);

            $this->token = $originalToken;

            return [
                'status' => false,
                'message' => 'Unable to add address at the moment.',
            ];
        }

        $this->token = $originalToken;

        if (! is_array($data)) {
            return [
                'status' => false,
                'message' => 'Unexpected address save response.',
            ];
        }

        $status = (bool) ($data['status'] ?? false);

        $rawMessage = $data['message'] ?? null;
        if (is_array($rawMessage)) {
            // Some APIs return validation errors as an array; take the first stringy value.
            $flattened = [];
            array_walk_recursive($rawMessage, function ($value) use (&$flattened) {
                if (is_scalar($value)) {
                    $flattened[] = (string) $value;
                }
            });

            $message = $flattened[0] ?? ($status ? 'Address added successfully.' : 'Failed to add address.');
        } else {
            $message = (string) ($rawMessage ?? ($status ? 'Address added successfully.' : 'Failed to add address.'));
        }

        return [
            'status' => $status,
            'message' => $message,
        ];
    }

    /**
     * Fetch list of states for a given country.
     *
     * @param string $token
     * @param int|string $countryId
     * @return array{status: bool, states: array<int, array{id:mixed,name:string}>, message: string}
     */
    public function getStates(string $token, int|string $countryId = 101): array
    {
        if ($token === '') {
            return [
                'status' => false,
                'states' => [],
                'message' => 'Missing authentication token.',
            ];
        }

        $originalToken = $this->token;
        $this->token = $token;

        try {
            $data = $this->request('POST', $this->stateListEndpoint, [
                'json' => [
                    'country_id' => $countryId,
                ],
            ]);
        } catch (\Throwable $exception) {
            Log::error('State list API call failed', [
                'service' => static::class,
                'endpoint' => $this->stateListEndpoint,
                'message' => $exception->getMessage(),
            ]);

            $this->token = $originalToken;

            return [
                'status' => false,
                'states' => [],
                'message' => 'Unable to load states at the moment.',
            ];
        }

        $this->token = $originalToken;

        if (! is_array($data)) {
            return [
                'status' => false,
                'states' => [],
                'message' => 'Unexpected state list response.',
            ];
        }

        $status = (bool) ($data['status'] ?? false);
        $message = (string) ($data['message'] ?? ($status ? 'States loaded successfully.' : 'Failed to load states.'));

        $rawStates = $data['state_list'] ?? $data['states'] ?? $data['data'] ?? [];
        $states = [];

        if (is_array($rawStates)) {
            foreach ($rawStates as $state) {
                if (! is_array($state)) {
                    continue;
                }

                $states[] = [
                    'id' => $state['id'] ?? null,
                    'name' => (string) ($state['name'] ?? ''),
                ];
            }
        }

        return [
            'status' => $status,
            'states' => $states,
            'message' => $message,
        ];
    }

    /**
     * Fetch list of cities for a given state.
     *
     * @param string $token
     * @param int|string $stateId
     * @return array{status: bool, cities: array<int, array{id:mixed,name:string}>, message: string}
     */
    public function getCities(string $token, int|string $stateId): array
    {
        if ($token === '') {
            return [
                'status' => false,
                'cities' => [],
                'message' => 'Missing authentication token.',
            ];
        }

        $originalToken = $this->token;
        $this->token = $token;

        try {
            $data = $this->request('POST', $this->cityListEndpoint, [
                'json' => [
                    'state_id' => $stateId,
                ],
            ]);
        } catch (\Throwable $exception) {
            Log::error('City list API call failed', [
                'service' => static::class,
                'endpoint' => $this->cityListEndpoint,
                'message' => $exception->getMessage(),
            ]);

            $this->token = $originalToken;

            return [
                'status' => false,
                'cities' => [],
                'message' => 'Unable to load cities at the moment.',
            ];
        }

        $this->token = $originalToken;

        if (! is_array($data)) {
            return [
                'status' => false,
                'cities' => [],
                'message' => 'Unexpected city list response.',
            ];
        }

        $status = (bool) ($data['status'] ?? false);
        $message = (string) ($data['message'] ?? ($status ? 'Cities loaded successfully.' : 'Failed to load cities.'));

        $rawCities = $data['city_list'] ?? $data['cities'] ?? $data['data'] ?? [];
        $cities = [];

        if (is_array($rawCities)) {
            foreach ($rawCities as $city) {
                if (! is_array($city)) {
                    continue;
                }

                $cities[] = [
                    'id' => $city['id'] ?? null,
                    'name' => (string) ($city['name'] ?? ''),
                ];
            }
        }

        return [
            'status' => $status,
            'cities' => $cities,
            'message' => $message,
        ];
    }

    /**
     * Mark a single user address as the default address.
     *
     * @param array<string, mixed> $payload
     * @param string $token
     * @return array{status: bool, message: string}
     */
    public function setDefaultAddress(array $payload, string $token): array
    {
        if ($token === '') {
            return [
                'status' => false,
                'message' => 'Missing authentication token.',
            ];
        }

        $originalToken = $this->token;
        $this->token = $token;

        try {
            $data = $this->request('POST', $this->defaultEndpoint, [
                'json' => $payload,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Address set-default API call failed', [
                'service' => static::class,
                'endpoint' => $this->defaultEndpoint,
                'message' => $exception->getMessage(),
            ]);

            $this->token = $originalToken;

            return [
                'status' => false,
                'message' => 'Unable to set default address at the moment.',
            ];
        }

        $this->token = $originalToken;

        if (! is_array($data)) {
            return [
                'status' => false,
                'message' => 'Unexpected set-default address response.',
            ];
        }

        $status = (bool) ($data['status'] ?? false);
        $message = (string) ($data['message'] ?? ($status ? 'Default address updated successfully.' : 'Failed to set default address.'));

        return [
            'status' => $status,
            'message' => $message,
        ];
    }

    /**
     * Normalize a single raw address record from the API.
     *
     * @param array<string, mixed> $address
     * @return array<string, mixed>
     */
    protected function normalizeAddress(array $address): array
    {
        $shippingCountry = $address['shipping_country'] ?? null;
        $shippingState = $address['shipping_state'] ?? null;
        $shippingCity = $address['shipping_city'] ?? null;

        $countryName = is_array($shippingCountry) ? ($shippingCountry['name'] ?? '') : (string) ($shippingCountry ?? '');
        $stateName = is_array($shippingState) ? ($shippingState['name'] ?? '') : (string) ($shippingState ?? '');
        $cityName = is_array($shippingCity) ? ($shippingCity['name'] ?? '') : (string) ($shippingCity ?? '');

        $addressLine = (string) ($address['shipping_address'] ?? $address['billing_address'] ?? '');
        $postalCode = (string) ($address['shipping_zip_code'] ?? $address['billing_zip_code'] ?? '');

        $fullAddressParts = array_filter([
            $addressLine,
            $cityName,
            $stateName,
            $postalCode,
        ]);

        $fullAddress = implode(', ', $fullAddressParts);

        $fullName = trim(
            (string) ($address['shipping_first_name'] ?? $address['billing_first_name'] ?? '') . ' ' .
            (string) ($address['shipping_last_name'] ?? $address['billing_last_name'] ?? '')
        );

        $rawType = $address['type'] ?? $address['address_type'] ?? null;
        $normalizedType = null;
        if (is_string($rawType) && $rawType !== '') {
            $normalizedType = strtolower($rawType);
        }

        return [
            'id' => $address['id'] ?? null,
            'full_name' => $fullName !== '' ? $fullName : null,
            'type' => $normalizedType,
            'is_default' => (bool) ($address['is_default'] ?? false),
            'address_line' => $addressLine,
            'city' => $cityName,
            'state' => $stateName,
            'country' => $countryName,
            'postal_code' => $postalCode,
            'phone' => (string) ($address['shipping_phone_number'] ?? $address['billing_phone_number'] ?? ''),
            'raw' => $address,
        ];
    }
}
