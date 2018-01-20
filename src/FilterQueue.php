<?php

namespace Aurora\Http\Transaction;

use InvalidArgumentException;
use Aurora\Http\Message\Filter\FilterInterface;
use Aurora\Http\Message\Filter\RequestFilterInterface;
use Aurora\Http\Message\Filter\ResponseFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplPriorityQueue;

/**
 * Class FilterQueue
 *
 * @author Panlatent <panlatent@gmail.com>
 */
class FilterQueue extends SplPriorityQueue
{
    /**
     * @var Transaction
     */
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function insert($value, $priority)
    {
        if (! $value instanceof FilterInterface) {
            throw new InvalidArgumentException('The first argument must be FilterInterface instance');
        }
        parent::insert($value, $priority);
    }

    public function runRequest(ServerRequestInterface $request)
    {
        $queue = [];
        while (! $this->isEmpty()) {
            $filter = $this->extract();
            if ($filter instanceof PriorityInterface) {
                $priority = $filter->getPriority();
            } else {
                $priority = 0;
            }
            if ($filter instanceof ProcessableInterface && ! $filter->canProcess()) {
                $queue[] = [$filter, $priority];
                continue;
            }
            if ($filter instanceof RequestFilterInterface) {
                $request = $filter->process($request);
            } elseif ($filter instanceof ResponseFilterInterface) {
                $queue[] = [$filter, $priority];
            }
        }
        foreach ($queue as list($filter, $priority)) {
            $this->insert($filter, $priority);
        }

        return $request;
    }

    public function runResponse(ResponseInterface $response)
    {
        while (! $this->isEmpty()) {
            $filter = $this->extract();
            if ($filter instanceof ProcessableInterface && ! $filter->canProcess()) {
                continue;
            }
            if ($filter instanceof ResponseFilterInterface) {
                $response = $filter->process($response);
                // $this->watchOptions();
            }
        }

        return $response;
    }
}