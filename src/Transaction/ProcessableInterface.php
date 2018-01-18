<?php


namespace Panlatent\Http\Transaction;


interface ProcessableInterface
{
    /**
     * @return bool
     */
    public function canProcess(): bool;
}