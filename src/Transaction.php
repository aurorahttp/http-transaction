<?php

namespace Aurora\Http\Transaction;

use Interop\Http\Server\RequestHandlerInterface;
use Panlatent\Context\ContextSensitiveInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Transaction implements ContextSensitiveInterface
{
    const STATUS_ERROR = 0;
    const STATUS_INIT = 1;
    const STATUS_REQUEST_FILTER_BEFORE = 2;
    const STATUS_REQUEST_FILTER_DOING = 3;
    const STATUS_REQUEST_FILTER_AFTER = 4;
    const STATUS_MIDDLEWARE_BEFORE = 5;
    const STATUS_MIDDLEWARE_DOING = 6;
    const STATUS_MIDDLEWARE_AFTER = 7;
    const STATUS_RESPONSE_FILTER_BEFORE = 8;
    const STATUS_RESPONSE_FILTER_DOING = 9;
    const STATUS_RESPONSE_FILTER_AFTER = 10;
    const STATUS_DONE = 11;

    /**
     * @var ServerRequestInterface
     */
    protected $request;
    /**
     * @var ResponseInterface
     */
    protected $response;
    /**
     * @var Context
     */
    protected $context;
    /**
     * @var FilterQueue
     */
    protected $filters;
    /**
     * @var MiddlewareStack
     */
    protected $middlewares;
    /**
     * @var RequestHandlerInterface
     */
    protected $requestHandler;
    /**
     * @var int int
     */
    protected $status;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
        $this->filters = new FilterQueue($this);
        $this->middlewares = new MiddlewareStack($this);
        $this->context = new Context();
        $this->status = static::STATUS_INIT;
    }

    public function handle(): ResponseInterface
    {
        $this->status = static::STATUS_REQUEST_FILTER_BEFORE;
        $this->status = static::STATUS_REQUEST_FILTER_DOING;

        $request = $this->filters->runRequest($this->request);
        $this->status = static::STATUS_REQUEST_FILTER_AFTER;

        $this->status = static::STATUS_MIDDLEWARE_BEFORE;
        $this->status = static::STATUS_MIDDLEWARE_DOING;
        $response = $this->middlewares->run($request);

        $this->status = static::STATUS_MIDDLEWARE_AFTER;

        $this->status = static::STATUS_RESPONSE_FILTER_BEFORE;
        $this->status = static::STATUS_RESPONSE_FILTER_DOING;
        $response = $this->filters->runResponse($response);

        $this->status = static::STATUS_RESPONSE_FILTER_AFTER;
        $this->status = static::STATUS_DONE;

        $this->request = $request;
        $this->response = $response;

        return $response;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }

    /**
     * @return FilterQueue
     */
    public function getFilters(): FilterQueue
    {
        return $this->filters;
    }

    /**
     * @return MiddlewareStack
     */
    public function getMiddlewares(): MiddlewareStack
    {
        return $this->middlewares;
    }

    /**
     * @return RequestHandlerInterface
     */
    public function getRequestHandler(): RequestHandlerInterface
    {
        return $this->requestHandler;
    }

    /**
     * @param RequestHandlerInterface $requestHandler
     */
    public function setRequestHandler(RequestHandlerInterface $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}