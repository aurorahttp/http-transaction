<?php


namespace Aurora\Http\Transaction;


interface ProcessableInterface
{
    /**
     * @return bool
     */
    public function canProcess(): bool;
}