<?php

namespace App\Services\Api;

use App\Services\Api\Clients\BaseApiClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Client\RequestException;

class AuthApiService extends BaseApiClient implements Contracts\AuthApiServiceInterface
{
    protected string $requestOtpEndpoint;
    protected string $resendOtpEndpoint;
    protected string $verifyOtpEndpoint;

    public function __construct()
    {
        parent::__construct();

        $config = config('auth_api');
        $this->baseUrl = (string) ($config['base_url'] ?? $this->baseUrl);
        $this->timeoutSeconds = (int) ($config['timeout'] ?? $this->timeoutSeconds);
        $this->retryTimes = (int) ($config['retry'] ?? $this->retryTimes);

        $this->requestOtpEndpoint = (string) Arr::get($config, 'endpoints.request_otp', '/login/otp/request');
        $this->resendOtpEndpoint = (string) Arr::get($config, 'endpoints.resend_otp', '/login/otp/resend');
        $this->verifyOtpEndpoint = (string) Arr::get($config, 'endpoints.verify_otp', '/login/otp/verify');
    }

    public function requestOtp(string $mobile, string $countryCode): DTOs\AuthApiResponse
    {
        $correlationId = (string) Str::uuid();

        $payload = [
            'mobile_no' => $mobile,
            'country_code' => $countryCode,
        ];

        $responseData = $this->callApi('POST', $this->requestOtpEndpoint, $payload, $correlationId);

        return DTOs\AuthApiResponse::fromArray($responseData, $correlationId);
    }

    public function resendOtp(string $mobile, string $countryCode): DTOs\AuthApiResponse
    {
        $correlationId = (string) Str::uuid();

        $payload = [
            'mobile_no' => $mobile,
            'country_code' => $countryCode,
        ];

        $responseData = $this->callApi('POST', $this->resendOtpEndpoint, $payload, $correlationId);

        return DTOs\AuthApiResponse::fromArray($responseData, $correlationId);
    }

    public function verifyOtp(string $mobile, string $countryCode, string $otp): DTOs\AuthApiResponse
    {
        $correlationId = (string) Str::uuid();

        $payload = [
            'mobile_no' => $mobile,
            'country_code' => $countryCode,
            'otp' => $otp,
        ];

        $responseData = $this->callApi('POST', $this->verifyOtpEndpoint, $payload, $correlationId);

        return DTOs\AuthApiResponse::fromArray($responseData, $correlationId);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    protected function callApi(string $method, string $endpoint, array $payload, string $correlationId): array
    {
        $maskedPayload = $payload;
        if (isset($maskedPayload['otp'])) {
            $maskedPayload['otp'] = '***';
        }

        Log::info('OTP auth API request', [
            'service' => static::class,
            'endpoint' => $endpoint,
            'correlation_id' => $correlationId,
            'payload' => $maskedPayload,
        ]);

        try {
            $data = $this->request($method, $endpoint, [
                'json' => $payload,
            ]);
        } catch (RequestException $exception) {
            $responseBody = $exception->response ? $exception->response->json() : null;

            $userMessage = 'Authentication service temporarily unavailable.';
            if (is_array($responseBody) && isset($responseBody['message'])) {
                $userMessage = (string) $responseBody['message'];
            }

            Log::error('OTP auth API call failed', [
                'service' => static::class,
                'endpoint' => $endpoint,
                'correlation_id' => $correlationId,
                'status' => $exception->response?->status(),
                'message' => $exception->getMessage(),
                'api_message' => $userMessage,
            ]);

            return [
                'status' => false,
                'message' => $userMessage,
                'data' => null,
                'error' => 'service_unavailable',
            ];
        } catch (\Throwable $exception) {
            Log::error('OTP auth API call failed', [
                'service' => static::class,
                'endpoint' => $endpoint,
                'correlation_id' => $correlationId,
                'message' => $exception->getMessage(),
            ]);

            return [
                'status' => false,
                'message' => 'Authentication service temporarily unavailable.',
                'data' => null,
                'error' => 'service_unavailable',
            ];
        }

        Log::info('OTP auth API response', [
            'service' => static::class,
            'endpoint' => $endpoint,
            'correlation_id' => $correlationId,
            'status' => $data['status'] ?? null,
        ]);

        if (! is_array($data)) {
            return [
                'status' => false,
                'message' => 'Unexpected authentication response.',
                'data' => null,
                'error' => 'invalid_response',
            ];
        }

        return $data;
    }
}
