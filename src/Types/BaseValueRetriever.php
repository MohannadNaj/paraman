<?php

namespace Paraman\Types;
use Paraman\Parameter;

abstract class BaseValueRetriever
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}