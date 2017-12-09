<?php

namespace Paraman\Types\Boolean;

use Paraman\Types\BaseValueRetriever;

class Retriever extends BaseValueRetriever
{
    public function getValue()
    {
        return (bool) $this->value;
    }
}