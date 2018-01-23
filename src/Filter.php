<?php

namespace Aurora\Http\Transaction;

use Aurora\Http\Message\Filter\Filter as MessageFilter;

abstract class Filter extends MessageFilter implements PriorityInterface
{
    use PriorityTrait;
}