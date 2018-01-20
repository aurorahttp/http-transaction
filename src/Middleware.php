<?php

namespace Aurora\Http\Transaction;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Panlatent\Context\ContextInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class Middleware implements MiddlewareInterface
{
    /**
     * @var ContextInterface
     */
    protected $context;
    /**
     * @var MiddlewareInterface;
     */
    protected $next;

    /**
     * @param ContextInterface $context
     */
    public function acceptContext(ContextInterface $context)
    {
        $this->context = $context;
    }

    public function setNext($middleware)
    {
        $this->next = $middleware;
    }

    public function next(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        if (! $this->next) {
            return $request;
        }
        return $this->process($request, $handler);
    }
}