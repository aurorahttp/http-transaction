<?php

namespace Aurora\Http\Transaction;

use Aurora\Http\Message\Filter\FilterInterface;

abstract class Filter implements FilterInterface, PriorityInterface
{
    use PriorityTrait;
}