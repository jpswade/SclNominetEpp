<?php

namespace SclNominetEpp;

use SclRequestResponse\AbstractRequestResponse as BaseAbstractRequestResponse;
use SclRequestResponse\RequestInterface;
use SclRequestResponse\ResponseInterface;

abstract class AbstractRequestResponse extends BaseAbstractRequestResponse
{
    /**
     * The stored request object.
     */
    private RequestInterface $request;

    /**
     * Process a request and store it for later retrieval.
     *
     * @param RequestInterface $request The request to process.
     * @return ResponseInterface The result of processing the request
     */
    public function processRequest(RequestInterface $request): ResponseInterface
    {
        $this->request = $request;
        return parent::processRequest($request);
    }

    /**
     * Get the currently stored request.
     *
     * @return RequestInterface The stored request
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
