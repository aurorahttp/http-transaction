<?php

namespace Panlatent\Http\Transaction;

use InvalidArgumentException;
use Panlatent\Http\Server\FilterInterface;
use SplPriorityQueue;

/**
 * Class FilterQueue
 *
 * @author Panlatent <panlatent@gmail.com>
 */
class FilterQueue extends SplPriorityQueue
{
    public function insert($value, $priority)
    {
        if (! $value instanceof FilterInterface) {
            throw new InvalidArgumentException('The first argument must be FilterInterface instance');
        }
        parent::insert($value, $priority);
    }
}