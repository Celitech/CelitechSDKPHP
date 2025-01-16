<?php

namespace Celitech\Hooks;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;

interface HookInterface
{
    public function __construct(array $params);
    public function beforeRequest(RequestInterface &$request): void;
    public function afterResponse(RequestInterface $request, ResponseInterface &$response): void;
    public function onError(RequestInterface $request, Exception $exception): void;
}
