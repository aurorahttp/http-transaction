<?php

namespace Panlatent\Http;

use Panlatent\Http\Server\RequestFilterInterface;
use Panlatent\Http\Transaction\Context;
use Panlatent\Http\Transaction\FilterQueue;
use Panlatent\Http\Transaction\MiddlewareStack;
use Panlatent\Http\Transaction\PriorityInterface;
use Panlatent\Http\Transaction\ProcessableInterface;
use Panlatent\Http\Transaction\ProcessInquiryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Transaction implements ContextAcceptInterface
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
     * @var FilterQueue
     */
    protected $filters;
    /**
     * @var MiddlewareStack
     */
    protected $middlewares;
    /**
     * @var Context
     */
    protected $context;
    /**
     * @var int int
     */
    protected $status;
    /**
     * @var callable[]
     */
    protected $notices = [];

    public function __construct()
    {
        $this->requestFilters = new FilterQueue();
        $this->responseFilters = new FilterQueue();
        $this->middlewares = new MiddlewareStack();
        $this->context = new Context();
        $this->status = static::STATUS_INIT;
    }


    public function handle(ServerRequestInterface $request): RequestInterface
    {

        $this->status = static::STATUS_REQUEST_FILTER_BEFORE;


        $this->status = static::STATUS_REQUEST_FILTER_DOING;
        $queue = new FilterQueue();
        while (! $this->filters->isEmpty()) {
            $filter = $this->filters->extract();
            if ($filter instanceof PriorityInterface) {
                $priority = $filter->getPriority();
            } else {
                $priority = 0;
            }
            if ($filter instanceof ProcessableInterface && ! $filter->canProcess()) {
                $queue->insert($filter, $priority);
                continue;
            }
            if ($filter instanceof RequestFilterInterface) {
                $request = $filter->process($request);
                $this->watchOptions();
            } elseif ($filter instanceof ResponseInterface) {
                $queue->insert($filter, $priority);
            }
        }
        $this->filters = $queue;
        $this->status = static::STATUS_REQUEST_FILTER_AFTER;

        $this->status = static::STATUS_MIDDLEWARE_BEFORE;
        $this->status = static::STATUS_MIDDLEWARE_DOING;
        $this->status = static::STATUS_MIDDLEWARE_AFTER;
        $this->status = static::STATUS_RESPONSE_FILTER_BEFORE;
        $this->status = static::STATUS_RESPONSE_FILTER_DOING;
        $this->status = static::STATUS_RESPONSE_FILTER_AFTER;
        $this->status = static::STATUS_DONE;
    }

    public function acceptContext(ContextInterface $context)
    {

    }

    public function watchContext()
    {

    }

    public function noticeStatus()
    {
        foreach ($this->notices as $notice) {
            call_user_func($notice, $this);
        }
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
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}