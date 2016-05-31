<?php

/**
 * Copyright 2016 Cloud Creativity Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace CloudCreativity\LaravelJsonApi\Services;

use CloudCreativity\JsonApi\Contracts\Http\ApiInterface;
use CloudCreativity\LaravelJsonApi\Http\Requests\AbstractRequest;
use CloudCreativity\LaravelJsonApi\Routing\ResourceRegistrar;
use RuntimeException;

/**
 * Class JsonApiService
 * @package CloudCreativity\LaravelJsonApi
 */
class JsonApiService
{

    /**
     * @var ResourceRegistrar
     */
    private $registrar;

    /**
     * JsonApiService constructor.
     * @param ResourceRegistrar $registrar
     */
    public function __construct(ResourceRegistrar $registrar)
    {
        $this->registrar = $registrar;
    }

    /**
     * @param $resourceType
     * @param $controller
     * @param array $options
     */
    public function resource($resourceType, $controller, array $options = [])
    {
        $this->registrar->resource($resourceType, $controller, $options);
    }

    /**
     * Get the active API.
     *
     * An active API will be available once the JSON API middleware has been run.
     *
     * @return ApiInterface
     */
    public function api()
    {
        if (!$this->isActive()) {
            throw new RuntimeException('No active API. The JSON API middleware has not been run.');
        }

        return app(ApiInterface::class);
    }

    /**
     * Get the parsed JSON API request for the current HTTP Request.
     *
     * A request will be registered if a request has completed validation upon resolution from
     * the service container.
     *
     * @return AbstractRequest
     */
    public function request()
    {
        if (!app()->bound(AbstractRequest::class)) {
            throw new RuntimeException('No active JSON API request.');
        }

        return app(AbstractRequest::class);
    }

    /**
     * Has JSON API support been started?
     *
     * @return bool
     */
    public function isActive()
    {
        return app()->bound(ApiInterface::class);
    }
}