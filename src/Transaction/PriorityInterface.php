<?php

namespace Panlatent\Http\Transaction;

interface PriorityInterface
{
    /**
     * @return int
     */
    public function getPriority();

    /**
     * @param int $priority
     */
    public function setPriority($priority);
}