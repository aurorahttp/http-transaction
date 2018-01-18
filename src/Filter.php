<?php

namespace Panlatent\Http;

use Panlatent\Http\Server\FilterInterface;
use Panlatent\Http\Transaction\PriorityInterface;
use Panlatent\Http\Transaction\PriorityTrait;
use Panlatent\Http\Transaction\ProcessableInterface;

abstract class Filter implements FilterInterface, ProcessableInterface, PriorityInterface
{
    use PriorityTrait;
}