<?php

namespace Panlatent\Http\Transaction;

use Interop\Http\Server\MiddlewareInterface;
use Panlatent\Http\Middleware;
use Panlatent\Http\Transaction;
use Psr\Http\Message\ServerRequestInterface;
use SplStack;

class MiddlewareStack extends SplStack
{
    /**
     * @var Transaction
     */
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function push($value)
    {
        if (! $value instanceof MiddlewareInterface) {
            throw new \InvalidArgumentException('The first argument must be FilterInterface instance');
        }
        if ($value instanceof Middleware && ! $this->isEmpty()) {
            $value->setNext($this->top());
        }

        parent::push($value);
    }

    /**
     * @param ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function run(ServerRequestInterface $request)
    {
        while (! $this->isEmpty()) {
            $middleware = $this->pop();
            if ($middleware instanceof ProcessableInterface && ! $middleware->canProcess()) {
                continue;
            }
            if (! $middleware instanceof MiddlewareInterface) {
                throw new \InvalidArgumentException('Invalid middleware object');
            }

            return $middleware->process($request, $this->transaction->getRequestHandler());
        }

        return $this->transaction->getRequestHandler()->handle($request);
    }
}