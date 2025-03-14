<?php

namespace Grayloon\Magento\Api;

use Exception;
use Grayloon\Magento\Magento;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class AbstractApi
{
    /**
     * The Magento Client instance.
     *
     * @var \Grayloon\Magento\Magento
     */
    public $magento;

    /**
     * The API request Uri builder.
     *
     * @var string
     */
    public $apiRequest;

    public function __construct(Magento $magento)
    {
        $this->magento = $magento;
        $this->apiRequest = $this->constructRequest();
    }

    /**
     * The initial API request before the builder.
     *
     * @return string
     */
    protected function constructRequest()
    {
        $request = $this->magento->baseUrl;
        $request .= '/'.$this->magento->basePath;
        $request .= '/'.$this->magento->storeCode;

        if ($this->magento->versionIncluded) {
            $request .= '/'.$this->magento->version;
        }

        return $request;
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param  string  $path
     * @param  string  $parameters
     * @return Response
     */
    protected function get($path, $parameters = [])
    {
        return $this->checkExceptions(Http::withToken($this->magento->token)->withoutVerifying()
            ->get($this->apiRequest.$path, $parameters), $this->apiRequest.$path, $parameters);
    }

    /**
     * Send a POST request with query parameters.
     *
     * @param  string  $path
     * @param  string  $parameters
     * @return Response
     */
    protected function post($path, $parameters = []): Response
    {
        return $this->checkExceptions(Http::withToken($this->magento->token)->withoutVerifying()
            ->post($this->apiRequest.$path, $parameters), $this->apiRequest.$path, $parameters);
    }

    /**
     * Send a PUT request.
     *
     * @param $path
     * @param  array  $parameters
     * @return Response|void
     */
    protected function put($path, $parameters = [])
    {
        return $this->checkExceptions(Http::withToken($this->magento->token)->withoutVerifying()
            ->put($this->apiRequest.$path, $parameters), $this->apiRequest.$path, $parameters);
    }

    /**
     * Send a DELETE request.
     *
     * @param $path
     * @param  array  $parameters
     * @return Response|void
     */
    protected function delete($path, $parameters = [])
    {
        return $this->checkExceptions(Http::withToken($this->magento->token)->withoutVerifying()
            ->delete($this->apiRequest.$path, $parameters), $this->apiRequest.$path, $parameters);
    }

    /**
     * Check for any type of invalid API Responses.
     *
     * @param  Response  $response
     * @return void
     *
     * @throws \Exception
     */
    protected function checkExceptions($response, $endpoint, $parameters)
    {
        if ($response->serverError()) {
            throw new Exception($response['message'] ?? $response);
        }

        if (! $response->successful()) {
            if (config('magento.log_failed_requests')) {
                Log::info('[MAGENTO API][STATUS] '.$response->status().' [ENDPOINT] '.$endpoint.' [PARAMETERS]  '.json_encode($parameters).' [RESPONSE] '.json_encode($response->json()));
            }
        }

        return $response;
    }

    /**
     * Validates the usage of the store code as needed.
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function validateSingleStoreCode()
    {
        if ($this->magento->storeCode === 'all') {
            throw new Exception(__('You must pass a single store code. "all" cannot be used.'));
        }

        return $this;
    }
}
