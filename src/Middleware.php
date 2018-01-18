<?php

namespace Panlatent\Http;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class Middleware implements MiddlewareInterface, ContextAcceptInterface
{
    /**
     * @var ContextInterface
     */
    protected $context;

    /**
     * @param ContextInterface $context
     */
    public function acceptContext(ContextInterface $context)
    {
        $this->context = $context;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {


    }

}